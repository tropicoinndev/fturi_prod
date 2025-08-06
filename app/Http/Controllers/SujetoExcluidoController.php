<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storesujeto_excluidoRequest;
use App\Http\Requests\Updatesujeto_excluidoRequest;
use App\Models\clientes;
use App\Models\dteBase;
use App\Models\dtes;
use App\Models\schemaSujetoExcluido;
use App\Models\sujeto_excluido;
use App\Utils;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SujetoExcluidoController extends Controller
{
    public $unidades;
    public $tipoItem;

    public function __construct()
    {
        $this->unidades = (new Utils)->unidades();
        $this->tipoItem = (new Utils)->tipoItem();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $correlativo = (new CorrelativosController)->getCorrelativoByToken(7005, session('caja')->id);
        return view('sujeto_excluido.index', [
            'correlativo' => $correlativo,
            'data' => sujeto_excluido::where('estado', true)->orderByDesc('id')->paginate(10)
        ]);
    }
    public function search(Request $r)
    {
        $correlativo = (new CorrelativosController)->getCorrelativoByToken(7005, session('caja')->id);
        $b = $r->busqueda;
        return view('sujeto_excluido.index', [
            'correlativo' => $correlativo,
            'busqueda' => $b,
            'data' => sujeto_excluido::where('estado', true)->where(function ($q) use ($b) {
                $q->where('correlativo', 'like', '%' . $b . '%')
                    ->orWhere('fecha', 'like', '%' . $b . '%')
                    ->orWhere(DB::raw('UPPER(titular)'), 'like', '%' . strtoupper($b) . '%');
            })->paginate(100)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()

    {
        $correlativo = (new CorrelativosController)->getCorrelativoByToken(7005, session('caja')->id);
        return view('sujeto_excluido.create', ['correlativo' => $correlativo]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storesujeto_excluidoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storesujeto_excluidoRequest $r)
    {
        try {

            $correlativo = (new CorrelativosController)->getCorrelativoByToken(7005, session('caja')->id);
            (new CorrelativosController)->isValidCorrelativo($correlativo);
            $c = clientes::find($r->clientes_id);
            $p = new sujeto_excluido;
            $p->titular = $c->nombre;
            $p->clientes_id = $r->clientes_id;
            $p->observacion = $r->observacion ?? null;
            $p->fecha = date("Y-m-d");
            $p->users_id = Auth::user()->id;
            $p->cajas_id = session('caja')->id;
            $p->correlativo = $correlativo->actual == 0 ? $correlativo->inicio : $correlativo->actual  + 1;
            if ($p->save()) (new CorrelativosController)->setCorrelativo($correlativo->id);

            return redirect()->route('sujeto_excluido.detalles', ['id' => $p->cid]);
        } catch (\Throwable $e) {
            return redirect()->back()->with('type', 'danger')->with('message', 'Error: ' . $e->getMessage());
        }
    }

    public function detalle(Request $r)
    {
        $id = Crypt::decryptString($r->id);
        $p = sujeto_excluido::findOrFail($id);
        return view('sujeto_excluido.detalles', ['p' => $p, 'unidades' => $this->unidades, 'items' => $this->tipoItem]);
    }

    public function completado(Request $r)
    {
        $id = Crypt::decryptString($r->id);
        $p = sujeto_excluido::findOrFail($id);
        $p->completo = true;
        $p->save();
        return redirect()->back()->with('message', 'Se completo el FESE, solamente falta autorizarse y enviarse');
    }

    public function autorizar(Request $r)
    {
        $r->validate([
            'password' => ['required', 'string'],
            'confirm' => ['required', 'accepted'],
            'tipo_operacion' => ['required', 'integer'],
            'clasificacion' => ['required', 'integer'],
            'sector' => ['required', 'integer'],
            'tipo_clasificacion' => ['required', 'integer'],
            'id' => ['required', 'string']
        ]);
        try {

            if (!Hash::check($r->password, Auth::user()->password))
                return throw new Exception('Contraseña incorrecta');
            $id = Crypt::decryptString($r->id);
            $p = sujeto_excluido::findOrFail($id);

            $p->tipo_operacion = $r->tipo_operacion;
            $p->clasificacion = $r->clasificacion;
            $p->sector = $r->sector;
            $p->tipo_clasificacion = $r->tipo_clasificacion;
            $p->save();

            $rs = $this->setFeToDTE($p->id);

            $json = json_decode($rs);
            if (is_object($json) && get_class($json) === 'stdClass') {
                $p->enviado = true;
                $p->autoriza_users_id = Auth::user()->id;
                $p->save();
                $dte = dtes::where('sujeto_excluidos_id', $p->id)->first();
                return redirect()->route('dtes.resultado', ['id' => $dte->cid])->with('message', 'Se envió el DTE a MH');
            } else
                return redirect()->back()->with('message', 'No se pudo enviar el comprobante, intente mas tarde');
        } catch (\Throwable $th) {

            return redirect()->back()->with('type', 'danger')->with('message', 'Error: ' . $th->getMessage());
        }
    }

    public function editarClasificacion(Request $r)
    {
        $r->validate([

            'tipo_operacion' => ['required', 'integer'],
            'clasificacion' => ['required', 'integer'],
            'sector' => ['required', 'integer'],
            'tipo_clasificacion' => ['required', 'integer'],
            'id' => ['required', 'string']
        ]);
        try {
            $id = Crypt::decryptString($r->id);
            $p = sujeto_excluido::findOrFail($id);
            $p->tipo_operacion = $r->tipo_operacion;
            $p->clasificacion = $r->clasificacion;
            $p->sector = $r->sector;
            $p->tipo_clasificacion = $r->tipo_clasificacion;
            $p->save();
            return redirect()->back()->with('message', 'Se guardaron los cambios');
        } catch (\Throwable $th) {

            return redirect()->back()->with('type', 'danger')->with('message', 'Error: ' . $th->getMessage());
        }
    }

    public function setFeToDTE($id)
    {
        try {
            $dte = new dteBase(null, null, $id);
            return $dte->generarDteSE();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function reenvioDte(Request $r)
    {
        try {
            $dteBase = new dteBase(null, null, Crypt::decryptString($r->id));
            $response = $dteBase->generarDteSE();
            return redirect()->back()->with('message', $response);
        } catch (\Throwable $th) {
            return redirect()->back()->with('type', 'danger')
                ->with('message', 'Ocurrió un erro:' . $th->getMessage());
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\sujeto_excluido  $sujeto_excluido
     * @return \Illuminate\Http\Response
     */
    public function show(sujeto_excluido $sujeto_excluido)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\sujeto_excluido  $sujeto_excluido
     * @return \Illuminate\Http\Response
     */
    public function edit(sujeto_excluido $sujeto_excluido)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatesujeto_excluidoRequest  $request
     * @param  \App\Models\sujeto_excluido  $sujeto_excluido
     * @return \Illuminate\Http\Response
     */
    public function update(Updatesujeto_excluidoRequest $request, sujeto_excluido $sujeto_excluido)
    {
        //
    }

    public function confirm(Request $r)
    {

        $p = sujeto_excluido::findOrFail(Crypt::decryptString($r->id));
        return view('confirm', [
            'th' => [
                'title'     => "Eliminar sujeto excluido",
                'sub'       => 'Sujeto #' . $p->correlativo . ' Titular: ' . $p->titular,
                'table'     => 'sujeto_excluido',
            ],
            'p' => $p,
        ]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\sujeto_excluido  $sujeto_excluido
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            $p = sujeto_excluido::find(Crypt::decryptString($r->id));
            $p->estado = false;
            $p->save();
            return redirect()->route('sujeto_excluido.index')->with('message', 'Se elimino el comprobante');
        } catch (\Throwable $th) {
            return redirect()->route('sujeto_excluido.index')->with('type', 'danger')->with('message', 'Error: ' . $th->getMessage());
        }
    }
}

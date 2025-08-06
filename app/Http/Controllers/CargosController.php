<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorecargosRequest as StoreRequest;
use App\Http\Requests\UpdatecargosRequest as UpdateRequest;
use App\Models\cargos as model;
use App\Models\cargos;
use App\Models\recepcion_cargos;
use App\Models\recepciones;
use App\Utils;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class CargosController extends Controller
{
    // cSpell:ignore
    private $table = 'cargos';
    public function __construct()
    {
        $this->getTh($this->table, 'Cargos', false);
    }

    public function index()
    {
        return view($this->table . '.index', [
            'th'        => $this->th['index'],
            'p'         => model::orderBy('cargo', 'DESC')->paginate(15),
            'table'     => $this->table,

        ]);
    }
    public function search(Request $r)
    {
        return view($this->table . '.index', [
            'th'            => $this->th['index'],
            'p'             => model::where(DB::raw('UPPER(cargo)'), 'ilike', '%' . strtoupper($r->txtBusqueda) . '%')->paginate(),
            'txtBusqueda'   => $r->txtBusqueda,
            'table'         => $this->table,

        ]);
    }

    public function create()
    {
        return view($this->table . '.create', [
            'th' => $this->th['create'],
            'table' => $this->table,
        ]);
    }

    public function store(StoreRequest $r)
    {
        try {
            $p = new model;
            $p->cargo = $r->cargo;
            $p->precio = $r->precio;
            $p->iva = $r->iva && $r->iva == 1;
            $p->propina = $r->propina && $r->propina == 1;
            $p->cesc = $r->cesc && $r->cesc == 1;
            $p->save();

            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Registro guardado correctamente: ' . $p->cargo)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }


    public function edit($id)
    {
        try {

            return view($this->table . ".create", [

                'th' => $this->th['edit'],
                'p' => model::findOrFail(Crypt::decryptString($id)),
                'table' => $this->table,

            ]);
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al encontrar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function update(UpdateRequest $r)
    {
        try {
            $p = model::findOrFail(Crypt::decryptString($r->id));
            $p->cargo = $r->cargo;
            $p->precio = $r->precio;
            $p->iva = $r->iva && $r->iva == 1;
            $p->propina = $r->propina && $r->propina == 1;
            $p->cesc = $r->cesc && $r->cesc == 1;
            $p->save();

            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Registro fue editado correctamente: ' . $p->cargo)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al editar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function destroy(Request $r)
    {
        try {
            if (!isset($r->id) || empty(trim($r->id)))
                return redirect()->route($this->table . '.index')
                    ->with('message', 'Ocurrió un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type', 'danger');

            $p = model::findOrFail(Crypt::decryptString($r->id));
            $p->delete();

            return to_route($this->table . '.index')
                ->with('message', 'Registro eliminado con éxito');
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrió un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }

    public function confirm($id)
    {
        try {
            return view("confirm", [
                'th' => $this->th['confirm'],
                'p' => model::findOrFail(Crypt::decryptString($id))
            ]);
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrió un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }

    public function status($id)
    {
        try {
            $p = model::findOrFail(Crypt::decryptString($id));
            $p->estado = !$p->estado;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Estado modificado correctamente: ' . $p->cargo);
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al cambiar estado: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function setIVA(Request $r)
    {
        try {
            $p = model::findOrFail(Crypt::decryptString($r->id));
            $p->iva = !$p->iva;
            $p->save();
            return redirect()->back()->with('message', 'Se edito el IVA del cargo ' . $p->cargo);
        } catch (\Throwable $th) {
            return redirect()->back()->with('type', 'danger')->with('message', 'Ocurrió un error al editar el [], error: ' . $th->getMessage());
        }
    }
    public function setCESC(Request $r)
    {
        try {
            $p = model::findOrFail(Crypt::decryptString($r->id));
            $p->cesc = !$p->cesc;
            $p->save();
            return redirect()->back()->with('message', 'Se edito el CESC del cargo ' . $p->cargo);
        } catch (\Throwable $th) {
            return redirect()->back()->with('type', 'danger')->with('message', 'Ocurrió un error al editar el [], error: ' . $th->getMessage());
        }
    }
    public function setPropina(Request $r)
    {
        try {
            $p = model::findOrFail(Crypt::decryptString($r->id));
            $p->propina = !$p->propina;
            $p->save();
            return redirect()->back()->with('message', 'Se edito la propina del cargo ' . $p->cargo);
        } catch (\Throwable $th) {
            return redirect()->back()->with('type', 'danger')->with('message', 'Ocurrió un error al editar el [], error: ' . $th->getMessage());
        }
    }

    public function storeRecepcion(Request $r)
    {
        $r->validate([
            'cantidad' => ['required', 'numeric'],
            'observacion' => ['nullable', 'string', 'max:200'],
            'cargos_id' => ['required', 'numeric'],
            'recepciones_id' => ['required', 'string'],
        ]);
        try {
            $recepcion_id = Crypt::decryptString($r->recepciones_id);
            $recepcion = recepciones::find($recepcion_id);
            if (!$recepcion->estado || $recepcion->facturada || $recepcion->eliminado)
                return throw new Exception('No se puede agregar esta estadía esta deshabilitada.');

            $cargos = cargos::find($r->cargos_id);
            if (!$cargos->estado)
                return throw new Exception('El cargo elegido esta deshabilitada.');
            //Calcular precios
            $precio = (new Utils)->getCalPrecio($cargos->precio, $cargos->iva, $cargos->cesc, $cargos->propina);
            $p = new recepcion_cargos;
            $p->cantidad = $r->cantidad;
            $p->precio = $cargos->precio;
            $p->iva = $precio->iva;
            $p->cesc = $precio->cesc;
            $p->propina = $precio->propina;
            $p->observacion = $r->observacion;
            $p->cargos_id = $cargos->id;
            $p->recepciones_id = $recepcion->id;
            $p->users_id = Auth::user()->id;
            $p->save();

            return redirect()->back()->with('message', 'Se agrego el cargo ' . $cargos->cargo . ' a la estadia #' . $recepcion->id);
        } catch (\Throwable $th) {
            return redirect()->back()->with('type', 'danger')->with('message', 'Error: ' . $th->getMessage());
        }
    }
    public function deleteRecepcion(Request $r)
    {
        try {
            $id = Crypt::decryptString($r->id);
            $p = recepcion_cargos::find($id);
            $p->eliminacion = trim($r->observacion);
            $p->estado = false;
            $p->fecha_eliminacion = date("Y-m-d");
            $p->eliminacion_users_id = Auth::user()->id;
            $p->save();
            return redirect()->back()->with('message', 'Se elimino el cargo correctamente');
        } catch (\Throwable $th) {
            return redirect()->back()->with('type', 'danger')->with('message', 'Error: ' . $th->getMessage());
        }
    }
}

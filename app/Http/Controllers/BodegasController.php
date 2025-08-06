<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorebodegasRequest;
use App\Http\Requests\UpdatebodegasRequest;
use App\Models\bodega_users;

#Agregar.
use App\Models\bodegas;
use App\Models\existenciasbyVencimiento as proximosaVencer;
use App\Models\existenciasminimasbyproductos as existenciaMinima;
use App\Models\requisiciones;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class BodegasController extends Controller
{
    private $table = 'bodegas';

    private $tipos = [
        ['id' => 1, 'tipo' => 'BODEGA GENERAL'],
        ['id' => 2, 'tipo' => 'BODEGA DE VENTAS'],
        ['id' => 3, 'tipo' => 'BODEGA DE PRODUCCION'],
        ['id' => 4, 'tipo' => 'BODEGA ADMINISTRATIVA']
    ];

    public function __construct()
    {
        $this->getTh($this->table, 'Bodegas');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->table . '.index', [
            'th' => $this->th['index'],
            'p' => bodegas::orderBy('id', 'DESC')->paginate(15),
            'data' => [
                'tipos' => $this->tipos
            ]
        ]);
    }
    //*funcion para dashboard se mostrar segun la sesion de bodega
    public function dashboard(Request $r)
    {
        if (session('bodega') != null) {
            return to_route('bodegas.my');
        }
        try {
        $bodega = session('bodega');
        $bodegaId = $bodega->id;
        $usuarioLogueado = auth()->user();
        $requisiciones = requisiciones::with(['relacionUsuarios', 'relacionBodegasEntrada', 'relacionBodegas', 'relacionBodegasSalida', 'relacionUserCreacion', 'relacionUserAutorizacion'])
            ->whereIn('bodega_salida_id', function ($q) use ($usuarioLogueado) {
                $q->from('bodega_users')
                    ->where('users_id', $usuarioLogueado->id)
                    ->select('bodegas_id');
            })
            ->where('estado', 2)
            ->whereNull('user_autorizacion_id')
            ->orderBy('id', 'DESC')
            ->take(15)
            ->get();
        $existencias_vencidas = proximosaVencer::where('bodega', $bodegaId)
            ->orderBy('id', 'DESC')
            ->take(15)
            ->get();
        $productosconExistencias = existenciaMinima::where('bodegas_id', $bodegaId)->orderBy('id', 'DESC')
            ->take(15)
            ->get();

        return view('bodegas.dashboard', compact('existencias_vencidas','productosconExistencias','requisiciones'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function menu()
    {
        return view('bodegas.menu');
    }
    #1
    public function my(Request $r){
        $bodega = session('bodega');
        $bodegaId = $bodega->id;

    $p = requisiciones::with(['relacionBodegasSalida', 'relacionBodegasEntrada', 'relacionUserAutorizacion', 'relacionUserCreacion'])
        ->where(function ($query) use ($bodegaId) {
                    $query->where('estado', 1)
                        ->orWhere('estado', 4)
                        ->where('bodega_entrada_id', $bodegaId);
                })
                ->orderBy('id', 'DESC')
                ->get();

        return view('bodegas.panel', [
            'th' => $this->th['index'],
            'p' => $p,
            'table' => $this->table,
            'data' => [
                'bodegaUsers' => bodegas::orderBy('id', 'DESC')->get(),
            ],
        ]);
    }
    #2
    public function login(){
        if(session('bodega') != null){
            return to_route('bodegas.my');
        }

        $bodegasUsers = bodega_users::where('users_id',Auth::id())->get();
        return view('bodegas.login',[
            'bodegas'=>$bodegasUsers
        ]);
    }
    #3
    public function auth(Request $r){
        $bodegasUsers = bodega_users::findOrFail(Crypt::decryptString($r->bodega));

        session(['bodega'=>bodegas::find($bodegasUsers->bodegas_id)]);
        return to_route('bodegas.dashboard');
    }
    #4
    public function logout(Request $r){
        if(session('bodega') != null){
            session()->forget('bodega');
        }

        return to_route('bodegas.login');
    }

    /* API LISTADO DE USUARIOS */
    public function apiGetUsuarios()
    {
        return response([
            'usuarios' => User::orderBy('name', 'ASC')->get()
        ]);
    }

    /* API STORE BODEGA USERS */
    public function apiStoreBodegaUsuario(Request $r)
    {
        $bodegasId = Crypt::decryptString($r->bodegas_id);

        $duplicados = bodega_users::where('users_id','=',$r->users_id)->where('bodegas_id','=',$bodegasId)->first();

        if($duplicados == null){
            $data = new bodega_users;
            $data->bodegas_id = $bodegasId;
            $data->users_id = $r->users_id;
            $data->save();
        }
        else{
            bodega_users::where('users_id','=',$r->users_id)->where('bodegas_id','=',$bodegasId)->delete();
        }

        return response([
            'message' =>($duplicados == null) ? 'OK, Usuario agregado.' : 'OK, Usuario eliminado.',
            'userList'=>$this->getPrueba($bodegasId),
            'type'    =>'success'
        ]);
    }

    /* API LISTADO DE BODEGAS USUARIOS */
    public function apiGetBodegasUsuarios(Request $r)
    {
        return response([
            'bodegaUsuarios' => $this->getPrueba(Crypt::decryptString($r->id))
        ]);
    }

    /* METODO AUXILIAR */
    public function getPrueba($bodegaId)
    {
        return bodega_users::where('bodegas_id', '=', $bodegaId)->with('relacionUsuarios')->get();
    }

    public function search(Request $request)
    {
        if (isset($request->txtBusqueda)) {
            $p = bodegas::where('bodega', 'ilike', '%' . $request->txtBusqueda . '%')
                ->paginate();

            return view($this->table . '.index', [
                'th' => $this->th['index'],
                'p' => $p,
                'data' => [
                    'tipos' => $this->tipos
                ],
                'txtBusqueda' => $request->txtBusqueda
            ]);
        } else {
            return to_route($this->table . '.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
        return view($this->table . '.create', [
            'th' => $this->th['create'],
            'table' => $this->table,
            'data' => [
                'tipos' => $this->tipos
            ]
        ]);
            }catch (\Throwable $th) {
                    return to_route($this->table . '.index')
                        ->with('message', 'Error al encontrar el registro: ' . $th->getMessage())
                        ->with('type', 'danger');
                }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorebodegasRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorebodegasRequest $request)
    {
        try {
            $p = new bodegas;
            $p->bodega = $request->bodega;
            $p->color_fondo = $request->color_fondo;
            $p->color_texto = $request->color_texto;
            $p->tipo = $request->tipo;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro guardado correctamente: ' . $p->bodega)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\bodegas  $bodegas
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = bodegas::findOrFail(Crypt::decryptString($id));

        return view($this->table . '.show', [
            'th' => $this->th['show'] = [
                'title' => 'Usuarios permitidos para la bodega: ' . $data->bodega,
                'table' => $this->table,
                'bread' => $this->table . '.show'
            ],
            'p' => $data,
            'data' => [
                'tipos' => $this->tipos
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\bodegas  $bodegas
     * @return \Illuminate\Http\Response
     */
    public function edit( $id)
    {
        try {

            return view($this->table . ".edit", [
                'th' => $this->th['edit'],
                'p' => bodegas::findOrFail(Crypt::decryptString($id)),
                'table' => $this->table,
                'data' => [
                'tipos' => $this->tipos
            ]
            ]);
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatebodegasRequest  $request
     * @param  \App\Models\bodegas  $bodegas
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatebodegasRequest $request)
    {
        try {
            $p = bodegas::findOrFail($request->id);
            $p->bodega = $request->bodega;
            $p->color_fondo = $request->color_fondo;
            $p->color_texto = $request->color_texto;
            $p->tipo = $request->tipo;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro guardado correctamente: ' . $p->bodega)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function confirm($id)
    {
        try {
            return view('confirm', [
                'th' => $this->th['confirm'],
                'p' => bodegas::findOrFail(Crypt::decryptString($id))
            ]);
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\bodegas  $bodegas
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            if (!isset($r->id) || empty(trim($r->id)))
                return redirect()->route($this->table . '.index')
                    ->with('message', 'Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type', 'danger');
            $p = bodegas::findOrFail(Crypt::decryptString($r->id));
            $p->delete();

            return to_route($this->table . '.index')
                ->with('message', 'Registro eliminado con exito: ' . $p->bodega);
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }

    /* API BORRAR REGISTRO DE BODEGA USERS */
    public function api_delete_bodega_usuario(Request $r)
    {
        try {
            $data = bodega_users::findOrFail($r->id);
            $data->delete();

            return response([
                'message' => 'OK, Usuario eliminado.',
                'type' => 'success'
            ]);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al eliminar el usuario de esta caja: ' . $th->getMessage(),
                'type' => 'danger'
            ]);
        }
    }

    public function api_delete_usuario(Request $r)
    {
        try {
            $data = User::findOrFail($r->id);
            $data->delete();

            return response([
                'message' => 'OK, Usuario eliminado: ' . $data->name,
                'type' => 'success'
            ]);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al eliminar el usuario: ' . $th->getMessage(),
                'type' => 'danger'
            ]);
        }
    }
}

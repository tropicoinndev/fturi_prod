<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storecajas_usersRequest;
use App\Http\Requests\Updatecajas_usersRequest;
use App\Models\cajas;
use App\Models\cajas_users;
use App\Models\User;
use Exception;
#Agregar
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class CajasUsersController extends Controller
{
    private $table = 'cajas_users';

    public function __construct()
    {
        $this->getTh($this->table, 'Cajas users');
    }

    public function index_api()
    {
        return response()->json(['list' => $this->getList()]);
    }

    private function getList()
    {
        return User::orderBy('name', 'ASC')->get();
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
            'p' => cajas_users::orderBy('id', 'DESC')->paginate(15),
            'table' => $this->table,
            'data' => [
                'users' => User::orderBy('name', 'ASC')->get(),
                'cajas' => cajas::orderBy('caja', 'ASC')->get()
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view($this->table . '.create', [
            'th' => $this->th['create'],
            'table' => $this->table,
            'data' => [
                'users' => User::orderBy('name', 'ASC')->get(),
                'cajas' => Cajas::orderBy('caja', 'ASC')->get()
            ],
        ]);
    }
    public function search(Request $request)
    {
        if (isset($request->txtBusqueda)) {
            $p = cajas_users::where('users_id', 'like', '%' . $request->txtBusqueda . '%')
                ->orWhere('cajas_id', 'like', '%' . $request->txtBusqueda . '%')
                ->paginate();

            return view($this->table . '.index', [
                'th' => $this->th['index'],
                'p' => $p,
                'txtBusqueda' => $request->txtBusqueda,
                'data' => [
                    'cajas' => cajas::orderBy('cajas', 'ASC')->get(),
                    'users' => User::orderBy('name', 'ASC')->get(),
                ],
            ]);
        } else {
            return to_route($this->table . '.index');
        }
    }
    /** este metodo es para mostrar el accesso a cajas */
    public function login()
    {
        return view($this->table . '.login', [
            'th' => $this->th['login'],
            'table' => $this->table,
            'data' => [
                'users' => User::orderBy('name', 'ASC')->get(),
                'cajas' => Cajas::orderBy('caja', 'ASC')->get()
            ],
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storecajas_usersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storecajas_usersRequest $request)
    {
        try {
            $p = new cajas_users;
            $p->pin = Hash::make('1234');
            $p->users_id = $request->users_id;
            $p->cajas_id = $request->cajas_id;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro guardado correctamente.')
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    /***esta funcion resetea el pin por defecto */
    public function resetearPin(Request $r)
    {
        try {
            $p = cajas_users::findOrFail(Crypt::decryptString($r->id));
            $p->pin = Hash::make('1234');
            $p->save();

            return redirect()->back()
                ->with('message', 'Se restablecio el pin de este usuario')
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
     * @param  \App\Models\cajas_users  $cajas_users
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return view($this->table . '.show', [
                'th' => $this->th['show'],
                'p' => cajas_users::findOrFail(Crypt::decryptString($id)),
                'table' => $this->table,
                'data' => [
                    'users' => User::orderBy('name', 'ASC')->get(),
                    'cajas' => Cajas::orderBy('caja', 'ASC')->get()
                ],
            ]);
        } catch (\Throwable $th) {
            return to_route($this->table . '.index')
                ->with('message', 'Error al encontrar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\cajas_users  $cajas_users
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            return view($this->table . '.edit', [
                'th' => $this->th['edit'],
                'p' => cajas_users::findOrFail(Crypt::decryptString($id)),
                'table' => $this->table,
                'data' => [
                    'users' => User::orderBy('name', 'ASC')->get(),
                    'cajas' => Cajas::orderBy('caja', 'ASC')->get()
                ],
            ]);
        } catch (\Throwable $th) {
            return to_route($this->table . '.index')
                ->with('message', 'Error al encontrar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatecajas_usersRequest  $request
     * @param  \App\Models\cajas_users  $cajas_users
     * @return \Illuminate\Http\Response
     */
    public function update(Updatecajas_usersRequest $request)
    {
        try {
            $p = cajas_users::findOrFail($request->id);

            $p->pin = $request->pin;
            $p->users_id = $request->users_id;
            $p->cajas_id = $request->cajas_id;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro actualizado correctamente.')
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return to_route($this->table . '.index')
                ->with('message', 'Error al actualizar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function confirm($id)
    {
        try {
            return view('confirm', [
                'th' => $this->th['confirm'],
                'p' => cajas_users::findOrFail(Crypt::decryptString($id))
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
     * @param  \App\Models\cajas_users  $cajas_users
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            if (!isset($r->id) || empty(trim($r->id)))
                return redirect()->route($this->table . '.index')
                    ->with('message', 'Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type', 'danger');

            #cajas_users::destroy(Crypt::decryptString($r->id));
            $p = cajas_users::findOrFail(Crypt::decryptString($r->id));
            $p->delete();

            return to_route($this->table . '.index')
                ->with('message', 'Registro eliminado con exito.');
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }
    public function destroy_api(Request $r)
    {
        $m = "Se elimino un usuario";
        $t = true;
        try {
            User::destroy($r->id);
        } catch (\Throwable $th) {
            $t = false;
            $m = "Error: " + $th->getMessage();
        }
        return response()->json([
            'list' => $this->getList(),
            'message' => $m,
            'type' => $t ? 'success' : 'danger',
        ]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\cajas_users  $cajas_users
     * @return $user
     */
    public function deleteCaja($id)
    {

        try {
            $p = cajas_users::findOrFail(Crypt::decryptString($id));
            $p->delete();
            return redirect()->back()
                ->with('message', 'Se le ha desasignado el permiso a esta caja.')->with('type','info');

        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $th->getMessage() . ')')
                ->with('type', 'danger');
        }

    }


    public function pin(Request $r)
    {
        try {
            $p = cajas_users::where('cajas_id', $r->caja)->where('users_id', Auth::user()->id)->first();
            if ($p == null)
                return redirect()->back()
                    ->with('message', 'Ocurrio un problema al encontrar esta configuracion.')
                    ->with('type', 'danger');

            if (!Hash::check($r->pin, $p->pin))
                return redirect()->back()
                    ->with('message', 'El PIN antiguo ingresado es incorrecto')
                    ->with('type', 'danger');

            if ($r->npin != $r->cpin)
                return redirect()->back()
                    ->with('message', 'Error el nuevo PIN y la confirmacion no coinciden.')
                    ->with('type', 'danger');

            $p->pin = Hash::make($r->npin);
            $p->save();

            return redirect()->route('cajas.logout')
                ->with('message', 'Se cambio el pin de esta caja.')
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    #----MOVIL----
    public function appPin(Request $r){
        try{
            #Asignacion de datos Post a variables
            $pinActual    = intval($r->txtPinActual);
            $pinNuevo     = intval($r->txtPinNuevo);
            $confirmarPin = intval($r->txtConfirmarPin);

            #Validacion de datos
            if($pinActual <= 0 || $pinActual == null)
                throw new Exception('No se encontró el parámetro: pin-actual.');

            if($pinNuevo <= 0 || $pinNuevo == null)
                throw new Exception('No se encontró el parámetro: pin-nuevo.');

            if($confirmarPin <= 0 || $confirmarPin == null)
                throw new Exception('No se encontró el parámetro: confirmar-pin.');

            #---Encontrar coincidencias del usuario logueado y la caja---
            $p = cajas_users::where('cajas_id',session('caja')->id)
                ->where('users_id',Auth::user()->id)->first();

            if(!$p)
                throw new Exception('Ocurrio un problema al encontrar esta configuración.');
            #---
            
            #Validacion de pin actual con el antiguo
            if(!Hash::check($pinActual, $p->pin))
                throw new Exception('El pin antiguo ingresado es incorrecto.');
            
            #Validacion del nuevo pin y el de confirmacion
            if($pinNuevo != $confirmarPin)
                throw new Exception('El pin nuevo y el de confirmación no coinciden.');

            #Cambiar pin
            $p->pin = Hash::make($pinNuevo);
            $p->save();

            return response()->json([
                'status'=>true,
                'message'=>'Se cambio el pin de esta caja.',
            ]);
        }
        catch(\Exception $e){
            return response()->json([
                'status'=>false,
                'message'=>$e->getMessage(),
            ]);
        }
    }
    #-------------
}
<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storetipo_mantenimiento_usersRequest;
use App\Http\Requests\Updatetipo_mantenimiento_usersRequest;
use App\Models\tipo_mantenimiento_users;
use App\Models\tipo_mantenimientos;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class TipoMantenimientoUsersController extends Controller
{
    private $table = 'tipo_mantenimiento_users';

    public function __construct()
    {
        $this->getTh($this->table, 'Tipo mantenimiento usuarios');
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
            'p' => tipo_mantenimiento_users::orderBy('id', 'DESC')->paginate(15),
            'table' => $this->table,
            'data' => [
                'users' => User::orderBy('name', 'ASC')->get(),
                'tipo_mantenimientos' => tipo_mantenimientos::orderBy('id', 'ASC')->get()
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storetipo_mantenimiento_usersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storetipo_mantenimiento_usersRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\tipo_mantenimiento_users  $tipo_mantenimiento_users
     * @return \Illuminate\Http\Response
     */
    public function show(tipo_mantenimiento_users $tipo_mantenimiento_users)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\tipo_mantenimiento_users  $tipo_mantenimiento_users
     * @return \Illuminate\Http\Response
     */
    public function edit(tipo_mantenimiento_users $tipo_mantenimiento_users)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatetipo_mantenimiento_usersRequest  $request
     * @param  \App\Models\tipo_mantenimiento_users  $tipo_mantenimiento_users
     * @return \Illuminate\Http\Response
     */
    public function update(Updatetipo_mantenimiento_usersRequest $request, tipo_mantenimiento_users $tipo_mantenimiento_users)
    {
        //
    }
        public function confirm($id)
    {
        try {
            return view("confirm", [
                'th' => $this->th['confirm'],
                'p' => tipo_mantenimiento_users::findOrFail(Crypt::decryptString($id))
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
     * @param  \App\Models\tipo_mantenimiento_users  $tipo_mantenimiento_users
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            if (!isset($r->id) || empty(trim($r->id))) {
                return redirect()
                    ->route($this->table . '.index')
                    ->with('message', 'Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type', 'danger');
            }

            $p = tipo_mantenimiento_users::findOrFail(Crypt::decryptString($r->id));
            $p->delete();

            return redirect()->route('users.show', ['id' => Crypt::encryptString($p->users_id)])->with('message', 'Se elimino el mantenimiento.')->with('type', 'danger');
        } catch (\Throwable $t) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }
}

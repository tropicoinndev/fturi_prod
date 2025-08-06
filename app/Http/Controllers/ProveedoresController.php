<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreproveedoresRequest;
use App\Http\Requests\UpdateproveedoresRequest;
use App\Models\proveedores;

#Agregar.
use Illuminate\Http\Request;
use App\Models\municipios;
use Illuminate\Support\Facades\Crypt;

class ProveedoresController extends Controller
{
    private $table = 'proveedores';

    public function __construct()
    {
        $this->getTh($this->table,'Proveedores');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->table.'.index',[
            'th'=>$this->th['index'],
            'p'=>proveedores::with('relacionMunicipios')->orderBy('id','DESC')->paginate(15),
            'data'=>[
                'municipios'=>municipios::orderBy('municipio','ASC')->get()
            ]
        ]);
    }

    public function search(Request $request){
        if(isset($request->txtBusqueda)){
            $p = proveedores::where('proveedor','ilike','%'.$request->txtBusqueda.'%')
                ->orWhere('nrc','ilike','%'.$request->txtBusqueda.'%')
                ->orWhere('nit','ilike','%'.$request->txtBusqueda.'%')
                ->orWhere('dui','ilike','%'.$request->txtBusqueda.'%')
                ->paginate();
            
            return view($this->table.'.index',[
                'th'=>$this->th['index'],
                'p'=>$p,
                'data'=>[
                    'municipios'=>municipios::orderBy('municipio','ASC')->get()
                ],
                'txtBusqueda'=>$request->txtBusqueda
            ]);
        }
        else{
            return to_route($this->table.'.index');
        }
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
                'municipios' => municipios::orderBy('municipio', 'ASC')->get(),

            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreproveedoresRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreproveedoresRequest $request)
    {
        try{
            $p = new proveedores;
            $p->proveedor = $request->proveedor;
            $p->nrc = $request->nrc;
            $p->nit = $request->nit;
            $p->dui = $request->dui;
            $p->direccion = $request->direccion;
            $p->municipios_id = $request->municipios_id;
            $p->contactos = $request->contactos;
            $p->informacion = $request->informacion;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Registro guardado correctamente: '.$p->proveedor)
                ->with('type','success');
        }
        catch(\Throwable $th){
            return to_route($this->table.'.index')
                ->with('message','Error al guardar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\proveedores  $proveedores
     * @return \Illuminate\Http\Response
     */
    public function show(proveedores $proveedores)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\proveedores  $proveedores
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            return view($this->table.'.edit',[
                'th'=>$this->th['edit'],
                'p'=>proveedores::findOrFail(Crypt::decryptString($id)),
                'table'=>$this->table,
                'data'=>[
                    'municipios'=>municipios::orderBy('municipio','ASC')->get()
                ],
            ]);
        }
        catch(\Throwable $th){
            return to_route($this->table.'.index')
                ->with('message','Error al encontrar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateproveedoresRequest  $request
     * @param  \App\Models\proveedores  $proveedores
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateproveedoresRequest $request)
    {
        try{
            $p = proveedores::findOrFail($request->id);

            $p->proveedor = $request->proveedor;
            $p->nrc = $request->nrc;
            $p->nit = $request->nit;
            $p->dui = $request->dui;
            $p->direccion = $request->direccion;
            $p->municipios_id = $request->municipios_id;
            $p->contactos = $request->contactos;
            $p->informacion = $request->informacion;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Registro actualizado correctamente: '.$p->proveedor)
                ->with('type','success');
        }
        catch(\Throwable $th){
            return to_route($this->table.'.index')
                ->with('message','Error al actualizar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }

    public function confirm($id){
        try{
            return view('confirm',[
                'th'=>$this->th['confirm'],
                'p'=>proveedores::findOrFail(Crypt::decryptString($id))
            ]);
        }
        catch(\Throwable $th){
            return redirect()->back()
                ->with('message','Ocurrio un error: '.$th->getMessage())
                ->with('type','danger');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\proveedores  $proveedores
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            if (!isset($r->id) || empty(trim($r->id)))
                return redirect()->route($this->table . '.index')
                    ->with('message', 'Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type', 'danger');

            #proveedores::destroy(Crypt::decryptString($r->id));
            $p = proveedores::findOrFail(Crypt::decryptString($r->id));
            $p->delete();

            return to_route($this->table . '.index')
                ->with('message', 'Registro eliminado con exito: ' . $p->proveedor);
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }

    public function statusPermiteCredito($id){
        try{
            $p = proveedores::findOrFail(Crypt::decryptString($id));
            $p->permite_credito = !$p->permite_credito;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Estado modificado correctamente: '.$p->proveedor)
                ->with('type','success');
        }
        catch(\Throwable $th){
            return to_route($this->table.'.index')
                ->with('message','Error al cambiar estado: '.$th->getMessage())
                ->with('type','error');
        }
    }
}

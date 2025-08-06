<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoresucursalesRequest;
use App\Http\Requests\UpdatesucursalesRequest;
use App\Models\correlativo_sucursal;
use App\Models\sucursales;

#Agregar
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SucursalesController extends Controller
{
    private $table = 'sucursales';

    public function __construct()
    {
        $this->getTh($this->table,'Sucursales');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->table.'.index',[
            'th'=>$this->th['create'],
            'p'=>sucursales::orderBy('id','DESC')->paginate(15),
            'table'=>$this->table,
            'data'=>[],
        ]);
    }

    public function search(Request $request)
    {
        if(isset($request->txtBusqueda)){
            $p = sucursales::where('sucursal','like','%'.$request->txtBusqueda.'%')
                ->orWhere('codigo','like','%'.$request->txtBusqueda.'%')
                ->orWhere('telefono','like','%'.$request->txtBusqueda.'%')
                ->orWhere('correo','like','%'.$request->txtBusqueda.'%')
                ->orWhere('nit','like','%'.$request->txtBusqueda.'%')
                ->orWhere('nrc','like','%'.$request->txtBusqueda.'%')
                ->paginate();

            return view($this->table.'.index',[
                'th'=>$this->th['index'],
                'p'=>$p,
                'txtBusqueda'=>$request->txtBusqueda,
                'data'=>[],
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
        return view($this->table.'.create',[
            'th'=>$this->th['create'],
            'table'=>$this->table,
            'data'=>[],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoresucursalesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoresucursalesRequest $r){
        try{
            $p = new sucursales;
            $p->logo          = $this->saveImage($r);
            $p->sucursal      = $r->sucursal;
            $p->direccion     = $r->direccion;
            $p->telefono      = $r->telefono;
            $p->correo        = $r->correo;
            $p->nit           = $r->nit;
            $p->nrc           = $r->nrc;
            $p->giro          = $r->giro;
            $p->codigo_establecimiento = $r->codigo_establecimiento;
            $p->municipios_id = $r->municipios_id;
            $p->matriz        = $r->matriz;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Registro guardado correctamente: '.$p->sucursal)
                ->with('type','success');
        }
        catch(\Throwable $th){
            return redirect()->route($this->table.'.index')
                ->with('message','Error al guardar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }
    private function saveImage($request){
        if($request->hasFile('logo')){
            $file = $request->file('logo');
            $nombre = date('YmdHis') . '_' . $file->getClientOriginalName();

            Storage::disk('logos')->put($nombre, File::get($file));

            return $nombre;#Retornar solo el nombre del archivo
        }
    
        return null;
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\sucursales  $sucursales
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        try{
            $sucusalId = Crypt::decryptString($id);

            $correlativoSucursal = correlativo_sucursal::with('sucursales')->where('sucursales_id',$sucusalId)->orderBy('id','desc')->get();

            return view($this->table.'.show',[
                'th'=>$this->th['correlativoSucursals'],
                #'table'=>$this->table,
                'p'=>sucursales::find($sucusalId),
                'correlativoSucursals'=>$correlativoSucursal,
                #'data'=>[],
            ]);
        }
        catch(\Throwable $th){
            return to_route($this->table.'.index')
                ->with('message','Error al encontrar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\sucursales  $sucursales
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            return view($this->table.'.edit',[
                'th'=>$this->th['edit'],
                'p'=>sucursales::findOrFail(Crypt::decryptString($id)),
                'table'=>$this->table,
                'data'=>[],
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
     * @param  \App\Http\Requests\UpdatesucursalesRequest  $request
     * @param  \App\Models\sucursales  $sucursales
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatesucursalesRequest $r){
        try{
            $p = sucursales::findOrFail($r->id);

            #Verificar si se ha enviado un nuevo logo
            if($r->hasFile('logo')){
                #Guardar la nueva imagen y obtener su nombre
                $nombreLogoNuevo = $this->saveImage($r);

                #Verificar si se obtuvo un nombre válido para el nuevo logo
                if($nombreLogoNuevo){

                    #Verificar si el nuevo logo es diferente al actual
                    if($nombreLogoNuevo != $p->logo){

                        #Eliminar el logo anterior si existe
                        if($p->logo)
                            Storage::disk('logos')->delete($p->logo);

                        #Asignar el nuevo nombre del logo
                        $p->logo = $nombreLogoNuevo;
                    }
                }
            }

            #Actualizar los demás campos de la sucursal
            $p->sucursal  = $r->sucursal;
            $p->direccion = $r->direccion;
            $p->telefono  = $r->telefono;
            $p->correo    = $r->correo;
            $p->nit       = $r->nit;
            $p->nrc       = $r->nrc;
            $p->giro      = $r->giro;
            $p->codigo_establecimiento = $r->codigo_establecimiento;

            #Verificar y actualizar el municipio solo si cambia
            if($r->municipios_id && $p->municipios_id != $r->municipios_id)
                $p->municipios_id = $r->municipios_id;

            $p->matriz = $r->matriz;
            $p->save();

            return redirect()->route($this->table.'.index')
                ->with('message', 'Registro actualizado correctamente: ' . $p->sucursal)
                ->with('type', 'success');
        }
        catch(\Throwable $th){
            return redirect()->route($this->table.'.index')
                ->with('message', 'Error al actualizar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }


    public function confirm($id)
    {
        try{
            return view('confirm',[
                'th'=>$this->th['confirm'],
                'p'=>sucursales::findOrFail(Crypt::decryptString($id))
            ]);
        }
        catch(\Throwable $t){
            return redirect()->back()
                ->with('message','Ocurrio un error ('.$t->getMessage().')')
                ->with('type','danger');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\sucursales  $sucursales
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try{
            if(!isset($r->id) || empty(trim($r->id)))
                return redirect()->route($this->table .'.index')
                    ->with('message','Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type','danger');

                #sucursales::destroy(Crypt::decryptString($r->id));
                $p = sucursales::findOrFail(Crypt::decryptString($r->id));
                $p->delete();

            return to_route($this->table.'.index')
                ->with('message','Registro eliminado con exito: '.$p->sucursal);
        }
        catch(\Throwable $t){
            return redirect()->back()
                ->with('message','Ocurrio un error ('.$t->getMessage().')')
                ->with('type','danger');
        }
    }
}

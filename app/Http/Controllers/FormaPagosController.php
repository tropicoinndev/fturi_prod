<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storeforma_pagosRequest;
use App\Http\Requests\Updateforma_pagosRequest;
use App\Models\forma_pagos;

#Agregar
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class FormaPagosController extends Controller
{
    private $table = 'forma_pagos';

    private $formaPagoTokens = [
        ['forma'=>'EFECTIVO', 'token'=>6001],
        ['forma'=>'CREDITO',  'token'=>6002],
        ['forma'=>'BANCOS',   'token'=>6003],
        ['forma'=>'ANTICIPOS','token'=>6004],
        ['forma'=>'CHEQUES',  'token'=>6005]
    ];

    public function __construct(){
        $this->getTh($this->table,'Forma de pagos');
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
            'p' =>forma_pagos::orderBy('id','DESC')->paginate(15),
            'data'=>[
                'formaPagoTokens'=>$this->formaPagoTokens,
            ],
        ]);
    }

    public function search(Request $request)
    {
        if(isset($request->txtBusqueda)){
            $p = forma_pagos::where('forma','ilike','%'.$request->txtBusqueda.'%')
                ->orWhere('token','ilike','%'.$request->txtBusqueda.'%')
                ->paginate();
            
            return view($this->table.'.index',[
                'th'=>$this->th['index'],
                'p'=>$p,
                'txtBusqueda'=>$request->txtBusqueda,
                'data'=>[
                'formaPagoTokens'=>$this->formaPagoTokens,
            ],
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
            'p' =>forma_pagos::orderBy('id','DESC')->paginate(15),
            'data'=>[
                'formaPagoTokens'=>$this->formaPagoTokens,
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storeforma_pagosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storeforma_pagosRequest $request)
    {
        try{
            $p = new forma_pagos;
            $p->forma = $request->forma;
            $p->token = $request->token;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Registro guardado correctamente: '.$p->forma)
                ->with('type','success');
        }
        catch(\Throwable $th){
            return redirect()->route($this->table.'.index')
                ->with('message','Error al guardar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\forma_pagos  $forma_pagos
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\forma_pagos  $forma_pagos
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            return view($this->table.'.edit',[
                'th'=>$this->th['edit'],
                'p'=>forma_pagos::findOrFail(Crypt::decryptString($id)),
                'data'=>[
                    'formaPagoTokens'=>$this->formaPagoTokens,
                ],
            ]);
        }
        catch(\Throwable $th){
            return to_route($this->table.'.index')
                ->with('message','Error al encontar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updateforma_pagosRequest  $request
     * @param  \App\Models\forma_pagos  $forma_pagos
     * @return \Illuminate\Http\Response
     */
    public function update(Updateforma_pagosRequest $r)
    {
        try{
            $p = forma_pagos::findOrFail($r->id);

            $p->forma = $r->forma;
            $p->token = $r->token;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Registro actualizado correctamente: '.$p->forma)
                ->with('type','success');
        }
        catch(\Throwable $th){
            return to_route($this->table.'.index')
                ->with('message','Error al actualizar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }

    public function confirm($id)
    {
        try{
            return view('confirm',[
                'th'=>$this->th['confirm'],
                'p'=>forma_pagos::findOrFail(Crypt::decryptString($id))
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
     * @param  \App\Models\forma_pagos  $forma_pagos
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try{
            if(!isset($r->id) || empty(trim($r->id)))
                return redirect()->route($this->table .'.index')
                    ->with('message','Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type','danger');

                #forma_pagos::destroy(Crypt::decryptString($r->id));
                $p = forma_pagos::findOrFail(Crypt::decryptString($r->id));
                $p->delete();

            return to_route($this->table.'.index')
                ->with('message','Registro eliminado con exito: '.$p->forma);
        }
        catch(\Throwable $t){
            return redirect()->back()
                ->with('message','Ocurrio un error ('.$t->getMessage().')')
                ->with('type','danger');
        }
    }
}

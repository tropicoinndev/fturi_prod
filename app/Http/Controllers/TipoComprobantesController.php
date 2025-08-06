<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storetipo_comprobantesRequest;
use App\Http\Requests\Updatetipo_comprobantesRequest;
use App\Models\tipo_comprobantes;

#Agregar
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class TipoComprobantesController extends Controller
{
    private $table = 'tipo_comprobantes';

    private $tipoComprobanteTokens = [
        ['id'=>1,'tipo'=>'CCF',               'token'=>7001],
        ['id'=>2,'tipo'=>'FACTURA CONSUMIDOR','token'=>7002],
        ['id'=>3,'tipo'=>'NOTA DE CREDITO',   'token'=>7003],
        ['id'=>4,'tipo'=>'NOTA DE DEBITO',    'token'=>7004],
        ['id'=>5,'tipo'=>'FACTURA DE SUJETO EXCLUIDO',    'token'=>7005]
    ];

    public function __construct(){
        $this->getTh($this->table,'Tipo de comprobantes');
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
            'data'=>[
                'tipoComprobanteTokens'=>$this->tipoComprobanteTokens,
            ],
            'p'=>tipo_comprobantes::orderBy('id','DESC')->paginate(15)
        ]);
    }

    public function search(Request $request)
    {
        if(isset($request->txtBusqueda)){
            $p = tipo_comprobantes::where('tipo','like','%'.$request->txtBusqueda.'%')
                ->orWhere('codigo','like','%'.$request->txtBusqueda.'%')
                ->orWhere('token','like','%'.$request->txtBusqueda.'%')
                ->paginate();

            return view($this->table.'.index',[
                'th'=>$this->th['index'],
                'p'=>$p,
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
        return view($this->table.'.create',[
            'th'=>$this->th['create'],
            'data'=>[
                'tipoComprobanteTokens'=>$this->tipoComprobanteTokens,
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storetipo_comprobantesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storetipo_comprobantesRequest $request)
    {
        try{
            $p = new tipo_comprobantes;
            $p->tipo = $request->tipo;
            $p->codigo = $request->codigo;
            $p->token = $request->token;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Registro guardado correctamente: '.$p->tipo)
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
     * @param  \App\Models\tipo_comprobantes  $tipo_comprobantes
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\tipo_comprobantes  $tipo_comprobantes
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            return view($this->table.'.edit',[
                'th'=>$this->th['edit'],
                'p'=>tipo_comprobantes::findOrFail(Crypt::decryptString($id)),
                'data' => [
                    'tipoComprobanteTokens' => $this->tipoComprobanteTokens,
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
     * @param  \App\Http\Requests\Updatetipo_comprobantesRequest  $request
     * @param  \App\Models\tipo_comprobantes  $tipo_comprobantes
     * @return \Illuminate\Http\Response
     */
    public function update(Updatetipo_comprobantesRequest $request)
    {
        try{
            $p = tipo_comprobantes::findOrFail($request->id);

            $p->tipo = $request->tipo;
            $p->codigo = $request->codigo;
            $p->token = $request->token;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Registro actualizado correctamente: '.$p->tipo)
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
                'p'=>tipo_comprobantes::findOrFail(Crypt::decryptString($id))
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
     * @param  \App\Models\tipo_comprobantes  $tipo_comprobantes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try{
            if(!isset($r->id) || empty(trim($r->id)))
                return redirect()->route($this->table .'.index')
                    ->with('message','Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type','danger');
                $p = tipo_comprobantes::findOrFail(Crypt::decryptString($r->id));
            if ($p->comprobantes->count() > 0) {
                return redirect()->route($this->table . '.index')
                    ->with('message', 'No se puede eliminar el tipo de comprobante porque tiene comprobantes asociados.')
                    ->with('type', 'danger');
            }
                $p->delete();

            return to_route($this->table.'.index')
                ->with('message','Registro eliminado con exito: '.$p->tipo);
        }
        catch(\Throwable $t){
            return redirect()->back()
                ->with('message','Ocurrio un error ('.$t->getMessage().')')
                ->with('type','danger');
        }
    }
}

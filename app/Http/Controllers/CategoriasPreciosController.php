<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\Storecategorias_preciosRequest;
use App\Http\Requests\Updatecategorias_preciosRequest;
use App\Models\rubro as Rubro;
use App\Models\categorias_precios;
use App\Models\precios;
use App\Models\bodega_cajas;
use Carbon\Carbon;
use Throwable;

class CategoriasPreciosController extends Controller
{
     private $table = 'categorias_precios';


     public function __construct()
    {
        $this->getTh($this->table, 'Categorías Precios');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $p = categorias_precios::with('rubros')->orderBy('categoria','DESC')->paginate(15);

        #Formatear los campos created_at y updated_at con Carbon
        $p->transform(function($item){
            $item->created_att = Carbon::parse($item->created_at)->diffForHumans();
            $item->updated_att = Carbon::parse($item->updated_at)->diffForHumans();
            return $item;
        });

        return view('categorias_precios.index',[
            'th'   =>$this->th['index'],
            'p'    =>$p,
            'table'=>$this->table,
            'data' =>[
                'rubros' =>Rubro::orderBy('id','DESC')->get(),
                'precios'=>precios::orderBy('id','DESC')->get(),#Se agregó
            ],
        ]);
    }

    #---MOVIL---
    public function getCategoriasPreciosApp(){
        $p = categorias_precios::with('rubros')->orderBy('categoria','ASC')->paginate(15);

        #Formatear los campos created_at y updated_at con Carbon
        $p->transform(function($item){
            $item->created_att = Carbon::parse($item->created_at)->diffForHumans();
            $item->updated_att = Carbon::parse($item->updated_at)->diffForHumans();
            return $item;
        });

        return view('app.categorias_precios',[
            'th'   =>$this->th['index'],
            'p'    =>$p,
            'table'=>$this->table,
            'bodegas'=>bodega_cajas::where("cajas_id",session('caja')->id)->with("bodegas")->get(),
        ]);
    }
    #----MOVIL---

    /**funcion para realiza busqueda */
      public function search(Request $r)
    {
        return view($this->table . '.index', [
            'th'            => $this->th['index'],
            'p'             => categorias_precios::where('categoria', 'ilike', '%' . $r->txtBusqueda . '%')->paginate(),
            'txtBusqueda'   => $r->txtBusqueda,
            'table'         => $this->table,
            'data' => [
                'rubros' => Rubro::orderBy('id', 'DESC')->get(),
                'precios'=>precios::orderBy('id','desc')->get(),
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
                'rubros' => Rubro::orderBy('id', 'DESC')->get()
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storecategorias_preciosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storecategorias_preciosRequest $request)
    {
            try {
            $p = new Categorias_precios;
            $p->categoria = $request->categoria;
            $p->descripcion = $request->descripcion;
            $p->rubros_id = $request->rubros_id;
            $p->token = $request->token;
            $p->save();

            return redirect()
                ->route($this->table . '.index', ['id' => Crypt::encryptString($p->id)])
                ->with('message', 'Registro guardado correctamente: ' . $p->categoria)
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
     * @param  \App\Models\categorias_precios  $categorias_precios
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\categorias_precios  $categorias_precios
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
          try {

            return view($this->table . ".edit", [

                'th' => $this->th['edit'],
                'p' => categorias_precios::findOrFail(Crypt::decryptString($id)),
                'table' => $this->table,
                'data' => [
                'rubros' => Rubro::orderBy('id', 'DESC')->get()
            ],

            ]);
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al encontrar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatecategorias_preciosRequest  $request
     * @param  \App\Models\categorias_precios  $categorias_precios
     * @return \Illuminate\Http\Response
     */
    public function update(Updatecategorias_preciosRequest $request)
    {

         try {
            $p = categorias_precios::findOrFail($request->id);
            $p->categoria = $request->categoria;
            $p->descripcion = $request->descripcion;
            $p->token = $request->token;
            $p->rubros_id = $request->rubros_id;
            $p->save();

            return redirect()->route($this->table . '.index')
                ->with('message', 'Registro editado correctamente: ' . $p->categoria)
                ->with('type', 'info');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al editar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\categorias_precios  $categorias_precios
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
          try {
            if (!isset($r->id) || empty(trim($r->id)))
                return redirect()->route($this->table . '.index')
                    ->with('message', 'Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type', 'danger');

            $p = categorias_precios::findOrFail(Crypt::decryptString($r->id));
            $p->delete();

            return to_route($this->table . '.index')
                ->with('message', 'Registro eliminado con exito: ' . $p->categoria);
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }
        public function confirm($id)
    {
        try {
            return view("confirm", [
                'th' => $this->th['confirm'],
                'p' => categorias_precios::findOrFail(Crypt::decryptString($id))
            ]);
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }
        public function status($id)
    {
        try {
            $p = categorias_precios::findOrFail(Crypt::decryptString($id));
            $p->estado = !$p->estado;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Estado modificado correctamente: ' . $p->categoria)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al cambiar estado: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

}

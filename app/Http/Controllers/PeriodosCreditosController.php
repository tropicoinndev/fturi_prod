<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storeperiodos_creditosRequest;
use App\Http\Requests\Updateperiodos_creditosRequest;
use App\Models\periodos_creditos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Throwable;

class PeriodosCreditosController extends Controller
{
    private $table = 'periodos_creditos';

    public function __construct(){
        $this->getTh($this->table,'Periodos Créditos');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return view($this->table.'.index',[
            'th'=>$this->th['index'],
            'p'=>periodos_creditos::orderBy('id','desc')->paginate(15),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        return view($this->table.'.create',[
            'th'=>$this->th['create']
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storeperiodos_creditosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storeperiodos_creditosRequest $r){
        try{
            $p = new periodos_creditos;
            $p->periodo = $r->periodo;
            $p->dias = $r->dias;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Registro guardado correctamente: '.$r->periodo.' · '.$p->dias)
                ->with('type','success');
        }
        catch(Throwable $th){
            return to_route($this->table.'.index')
                ->with('message','Error al guardar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\periodos_creditos  $periodos_creditos
     * @return \Illuminate\Http\Response
     */
    public function show(periodos_creditos $periodos_creditos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\periodos_creditos  $periodos_creditos
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        try{
            return view($this->table.'.edit',[
                'th'=>$this->th['edit'],
                'p'=>periodos_creditos::find(Crypt::decryptString($id)),
            ]);
        }
        catch(Throwable $th){
            return to_route($this->table.'.index')
                ->with('message','Error al encontrar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updateperiodos_creditosRequest  $request
     * @param  \App\Models\periodos_creditos  $periodos_creditos
     * @return \Illuminate\Http\Response
     */
    public function update(Updateperiodos_creditosRequest $r){
        try{
            $p = periodos_creditos::find(Crypt::decryptString($r->id));
            $p->periodo = $r->periodo;
            $p->dias = $r->dias;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Registro editado correctamente: '.$p->periodo.' · '.$p->dias)
                ->with('type','success');
        }
        catch(Throwable $th){
            return to_route($this->table.'.index')
                ->with('message','Error al editar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }

    public function confirm($id){
        try{
            $p = periodos_creditos::find(Crypt::decryptString($id));

            #Verificar si el registro ya no está activo para poder eliminarlo.
            if($p->estado)
                return to_route($this->table.'.index')
                    ->with('message','Error: antes de eliminar el registro: ('.$p->periodo.' · '.$p->dias.') debe desactivarlo.')
                    ->with('type','danger');

            return view('confirm',[
                'th'=>$this->th['confirm'],
                'p'=>$p,
            ]);
        }
        catch(Throwable $th){
            return redirect()->back()
                ->with('message','Ocurrio un error: '.$th->getMessage())
                ->with('type','danger');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\periodos_creditos  $periodos_creditos
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r){
        try{
            if(!isset($r->id) || empty(trim($r->id)))
                return to_route($this->table.'.index')
                    ->with('message','Error, el identificador del registro no cumple los requisitos necesarios.')
                    ->with('type','danger');

            $p = periodos_creditos::find(Crypt::decryptString($r->id));
            $p->delete();

            return to_route($this->table.'.index')
                ->with('message','Registro eliminado correctamente: '.$p->periodo.' · '.$p->dias)
                ->with('type','success');
        }
        catch(Throwable $th){
            return redirect()->back()
                ->with('message','Error al eliminar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }

    public function search(Request $r){
        return view($this->table.'.index',[
            'th'=>$this->th['index'],
            'p'=>periodos_creditos::where('periodo','ilike','%'.$r->txtBusqueda.'%')->paginate(),
            'txtBusqueda'=>$r->txtBusqueda,
        ]);
    }

    public function status($id){
        try{
            $p = periodos_creditos::find(Crypt::decryptString($id));
            $p->estado = !$p->estado;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Estado modificado correctamente: '.$p->periodo.' · '.$p->dias)
                ->with('type','success');
        }
        catch(Throwable $th){
            return to_route($this->table.'.index')
                ->with('message','Error al cambiar de estado: '.$th->getMessage())
                ->with('type','danger');
        }
    }
}

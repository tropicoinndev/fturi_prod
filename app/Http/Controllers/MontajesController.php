<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\StoremontajesRequest;
use App\Http\Requests\UpdatemontajesRequest;
use App\Models\montajes;
use App\Models\galerias;
use App\Models\montajes_galerias;
use Illuminate\Support\Facades\Crypt;

class MontajesController extends Controller
{
    private $table = 'montajes';

    public function __construct(){
        $this->getTh($this->table,'Montajes');
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
            'p'=>montajes::orderBy('id','DESC')->paginate(15)
        ]);
    }
    public function search(Request $r){
        return view($this->table . '.index', [
            'th'         => $this->th['index'],
            'p'       => montajes::where('montaje', 'ilike', '%' . $r->txtBusqueda . '%')->paginate(),
            'txtBusqueda' => $r->txtBusqueda,
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

            return view($this->table.'.create',[
            'th'=>$this->th['create']
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoremontajesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoremontajesRequest $request)
    {
        try{
            $data = new Montajes;
            $data->montaje = $request->montaje;
            $data->descripcion = $request->descripcion;
            $data->save();

            return to_route($this->table.'.index')
                ->with('message','Registro guardado correctamente: '.$data->montaje)
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
     * @param  \App\Models\montajes  $montajes
     * @return \Illuminate\Http\Response
     */
    public function show( $id)
    {
    $montaje = montajes::findOrFail(Crypt::decryptString($id));
    $montajes_galerias = montajes_galerias::with(['galerias','montajes'])->where('montajes_id', $montaje->id)->get();
    $galerias = galerias::all();

    return view($this->table . '.show', [
        'th' => $this->th['show'] =  [
            'title' => $montaje->montaje,
            'table' => $this->table,
            'bread' => $this->table . '.show'
        ],
        'p' => $montaje,
        'galerias' =>$galerias,
        'table' => $this->table,
        'montajes_galerias' => $montajes_galerias,
    ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\montajes  $montajes
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            return view($this->table . ".edit",[
                'th' => $this->th['edit'],
                'p' => montajes::findOrFail(Crypt::decryptString($id))
            ]);

        }
        catch(\Throwable $th){
            return redirect()->route($this->table.'.index')
                ->with('message','Error al encontrar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatemontajesRequest  $request
     * @param  \App\Models\montajes  $montajes
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatemontajesRequest $request)
    {
        try{
            $p = montajes::findOrFail($request->id);
            $p->montaje = $request->montaje;
            $p->descripcion = $request->descripcion;
            $p->save();

            return redirect()->route($this->table.'.index')
                ->with('message','Registro editado correctamente: '.$p->montaje)
                ->with('type','info');
        }
        catch(\Throwable $th){
            return redirect()->route($this->table.'.index')
                ->with('message','Error al editar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }
    public function confirm($id)
    {
        try {
            return view("confirm", [
                'th' => $this->th['confirm'],
                'p' => Montajes::findOrFail(Crypt::decryptString($id))
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
     * @param  \App\Models\montajes  $montajes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            if (!isset($r->id) || empty(trim($r->id)))
                return redirect()->route($this->table . '.index')
                    ->with('message', 'Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type', 'danger');

            Montajes::destroy(Crypt::decryptString($r->id));

            return redirect()->route($this->table . '.index')
                ->with('message', 'Registro eliminado con exito');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $th->getMessage() . ')')
                ->with('type', 'danger');
        }
    }
        public function status($id)
    {
        try{
            $p = montajes::findOrFail(Crypt::decryptString($id));
            $p->estado = !$p->estado;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Estado modificado correctamente.')
                ->with('type','success');
        }
        catch(\Throwable $th){
            return redirect()->route($this->table.'.index')
                ->with('message','Error al cambiar estado: '.$th->getMessage())
                ->with('type','danger');
        }
    }
}

<?php

namespace App\Http\Controllers;
use App\Http\Requests\StoregaleriasRequest;
use App\Http\Requests\UpdategaleriasRequest;
use App\Models\categoria_fotos;
use App\Models\galerias;
use App\Models\montajes;
use App\Models\montajes_galerias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
class GaleriasController extends Controller
{
    private $table = 'galerias';

    public function __construct(){
        $this->getTh($this->table,'Galerias');
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
            'p'=>galerias::orderBy('id','DESC')->paginate(10),
            'data' => [
                'categoria_fotos' => categoria_fotos::orderBy('id', 'DESC')->get(),
            ],
        ]);
    }
    public function search(Request $r){
        return view($this->table . '.index', [
            'th'         => $this->th['index'],
            'p'       => galerias::where('descripcion', 'ilike', '%' . $r->txtBusqueda . '%')->paginate(),
            'txtBusqueda' => $r->txtBusqueda,
            'table' => $this->table,
            'data' => [
                'categoria_fotos' => categoria_fotos::orderBy('id', 'DESC')->get(),
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
            return view($this->table.'.create',[
            'th'=>$this->th['create'],
            'table' => $this->table,
            'data' => [
                'categoria_fotos' => categoria_fotos::orderBy('id', 'DESC')->get(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoregaleriasRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoregaleriasRequest $request)
    {

        try{
            $nombreFoto = $this->saveImage($request);
            $data = new Galerias;
            $data->foto = $nombreFoto;
            $data->descripcion = $request->descripcion;
            $data->categoria_fotos_id = $request->categoria_fotos_id;
            $data->save();

            return to_route($this->table.'.index')
                ->with('message','Registro guardado correctamente: '.$data->descripcion)
                ->with('type','success');
        }
        catch(\Throwable $th){
            return redirect()->route($this->table.'.index')
                ->with('message','Error al guardar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }
    private function saveImage($request){
          $file = $request->file('foto');

            if (!isset($file) || !$request->hasFile('foto')) {
                return redirect()->route($this->table . '.index')->with('danger', 'Debe seleccionar una imagen tipo jpg o png');
            }

            $nombre = date('YmdHis') . '_' . $file->getClientOriginalName();
            Storage::disk('multimedia')->put($nombre, File::get($file));

            return $nombre;
    }
    public function galeriaFoto(Request $request)
    {
        try {
            $request->validate([
                'foto' => ['required'],
                'descripcion' => ['required', 'string'],
                'categoria_fotos_id' => ['required'],
            ]);
            dd($request->all());
        $addFoto = $this->saveImage($request);
        $af = new galerias;
        $af->foto = $addFoto;
        $af->descripcion = $request->descripcion;
        $af->categoria_fotos_id = Crypt::decryptString($request->categoria_fotos_id);
        $af->save();
            (new EventosGaleriasController())->galeriaEvento($af->id, Crypt::decryptString($request->eventos_id));
        return redirect()->back()
                ->with('message','Se agrego la foto  correctamente a galerias de el evento: ')
                ->with('type','success');
        }catch(\Throwable $th)
        {
            return redirect()->route($this->table.'.index')
                ->with('message','Error al guardar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }
    /***que agrega foto desde el modal para agregar  */
    public function addGaleria(Request $r)
    {
        try {
            $r->validate([
                'foto' => ['required'],
                'descripcion' => ['required', 'string'],
                'categoria_fotos_id' => ['required'],
            ]);

            $addFoto = $this->saveImage($r);
            $af = new galerias;
            $af->foto = $addFoto;
            $af->descripcion = $r->descripcion;
            $af->categoria_fotos_id = Crypt::decryptString($r->categoria_fotos_id);
            $af->save();
            (new EventosGaleriasController())->galeriaEvento($af->id, Crypt::decryptString($r->eventos_id));

            return redirect()->back()
                ->with('message', 'Se agrego la foto  correctamente: ')
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
     * @param  \App\Models\galerias  $galerias
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $galeria = galerias::findOrFail(Crypt::decryptString($id));
        $montajes_galerias = montajes_galerias::with(['galerias','montajes'])->where('galerias_id',$galeria->id)->get();
        $montajes = montajes::all();
        return view($this->table. '.show',[
            'th'=>$this->th['show'] = [
            'title' => $galeria->descripcion,
            'table' => $this->table,
            'bread'=> $this->table . '.show'
            ],
            'p'=> $galeria,
            'montajes' => $montajes,
            'table'=> $this->table,
            'montajes_galerias'=> $montajes_galerias,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\galerias  $galerias
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            return view($this->table . ".edit",[
                'th' => $this->th['edit'],
                'p' => galerias::findOrFail(Crypt::decryptString($id)),
                'table' => $this->table,
                'data' => [
                    'categoria_fotos' => categoria_fotos::orderBy('id', 'DESC')->get(),
                ],
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
     * @param  \App\Http\Requests\UpdategaleriasRequest  $request
     * @param  \App\Models\galerias  $galerias
     * @return \Illuminate\Http\Response
     */
    public function update(UpdategaleriasRequest $request)
    {
        try{
            $nombreFoto = $this->saveImage($request);
            $p = galerias::findOrFail($request->id);
            $p->foto = $nombreFoto;
            $p->descripcion = $request->descripcion;
            $p->categoria_fotos_id = $request->categoria_fotos_id;
            $p->save();

            return redirect()->route($this->table.'.index')
                ->with('message','Registro editado correctamente: '.$p->descripcion)
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
                'p' => galerias::findOrFail(Crypt::decryptString($id))
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
     * @param  \App\Models\galerias  $galerias
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            if (!isset($r->id) || empty(trim($r->id)))
                return redirect()->route($this->table . '.index')
                    ->with('message', 'Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type', 'danger');

            galerias::destroy(Crypt::decryptString($r->id));

            return redirect()->route($this->table . '.index')
                ->with('message', 'Registro eliminado con exito');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $th->getMessage() . ')')
                ->with('type', 'danger');
        }
    }
}

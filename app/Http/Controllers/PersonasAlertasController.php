<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storepersonas_alertasRequest;
use App\Http\Requests\Updatepersonas_alertasRequest;
use App\Models\personas_alertas;

#Add
use Throwable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ViewToExcel;

#Import
use App\Imports\PersonasAlertasImport;

class PersonasAlertasController extends Controller
{
    private $table = 'personas_alertas';

    public function __construct()
    {
        $this->getTh($this->table, 'Personas Alertas');
    }

    public function index(){
        return view($this->table . '.index', [
            'th' => $this->th['index'],
            'p' => personas_alertas::orderBy('id', 'desc')->paginate(15),
        ]);
    }

    public function search(Request $r){
        $p = personas_alertas::where('nombres', 'ilike', '%' . $r->txtBusqueda . '%')
            ->orWhere('apellidos', 'ilike', '%' . $r->txtBusqueda . '%')
            ->orWhere('alias', 'ilike', '%' . $r->txtBusqueda . '%')
            ->orWhere('numero_identificacion', 'ilike', '%' . $r->txtBusqueda . '%')
            ->paginate(200);

        return view($this->table . '.index', [
            'th' => $this->th['index'],
            'p' => $p,
            'txtBusqueda' => $r->txtBusqueda,
        ]);
    }

    public function create(){
        return view($this->table . '.create', [
            'th' => $this->th['create'],
        ]);
    }

    public function store(Storepersonas_alertasRequest $r){
        try{
            $p = new personas_alertas;
            $p->nombres = $r->nombres;
            $p->apellidos = $r->apellidos;
            $p->alias = $r->alias;
            $p->numero_identificacion = $r->numero_identificacion;
            $p->users_id = auth()->id();
            $p->save();

            return to_route($this->table.'.index')
                ->with('type','success')
                ->with('message','Registro guardado correctamente: '.$p->nombres.' '.$p->apellidos);
        }
        catch(Throwable $th){
            return to_route($this->table.'.index')
                ->with('type','danger')
                ->with('message','Error: '.$th->getMessage());
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id){
        try{
            $p = personas_alertas::find(Crypt::decryptString($id));

            if(!$p)
                throw new Exception('No se encontró el registro solicitado.');

            return view($this->table.'.edit',[
                'th'=>$this->th['edit'],
                'table'=>$this->table,
                'p'=>$p,
            ]);
        }
        catch(Throwable $th){
            return to_route($this->table.'.index')
                ->with('type','danger')
                ->with('message','Error: '.$th->getMessage());
        }
    }

    public function update(Updatepersonas_alertasRequest $r){
        try{
            $p = personas_alertas::find(Crypt::decryptString($r->id));

            if(!$p)
                throw new Exception('No se encontró el registro solicitado.');

            $p->nombres = $r->nombres;
            $p->apellidos = $r->apellidos;
            $p->alias = $r->alias;
            $p->numero_identificacion = $r->numero_identificacion;
            $p->users_id = auth()->id();
            $p->save();

            return to_route($this->table.'.index')
                ->with('type','success')
                ->with('message','Registro editado correctamente: '.$p->nombres.' '.$p->apellidos);
        }
        catch(Throwable $th){
            return to_route($this->table.'.index')
                ->with('type','danger')
                ->with('message','Error: '.$th->getMessage());
        }
    }

    public function confirm($id){
        try{
            return view('confirm',[
                'th'=>$this->th['confirm'],
                'p'=>personas_alertas::find(Crypt::decryptString($id)),
            ]);
        }
        catch(Throwable $th){
            return redirect()->back()
                ->with('type','danger')
                ->with('message','Ocurrio un error: '.$th->getMessage());
        }
    }

    public function destroy(Request $r){
        try{
            if(!isset($r->id) || empty($r->id))
                return to_route($this->table.'.index')
                    ->with('type','danger')
                    ->with('message','Ocurrio un error, el identificador no cumple los requerimientos necesarios.');
            
            $p = personas_alertas::find(Crypt::decryptString($r->id));
            $p->delete();

            return to_route($this->table.'.index')
                ->with('type','success')
                ->with('message','Registro eliminado correctamente: '.$p->nombres.' '.$p->apellidos);
        }
        catch(Throwable $th){
            return redirect()->back()
                ->with('type','danger')
                ->with('message','Ocurrio un error: '.$th->getMessage());
        }
    }

    public function ilicita($id){
        try{
            $p = personas_alertas::find(Crypt::decryptString($id));

            if(!$p)
                throw new Exception('No se encontró el registro solicitado.');

            $p->ilicita = !$p->ilicita;
            $p->save();

            return to_route($this->table.'.index')
                ->with('type','success')
                ->with('message','Se cambió el estado (ilicita) del registro: '.$p->nombres.' '.$p->apellidos);
        }
        catch(Throwable $th){
            return to_route($this->table.'.index')
                ->with('type','danger')
                ->with('message','Error: '.$th->getMessage());
        }
    }

    public function peps($id){
        try{
            $p = personas_alertas::find(Crypt::decryptString($id));

            if(!$p)
                throw new Exception('No se encontró el registro solicitado.');

            $p->peps = !$p->peps;
            $p->save();

            return to_route($this->table.'.index')
                ->with('type','success')
                ->with('message','Se cambió el estado (peps) del registro: '.$p->nombres.' '.$p->nombres);
        }
        catch(Throwable $th){
            return to_route($this->table.'.index')
                ->with('type','danger')
                ->with('message','Error: '.$th->getMessage());
        }
    }

    public function status($id){
        try{
            $p = personas_alertas::find(Crypt::decryptString($id));

            if(!$p)
                throw new Exception('No se encontró el registro solicitado.');

            $p->estado = !$p->estado;
            $p->save();

            return to_route($this->table.'.index')
                ->with('type','success')
                ->with('message','Se cambió el estado (alerta) del registro: '.$p->nombres.' '.$p->apellidos);
        }
        catch(Throwable $th){
            return to_route($this->table.'.index')
                ->with('type','danger')
                ->with('message','Error: '.$th->getMessage());
        }
    }

    public function descargarFormato($format){
        if(!isset($format) || $format == null)
            throw new Exception('No se encotró el parámetro: format o su valor es nulo.');

        $f = Crypt::decryptString($format);
        switch($f){
            case 1:#Excel
                $model = \Maatwebsite\Excel\Excel::XLSX;
                $extention = 'xlsx';
            break;
            case 2:#CSV
                $model = \Maatwebsite\Excel\Excel::CSV;
                $extention = 'csv';
            break;
            default:
                throw new Exception('El valor del parámetro: format debe ser 1 o 2.');
        }

        $v = view($this->table.'.formato_excel');

        $rs = Excel::download(new ViewToExcel($v), 'formato_importacion_personas_alertas.'.$extention, $model);
        ob_end_clean();

        return $rs;
    }

    public function importData(Request $r){
        try{
            $file = $r->personas_alertas_import;#Tomar el archivo del request
        
            #Verificar si el archivo cargado es válido (Si se cargo bien durante el request, no está dañado y no excede en tamaño establecido en php.ini)
            if(!isset($file) || !$file->isValid())
                throw new Exception('No se seleccionó ningún archivo o no es válido.');

            #Verificar la extensión del archivo
            $extention = $file->getClientOriginalExtension();
            if(!in_array($extention, ['xlsx','csv']))
                throw new Exception('El archivo debe ser Excel o CSV.');
            
            Excel::import(new PersonasAlertasImport, $file, null, $extention === 'csv' ? \Maatwebsite\Excel\Excel::CSV : \Maatwebsite\Excel\Excel::XLSX);

            return to_route($this->table.'.index')
                ->with('type','success')
                ->with('message','Registros importados correctamente.');
        }
        catch(Throwable $th){
            return to_route($this->table.'.index')
                ->with('type','danger')
                ->with('message','Error al importar datos: '.$th->getMessage());
        }
    }

    public function exportData($extention){
        try{
            if(!isset($extention) || $extention == null)
                throw new Exception('No se encontró el parámetro: extention o su valor es nulo.');

            $e = Crypt::decryptString($extention);
            switch($e){
                case 1:#Excel
                    $model = \Maatwebsite\Excel\Excel::XLSX;
                    $ext = 'xlsx';
                break;
                case 2:#CSV
                    $model = \Maatwebsite\Excel\Excel::CSV;
                    $ext = 'csv';
                break;
                default:
                    throw new Exception('El valor del parámetro: extention debe ser 1 o 2.');
            }

            $v = view($this->table.'.export_excel',[
                'p'=>personas_alertas::all(),
            ]);

            $rs = Excel::download(new ViewToExcel($v), 'exportacion_personas_alertas.'.$ext, $model);
            ob_end_clean();

            return $rs;
        }
        catch(Throwable $th){
            return to_route($this->table.'.index')
                ->with('type','danger')
                ->with('message','Error: '.$th->getMessage());
        }
    }

    public function apiNombres(Request $r)
    {
        //cSpell:ignore busqueda, ilike, identificacion
        try {
            $busqueda = $r->busqueda;
            $arrBusqueda = array_filter(explode(' ', $busqueda));;

            if (count($arrBusqueda) == 0)
                return response()->json(['list' => [], 'count' => 0, 'error' => false]);


            $p = personas_alertas::query();
            foreach ($arrBusqueda as $v) {
                if (strlen($v) > 3) {
                    $vu = "%" . strtoupper($v) . "%";
                    $p =
                        $p->where('nombres', 'ilike', $vu)
                        ->orWhere('apellidos', 'ilike', $vu)
                        ->orWhere('alias', 'ilike', $vu);
                }
            }
            $p = $p->get();

            return response()->json(['list' => $p, 'count' => $p->count(), 'error' => false]);
        } catch (\Throwable $th) {
            return response()->json(['list' => [], 'count' => 0, 'error' => true, 'message' => $th->getMessage()]);
        }
    }
    public function apiIdentificaciones(Request $r)
    {
        try {
            $busqueda = $r->busqueda;

            if (strlen($busqueda) == 0)
                return response()->json(['list' => [], 'count' => 0, 'error' => false]);
            $p = personas_alertas::where('estado', true)
                ->where('numero_identificacion', 'ilike', '%' . strtoupper($busqueda) . '%')
                ->get();
            return response()->json(['list' => $p, 'count' => $p->count(), 'error' => false]);
        } catch (\Throwable $th) {
            return response()->json(['list' => [], 'count' => 0, 'error' => true, 'message' => $th->getMessage()]);
        }
    }
}

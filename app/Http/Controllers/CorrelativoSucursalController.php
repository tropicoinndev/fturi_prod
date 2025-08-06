<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storecorrelativo_sucursalRequest;
use App\Http\Requests\Updatecorrelativo_sucursalRequest;
use App\Models\correlativo_sucursal;
use App\Models\correlativos;
use App\Models\sucursales;
use Exception;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

#Solo para pruebas, debe eliminarse
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

use function PHPUnit\Framework\returnSelf;

#use InvalidArgumentException;

class CorrelativoSucursalController extends Controller
{
    private $table = 'correlativo_sucursal';

    public function __construct()
    {
        $this->getTh($this->table, 'Correlativo Sucursales');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\Storecorrelativo_sucursalRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $r){
        try{
            #Asignacion de datos POST a variables
            $sucursalId = Crypt::decryptString($r->sucursalId);
            $tipoDte    = Crypt::decryptString($r->tipoDte);

            #Validacion de datos
            if($sucursalId <= 0 || $sucursalId === null)
                throw new Exception('No se encontró el parámetro: sucursal_id.');
            if($tipoDte <= 0 || $tipoDte === null)
                throw new Exception('No se encontró el parámetro: tipo_dte.');

            #---Validar si la sucursal existe---
            $s = sucursales::find($sucursalId);

            if(!$s)
                throw new Exception('No se encontró la sucursal solicitada.');
            #---------------------------

            #---Validar que ya exista un correlativo asignado a esa sucursal---
            if(correlativo_sucursal::where('sucursales_id',$s->id)->where('tipo_dte',$tipoDte)->first())
                throw new Exception('Ya existe un DTE asignado a la sucursal: '.$s->sucursal);
            #-----------------------------

            #---Almacenamiento de datos---
            $d = new correlativo_sucursal();
            $d->year = date('Y');
            $d->sucursales_id = $s->id;
            $d->tipo_dte = $tipoDte;
            $d->save();

            return response()->json([
                'status' => true,
                'message' => 'Correlativo agregado correctamente.',
            ]);
        } catch (Throwable $t) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $t->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\correlativo_sucursal  $correlativo_sucursal
     * @return \Illuminate\Http\Response
     */
    public function show(correlativo_sucursal $correlativo_sucursal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\correlativo_sucursal  $correlativo_sucursal
     * @return \Illuminate\Http\Response
     */
    public function edit(correlativo_sucursal $correlativo_sucursal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatecorrelativo_sucursalRequest  $request
     * @param  \App\Models\correlativo_sucursal  $correlativo_sucursal
     * @return \Illuminate\Http\Response
     */
    public function update(Updatecorrelativo_sucursalRequest $r)
    {
        try {
            #Asignacion de datos POST a variables
            $id         = Crypt::decryptString($r->id);
            $actual     = intval($r->actual);
            $sucursalId = Crypt::decryptString($r->sucursalId);
            $tipoDte    = intval($r->tipoDte);

            #Validacion de datos
            if ($id <= 0 || $id === null)
                throw new Exception('No se encontró el parámetro: id.');
            if ($actual < 0 || $actual === null)
                throw new Exception('El valor: actual debe ser positivo.');
            if($sucursalId <= 0 || $sucursalId === null)
                throw new Exception('No se encontró el parámetro: sucursal_id.');
            if($tipoDte <= 0 || $tipoDte === null)
                throw new Exception('No se encontró el parámetro: tipo_dte.');

            #---Validar coincidencias---
            $cs = correlativo_sucursal::where('id',$id)
                ->where('sucursales_id',$sucursalId)
                ->where('tipo_dte',$tipoDte)
                ->first();

            if (!$cs)
                throw new Exception('No se encontró el registro solicitado.');
            #---------------------------

            #Actualizacion de datos
            $cs->actual = $actual;
            $cs->save();

            return response()->json([
                'status' => true,
                'message' => 'Se actualizó con exito en correlativo actual.',
            ]);
        } catch (Throwable $t) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $t->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\correlativo_sucursal  $correlativo_sucursal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            #---Validar coincidencias---
            $p = correlativo_sucursal::find(Crypt::decryptString($r->id));

            if (!$p)
                throw new Exception('No se encontró el registro solicitado.');
            #---------------------------

            #---Eliminacion de registro---
            if($p->estado)
               throw new Exception('No se puede eliminar el correlativo, ya que aún está activo.');
            else if($p->actual !== 0)
                throw new Exception('No se puede eliminar el correlativo, ya que aún está en uso.');
            else
                $p->delete();
            #-----------------------------

            return response()->json([
                'status' => true,
                'message' => 'Correlativos eliminado con exito: ' . $p->sucursales->sucursal,
            ]);
        } catch (Throwable $t) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $t->getMessage()
            ]);
        }
    }


    public function getCorrelativo($sucursal_id, $tipo_dte): int
    {
        try {
            $p = correlativo_sucursal::where("sucursales_id", $sucursal_id)
                ->where("estado", true)
                ->where("year", date("Y"))
                ->where('tipo_dte', $tipo_dte)
                ->whereColumn("actual", "<", "final")
                ->first();

            if ($p == null or $p->id == null)
                throw new Exception("No se encontró un correlativo disponible para esta sucursal, debe agregar o reiniciar el correlativo");

            $correlativo = $p->actual + 1;
            $p->actual = $correlativo;
            if ($p->actual == $p->final)
                $p->estado = false;
            $p->save();

            return $correlativo;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getData(Request $r)
    {
        try {
            #---Validar id de sucursal---
            $sucursalId = Crypt::decryptString($r->sucursalId);

            if ($sucursalId <= 0 || $sucursalId === null)
                throw new Exception('No se encontró el parámetro: sucursal_id.');
            #----------------------------

            #---Validar coindidencias---
            $p = correlativo_sucursal::with('sucursales')
                ->where('sucursales_id', $sucursalId)
                ->orderBy('id', 'desc')
                ->get();

            if ($p->count() <= 0)
                throw new Exception('No hay ningún correlativo asigando a esta sucursal.');
            #---------------------------

            return response()->json([
                'status' => true,
                'message' => 'Datos encontrados.',
                'correlativoSucursals' => $p,
            ]);
        } catch (Throwable $t) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $t->getMessage(),
            ]);
        }
    }

    public function status(Request $r)
    {
        try {
            #---Validar coincidencias---
            $p = correlativo_sucursal::find(Crypt::decryptString($r->id));

            if (!$p)
                throw new Exception('No se encontró el registro solicitado.');
            #---------------------------

            $p->estado = !$p->estado;
            $p->save();

            return response()->json([
                'status' => true,
                'message' => 'Estado modificado con exito: ' . $p->sucursales->sucursal,
            ]);
        } catch (Throwable $t) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $t->getMessage(),
            ]);
        }
    }

    public function restart(Request $r)
    {
        try {
            #---Validar coincidencias---
            $p = correlativo_sucursal::find(Crypt::decryptString($r->id));

            if (!$p)
                throw new Exception('No se encontró el registro solicitado.');
            #---------------------------
            
            #Actualizar año y reiniciar correlativo a cero
            $currentYear = date('Y');

            if($currentYear <= $p->year){
                throw new Exception('El año almacenado en la Base de Datos: '.$p->year.' está atrasado o es igual al año actual: '.$currentYear.'; No se puede reiniciar el correlativo.');
            }
            else{
                $p->year = $currentYear;
                $p->actual = 0;
                $p->save();
            }

            return response()->json([
                'status' => true,
                'message' => 'Se reinicio con exito el correlativo actual a cero.',
            ]);
        } catch (Throwable $t) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $t->getMessage(),
            ]);
        }
    }

    #Solo para pruebas, deben eliminarse
    public function getPDF(): DomPDFPDF
    {
        $pdf = PDF::getFacadeRoot();
        $dompdf = $pdf->getDomPDF();
        $dompdf->setHttpContext(
            stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ]),
        );
        return $pdf;
    }

    public function formatoDteCcf()
    {
        $pdf = $this->getPDF();
        $pdf->loadView('mail.fefc2', [
            'data' => 'Hello Wordl.',
        ]);
        return $pdf->stream();
    }
}

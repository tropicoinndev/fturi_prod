<?php

namespace App\Http\Controllers;

use App\Exports\viewExport;
use App\Models\bodega_users;
use App\Models\bodegas;
use App\Models\existencias;
use App\Models\productos;
use App\Models\requisicion_detalles;
use App\Models\requisiciones;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ExistenciasController extends Controller
{
    private $table = 'existencias';

    public function __construct()
    {
        $this->getTh($this->table, 'Existencias');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $r)
    {
        $requisiciones = requisiciones::with('relacionBodegasEntrada', 'relacionBodegasSalida')
            ->findOrFail(Crypt::decryptString($r->existenciasId));
        return view('existencias.index', [
            'th'   => $this->th['index'],
            'table' => $this->table,
            'p'    => $requisiciones,
            'existencias' => $this->getExistencias(($requisiciones->id)),
        ]);
    }
    /*API PARA BUSQUEDA DE PRODUCTOS CON EXISTENCIAS */
    public function productosExistencias(Request $r)
    {
        $txtBusqueda = $r->input('txtBusqueda');

        $id = $r->id;

        $requisicion = requisiciones::find($id);
        $bodegaId = $requisicion->relacionbodegasSalida->id;
        // pendiente corregir y revisar si se usara el de la requisicion o en bodega logueado
        $resultados = DB::table('getexistenciasbyproducto')
            ->where('producto_nombre', 'ilike', '%' . $txtBusqueda . '%')
            ->where('bodegas_id', $bodegaId)
            ->get();
        if (count($resultados) > 0) {
            //  devuelve los nombres de los productos en existencia
            return response()->json([
                'nombresProductosEnExistencia' => $resultados
            ]);
        } else {
            // Si no se encontraron resultados, envía un mensaje de que no se encontraron productos
            return response()->json([
                'mensaje' => 'NO SE ENCONTARON PRODUCTOS O NO TIENE EXISTENCIA'
            ]);
        }
    }

    static public function crearExistencias($tipoBodega, $cantidadHistorial, $existenci, $p_costo, $idProductos, $idRequisicionDetalles, $fechaVencimiento)
    {
        try {

            $existencias                          = new existencias;
            $existencias->bodegas_id              = $tipoBodega;
            $existencias->cantidad_historial      = $cantidadHistorial;
            $existencias->existencia              = $existenci;
            $existencias->productos_id            = $idProductos;
            $existencias->requisicion_detalles_id = $idRequisicionDetalles;
            $existencias->vencimiento             = $fechaVencimiento;
            $existencias->precio_costo             = $p_costo;
            $existencias->save();

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function getExistencias($requisionId)
    {
        return requisicion_detalles::with('relacionProductos', 'relacionLotes', 'relacionExistencias', 'relacionRequisiciones')->where('requisiciones_id', '=', $requisionId)->orderBy('id', 'DESC')->get();
    }

    static public function borrarExistencias($requisicionDetalleId)
    {
        #return (existencias::destroy($arrayExistencias->id)) ? true : false;
        existencias::where('requisicion_detalles_id', '=', $requisicionDetalleId)->delete();
    }
    public function productos(Request $r)
    {
        $productos = Cache::remember('productos_' . $r->input('productos_id'), now()->addMinutes(10), function () use ($r) {
            return Productos::when($r->has('productos_id') && strlen($r->input('productos_id')) >= 4, function ($query) use ($r) {
                return $query->where('nombre', 'ilike', '%' . $r->input('productos_id') . '%');
            })->get(); // se trabajo de esta forma por que seran miles de productos que se buscaran
        });
        if (count($productos) === 0) {
            return response([
                'productos' => [],
                'mensaje' => 'NO SE ENCONTRO EL PRODUCTO'
            ]);
        }

        return response([
            'productos' => $productos,

        ]);
    }
    /***REPORTE DE EXISTENCIAS POR PRODUCTO Y BODEGA */

    public function reporte_existencias(Request $r)
    {
        if (!session('bodega'))
            return redirect()->route("bodegas.login");
        if (isset($r->bodega_users_id) && isset($r->productos_id)) {
            $v = $r->validate([
                'bodega_users_id' => ['required', 'int'],
                'productos_id' => ['required', 'int'],
            ]);
            if (!$v) {
                return redirect()
                    ->back()
                    ->with('message', 'los datos  no son validos')
                    ->with('type', 'danger');
            }
        }
        if (isset($r->accion)) {
            $accion = Crypt::decryptString($r->accion);

            if ($accion == 2 && isset($r->bodega_users_id) && isset($r->productos_id)) { //al usar && me funciona la busqueda de producto y bodega
                return $this->getReporteExistencias($r->bodega_users_id, $r->productos_id);
            }
        }
        $usuarioLogueado = Auth::user();


        $bodegalogueado = session('bodega')->id;
        $bodegaId = $r->bodega_users_id;
        $productosId = $r->productos_id;
        $bodega = bodega_users::with(['relacionUsuarios', 'relacionBodegas'])
            ->where('users_id', $usuarioLogueado->id)
            ->orderBy('id', 'DESC')
            ->get();
        $reporteExistencias = existencias::leftJoin('requisicion_detalles', 'existencias.requisicion_detalles_id', '=', 'requisicion_detalles.id')
            ->leftJoin('requisiciones', 'requisicion_detalles.requisiciones_id', '=', 'requisiciones.id')
            ->leftJoin('bodegas as bodega_entrada', 'requisiciones.bodega_entrada_id', '=', 'bodega_entrada.id')  // Bodega de entrada
            ->leftJoin('bodegas as bodega_salida', 'requisiciones.bodega_salida_id', '=', 'bodega_salida.id')
            ->with(['productosExistencias', 'bodegasExistencias'])
            ->select([
                'existencias.id as lote',
                'existencias.vencimiento as vencimiento',
                'existencias.existencia as existencia',
                'requisiciones.fecha',
                'requisicion_detalles.productos_id',
                'bodega_entrada.bodega as bodega_entrada',
                'bodega_salida.bodega as bodega_salida',
                'requisiciones.updated_at as salio',
                'requisiciones.created_at as ingreso',
            ])
            ->where('existencias.existencia', '>', 0)
            ->where('existencias.bodegas_id', $bodegaId)
            ->where('existencias.productos_id', $productosId)
            ->where('existencias.estado', true)
            ->orderBy('existencias.id', 'DESC')
            ->get();
        return view('existencias.reporte_existencias', compact('bodegaId', 'reporteExistencias', 'bodega', 'bodegalogueado', 'productosId'));
    }
    public function getReporteExistencias($bodegaId, $productosId)
    {
        if (!session('bodega'))
            return redirect()->route("bodegas.login");
        return view('existencias.container_existencias', ['url' => route('existencias.existencias_reporte_pdf', ['bodegaId' => Crypt::encryptString($bodegaId), 'productosId' => Crypt::encryptString($productosId), 'bodega' => Crypt::encryptString(session('bodega')->id)])]);
    }
    protected function getPDF(): DomPDFPDF
    {
        $pdf = PDF::getFacadeRoot();
        $dompdf = $pdf->getDomPDF();
        $dompdf->setHttpContext(stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE
            ],
        ]));
        return $pdf;
    }
    public function getReportePDF(Request $r)
    {
        $usuarioLogueado = Auth::user();
        $bodegalogueado = session('bodega')->id;
        $bodega = bodega_users::with(['relacionUsuarios', 'relacionBodegas'])
            ->where('users_id', $usuarioLogueado->id)
            ->orderBy('id', 'DESC')
            ->get();
        $bodegaId = Crypt::decryptString($r->bodegaId);
        $bodega = bodegas::find(Crypt::decryptString($r->bodega));
        $productosId = Crypt::decryptString($r->productosId);
        $reporteExistencias = existencias::leftJoin('requisicion_detalles', 'existencias.requisicion_detalles_id', '=', 'requisicion_detalles.id')
            ->leftJoin('requisiciones', 'requisicion_detalles.requisiciones_id', '=', 'requisiciones.id')
            ->leftJoin('bodegas as bodega_entrada', 'requisiciones.bodega_entrada_id', '=', 'bodega_entrada.id')  // Bodega de entrada
            ->leftJoin('bodegas as bodega_salida', 'requisiciones.bodega_salida_id', '=', 'bodega_salida.id')
            ->leftJoin('users as user_crea', 'requisiciones.user_creacion_id', '=', 'user_crea.id')

            ->with(['productosExistencias', 'bodegasExistencias'])
            ->select([
                'existencias.id as lote',
                'existencias.vencimiento as vencimiento',
                'existencias.existencia as existencia',
                'existencias.precio_costo as precio_costo',
                'requisiciones.fecha',
                'requisicion_detalles.productos_id',
                'bodega_entrada.bodega as bodega_entrada',
                'bodega_salida.bodega as bodega_salida',
                'requisiciones.updated_at as salio',
                'requisiciones.created_at as ingreso',
                'user_crea.name as user_crea',
            ])
            ->where('existencias.existencia', '>', 0)
            ->where('existencias.bodegas_id', $bodegaId)
            ->where('existencias.productos_id', $productosId)
            ->where('existencias.estado', true)
            ->orderBy('existencias.id', 'DESC')
            ->get();

        return Pdf::loadView('existencias.existencias_print', compact('bodegaId', 'reporteExistencias', 'bodega', 'bodegalogueado', 'productosId'))
            ->setPaper('letter', 'landscape')
            ->stream();
    }
    /**REPORTE DE EXISTENCIAS POR BODEGAS */
    public function reporte_bodega_existencia(Request $r)
    {
        if (!session('bodega'))
            return redirect()->route("bodegas.login");
        try {
            if (isset($r->bodega_users_id)) {
                $v = $r->validate([
                    'bodega_users_id' => ['required', 'int']
                ]);
                if (!$v) {
                    return redirect()
                        ->back()
                        ->with('message', 'el id de bodega no es valido')
                        ->with('type', 'danger');
                }
            }
            if (isset($r->accion)) {
                $accion = Crypt::decryptString($r->accion);

                if ($accion == 2 && isset($r->bodega_users_id)) { //al usar && me funciona la busqueda de producto y bodega
                    return $this->getReporteExistenciasBodega($r->bodega_users_id);
                }
            }
            $usuarioLogueado = Auth::user();


            $bodegalogueado = session('bodega')->id;
            $bodegaId = $r->bodega_users_id;
            $bodega = bodega_users::with(['relacionUsuarios', 'relacionBodegas'])
                ->where('users_id', $usuarioLogueado->id)
                ->orderBy('id', 'DESC')
                ->get();

            $existencias = existencias::leftJoin('requisicion_detalles', 'existencias.requisicion_detalles_id', '=', 'requisicion_detalles.id')
                ->leftJoin('requisiciones', 'requisicion_detalles.requisiciones_id', '=', 'requisiciones.id')
                ->leftJoin('bodegas as bodega_entrada', 'requisiciones.bodega_entrada_id', '=', 'bodega_entrada.id')  // Bodega de entrada
                ->leftJoin('bodegas as bodega_salida', 'requisiciones.bodega_salida_id', '=', 'bodega_salida.id')
                ->leftJoin('users as user_crea', 'requisiciones.user_creacion_id', '=', 'user_crea.id')
                ->with(['productosExistencias', 'bodegasExistencias'])
                ->select([
                    'existencias.id as lote',
                    'existencias.vencimiento as vencimiento',
                    'existencias.existencia as existencia',
                    'requisiciones.fecha',
                    'existencias.precio_costo as precio_costo',
                    'requisicion_detalles.productos_id',
                    'bodega_entrada.bodega as bodega_entrada',
                    'bodega_salida.bodega as bodega_salida',
                    'user_crea.name as user_crea',
                    'requisiciones.updated_at as salio',
                    'requisiciones.created_at as ingreso',
                ])
                ->where('existencias.existencia', '>', 0)
                ->where('existencias.bodegas_id', $bodegaId)
                ->where('existencias.estado', true)
                ->orderBy('existencias.id', 'DESC')
                ->get();

            return view('existencias.existencia_bodega', compact('bodegaId', 'existencias', 'bodega', 'bodegalogueado'));
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrió un error inesperado: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function getReporteExistenciasBodega($bodegaId)
    {
        if (!session('bodega'))
            return redirect()->route("bodegas.login");
        return view('existencias.container_existenciabyBodega', ['url' => route('existencias.reporte_existencia_bodega_pdf', ['bodegaId' => Crypt::encryptString($bodegaId), 'bodega' => Crypt::encryptString(session('bodega')->id)])]);
    }
    public function getReporteBodegaExistenciaPDF(Request $r)
    {
        $usuarioLogueado = Auth::user();
        $bodegalogueado = session('bodega')->id;
        $bodega = bodega_users::with(['relacionUsuarios', 'relacionBodegas'])
            ->where('users_id', $usuarioLogueado->id)
            ->orderBy('id', 'DESC')
            ->get();
        $bodegaId = Crypt::decryptString($r->bodegaId);
        $bodega = bodegas::find(Crypt::decryptString($r->bodega));
        $reporteExistenciasBodega = existencias::leftJoin('requisicion_detalles', 'existencias.requisicion_detalles_id', '=', 'requisicion_detalles.id')
            ->leftJoin('requisiciones', 'requisicion_detalles.requisiciones_id', '=', 'requisiciones.id')
            ->leftJoin('bodegas as bodega_entrada', 'requisiciones.bodega_entrada_id', '=', 'bodega_entrada.id')  // Bodega de entrada
            ->leftJoin('bodegas as bodega_salida', 'requisiciones.bodega_salida_id', '=', 'bodega_salida.id')
            ->leftJoin('bodegas as bodega_existencia', 'existencias.bodegas_id', '=', 'bodega_existencia.id')
            ->leftJoin('users as user_crea', 'requisiciones.user_creacion_id', '=', 'user_crea.id')
            ->with(['productosExistencias', 'bodegasExistencias', 'requisicionExistencias'])
            ->select([
                'existencias.id as lote',
                'existencias.vencimiento as vencimiento',
                'existencias.existencia as existencia',
                'requisiciones.fecha',
                'requisicion_detalles.productos_id',
                'existencias.precio_costo as precio_costo',
                'bodega_entrada.bodega as bodega_entrada',
                'bodega_salida.bodega as bodega_salida',
                'bodega_existencia.bodega as bodega_existe',
                'user_crea.name as user_crea',
                'requisiciones.updated_at as salio',
                'requisiciones.created_at as ingreso',
            ])
            ->where('existencias.existencia', '>', 0)
            ->where('existencias.bodegas_id', $bodegaId)
            ->where('existencias.estado', true)
            ->orderBy('existencias.id', 'DESC')
            ->get();

        return Pdf::loadView('existencias.existencia_bodega_print', compact('bodegaId', 'reporteExistenciasBodega', 'bodega', 'bodegalogueado'))
            ->setPaper('letter', 'landscape')
            ->stream();
    }

    public function reportesVencimientos()
    {
        $prox = Carbon::now()->addDays(15)->format("Y-m-d");
        return view('existencias.vencidosForm', ['bodegas' => bodegas::all(), 'prox' => $prox]);
    }
    public function getReporteVencimiento(Request $r)
    {
        $prox = Carbon::now()->addDays(15)->format("Y-m-d");
        $hoy = Carbon::now()->format("Y-m-d");

        if ($r->fecha != null)
            $prox = Carbon::parse($r->fecha)->format("Y-m-d");

        $bodega = [];
        foreach ($r->bodegas as $v) {
            if ($v == 0) {
                $bodega = [];
                break;
            } else
                array_push($bodega, $v);
        }

        $vencidos = existencias::where('vencimiento', '<=', $hoy)->where("existencia", ">", 0)->orderBy('vencimiento');
        $proximos = existencias::whereBetween('vencimiento', [$hoy, $prox])->where("existencia", ">", 0)->orderBy('vencimiento');

        if (isset($bodega) && count($bodega) > 0) {
            $vencidos = $vencidos->whereIn('bodegas_id', $bodega);
            $proximos = $proximos->whereIn('bodegas_id', $bodega);
        }

        $vencidos = $vencidos->get();
        $proximos = $proximos->get();
        switch ($r->accion) {
            case 1:
                return view('existencias.vencidosPreview', ['vencidos' => $vencidos, 'proximos' => $proximos, 'prox' => $prox]);
                break;
            case 2:
                $snap = SnappyPdf::loadView('existencias.vencidosPrint', ['vencidos' => $vencidos, 'proximos' => $proximos, 'prox' => $prox])
                    ->setPaper('letter')
                    ->setOption('margin-top', '10mm')
                    ->setOption('margin-bottom', '10mm')
                    ->setOption('margin-left', '10mm')
                    ->setOption('margin-right', '10mm');
                return $snap->inline('reporte_vencimiento' . date("d_m_Y") . '.pdf');
                break;
            case 3:
                $view = view('existencias.vencidosExcel', ['vencidos' => $vencidos, 'proximos' => $proximos, 'prox' => $prox]);
                $rs = Excel::download(new viewExport($view), 'reporte_vencimiento' . date("d_m_Y") . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
                ob_end_clean();
                return $rs;

                break;
        }
    }
}

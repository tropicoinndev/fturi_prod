<?php

namespace App\Http\Controllers;

use App\Exports\InvalidacionesExport;
use App\Exports\SujetosExcluidosExport;
use App\Exports\SujetosExcluidosMigracionExport;
use App\Exports\VentasConsumidorExport;
use App\Exports\VentasContribuyentesExport;
use App\Models\anulacion_comprobantes;
use App\Models\anulaciones;
use App\Models\comprobantes;
use App\Models\sucursales;
use App\Models\tipo_comprobantes;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDF;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LibrosContablesController extends Controller
{
    public function contribuyentes(Request $r)
    {
        return view('report.iva.contribuyentes', ['sucursales' => sucursales::all()]);
    }

    public function consumidor(Request $r)
    {
        return view('report.iva.consumidor', ['sucursales' => sucursales::all()]);
    }

    public function getReportContribuyentes(Request $r)
    {

        $r->validate([
            'sucursales_id' => ['required', 'string'],
            'mes' => ['required', 'date_format:Y-m'],
            'cantidad' => ['required', 'integer'],
        ]);
        try {

            setlocale(LC_TIME, 'es_ES.UTF-8');
            $sucursal = sucursales::find(Crypt::decryptString($r->sucursales_id));
            $cantidad = $r->cantidad;
            $actual = Carbon::createFromFormat('Y-m', $r->mes);
            $mes = $actual->format('m');
            $anio = $actual->format('Y');
            $ultimoDia = $actual->endOfMonth()->day;
            $tipo = tipo_comprobantes::whereIn('token', [7001, 7003])->get();

            $comprobantes = comprobantes::whereIn('tipo_comprobantes_id', $tipo->pluck('id'))
                ->whereMonth('fecha', $mes)
                ->whereYear('fecha', $anio)
                ->where('sucursales_id', $sucursal->id)
                ->where('eliminado', false)
                ->orderBy('fecha', 'asc')
                ->orderBy('tipo_comprobantes_id', 'asc')
                ->orderBy('created_at', 'asc')
                ->get();


            if ($comprobantes->count() == 0)
                return redirect()->back()->with('message', 'No se encontraron datos de la sucursal, y mes seleccionado. Verifique los parámetros de generacion del libro e intente generarlo nuevamente');

            $pdf = $this->getPDF();

            $pdf->loadView('report.iva.contribuyentes_print', [
                'comprobantes' => $comprobantes,
                'fecha' => $actual,
                'cantidad' => $cantidad,
                'sucursal' => $sucursal,
            ]);
            $pdf->setPaper('letter', 'landscape');
            return $pdf->stream();
        } catch (\Throwable $th) {
            throw $th;
            /*
           return redirect()->back()
                ->with('message', 'Error: ' . $th->getMessage())
                ->with('type', 'danger');
                */
        }
    }

    public function getReportConsumidor(Request $r)
    {
        $r->validate([
            'sucursales_id' => ['required', 'string'],
            'mes' => ['required', 'date_format:Y-m'],
            'cantidad' => ['required', 'integer'],
        ]);
        try {
            setlocale(LC_TIME, 'es_ES.UTF-8');
            $sucursal = sucursales::find(Crypt::decryptString($r->sucursales_id));
            $cantidad = $r->cantidad;
            $actual = Carbon::createFromFormat('Y-m', $r->mes);
            $mes = $actual->format('m');
            $anio = $actual->format('Y');

            $tipo = tipo_comprobantes::whereIn('token', [7002])->get();

            $comprobantes = DB::table('libro_consumidor_final')
                ->whereMonth('fecha', $mes)
                ->whereYear('fecha', $anio)
                ->where('sucursales_id', $sucursal->id)
                ->get();
            /*
            $anulaciones = comprobantes::whereIn('tipo_comprobantes_id', $tipo->pluck('id'))
                ->leftJoin('anulacion_comprobantes as ac', "ac.comprobantes_id", "comprobantes.id")
                ->whereMonth('comprobantes.fecha', $mes)
                ->whereYear('comprobantes.fecha', $anio)
                ->where('sucursales_id', $sucursal->id)
                ->where('estado', false)
                ->select(['comprobantes.*', 'ac.fecha as anulacion'])
                ->orderBy('created_at', 'asc')
                ->get();
*/
            if ($comprobantes->count() == 0)
                return redirect()->back()->with('message', 'No se encontraron datos de la sucursal, y mes seleccionado. Verifique los parámetros de generacion del libro e intente generarlo nuevamente');


            $pdf = $this->getPDF();

            $pdf->loadView('report.iva.consumidor_print', [
                'comprobantes' => $comprobantes,
                'fecha' => $actual,
                'cantidad' => $cantidad,
                'sucursal' => $sucursal,
                //'anulaciones' => $anulaciones
            ]);
            $pdf->setPaper('letter', 'landscape');
            return $pdf->stream();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    protected function getPDF(): DomPDF
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

    public function getDays($mes, $anio)
    {
        if ($mes < 1 || $mes > 12)
            throw new Exception('El mes debe ser entre 1 y 12');

        return cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
    }


    public function anexosContribuyentes(Request $r)
    {
        return view('report.anexos_ventas.contribuyentes');
    }

    public function anexosConsumidor(Request $r)
    {
        return view('report.anexos_ventas.consumidor');
    }

    public function anexosContribuyentesAccion(Request $r)
    {
        $r->validate([
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date'],
            'anuladas' => ['nullable', 'integer'],
            'accion' => ['required', 'integer', 'min:1', 'max:3'],
        ]);
        try {
            switch ($r->accion) {
                case 1:
                    $data = $this->getDataContribuyenteAnexo($r->fecha_inicio, $r->fecha_fin, $r->anuladas);
                    return view('report.anexos_ventas.contribuyentes_preview', ['data' => $data, 'anulacion' => isset($r->anuladas)]);
                    break;
                case 2:
                    return $this->getExcel($r->fecha_inicio, $r->fecha_fin, isset($r->anuladas));
                    break;
                case 3:
                    return $this->getCSV($r->fecha_inicio, $r->fecha_fin);
                    break;
                default:
                    return redirect()->back()->with('message', 'No se encontró la acción seleccionada')->with('type', 'danger');
                    break;
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'Error: ' . $th->getMessage())->with('type', 'danger');
        }
    }

    public function getDataContribuyenteAnexo($fecha_inicio, $fecha_fin, $anuladas = null)
    {
        try {
            $data = DB::table('anexo_contribuyentes')->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
            if ($anuladas != null && $anuladas == 1)
                $data = $data->where('emision', '!=', null)
                    ->orderBy('anulacion', 'asc')
                    ->orderBy('emision', 'asc');
            else
                $data = $data->where('anulacion_estado', null)

                    ->orderBy('emision', 'asc');
            return $data->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getExcel($fecha_inicio, $fecha_fin, $anuladas = false)
    {
        try {
            $name = 'ventas_contribuyentes_' . $fecha_inicio . '_al_' . $fecha_fin . '.xlsx';
            return Excel::download(new VentasContribuyentesExport($fecha_inicio, $fecha_fin, $anuladas, true), $name);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getCSV($fecha_inicio, $fecha_fin, $anuladas = false)
    {
        try {
            $name = 'ventas_contribuyentes_' . $fecha_inicio . '_al_' . $fecha_fin . '.csv';
            return Excel::download(new VentasContribuyentesExport($fecha_inicio, $fecha_fin, $anuladas), $name);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function anexosConsumidorAccion(Request $r)
    {
        $r->validate([
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date'],
            'accion' => ['required', 'integer', 'min:1', 'max:3'],
        ]);
        try {
            switch ($r->accion) {
                case 1:
                    $data = $this->getDataConsumidorAnexo($r->fecha_inicio, $r->fecha_fin);
                    return view('report.anexos_ventas.consumidor_preview', ['data' => $data]);
                    break;
                case 2:
                    return $this->getExcelConsumidor($r->fecha_inicio, $r->fecha_fin);
                    break;
                case 3:
                    return $this->getCSVConsumidor($r->fecha_inicio, $r->fecha_fin);
                    break;
                default:
                    return redirect()->back()->with('message', 'No se encontró la acción seleccionada')->with('type', 'danger');
                    break;
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'Error: ' . $th->getMessage())->with('type', 'danger');
        }
    }

    public function getDataConsumidorAnexo($fecha_inicio, $fecha_fin)
    {
        try {
            return DB::table('anexo_consumidor')->whereBetween('fecha', [$fecha_inicio, $fecha_fin])->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getExcelConsumidor($fecha_inicio, $fecha_fin)
    {
        try {
            $name = 'ventas_consumidor_final_' . $fecha_inicio . '_al_' . $fecha_fin . '.xlsx';
            return Excel::download(new VentasConsumidorExport($fecha_inicio, $fecha_fin, true), $name);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getCSVConsumidor($fecha_inicio, $fecha_fin)
    {
        try {
            $name = 'ventas_consumidor_final_' . $fecha_inicio . '_al_' . $fecha_fin . '.csv';
            return Excel::download(new VentasConsumidorExport($fecha_inicio, $fecha_fin), $name);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function anexosInvalidados(Request $r)
    {
        return view('report.anexos_ventas.invalidados');
    }
    public function anexosInvalidadosAccion(Request $r)
    {
        $r->validate([
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date'],
            'accion' => ['required', 'integer', 'min:1', 'max:3'],
        ]);
        try {
            switch ($r->accion) {
                case 1:
                    $data = $this->getDataInvalidadosAnexo($r->fecha_inicio, $r->fecha_fin);
                    return view('report.anexos_ventas.invalidados_preview', ['data' => $data]);
                    break;
                case 2:
                    return $this->getExcelInvalidados($r->fecha_inicio, $r->fecha_fin);
                    break;
                case 3:
                    return $this->getCSVInvalidados($r->fecha_inicio, $r->fecha_fin);
                    break;
                default:
                    return redirect()->back()->with('message', 'No se encontró la acción seleccionada')->with('type', 'danger');
                    break;
            }
        } catch (\Throwable $th) {
            throw $th;
            //return redirect()->back()->with('message', 'Error: ' . $th->getMessage())->with('type', 'danger');
        }
    }

    public function getDataInvalidadosAnexo($fecha_inicio, $fecha_fin)
    {
        try {
            return DB::table('anexo_invalidaciones')->whereBetween('anulacion', [$fecha_inicio, $fecha_fin])->orderBy('tipo_documento', 'asc')->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getExcelInvalidados($fecha_inicio, $fecha_fin)
    {
        try {
            $name = 'invalidaciones_' . $fecha_inicio . '_al_' . $fecha_fin . '.xlsx';
            return Excel::download(new InvalidacionesExport($fecha_inicio, $fecha_fin, true), $name);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getCSVInvalidados($fecha_inicio, $fecha_fin)
    {
        try {
            $name = 'invalidaciones_' . $fecha_inicio . '_al_' . $fecha_fin . '.csv';
            return Excel::download(new InvalidacionesExport($fecha_inicio, $fecha_fin), $name);
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function anexosSujetosExcluidos(Request $r)
    {
        return view('report.anexos_compras.sujetos_excluidos');
    }
    public function anexosSujetosExcluidosAccion(Request $r)
    {
        $r->validate([
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date'],
            'accion' => ['required', 'integer', 'min:1', 'max:4'],
        ]);
        try {
            switch ($r->accion) {
                case 1:
                    $data = $this->getDataSujetosAnexo($r->fecha_inicio, $r->fecha_fin);
                    return view('report.anexos_compras.sujetos_excluidos_preview', ['data' => $data]);
                    break;
                case 2:
                    return $this->getExcelSujetos($r->fecha_inicio, $r->fecha_fin);
                    break;
                case 3:
                    return $this->getCSVSujetos($r->fecha_inicio, $r->fecha_fin);
                    break;
                case 4:
                    return $this->getExcelSujetosMigracion($r->fecha_inicio, $r->fecha_fin);
                    break;
                default:
                    return redirect()->back()->with('message', 'No se encontró la acción seleccionada')->with('type', 'danger');
                    break;
            }
        } catch (\Throwable $th) {
            throw $th;
            //return redirect()->back()->with('message', 'Error: ' . $th->getMessage())->with('type', 'danger');
        }
    }

    public function getDataSujetosAnexo($fecha_inicio, $fecha_fin)
    {
        try {
            return DB::table('anexo_sujetos')->whereBetween('fecha', [$fecha_inicio, $fecha_fin])->orderBy('tipo_documento', 'asc')->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getExcelSujetos($fecha_inicio, $fecha_fin)
    {
        try {
            $name = 'sujetos_excluidos_' . $fecha_inicio . '_al_' . $fecha_fin . '.xlsx';
            return Excel::download(new SujetosExcluidosExport($fecha_inicio, $fecha_fin, true), $name);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getCSVSujetos($fecha_inicio, $fecha_fin)
    {
        try {
            $name = 'sujetos_excluidos_' . $fecha_inicio . '_al_' . $fecha_fin . '.csv';
            return Excel::download(new SujetosExcluidosExport($fecha_inicio, $fecha_fin), $name);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function getExcelSujetosMigracion($fecha_inicio, $fecha_fin)
    {
        try {
            $name = 'sujetos_excluidos_migracion_' . $fecha_inicio . '_al_' . $fecha_fin . '.xlsx';
            return Excel::download(new SujetosExcluidosMigracionExport($fecha_inicio, $fecha_fin, true), $name);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

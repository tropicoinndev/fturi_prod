<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storedte_contingenciasRequest;
use App\Models\contingencias;
use App\Models\dte_contingencias;
use App\Models\dtes;
use Carbon\Carbon;
use Exception;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class DteContingenciasController extends Controller
{

    public function index()
    {
        $last = Carbon::now()->subDays(15)->format("Y-m-d");
        $dtes = dte_contingencias::where("resuelto", false)->whereNotIn('id', function ($q) use ($last) {
            $q->from('mh_contingencia_items')->select('dtes_id')->whereDate('created_at', '>=', $last);
        })->get();
        $contingencias = contingencias::where('estado', true)->get();
        return view('dtes.contingencias_config', ['dtes' => $dtes, "contingencias" => $contingencias]);
    }

    public function store(Storedte_contingenciasRequest $r)
    {
        try {
            $contingencias = $r->contingencias;

            $opciones = (int) intval($r->opciones);
            if ($contingencias == null || count($contingencias) == 0) throw new Exception("Debe seleccionarse uno o mas DTEs.");
            $ids = $this->getIds($contingencias);
            //return var_dump($ids);
            switch ($opciones) {
                case 1:
                    if ($this->validarDiferentes($ids) > 1) throw new Exception("No se pueden agregar de diferentes tipos de contingencias, seleccione solo un tipo de contingencia");
                    $p = (new MhContingenciasController)->setCreateContingencia($ids);
                    if ($p && $p->id != null)
                        dte_contingencias::whereIn('id', $ids)->update(['resuelto' => true]);
                    return redirect()->route('dte.contingencias_mh', ['id' => Crypt::encryptString($p->id)]);
                    break;
                case 2:
                    $this->setResueltas($ids);
                    break;
                case 3:
                    $contingencias_id = Crypt::decryptString($r->contingencias_id);
                    if ($contingencias_id == null || !($contingencias_id > 0))
                        throw new Exception("Debe seleccionarse un tipo de contingencias");

                    $this->setUpdateContingencia($ids, $contingencias_id);
                    break;
                default:
                    throw new Exception("Opcion no encontrada");
                    break;
            }
            return redirect()
                ->back()
                ->with("message", "Cambios realizados con éxito");
        } catch (\Throwable $th) {
            throw $th;
            return redirect()
                ->back()
                ->with('type', "danger")
                ->with("message", "Error: " . $th->getMessage());
        }
    }

    private function getIds(array $contingencias): array
    {
        $id = array();
        foreach ($contingencias as $v) {
            array_push($id, intval(Crypt::decryptString($v)));
        }
        return $id;
    }
    public function validarDiferentes(array $contingencias)
    {
        return dte_contingencias::whereIn('id', $contingencias)
            ->distinct("contingencias_id")
            ->count();
    }
    public function setResueltas(array $contingencias)
    {

        dte_contingencias::whereIn('id', $contingencias)->update(['resuelto' => true]);
    }

    public function setUpdateContingencia(array $contingencias, int $contingencias_id)
    {

        dte_contingencias::whereIn('id', $contingencias)->update(['contingencias_id' => $contingencias_id]);
    }
    public function newAutoContingencia($dtes_id, $contingencias_id)
    {
        try {
            $dte = dte_contingencias::where('dtes_id', $dtes_id)->first();
            if ($dte != null && $dte->id > 0)
                return $dte;
            $d = dtes::find($dtes_id);

            $p = new dte_contingencias;
            $p->fecha_comprobante = $d->comprobante?->fecha ?? $d->sujeto?->fecha;
            $p->modelo_factura = 2;
            $p->tipo_transmision = 2;
            $p->contingencias_id = $contingencias_id;
            $p->dtes_id = $dtes_id;
            $p->users_id = Auth::user()->id;
            $p->created_at = $d->comprobante?->created_at ?? $d->sujeto?->created_at;
            $p->save();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

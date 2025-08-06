<?php

namespace App\Http\Controllers;

use App\Exports\ViewToExcel;
use App\Http\Requests\StorerosRequest;
use App\Http\Requests\UpdaterosRequest;
use App\Models\comprobantes;
use App\Models\forma_pagos;
use App\Models\identificaciones;
use App\Models\ros;
use App\Models\sucursales;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;

class RosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ros.index', ['data' => ros::orderByDesc('id')->paginate(10)]);
    }

    public function search(Request $r)
    {
        $data = ros::where('nombre', 'ilike', '%' . strtoupper($r->busqueda) . '%')->orWhere('fecha', 'like', '%' . $r->busqueda . '%')->orderByDesc('id')->paginate(1000);
        return view('ros.index', ['data' => $data, 'busqueda' => $r->busqueda]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ros.create', [
            'sucursales' => sucursales::all(),
            'formas' => forma_pagos::all(),
            'identificaciones' => identificaciones::where('tipo_cliente', true)->where('estado', true)->get()
        ]);
    }

    public function comprobantes(Request $r)
    {
        try {
            $c = intval($r->bq);
            return response()->json(['list' => comprobantes::with(['clientes', 'sucursal'])->where('correlativo', $c)->get(), 'error' => false]);
        } catch (\Throwable $th) {
            return response()->json(['list' => [], 'error' => true, 'message' => $th->getMessage()]);
        }
    }

    public function store(StorerosRequest $r)
    {
        try {
            $p = new ros;
            $p->comprobantes_id = $r->comprobantes_id;
            $p->sucursales_id = $r->sucursales_id;

            $p->fecha = $r->fecha;
            $p->forma_pagos_id = $r->forma_pagos_id;
            $p->monto = $r->monto;
            $p->clase_producto = $r->clase;
            $p->nombre = $r->nombre;
            $p->identificaciones_id = $r->identificaciones;
            $p->numero_identificacion = $r->numero;
            $p->observaciones = $r->observaciones;
            $p->cargo = $r->cargo;
            $p->users_id = Auth::user()->id;
            $p->save();
            return redirect()->route('ros.index')->with('message', 'Se guardo correctamente el reporte');
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'Error: ' . $th->getMessage());
        }
    }
    public function print(Request $r)
    {
        try {

            $snap = SnappyPdf::loadView('ros.print', [
                'p' => ros::find(Crypt::decryptString($r->id))
            ])
                ->setPaper('letter')
                ->setOption('margin-top', '10mm')
                ->setOption('margin-bottom', '10mm')
                ->setOption('margin-left', '10mm')
                ->setOption('margin-right', '10mm');

            return $snap->inline('ros.pdf');
        } catch (\Throwable $th) {
            return response()->json(['list' => [], 'error' => true, 'message' => $th->getMessage()]);
        }
    }
    public function excel(Request $r)
    {
        try {

            $v = view('ros.excel', ['p' => ros::find(Crypt::decryptString($r->id))]);
            $rs = Excel::download(new ViewToExcel($v), 'ros.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            ob_end_clean();
            return $rs;
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('message', 'Error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
}

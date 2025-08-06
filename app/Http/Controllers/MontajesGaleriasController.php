<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storemontajes_galeriasRequest;
use App\Http\Requests\Updatemontajes_galeriasRequest;
use App\Models\montajes_galerias;
use App\Models\montajes;
use App\Models\galerias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
class MontajesGaleriasController extends Controller
{
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storemontajes_galeriasRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storemontajes_galeriasRequest $r)
    {
        try {
        $montaje = montajes::find(Crypt::decryptString($r->montaje));
        $galeria = galerias::find($r->galeria);

        if (montajes_galerias::where('montajes_id', $montaje->id)->where('galerias_id', $galeria->id)->count() > 0) {
            montajes_galerias::where('montajes_id', $montaje->id)->where('galerias_id', $galeria->id)->delete();
            $message = "Se eliminó correctamente";
        } else {
            $p = new montajes_galerias;
            $p->montajes_id = $montaje->id;
            $p->galerias_id = $galeria->id;
            $p->save();
            $message = "Se agregó correctamente";
        }

        $list = montajes_galerias::where("montajes_id", $montaje->id)->with("galerias")->get();

        return response()->json(['message' => $message, 'type' => "info", "list" => $list]);
    } catch (\Throwable $th) {
        return response()->json(['message' => "Ocurrió un error:" . $th->getMessage(), 'type' => "info"]);
    }
            
    }
    // * funcion para agregar montajes a galeria desde su show
    public function storeMontaje(Request $r)
    {
        try {
        $galeria = galerias::find(Crypt::decryptString($r->galeria));
        $montaje = montajes::find($r->montaje);

        if (montajes_galerias::where('galerias_id', $galeria->id)->where('montajes_id', $montaje->id)->count() > 0) {
            montajes_galerias::where('galerias_id', $galeria->id)->where('montajes_id', $montaje->id)->delete();
            $message = "Se eliminó correctamente";
        } else {
            $p = new montajes_galerias;
            $p->montajes_id = $montaje->id;
            $p->galerias_id = $galeria->id;
            $p->save();
            $message = "Se agregó correctamente";
        }

        $list = montajes_galerias::where("galerias_id", $galeria->id)->with("montajes")->get();

        return response()->json(['message' => $message, 'type' => "info", "list" => $list]);
    } catch (\Throwable $th) {
        return response()->json(['message' => "Ocurrió un error:" . $th->getMessage(), 'type' => "info"]);
    }
            
    }
    


}

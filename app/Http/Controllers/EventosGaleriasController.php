<?php

namespace App\Http\Controllers;
use App\Http\Requests\Storeeventos_galeriasRequest;
use App\Models\eventos_galerias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Throwable;

class EventosGaleriasController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storeeventos_galeriasRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storeeventos_galeriasRequest $request)
    {
        //
    }

    //**funcion para guardar la nueva foto a evento */
    public function galeriaEvento($galeria, $evento)
    {
        try {
            $ge = new eventos_galerias();
            $ge->galerias_id = $galeria;
            $ge->eventos_id = $evento;
            $ge->save();
            return $ge;
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    //***funcion para actualizar la galeria de evento*/
    public function editFoto(Request $r)
    {
        try {
            $r->validate([
                'galeriasEvento' =>['required'],
                'eventos_id' =>['required'],
            ], [
            'galeriasEvento.required' => 'seleccione al menos una foto de galerias para poder actualizar la galeria de evento.',
        ]);
            eventos_galerias::where('eventos_id', Crypt::decryptString($r->eventos_id))->delete();
            if (isset($r->galeriasEvento) && count($r->galeriasEvento) >0) {
                foreach ($r->galeriasEvento as $g) {
                    $gaE = new eventos_galerias();
                    $gaE->galerias_id = $g;
                    $gaE->eventos_id = Crypt::decryptString($r->eventos_id);
                    $gaE->save();
                }
            }
            return redirect()
                ->back()
                ->with('message', 'Se actualizaron las galeria del evento #: ' . Crypt::decryptString($r->eventos_id))
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrió un problema al actualizar la galeria, error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    //funcion para anadir fotos desde el detalle eventos
    //***funcion para agregar la galerias de evento*/
    public function addFoto(Request $r)
    {
        try {
            $r->validate([
                'galerias' =>['required'],
                'eventos_id' =>['required'],
            ],[
            'galerias.required' => 'seleccione al menos una foto para asociarla ala galeria de eventos.',
            ]);
            $existingGalerias = eventos_galerias::whereIn('galerias_id', $r->galerias)
                ->where('eventos_id', Crypt::decryptString($r->eventos_id))
                ->pluck('galerias_id')
                ->toArray();

            $nuevasGalerias = array_diff($r->galerias, $existingGalerias);

            if (!empty($nuevasGalerias)) {
                foreach ($nuevasGalerias as $g) {
                    $gaE = new eventos_galerias();
                    $gaE->galerias_id = $g;
                    $gaE->eventos_id = Crypt::decryptString($r->eventos_id);
                    $gaE->save();
                }
                return redirect()
                    ->back()
                    ->with('message', 'Se agregaron fotos nuevas a la galería del evento  #: ' . Crypt::decryptString($r->eventos_id) . '  se crearon  ' . count($nuevasGalerias) . ' galeria(s).')
                    ->with('type', 'success');
            } else {
                return redirect()
                    ->back()
                    ->with('message', 'No se agregaron fotos nuevas a la galería del evento  #: ' . Crypt::decryptString($r->eventos_id) . ' ya existen  ' . count($existingGalerias ?? []) . ' galeria(s).')
                    ->with('type', 'danger');
            }
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrió un problema al agregar la galería, error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
}

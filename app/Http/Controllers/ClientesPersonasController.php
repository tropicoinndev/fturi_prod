<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storeclientes_personasRequest;
use App\Http\Requests\Updateclientes_personasRequest;
use App\Models\clientes_personas;

#Add
use Illuminate\Http\Request;
use Exception;
use Throwable;

class ClientesPersonasController extends Controller
{
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
     * Store a new client-person relation.
     *
     * Este método guarda la relación entre un cliente y una persona natural en la base de datos.
     * 
     * @param \App\Models\clientes $clientes_id
     * @param \App\Models\personas_naturales $personas_naturales_id
     * 
     * @return \Illuminate\Http\JsonResponse Respuesta JSON que indica si la operación fue exitosa o si ocurrió un error.
     */
    public function apiStore(Storeclientes_personasRequest $r){
        try{
            if(!isset($r->clientes_id) || $r->clientes_id == null)
                throw new Exception('Debe proporcionar un valor para el parámetro: cliente id');

            if(!isset($r->personas_naturales_id) || $r->personas_naturales_id == null)
                throw new Exception('Debe proporcionar un valor para el parámetro: persona natural id');

            $d = new clientes_personas;
            $d->clientes_id = Crypt::decryptString($r->clientes_id);
            $d->personas_naturales_id = Crypt::decryptString($r->personas_naturales_id);
            $d->save();

            return response()->json([
                'status'=>true,
                'message'=>'Registro guardado correctamente.',
            ]);
        }
        catch(Throwable $th){
            return response()->json([
                'status'=>false,
                'message'=>'Error: '.$th->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\clientes_personas  $clientes_personas
     * @return \Illuminate\Http\Response
     */
    public function show(clientes_personas $clientes_personas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\clientes_personas  $clientes_personas
     * @return \Illuminate\Http\Response
     */
    public function edit(clientes_personas $clientes_personas)
    {
        //
    }

    /**
     *
     * Este método actualiza los campos de clientes_id y personas_naturales_id según el propio id de la tabla.
     * 
     * @param \App\Models\clientes_personas $id (El id corresponde al id de ésta misma tabla)
     * @param \App\Models\clientes $clientes_id
     * @param \App\Models\personas_naturales $personas_naturales_id
     * 
     * @return \Illuminate\Http\JsonResponse Respuesta JSON que indica si la operación fue exitosa o si ocurrió un error.
     */
    public function apiUpdate(Updateclientes_personasRequest $r){
        try{
            #Validación de parámetros
            if(!isset($r->id) || $r->id == null)
                throw new Exception('Debe proporcionar un valor para el parámetro: id');

            if(!isset($r->clientes_id) || $r->clientes_id == null)
                throw new Exception('Debe proporcionar un valor para el parámetro: cliente id');

            if(!isset($r->personas_naturales_id) || $r->personas_naturales_id == null)
                throw new Exception('Debe proporcionar un valor para el parámetro: persona natural id');

            #Encontrar registro solicitado
            $d = personas_naturales::find(Crypt::decryptString($r->id));
            if(!$d)
                throw new Exception('No se encontró el registro solicitado.');

            #Actualización de datos
            $d->clientes_id = Crypt::decryptString($r->clientes_id);
            $d->personas_naturales_id = Crypt::decryptString($r->personas_naturales_id);
            $d->save();

            return response()->json([
                'status'=>true,
                'message'=>'Registro editado correctamente.',
            ]);
        }
        catch(Throwable $th){
            return response()->json([
                'status'=>false,
                'message'=>'Error: '.$th->getMessage(),
            ]);
        }
    }

    /**
     *
     * Este método elimina el registro solicitado.
     * 
     * @param \App\Models\clientes_personas $id (El id corresponde al id de ésta misma tabla)
     * 
     * @return \Illuminate\Http\JsonResponse Respuesta JSON que indica si la operación fue exitosa o si ocurrió un error.
     */
    public function apiDestroy(Request $r){
        try{
            #Validación del parámetro id
            if(!isset($r->id) || $r->id == null)
                throw new Exception('Debe proporcionar un valor para el parámetro: id');

            #Encontrar registro solicitado
            $d = personas_naturales::find(Crypt::decryptString($r->id));
            if(!$d)
                throw new Exception('No se encontró el registro solicitado.');

            #Eliminación de registro
            $d->delete();
            
            return response()->json([
                'status'=>true,
                'message'=>'Registro eliminado correctamente.',
            ]);
        }
        catch(Throwable $th){
            return response()->json([
                'status'=>false,
                'message'=>'Error: '.$th->getMessage(),
            ]);
        }
    }
}

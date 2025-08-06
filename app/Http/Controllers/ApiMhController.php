<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storeapi_mhRequest;
use App\Http\Requests\Updateapi_mhRequest;
use App\Models\api_mh;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;

class ApiMhController extends Controller
{
    private $user;
    private $pwd;
    private $url;

    public function __construct()
    {
        $this->user = env('nit', '12170509850014');
        $this->pwd = env('PASSWORD_API');
        $this->url = env('HOST_API');
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
     * @param  \App\Http\Requests\Storeapi_mhRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function getAuth()
    {
        try {

            $p = api_mh::where("estado", true)->where('finalizacion', '>',  now())->first();
            if ($p && $p->token != null)
                return $p;

            $url = $this->url . "/seguridad/auth";
            $data = [
                "pwd" => $this->pwd,
                "user" => $this->user,
            ];
            $solicitud = now();
            $rs = Http::withHeaders(['Content-Type' => 'application/x-www-form-urlencoded'])
                ->asForm()
                ->post($url, $data);
            //return $rs;
            if ($rs->successful()) {
                $rp = json_decode($rs->body(), false);
                $rt = $rp->body;

                //Deshabilitar los tokens vencidos
                api_mh::where('finalizacion', '<', now())->where('estado', true)->update(['estado' => false]);

                $api = new api_mh;
                $api->user = $rt->user;
                $api->token = $rt->token;
                $api->rol = json_encode($rt->rol);
                $api->roles = json_encode($rt->roles);
                $api->token_type = $rt->tokenType;
                $api->sesion = $solicitud;
                $api->finalizacion = Carbon::parse($solicitud)->addHours(24);
                $api->estado = true;
                $api->save();
                return $api;
            } else {
                throw new Exception('No se pudo autenticar en MH por favor espere un momento y vuelva a intentar');
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function loginMh()
    {
        try {
            $this->getAuth();
            return redirect()->back()->with("message", 'Se realizo la autenticación.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('type', 'danger')
                ->with("message", 'Error al autenticar: ' . $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\api_mh  $api_mh
     * @return \Illuminate\Http\Response
     */
    public function show(api_mh $api_mh)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\api_mh  $api_mh
     * @return \Illuminate\Http\Response
     */
    public function edit(api_mh $api_mh)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updateapi_mhRequest  $request
     * @param  \App\Models\api_mh  $api_mh
     * @return \Illuminate\Http\Response
     */
    public function update(Updateapi_mhRequest $request, api_mh $api_mh)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\api_mh  $api_mh
     * @return \Illuminate\Http\Response
     */
    public function destroy(api_mh $api_mh)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storecliente_girosRequest;
use App\Http\Requests\Updatecliente_girosRequest;
use App\Models\cliente_giros;
use App\Models\clientes;
use App\Models\giros;

class ClienteGirosController extends Controller
{
      private $table = 'cliente_giros';

    public function __construct(){
        $this->getTh($this->table,'Cliente_giros');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         return view($this->table.'.index',[
            'th'=>$this->th['index'],
            'p'=>clientes::orderBy('id','DESC')->paginate(15),
            'table'=>$this->table,
            'data' => [
                'clientes'=>clientes::orderBy('nombre', 'ASC')->get(),
                'giros'=>giros::orderBy('giros','ASC')->get(),
            ],
        ]);
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
     * @param  \App\Http\Requests\Storecliente_girosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storecliente_girosRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\cliente_giros  $cliente_giros
     * @return \Illuminate\Http\Response
     */
    public function show(cliente_giros $cliente_giros)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\cliente_giros  $cliente_giros
     * @return \Illuminate\Http\Response
     */
    public function edit(cliente_giros $cliente_giros)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatecliente_girosRequest  $request
     * @param  \App\Models\cliente_giros  $cliente_giros
     * @return \Illuminate\Http\Response
     */
    public function update(Updatecliente_girosRequest $request, cliente_giros $cliente_giros)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\cliente_giros  $cliente_giros
     * @return \Illuminate\Http\Response
     */
    public function destroy(cliente_giros $cliente_giros)
    {
        //
    }
}

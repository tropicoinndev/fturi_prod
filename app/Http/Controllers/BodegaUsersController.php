<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storebodega_usersRequest;
use App\Http\Requests\Updatebodega_usersRequest;
use App\Models\bodega_users;

class BodegaUsersController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storebodega_usersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storebodega_usersRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\bodega_users  $bodega_users
     * @return \Illuminate\Http\Response
     */
    public function show(bodega_users $bodega_users)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\bodega_users  $bodega_users
     * @return \Illuminate\Http\Response
     */
    public function edit(bodega_users $bodega_users)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatebodega_usersRequest  $request
     * @param  \App\Models\bodega_users  $bodega_users
     * @return \Illuminate\Http\Response
     */
    public function update(Updatebodega_usersRequest $request, bodega_users $bodega_users)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\bodega_users  $bodega_users
     * @return \Illuminate\Http\Response
     */
    public function destroy(bodega_users $bodega_users)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }
    public function index_api()
    {
        return response()->json(['list' => $this->getList()]);
    }

    private function getList()
    {
        return Permission::get();
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    public function store_api(Request $r)
    {
        $m = 'Ok';
        $t = 'info';
        try {
            Permission::create(['name' => $r->permission]);
        } catch (\Throwable $e) {
            $m = 'Error' . $e->getMessage();
            $t = 'danger';
        }
        return response()->json(
            [
                'list' => $this->getList(),
                'message' => $m,
                'type' => $t
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function destroy_api(Request $r)
    {
        $m = "Se elimino un permiso";
        $t = true;
        try {
            Permission::destroy($r->id);
        } catch (\Throwable $th) {
            $t = false;
            $m = "Error: " + $th->getMessage();
        }
        return response()->json([
            'list' => $this->getList(),
            'message' => $m,
            'type' => $t ? 'success' : 'danger',
        ]);
    }
}

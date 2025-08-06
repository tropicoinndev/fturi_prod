<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $th;

    protected function getTh($table, $title = '', $btnAdd = true)
    {
        if (empty(trim($title)))
            $title = $table;

        $this->th = [
            'index'     => [
                'title'     => $title,
                'sub'       => 'Listado de ' . $title,
                'table'     => $table,
                'bread'     => $table,
                'btnAdd'    => $btnAdd,
            ],
            'create'    => [
                'title'     => $title,
                'sub'       => 'Agregar ' . $title,
                'table'     => $table,
                'bread'     => $table . '.create'
            ],
            'edit'      => [
                'title'     => $title,
                'sub'       => 'Editar ' . $title,
                'table'     => $table,
                'bread'     => $table . '.edit'
            ],
            'confirm'       => [
                'title'     => $title,
                'sub'       => 'Eliminar ' . $title,
                'table'     => $table,
                'bread'     => $table . '.confirm'
            ],
            'confirmOrden'  => [
                'title'     => $title,
                'sub'       => 'Anular ' . $title,
                'table'     => $table,
                'bread'     => $table . '.confirm'
            ],
            'confirmCompra' => [
                'title'     => $title,
                'sub'       => 'Anular ' . $title,
                'table'     => $table,
                'bread'     => $table . '.confirm'
            ],
            'confirmRequisicion' => [
                'title'     => $title,
                'sub'       => 'Anular ' . $title,
                'table'     => $table,
                'bread'     => $table . '.confirm'
            ],
            'ordenesEvento' => [
                'title'     => $title,
                'sub'       => 'Ordenes del evento ' . $title,
                'table'     => $table,
                'bread'     => $table . '.confirm'
            ],
            'show'          => [
                'title'     => $title,
                'sub'       => 'Detalle ' . $title,
                'table'     => $table,
                'bread'     => $table . '.show'
            ],
            'correlativoSucursals'=>[
                'title'     => 'Correlativo ' . $title,
                'sub'       => 'Correlativo ' . $title,
                'table'     => $table,
                'bread'     => $table . '.show'
            ],
            'update'        => [
                'title'     => $title,
                'sub'       => 'Actualizar ' . $title,
                'table'     => $table,
                'bread'     => $table . '.update'
            ],
            'precioCajas'   => [
                'title'     => 'Asignar precios a cajas',
                'sub'       => 'Asignacion de precios a cajas',
                'table'     => $table,
                'bread'     => $table . '.precioCajasIndex'
            ],
        ];
    }
}

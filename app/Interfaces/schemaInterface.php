<?php

namespace App\Interfaces;

interface schemaInterface
{
    public function getIdentificacion(): array;
    public function numeroControl();
    public function formatCorrelativo($correlativo): string;
    public function getEmisor(): array;
    public function getReceptor();
    public function getCuerpoDocumento(): array;
    public function getTributos($v);
    public function getResumen(): array;
    public function getTributosResumen();
    public function montoOperacion();
    public function getSubTotal(): float;
    public function getCondiciones();
    public function getPagos();
    public function getFormaPagoCod($token): string;
    public function getExtension();
    public function toArray();
    public function validar($j);
    public function getJson();
    public function getVersion(): int;
    public function getTipoDte(): string;
}

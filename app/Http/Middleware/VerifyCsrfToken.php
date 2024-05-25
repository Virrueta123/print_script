<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'impresion_ingresos',
        'impresion_gastos',
        "impresion_ingresos_grupal",
        "imprimir_desembolso"
    ];
}

<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/impresion_prueba', [App\Http\Controllers\print_controller::class, 'impresion_prueba'])->name('impresion_prueba');

Route::post('/impresion_gastos', [App\Http\Controllers\print_controller::class, 'impresion_gastos'])->name('impresion_gastos');

Route::post('/impresion_ingresos', [App\Http\Controllers\print_controller::class, 'impresion_ingresos'])->name('impresion_ingresos');

Route::post('/impresion_ingresos_grupal', [App\Http\Controllers\print_controller::class, 'impresion_ingresos_grupal'])->name('impresion_ingresos_grupal');

Route::post('/imprimir_desembolso', [App\Http\Controllers\print_controller::class, 'imprimir_desembolso'])->name('imprimir_desembolso');

Route::post('/ipc', [App\Http\Controllers\print_controller::class, 'impresion_prueba_cautiva'])->name('impresion_prueba_cautiva');

Route::get('/impresion_voucher_prestamo_cancelado', [App\Http\Controllers\print_controller::class, 'impresion_voucher_prestamo_cancelado']);

Route::get('/', function () {
    return view('welcome');
});

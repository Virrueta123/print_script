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


Route::get('/', function () {
    return view('welcome');
});

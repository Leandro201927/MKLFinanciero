<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\GastoController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;

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

Route::get('/', function () {
    return redirect('/dashboard');
})->middleware('auth');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

Route::get('/producto', [ProductoController::class, 'index'])->name('producto')->middleware('auth');
Route::get('/producto/{producto}/edit', [ProductoController::class, 'edit'])->name('producto.edit')->middleware('auth');
Route::put('/producto/{producto}', [ProductoController::class, 'update'])->name('producto.update')->middleware('auth');
Route::delete('/producto/{producto}', [ProductoController::class, 'destroy'])->name('producto.destroy')->middleware('auth');
Route::get('/producto/create', [ProductoController::class, 'create'])->name('producto.create')->middleware('auth');
Route::post('/producto', [ProductoController::class, 'store'])->name('producto.store')->middleware('auth');
// Route::get('/imprimirProductos', [ProductoController::class, 'imprimirProducto'])->name('producto.imprimirProductos');

Route::get('/venta', [VentaController::class, 'index'])->name('venta')->middleware('auth');
Route::get('/venta/{venta}/edit', [VentaController::class, 'edit'])->name('venta.edit')->middleware('auth');
Route::put('/venta/{venta}', [VentaController::class, 'update'])->name('venta.update')->middleware('auth');
Route::delete('/venta/{venta}', [VentaController::class, 'destroy'])->name('venta.destroy')->middleware('auth');
Route::get('/venta/create', [VentaController::class, 'create'])->name('venta.create')->middleware('auth');
Route::post('/venta', [VentaController::class, 'store'])->name('venta.store')->middleware('auth');
// Route::get('/imprimirVenta', [VentaController::class, 'imprimirVenta'])->name('venta.imprimirVentas');

Route::get('/gasto', [GastoController::class, 'index'])->name('gasto')->middleware('auth');
Route::get('/gasto/{gasto}/edit', [GastoController::class, 'edit'])->name('gasto.edit')->middleware('auth');
Route::put('/gasto/{gasto}', [GastoController::class, 'update'])->name('gasto.update')->middleware('auth');
Route::delete('/gasto/{gasto}', [GastoController::class, 'destroy'])->name('gasto.destroy')->middleware('auth');
Route::get('/gasto/create', [GastoController::class, 'create'])->name('gasto.create')->middleware('auth');
Route::post('/gasto', [GastoController::class, 'store'])->name('gasto.store')->middleware('auth');
// Route::get('/imprimirGasto', [GastoController::class, 'imprimirGasto'])->name('gasto.imprimirGastos');

Route::get('/signin', function () {
    return view('account-pages.signin');
})->name('signin');

Route::get('/signup', function () {
    return view('account-pages.signup');
})->name('signup')->middleware('guest');

Route::get('/sign-up', [RegisterController::class, 'create'])
    ->middleware('guest')
    ->name('sign-up');

Route::post('/sign-up', [RegisterController::class, 'store'])
    ->middleware('guest');

Route::get('/sign-in', [LoginController::class, 'create'])
    ->middleware('guest')
    ->name('sign-in');

Route::post('/sign-in', [LoginController::class, 'store'])
    ->middleware('guest');

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
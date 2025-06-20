<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Ga4QshaOhController;
use App\Http\Controllers\GscQshaOhController;

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
    return view('main.index');
});


Route::get('/ga4_qsha_oh', [Ga4QshaOhController::class, 'index'])->name('ga4_qsha_oh');
Route::get('/gsc_qsha_oh', [GscQshaOhController::class, 'index'])->name('gsc_qsha_oh');

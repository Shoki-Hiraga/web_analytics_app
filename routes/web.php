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
Route::get('/ga4_qsha_oh/maker', [Ga4QshaOhController::class, 'showByDirectory'])->name('ga4_qsha_oh.maker');
Route::get('/ga4_qsha_oh/result', [Ga4QshaOhController::class, 'showByDirectory'])->name('ga4_qsha_oh.result');
Route::get('/ga4_qsha_oh/usersvoice', [Ga4QshaOhController::class, 'showByDirectory'])->name('ga4_qsha_oh.usersvoice');
Route::get('/ga4_qsha_oh/historia', [Ga4QshaOhController::class, 'showByDirectory'])->name('ga4_qsha_oh.historia');

Route::get('/gsc_qsha_oh/maker', [GscQshaOhController::class, 'showByDirectory'])->name('gsc_qsha_oh.maker');
Route::get('/gsc_qsha_oh/result', [GscQshaOhController::class, 'showByDirectory'])->name('gsc_qsha_oh.result');
Route::get('/gsc_qsha_oh/usersvoice', [GscQshaOhController::class, 'showByDirectory'])->name('gsc_qsha_oh.usersvoice');
Route::get('/gsc_qsha_oh/historia', [GscQshaOhController::class, 'showByDirectory'])->name('gsc_qsha_oh.historia');

// GA4 YoY & MoM
Route::get('/ga4_qsha_oh/yoy', [Ga4QshaOhController::class, 'yoy'])->name('ga4_qsha_oh.yoy');
Route::get('/ga4_qsha_oh/mom', [Ga4QshaOhController::class, 'mom'])->name('ga4_qsha_oh.mom');

// GSC YoY & MoM
Route::get('/gsc_qsha_oh/yoy', [GscQshaOhController::class, 'yoy'])->name('gsc_qsha_oh.yoy');
Route::get('/gsc_qsha_oh/mom', [GscQshaOhController::class, 'mom'])->name('gsc_qsha_oh.mom');

<?php

use App\Http\Controllers\ServerController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\WeaponController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\GameController;
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

Route::get('/welcome', function () {
    return view('welcome');
});


Route::get('/',[ServerController::class, 'index'])->name('home');

Route::get('/games/{server?}',[GameController::class, 'index'])->name('games');
Route::get('/game/{game}',[GameController::class, 'show'])->name('game.show');

Route::get('/servers',[ServerController::class, 'index'])->name('servers');
Route::get('/server/{server}',[ServerController::class, 'show'])->name('server.show');

Route::get('/players/{game}',[PlayerController::class, 'index'])->name('players');
Route::get('/player/{playerId}',[PlayerController::class, 'show'])->name('player.show');

Route::get('/weapons/{game}',[WeaponController::class, 'index'])->name('weapons');
Route::get('/weapon/{game}/{code}',[WeaponController::class, 'show'])->name('weapon.show');

Route::get('/maps/{game}',[MapController::class, 'index'])->name('maps');
Route::get('/map/{game}/{map}',[MapController::class, 'show'])->name('map.show');

Route::any('{query}', function() { return redirect('/'); })->where('query', '.*');

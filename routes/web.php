<?php

use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;

Route::get('/', [GameController::class, 'index'])->name('find-falcone');
Route::post('/find-falcone', [GameController::class, 'findFalcone'])->name('find-falcone.search');
Route::get('/how-to', [GameController::class, 'instructions'])->name('find-falcone.instructions');
Route::get('/story', [GameController::class, 'story'])->name('find-falcone.story');

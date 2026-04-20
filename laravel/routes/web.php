<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;

Route::get('notes',[NoteController::class,'index'])->name('notes.index');

/**
 * SPAのルーティングを全て受け取る
 */
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');


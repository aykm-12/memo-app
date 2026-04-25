<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;

Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
Route::get('/notes', [NoteController::class, 'index'])->name('notes.index');

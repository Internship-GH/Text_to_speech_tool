<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConversionController;
use App\Http\Controllers\TranslationController;



Route::get('/', [ConversionController::class, 'main_page']);
Route::post('/convert', [ConversionController::class, 'convert']);
Route::post('/translate', [TranslationController::class, 'translate']);


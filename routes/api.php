<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConversionController;
use App\Http\Controllers\TranslationController;


Route::post('/translate', [TranslationController::class, 'translate']);
//Route::match(['get', 'post'], '/translate', [TranslationController::class, 'translate']);

<?php

use App\Http\Controllers\GetCountyInfoController;
use App\Http\Middleware\ExceptionHandler;
use App\Http\Middleware\Validation\GetCountyInfoValidation;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/search/county/{code}', GetCountyInfoController::class)
    ->middleware(GetCountyInfoValidation::class)
    ->name('SEARCH_COUNTY_CODE');

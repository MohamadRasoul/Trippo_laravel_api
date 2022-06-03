<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Dashboard\CityController;




Route::group([
    "prefix"     => 'city',
    "controller" => CityController::class
], function () {
    Route::get('index', 'index');
    Route::post('store', 'store');
    Route::get('{city}/show', 'show');
    Route::post('{city}/update', 'update');
    Route::delete('{city}/delete', 'destroy');
});

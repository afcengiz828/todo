<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;


Route::get('todos/search', [TodoController::class, "search"]);
Route::apiResource('todos', TodoController::class)->middleware('checkData');

?>
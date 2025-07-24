<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use App\Http\Middleware\checkData;

Route::get('todos/search', [TodoController::class, "search"]);
Route::apiResource('todos', TodoController::class)->middleware('checkData');
Route::middleware(['throttle:custom-ip'])->group(function () {
    Route::get('/todos', function () {
        return response()->json(['message' => 'Bu istek limitli!']);
    });
});

?>
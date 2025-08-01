<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use App\Http\Middleware\checkData;

Route::apiResource('todos', TodoController::class);

Route::get('todos/search', [TodoController::class, "search"]);
// Route::middleware(['throttle:custom-ip'])->group(function () {
//     Route::get('/todos', function () {
//         return response()->json(['message' => 'Bu istek limitli!']);
//     });
// });

?>
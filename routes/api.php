<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use App\Http\Middleware\CheckUserRole;

Route::post("register", [AuthController::class, "register"]);
Route::post("login", [AuthController::class, "login"]);

Route::middleware(["jwt.auth"])->group(function () {
    Route::post("logout", [AuthController::class, "logout"]);
    Route::get('todos/search', [TodoController::class, "search"]);
    Route::apiResource('todos', TodoController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::get('categories/{id}/todos', [CategoryController::class, "todos"])->middleware(CheckUserRole::class, ":user,god");
     
})


// Route::middleware(['throttle:custom-ip'])->group(function () {
//     Route::get('/todos', function () {
//         return response()->json(['message' => 'Bu istek limitli!']);
//     });
// });

?>
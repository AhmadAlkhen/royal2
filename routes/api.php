<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\PostReportController;
use App\Http\Controllers\API\UserController;


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



Route::post('/auth/login', [AuthController::class, 'loginUser']);


// Rotes for user
Route::get('/users/index', [UserController::class, 'index'])->middleware(['auth:sanctum','custom','role:admin']);
Route::post('/users/store', [UserController::class, 'store'])->middleware(['auth:sanctum','custom','role:admin']);
Route::get('/users/preview/{id}', [UserController::class, 'getUser'])->middleware(['auth:sanctum','custom','role:admin']);
Route::post('/users/update/{id}', [UserController::class, 'update'])->middleware(['auth:sanctum','custom','role:admin']);
Route::delete('/users/delete/{id}', [UserController::class, 'delete'])->middleware(['auth:sanctum','custom','role:admin']);


// Rotes for posts
Route::get('/posts/index', [PostController::class, 'index'])->middleware(['auth:sanctum','custom']);
Route::post('/posts/store', [PostController::class, 'store'])->middleware(['auth:sanctum','custom']);
Route::get('/posts/preview/{id}', [PostController::class, 'getPost'])->middleware(['auth:sanctum','custom']);
Route::post('/posts/update', [PostController::class, 'update'])->middleware(['auth:sanctum','custom']);
Route::delete('/posts/delete/{id}', [PostController::class, 'delete'])->middleware(['auth:sanctum','custom']);

// Rotes for reports
Route::get('/posts-reports/index', [PostReportController::class, 'index'])->middleware(['auth:sanctum','custom','role:admin,moderator']);
Route::post('/posts-reports/store', [PostReportController::class, 'store'])->middleware(['auth:sanctum','custom','role:admin,moderator']);
Route::get('/posts-reports/preview/{id}', [PostReportController::class, 'getReport'])->middleware(['auth:sanctum','custom','role:admin,moderator']);

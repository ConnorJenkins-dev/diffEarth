<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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





Route::middleware('auth:sanctum')->get('/user/roles', [UserController::class, 'getRoles']);


Route::post('/upload', [App\Http\Controllers\FileUploadController::class, 'store'])
    ->name('upload.store');

Route::get('/test', function () {
    return response()->json(['message' => 'API routes are working!']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });

    Route::get('/dashboard', function () {
        return response()->json(['message' => 'Dashboard for authenticated users']);
    });

    Route::middleware(['role:admin'])->get('/admin', function () {
        return response()->json(['message' => 'Admin panel']);
    });
});

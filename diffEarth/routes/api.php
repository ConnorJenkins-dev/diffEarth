<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DatasetController;


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

// route for rendering datasets
Route::get('/datasets', [DatasetController::class, 'index']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/upload', [App\Http\Controllers\FileUploadController::class, 'store'])
    ->name('upload.store');

Route::get('/test', function () {
    return response()->json(['message' => 'API routes are working!']);
});

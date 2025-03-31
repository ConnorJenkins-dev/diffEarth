<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\DatasetController;
use App\Http\Controllers\AlertController;

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

Route::get('/dataset', [App\Http\Controllers\GraphDataController::class, 'getAllDatasets'])
    -> name('datasets.index');

Route::get('/dataset/{datasetId}/columns', [App\Http\Controllers\GraphDataController::class, 'getAllColumns'])
    -> name('columns.index');

Route::get('/columns/{columnId}/datapoints', [App\Http\Controllers\GraphDataController::class, 'getDataPoints'])
    -> name('datapoints.index');

Route::get('/columns/{columnId}/data+stamp', [App\Http\Controllers\GraphDataController::class, 'getDataAndTimestamp']);

Route::get('/dataset', [App\Http\Controllers\GraphDataController::class, 'getAllDatasets'])
    -> name('datasets.index');

Route::get('/dataset/{datasetId}/columns', [App\Http\Controllers\GraphDataController::class, 'getAllColumns'])
    -> name('columns.index');

Route::get('/columns/{columnId}/datapoints', [App\Http\Controllers\GraphDataController::class, 'getDataPoints'])
    -> name('datapoints.index');

Route::get('/columns/{columnId}/data+stamp', [App\Http\Controllers\GraphDataController::class, 'getDataAndTimestamp']);

Route::get('/alerts', [AlertController::class, 'index']);

Route::post('/send-email', [EmailController::class, 'sendEmail']);

Route::delete('/alerts/{id}', [AlertController::class, 'destroy']);

Route::post('/alerts', [AlertController::class, 'store']);

Route::post('/alerts', [AlertController::class, 'store']);

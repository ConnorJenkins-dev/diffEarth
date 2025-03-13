<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\CorsMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\DatasetController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\DeploymentController;

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

Route::get('/deployments/in-progress', [App\Http\Controllers\DeploymentController::class, 'inProgressIndex'])
    ->name('deployments.in-progress-index');
Route::post('/deployments/in-progress', [App\Http\Controllers\DeploymentController::class, 'inProgressStore'])
    ->name('deployments.in-progress-store');
Route::get('/deployments', [DeploymentController::class, 'index']);
Route::get('/deployments/{id}', [DeploymentController::class, 'show']);
Route::post('/deployments', [DeploymentController::class, 'store']);
Route::delete('/deployments/{id}', [DeploymentController::class, 'destroy']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/user/roles', [UserController::class, 'getRoles']);

Route::post('/upload', [App\Http\Controllers\FileUploadController::class, 'store'])
    ->name('upload.store');

Route::get('/dashboard/{uid}', [DeploymentController::class, 'showByUuid'])
    ->name('deployments.show-by-uuid');



Route::get('/biographyText/{id}', [App\Http\Controllers\AboutUsController::class, 'index'])
    ->name('biographyText.index');

Route::post('/biographyText/{id}', [App\Http\Controllers\AboutUsController::class, 'store'])
    ->name('biographyText.store');

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
        return response()->json(['message' => 'Welcome Admin']);
    });
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

Route::post('/send-email', [EmailController::class, 'sendEmail']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware(['auth:sanctum', 'role:collaborator'])->get('/about', function () {
    return response()->json(['message' => 'Welcome to about us page!']);
});

Route::middleware([CorsMiddleware::class])->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
});

Route::options('/{any}', function () {
    return response()->json([]) // You can keep it empty, or return something if you prefer
    ->header('Access-Control-Allow-Origin', '*') // Allow any origin, adjust if needed
    ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
})->where('any', '.*');

Route::get('/emails', [UserController::class, 'getAllEmails']);

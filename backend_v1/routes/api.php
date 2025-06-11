<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\JobController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\ApplicationController;
use App\Http\Controllers\API\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Auth routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Public routes
Route::get('jobs/public', [JobController::class, 'index']);
Route::get('jobs/public/{job}', [JobController::class, 'show']);
Route::get('companies/public', [CompanyController::class, 'index']);
Route::get('companies/public/{company}', [CompanyController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
    return $request->user();
    });

    // Jobs routes
    Route::apiResource('jobs', JobController::class);
    Route::get('jobs/company/{company}', [JobController::class, 'byCompany']);

    // Companies routes
    Route::apiResource('companies', CompanyController::class);

    // Applications routes
    Route::apiResource('applications', ApplicationController::class);
    Route::get('applications/user/{user}', [ApplicationController::class, 'byUser']);
    Route::get('applications/job/{job}', [ApplicationController::class, 'byJob']);
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\JobController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\ApplicationController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\SavedJobController;

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
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('profile', [AuthController::class, 'profile'])->middleware('auth:sanctum');
    Route::put('profile', [AuthController::class, 'updateProfile'])->middleware('auth:sanctum');
});

// Public routes
Route::get('jobs/public', [JobController::class, 'index']);
Route::get('jobs/public/{job}', [JobController::class, 'show']);
Route::get('companies/public', [CompanyController::class, 'index']);
Route::get('companies/public/{company}', [CompanyController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    // Jobs routes
    Route::apiResource('jobs', JobController::class);
    Route::get('jobs/recommended', [JobController::class, 'recommended']);
    Route::post('jobs/{id}/restore', [JobController::class, 'restore']);
    Route::get('jobs/company/{company}', [JobController::class, 'byCompany']);

    // Companies routes
    Route::get('companies/my-company', [CompanyController::class, 'myCompany']);
    Route::apiResource('companies', CompanyController::class);

    // Applications routes
    Route::apiResource('applications', ApplicationController::class);
    Route::get('applications/user/{user}', [ApplicationController::class, 'byUser']);
    Route::get('applications/job/{job}', [ApplicationController::class, 'byJob']);

    // Saved Jobs routes
    Route::get('saved-jobs', [SavedJobController::class, 'index']);
    Route::post('jobs/{job}/save', [SavedJobController::class, 'store']);
    Route::delete('jobs/{job}/save', [SavedJobController::class, 'destroy']);
});

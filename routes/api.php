<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\ScheduleController;
use App\Http\Controllers\API\SessionController;
use App\Http\Controllers\API\EducatorController;
use App\Http\Controllers\API\CourseController;



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

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/schedules', [ScheduleController::class, 'index']);
    Route::post('/schedules', [ScheduleController::class, 'store']);
    Route::put('/schedules/{schedule}', [ScheduleController::class, 'update']);
    Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy']);

    // Sessions for dropdown/autofill
    Route::get('/sessions', [SessionController::class, 'index']);

    Route::post('/student/profile/update-avatar', [\App\Http\Controllers\Student\ProfileController::class, 'updateAvatar'])->name('api.student.profile.update-avatar');
});

// Public API routes (no authentication required)
Route::get('/educators', [EducatorController::class, 'index'])->name('api.educators.index');
Route::get('/courses', [CourseController::class, 'index'])->name('api.courses.index');
Route::get('/test', function() {
    return response()->json(['test' => 'API is working']);
});

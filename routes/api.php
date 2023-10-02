<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Website\ClassesController;
use App\Http\Controllers\Website\SubjectController;
use App\Http\Controllers\Website\DifficultyController;
use App\Http\Controllers\Website\QuestionController;
use App\Http\Controllers\Website\AuthController;
use App\Http\Controllers\Website\TeacherController;
use App\Http\Controllers\Website\StudentController;
use App\Http\Controllers\Website\TestController;
use App\Http\Controllers\Website\AssessmentController;
use App\Http\Controllers\Website\ReportController;
use App\Http\Controllers\Website\TagController;
use App\Http\Controllers\Website\UserController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// REGISTERATION
  Route::post('/register',[AuthController::class, 'register']);

// UPDATE PASSWORD
  Route::post('/update-user',[UserController::class, 'updateUser']);

// ALL USERS
  Route::get('/users',[UserController::class, 'allUsers']);

// ALL USERS
  Route::delete('/delete-users/{id}',[UserController::class, 'deleteUser']);

// LOGIN
  Route::post('/login',[AuthController::class, 'login']);

// UPDATE PASSWORD
  Route::post('/update-password',[AuthController::class, 'updatePassword']);

// FORGET PASSWORD
  Route::post('/forget-password',[AuthController::class, 'forgetPassword']);


// TEACHER
    Route::resource('teacher','App\Http\Controllers\Website\TeacherController')->only(['index', 'store', 'destroy']);
    Route::post('/teacher-update',[TeacherController::class, 'update']);

// STUDENT
    Route::resource('student','App\Http\Controllers\Website\StudentController')->only(['index', 'store', 'destroy']);
    Route::post('/student-update',[StudentController::class, 'update']);

// CLASS:-
    Route::resource('classes','App\Http\Controllers\Website\ClassesController')->only(['index', 'store', 'destroy']);
    Route::post('/class-update',[ClassesController::class, 'update']);

// SUBJECT
    Route::resource('subjects','App\Http\Controllers\Website\SubjectController')->only(['index', 'store', 'destroy']);
    Route::post('/subject-update',[SubjectController::class, 'update']);

// DIFFICULTY
    Route::resource('difficulty','App\Http\Controllers\Website\DifficultyController')->only  (['index', 'store', 'destroy']);
    Route::post('/difficulty-update',[DifficultyController::class, 'update']);

// QUESTION 
    Route::resource('question','App\Http\Controllers\Website\QuestionController')->only  (['index', 'store', 'destroy']);
    Route::post('/question-update',[QuestionController::class, 'update']);

// TEST MODULE  
    Route::resource('tests','App\Http\Controllers\Website\TestController')->only  (['index', 'store', 'destroy']);
    Route::post('/test-update',[TestController::class, 'update']);

// ASSESSMENT MODULE  
    Route::resource('assessment','App\Http\Controllers\Website\AssessmentController')->only  (['index', 'store', 'destroy']);
    Route::post('/assessment-update',[AssessmentController::class, 'update']);

// REPORT MODULE  
    Route::resource('report','App\Http\Controllers\Website\ReportController')->only  (['index', 'store', 'destroy']);
    Route::post('/report-update',[ReportController::class, 'update']);

// TAG  
    Route::resource('tag','App\Http\Controllers\Website\TagController')->only  (['index', 'store', 'destroy']);
    Route::post('/tag-update',[TagController::class, 'update']);
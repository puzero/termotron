<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnswerController;
use App\Models\Answer;
use App\Models\Survey;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class,'logout']);

Route::get('/userdata', [AuthController::class, 'user']);
Route::get('/users', [UserController::class, 'index']);
Route::get('/employees', [EmployeeController::class, 'index']);
Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);
Route::post('/employees/{id}', [EmployeeController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);
Route::post('/users/{id}', [UserController::class, 'update']);
Route::get('/positions', [PositionController::class, 'index']);
Route::get('/departments',[DepartmentController::class, 'index']);

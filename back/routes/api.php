<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnswerController;
use App\Models\Answer;
use App\Models\Survey;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class,'logout']);

Route::get('/userdata', [AuthController::class, 'user']);
Route::get('/users', [UserController::class, 'index']);
Route::get('/questions/survey/{id}', [QuestionController::class,'index']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);
Route::post('/users/{id}', [UserController::class, 'update']);
Route::post('/answers', [AnswerController::class, 'store']);

Route::get('/surveys/{surveyId}/users/{userId}', [SurveyController::class,'userAnswers']);
Route::get('/surveys',[SurveyController::class,'index']);
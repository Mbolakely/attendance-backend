<?php

use App\Http\Controllers\ClasseController;
use App\Http\Controllers\FaceController;
use App\Http\Controllers\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Routes pour les etudiants
Route::controller(StudentController::class)->group(function() {
    Route::get('/etudiants', 'index');
    Route::delete('/etudiants/{id}', 'destroy');
    Route::get('/etudiants/{id}', 'show');
    Route::post('/etudiants/add', 'store');
    Route::put('/etudiants/edit/{id}', 'update');
});

// Routes pour l'encodage
Route::controller(FaceController::class)->group(function() {
    Route::post('/etudiants/encode', 'encode');
});

// Routes pour les classes
Route::controller(ClasseController::class)->group(function() {
    Route::get('/classes', 'index');
    Route::post('/classes/add', 'store');
    Route::get('/classes/{id}', 'show');
    Route::put('/classes/edit/{id}', 'update');
    Route::delete('/classes/{id}', 'destroy');
});
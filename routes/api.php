<?php

use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\FaceController;
use App\Http\Controllers\SceanceController;
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
    Route::post('/etudiants/{matricule}/encode', 'encode');
});

// Routes pour les classes
Route::controller(ClasseController::class)->group(function() {
    Route::get('/classes', 'index');
    Route::get('/classes/simple', 'allClasses');
    Route::post('/classes/add', 'store');
    Route::get('/classes/{id}', 'show');
    Route::put('/classes/edit/{id}', 'update');
    Route::delete('/classes/{id}', 'destroy');
    Route::get('/classes/{id}/etudiants', 'students');
});

// Routes pour les séances
Route::controller(SceanceController::class)->group(function() {
    Route::get('/sceances', 'index');
    Route::post('/sceances/add', 'store');
    Route::get('/sceances/{id}', 'show');
    Route::put('/sceances/edit/{id}', 'update');
    Route::delete('/sceances/{id}', 'destroy');
});

//Routes pour le pointage
Route::controller(AttendanceController::class)->group(function() {
    Route::post('/pointage', 'attend');
    Route::post('/pointage/{attendance}/justificatif', 'uploadJustificatif');
});

// Routes pour les absences
Route::controller(AbsenceController::class)->group(function() {
    Route::get('/absences', 'absences');
    Route::get('/absences/kpi/global', 'globalKpi');
    Route::get('/absences/kpi/{student}', 'studentKpi');
});

//Routes pour les graphiques
Route::controller(ChartController::class)->group(function() {
    Route::get('/charts', 'index');
});
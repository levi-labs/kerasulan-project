<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataTrainingController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RecruitmentController;
use App\Http\Controllers\TrainingDataController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::controller(DashboardController::class)->prefix('dashboard')->group(function () {
    Route::get('index', 'index')->name('dashboard.index');
});

Route::controller(TrainingDataController::class)->prefix('training-data')->group(function () {
    Route::get('index', 'index')->name('training-data.index');
    Route::post('import', 'import')->name('training-data.import');
    Route::get('process', 'process')->name('training-data.process');
    Route::get('create', 'create')->name('training-data.create');
    Route::post('store', 'store')->name('training-data.store');
    Route::get('edit/{trainingData}', 'edit')->name('training-data.edit');
    Route::put('update/{trainingData}', 'update')->name('training-data.update');
    Route::delete('destroy/{trainingData}', 'destroy')->name('training-data.destroy');
});


Route::controller(RecruitmentController::class)->prefix('recruitments')->group(function () {
    Route::get('index', 'index')->name('recruitments.index');
    Route::post('import', 'import')->name('recruitments.import');
    Route::get('process', 'process')->name('recruitments.process');
    Route::get('create', 'create')->name('recruitments.create');
    Route::post('store', 'store')->name('recruitments.store');
    Route::get('show/{recruitment}', 'show')->name('recruitments.show');
    Route::get('edit/{recruitment}', 'edit')->name('recruitments.edit');
    Route::put('update/{recruitment}', 'update')->name('recruitments.update');
    Route::delete('destroy/{recruitment}', 'destroy')->name('recruitments.destroy');
});

Route::controller(DataTrainingController::class)->prefix('data-training')->group(function () {
    Route::get('index', 'index')->name('data-training.index');
    Route::post('import', 'import')->name('data-training.import');
    Route::get('process', 'process')->name('data-training.process');
    Route::post('predicted', 'checkPredicted')->name('data-training.predicted');
    Route::get('create', 'create')->name('data-training.create');
    Route::post('store', 'store')->name('data-training.store');
    Route::get('show/{dataTraining}', 'show')->name('data-training.show');
    Route::get('edit/{dataTraining}', 'edit')->name('data-training.edit');
    Route::put('update/{dataTraining}', 'update')->name('data-training.update');
    Route::delete('destroy/{dataTraining}', 'destroy')->name('data-training.destroy');
});

Route::controller(UserController::class)->prefix('users')->group(function () {
    Route::get('index', 'index')->name('users.index');
    Route::get('create', 'create')->name('users.create');
    Route::post('store', 'store')->name('users.store');
    Route::get('edit/{user}', 'edit')->name('users.edit');
    Route::put('update/{user}', 'update')->name('users.update');
    Route::delete('destroy/{user}', 'destroy')->name('users.destroy');
});

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.post');

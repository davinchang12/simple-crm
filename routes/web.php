<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', '/login');
Auth::routes();

Route::group([
    'middleware' => ['auth', 'web'],
    'prefix' => 'home',
    'as' => 'home.'
], function () {
    Route::get('/', [HomeController::class, 'index'])->name('index');

    Route::resource('/users', UserController::class)->only(['index', 'edit', 'update'])->middleware('role:admin');

    Route::group(['middleware' => 'role:admin|manager'], function () {
        Route::resource('/clients', ClientController::class)->except(['show']);
        Route::resource('/projects', ProjectController::class)->except(['index, show']);
    });

    Route::group(['middleware' => 'role:admin|manager|worker'], function () {
        Route::get('/projects/', [ProjectController::class, 'index'])->name('projects.index');
        Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    });

    Route::resource('/tasks', TaskController::class);
});

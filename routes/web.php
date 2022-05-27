<?php

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

use App\Http\Controllers\ProjectsController;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'auth'], function () {
	Route::get('/projects', [ProjectsController::class, 'index']);
	Route::get('/projects/create', [ProjectsController::class, 'create']);
	Route::get('/projects/{project}', [ProjectsController::class, 'show']);
	Route::post('/projects', [ProjectsController::class, 'store']);

	Route::get('/home', 'HomeController@index')->name('home');
});

Auth::routes();


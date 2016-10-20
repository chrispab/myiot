<?php

use Illuminate\Support\Facades\DB;


Auth::routes();


Route::get('/', function () {
    return view('welcome');

})->middleware('auth');

// Route::get('graph', function () {
//     return 'graph';
// });

Route::get('graph/{hours}', 'GraphController@home')->middleware('auth');

Route::get('dashboard', 'DashboardController@home')->middleware('auth');


Route::get('/home', 'HomeController@index')->middleware('auth');

Route::get('/test', 'SomeController@testfunction');
//Route::post('/test', 'SomeController@testfunction');
Route::get('/ajax', 'AjaxController@ajax');

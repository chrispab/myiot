<?php

use Illuminate\Support\Facades\DB;

Auth::routes();


Route::get('/', function () {
    return view('dashboard');
})->middleware('auth');


//Route::get('graph/{hours}', 'GraphController@home')->middleware('auth');
//Route::get('graph2/{hours}', 'Graph2Controller@home')->middleware('auth');
Route::get('graph/{zone}/{hours}', 'GraphZoneController@ajaxgraph')->middleware('auth');
Route::get('ajaxgraph/{zone}/{hours}', 'GraphZoneController@ajaxgraph')->middleware('auth');
//Route::get('graphall/{zone}/{hours}', 'GraphZoneController@ajaxgraphall')->middleware('auth');
//Route::get('graphall/{hours}', 'GraphZoneController@graphall')->middleware('auth');
Route::get('graphall/{hours}', function () {
    return view('ajaxgraphall');
})->middleware('auth');

Route::post('getajaxgraphdata/{zone}/{hours}', 'GraphZoneController@getajaxgraphdata')->middleware('auth');


Route::get('getgraphdata/{zone}/{hours}', 'GraphZoneController@getgraphdata')->middleware('auth');


Route::get('/dashboard', 'DashboardController@home')->middleware('auth');

Route::get('/home', 'HomeController@index')->middleware('auth');

Route::get('/ajax', 'AjaxController@ajax');

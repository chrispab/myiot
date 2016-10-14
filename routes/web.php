<?php

use Illuminate\Support\Facades\DB;


Route::get('/', function () {
    return view('welcome');

});

// Route::get('graph', function () {
//     return 'graph';
// });

Route::get('graph/{hours}', 'GraphController@home')->middleware('auth');;

Route::get('dashboard', 'DashboardController@home');

Auth::routes();

Route::get('/home', 'HomeController@index');

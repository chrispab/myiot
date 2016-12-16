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

Route::post('getajaxgraphdata/{zone}/{hours}', 'GraphZoneController@getajaxgraphdata')->middleware('auth');


Route::get('getgraphdata/{zone}/{hours}', 'GraphZoneController@getgraphdata')->middleware('auth');


Route::get('/dashboard', 'DashboardController@home')->middleware('auth');

Route::get('/home', 'HomeController@index')->middleware('auth');

Route::get('/test', 'SomeController@testfunction');
Route::post('/test', 'SomeController@testfunction');

Route::get('/ajax', 'AjaxController@ajax');

Route::get('/paintings', function () {
    //$data = '{"id": 1, "title": "Mona Lisa", "painter_id": 2, "created_at": "2014-01-24 19:49:55" }';
$data='{
    "title": "A cool blog post",
    "clicks": 4000,
    "children": null,
    "published": true,
    "comments": [
        {
            "author": "Mister X",
            "message": "A really cool posting"
        },
        {
            "author": "Misrer Y",
            "message": "Its me again!"
        }
    ]
}';
// '{
    //   "id": 1,
    //     "title": "Mona Lisa",
    //     "body": "The Mona Lisa is a half-length portrait of a",
    //     "painter_id": 2,
    //     "created_at": "2014-01-24 19:49:55",
    //     "updated_at": "2014-01-24 19:49:55"
    // },
    // {
    //     "id": 2,
    //     "title": "Last Supper",
    //     "body": "The Last Supper is a late 15th-century mural",
    //     "painter_id": 2,
    //     "created_at": "2014-01-24 19:55:46",
    //     "updated_at": "2014-01-24 19:55:46"
    // }';

    //return $paintings;
    var_dump(json_decode($data));
    var_dump(json_decode($data, true));

});

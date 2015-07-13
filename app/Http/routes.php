<?php

Route::get('/', 'HomeController@index');
//-----------------
Route::get('sexoffenders', 'SexoffendersController@index');
Route::get('sexoffenders/queue', 'SexoffendersController@queue');
//--
Route::get('sexoffenders/start', 'SexoffendersController@start');
Route::get('sexoffenders/data-start', 'SexoffendersController@data_start');
//--
Route::get('sexoffenders/running', 'SexoffendersController@running');
Route::get('sexoffenders/data-running', 'SexoffendersController@data_running');
//--
Route::get('sexoffenders/paused', 'SexoffendersController@paused');
Route::get('sexoffenders/data-paused', 'SexoffendersController@data_paused');
//--
Route::get('sexoffenders/completed', 'SexoffendersController@completed');
Route::get('sexoffenders/data-completed', 'SexoffendersController@data_completed');
//--
Route::get('sexoffenders/history', 'SexoffendersController@history');
//-----------------
Route::get('environment', 'EnvironmentController@index');
Route::get('foreclosure', 'ForeclosureController@index');
Route::get('property', 'PropertyController@index');
//-----------------
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');


Route::get('test', 'TestController@test');

Route::get('image',function(){
    $image = storage_path().'/app/sexoffenders/georgia/2579.nofile';
    $ext = '.nofile';
    $jpg = str_replace($ext,'.jpg',$image);

    Image::make($image)->save($jpg,100);
});

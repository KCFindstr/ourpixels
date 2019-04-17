<?php

Route::get('/', 'ImageController@index');
Route::post('/image/create', 'ImageController@create');
Route::get('/image/{id}', 'ImageController@image');
Route::post('/image/{id}/edit', 'ImageController@edit');
Route::post('/image/{id}/collab', 'ImageController@collab');
Route::post('/image/{id}/delete', 'ImageController@delete');

Route::get('/signin', 'UserController@signin');
Route::get('/register', 'UserController@register');
Route::get('/user/{id}', 'UserController@index');

Route::post('/signin', 'UserController@onSignin');
Route::post('/register', 'UserController@onRegister');
Route::post('/logout', 'UserController@logout');
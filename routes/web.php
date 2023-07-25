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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'MainController@home')->name('home');

Route::prefix('admins')->group(function() {
    Route::post('/insert-genre', 'MainController@insert_genre')->name('insert.genre');

    Route::put('/update-genre/{genre}', 'MainController@update_genre')->name('update.genre');

    Route::post('/insert-video', 'MainController@insert_video')->name('insert.video');

    Route::put('/update-video/{video}', 'MainController@update_video')->name('update.video');

    Route::delete('/delete-record/{type}/{id}', 'MainController@delete_record')->name('delete.record');
});

Route::prefix('users')->group(function() {
    Route::get('/dashboard', 'UsersDashboardController@dashboard')->name('users.dashboard');

    Route::post('/add-vote', 'UsersDashboardController@add_vote')->name('add.vote');

    Route::get('/edit-votes', 'UsersDashboardController@edit_votes')->name('edit.votes');

    Route::put('/update-vote/{vote}', 'UsersDashboardController@update_vote')->name('update.vote');

    Route::delete('/delete-vote/{vote}', 'UsersDashboardController@delete_vote')->name('delete.vote');
});
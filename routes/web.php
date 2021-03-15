<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route For Creating Albums - Get Method Retrieves Page
Route::get('/album/create','App\Http\Controllers\AlbumController@create')->name('album.create')->middleware('auth');

// Route For Storing Albums in Database 
Route::post('/album/store','App\Http\Controllers\AlbumController@store')->middleware('auth');

Route::get('/{id}/category','App\Http\Controllers\FrontendController@albumCategory')->name('album.category');


Route::get('/album/index','App\Http\Controllers\AlbumController@index');

Route::get('/getalbums','App\Http\Controllers\AlbumController@getAlbums')->middleware('auth');

Route::put('/albums/{id}/edit','App\Http\Controllers\AlbumController@update')->middleware('auth');

Route::delete('/albums/{id}/delete','App\Http\Controllers\AlbumController@destroy')->middleware('auth');

Route::get('upload/images/{id}','App\Http\Controllers\GalleryController@create')->name('image.create')->middleware('auth');

route::post('uploadImage','App\Http\Controllers\GalleryController@upload')->middleware('auth');

route::get('/getImages','App\Http\Controllers\GalleryController@images')->middleware('auth');

Route::delete('/image/{id}','App\Http\Controllers\GalleryController@destroy');

Route::get('/albums/{slug}/{id}','App\Http\Controllers\GalleryController@viewAlbums')->name('view.album');

Route::get('/','App\Http\Controllers\FrontendController@index');

Route::get('/user/profile/{id}','App\Http\Controllers\FrontendController@userAlbum')->name('user.album');

Route::post('/follow',"App\Http\Controllers\FollowController@followUnfollow")->middleware('auth');


Route::get('/profile','App\Http\Controllers\FollowController@profile')->name('profile');

Route::post('/profile-pic','App\Http\Controllers\FollowController@updateProfilepic');

Route::get('user/{id}','App\Http\Controllers\FollowController@userProfilePic');

Route::get('user/bg/{id}','App\Http\Controllers\FollowController@userbgPic');

Route::post('bg-pic','App\Http\Controllers\FollowController@updatebgPic')->middleware('auth');

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

Route::resource('admin/blog', 'BlogController')->names('admin.blogs');
Route::post('admin/blog/upload','BlogController@imageUploader')->name('admin.blogs.upload');

/*
 * site routes
 * */

Route::resource('/home/blogs', 'SiteController')->names('site.blogs');
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

//  use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route;

Route::get('/', 'QuestionsController@index');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('questions','QuestionsController')->except('show');

Route::resource('questions.answers','AnswersController')->except('index','create','show');

Route::get('/questions/{slug}','QuestionsController@show')->name('questions.show');

Route::post('/answers/{answer}/accept','AcceptAnswerController')->name('accept.answer');

Route::post('/questions/{question}/favourites','FavouritesController@store')->name('questions.favourite');
Route::delete('/questions/{question}/favourites','FavouritesController@destroy')->name('questions.unfavourite');

Route::post('/questions/{$question}/vote','VoteQuestionController');
Route::post('/answers/{$answer}/vote','VoteAnswerController');
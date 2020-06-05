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

Auth::routes(['verify' => true]);
// Auth::routes();

Route::middleware('verified')->group(function(){
    Route::get('/home', 'HomeController@index')->name('home');

    Route::resource('questions','QuestionsController')->except('show','index');
    
    Route::resource('questions.answers','AnswersController')->except('create','show','index');

    Route::post('/answers/{answer}/accept','AcceptAnswerController')->name('accept.answer');
    
    Route::post('/questions/{question}/favourites','FavouritesController@store');//->name('questions.favourite');
    Route::delete('/questions/{question}/favourites','FavouritesController@destroy');//->name('questions.unfavourite');
    
    Route::post('/questions/{question}/vote','VoteQuestionController'); //->name('up.vote');
    Route::post('/answers/{answer}/vote','VoteAnswerController');
});

Route::get('/questions/{slug}','QuestionsController@show')->name('questions.show');
Route::get('/questions', 'QuestionsController@index')->name('questions.index');
Route::get('/questions/{question}/answers', 'AnswersController@index')->name('questions.answers.index');


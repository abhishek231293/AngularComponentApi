<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::options('login', 'WebController@login')->middleware(['cors']);
Route::post('login', 'WebController@login')->middleware(['cors']);

Route::group(['middleware' => ['cors','jwt.auth']], function(){
    /*----------------------Web Api------------------*/
    Route::options('user', 'WebController@user');
    Route::post('user', 'WebController@user');

    Route::options('category-list', 'CaseController@CategoryList');
    Route::post('category-list', 'CaseController@getCategoryList');

    Route::options('project-list', 'CaseController@getProjectList');
    Route::post('project-list', 'CaseController@getProjectList');

    Route::options('project-detail', 'CaseController@getProjectDetail');
    Route::post('project-detail', 'CaseController@getProjectDetail');

    Route::options('create-new-case', 'CaseController@createNewCase');
    Route::post('create-new-case', 'CaseController@createNewCase');

    Route::options('update-project', 'CaseController@updateProject');
    Route::post('update-project', 'CaseController@updateProject');

    Route::options('upload-csv', 'ApiController@uploadCsv');
    Route::post('upload-csv', 'ApiController@uploadCsv');

});
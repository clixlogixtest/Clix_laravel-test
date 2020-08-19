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

/*Route::group([
    'prefix' => 'auth'
  ], function () {
    Route::post('register','AuthController@register');
    Route::post('login','AuthController@login');
    Route::post('forgetpassword','AuthController@forgetPassword');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('user','AuthController@user');
        Route::get('logout','AuthController@logout');
    });
});

Route::group([    
    'namespace' => 'Api',    
    'middleware' => 'api',    
    'prefix' => 'password'
], function () {    
    Route::post('create', 'PasswordResetController@create');
    Route::get('find/{token}', 'PasswordResetController@find');
    Route::post('reset', 'PasswordResetController@reset');
});*/

//auth routes
/*Route::post('v1/user-register', 'API\V1\AuthController@register');
Route::post('v1/user-login', 'API\V1\AuthController@login');
*/
Route::post('v1/signin', 'Api\V1\AuthController@signin')->name('signin');
Route::post('v1/checkEmail', 'Api\V1\AuthController@checkEmail');
Route::post('v1/register', 'Api\V1\AuthController@register');
Route::post('v1/forgotPassword', 'Api\V1\AuthController@resetMail');

Route::group(['namespace' => 'Api', 'middleware' => 'api', 'prefix' => 'password' ], function(){    
    Route::post('create', 'V1\AuthController@create');
    Route::get('find/{token}', 'V1\AuthController@find');
    Route::post('reset', 'V1\AuthController@reset');
});

Route::get('v1/openCompetitionList/{organisation_id}', 'Api\V1\ApiController@openCompetitionList');
Route::get('v1/openCompetitionDetails/{competition_id}', 'Api\V1\ApiController@openCompetitionDetails');
Route::post('v1/challengesQuestion', 'Api\V1\ApiController@challengesQuestion');
Route::post('v1/getPlayerWalletBallance', 'Api\V1\ApiController@getPlayerWalletBallance');
Route::get('v1/termsAndConditions', 'Api\V1\ApiController@termsAndConditions');
Route::get('v1/faqs', 'Api\V1\ApiController@faqs');
Route::get('v1/howToPlay', 'Api\V1\ApiController@howToPlay');
Route::get('v1/myAccount', 'Api\V1\ApiController@myAccount');
Route::post('v1/sendEmailVerificationOtp', 'Api\V1\ApiController@sendEmailVerificationOtp');
Route::post('v1/validateEmailVerificationOtp', 'Api\V1\ApiController@validateEmailVerificationOtp');
Route::put('v1/updateProfile', 'Api\V1\ApiController@updateProfile');
Route::post('v1/updatePassword', 'Api\V1\ApiController@updatePassword');
Route::post('v1/myTickets', 'Api\V1\ApiController@myTickets');
Route::post('v1/allDrawn', 'Api\V1\ApiController@allDrawn');

Route::post('v1/social/login/{social}/callback','Api\V1\SocialController@handleProviderCallback')->where('social','twitter|facebook|linkedin|google|github|bitbucket|apple');

Route::post('v1/logout', 'Api\V1\ApiController@logout');
Route::post('v1/bookTicket', 'Api\V1\ApiController@bookTicket');
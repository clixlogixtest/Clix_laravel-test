<?php

use App\Http\Controllers\API\AddonServiceController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CompanyDetailController;
use App\Http\Controllers\API\CountryController;
use App\Http\Controllers\API\HomeScreenController;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\MobileContentController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\PackageController;
use App\Http\Controllers\API\PageController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\SicCodeController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


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

Route::group(['prefix' => 'v1'], function() {

	/************Login Controller****************/
	Route::post('/login', [LoginController::class, 'login']);
	/********************************************/

	/************Register Controller****************/
	Route::post('/register', [RegisterController::class, 'register']);
	/**********************************************/

	/************User Controller****************/
	Route::post('/forgotPassword', [UserController::class, 'forgotPassword']);
	Route::post('/verifyOtp', [UserController::class, 'verifyOtp']);
	Route::post('/resetPassword', [UserController::class, 'resetPassword']);
	/**********************************************/

	/************MobileContent Controller****************/
	Route::get('/homeContent', [MobileContentController::class, 'getHomeContent']);
	/********************************************/

	/************Page Controller****************/
	Route::get('/page', [PageController::class, 'getPage']);
	Route::post('/contactUs', [PageController::class, 'storeContactUs']);
	Route::get('/faq', [PageController::class, 'getFaq']);
	/********************************************/

	/************Country Controller****************/
	Route::get('/countries', [CountryController::class, 'getCountries']);
	/********************************************/

	/************SicCode Controller****************/
	Route::get('/sicCodes', [SicCodeController::class, 'getSicCodes']);
	/********************************************/

	Route::group(['middleware' => ['auth:api']], function() {

		/************User Controller****************/
		Route::get('/profile', [UserController::class, 'getProfile']);
		Route::put('/profile', [UserController::class, 'updateProfile']);
		Route::post('/changePassword', [UserController::class, 'changePassword']);
		Route::get('/verifyEmail', [UserController::class, 'verifyEmail']);
		Route::put('/deviceToken', [UserController::class, 'updateDeviceToken']);
		Route::post('/logout', [UserController::class, 'logout']);
		/********************************************/

		/************HomeScreen Controller****************/
		Route::get('/search/companies', [HomeScreenController::class, 'searchCompanies']);
		/********************************************/

		/************Package Controller****************/
		Route::get('/packages', [PackageController::class, 'getPackages']);
		/********************************************/
		
		/************AddonService Controller****************/
		Route::get('/addonServices', [AddonServiceController::class, 'getAddonServices']);
		/********************************************/

		/************Cart Controller****************/
		Route::get('/cart', [CartController::class, 'getCart']);
		Route::post('/cart', [CartController::class, 'addToCart']);
		Route::delete('/cart', [CartController::class, 'deleteFromCart']);
		/********************************************/

		/************CompanyDetail Controller****************/
		Route::get('/companyDetail', [CompanyDetailController::class, 'getCompanyDetail']);
		Route::post('/companyDetail', [CompanyDetailController::class, 'addCompanyDetail']);
		Route::put('/billingAddress', [CompanyDetailController::class, 'updateBillingAddress']);
		/********************************************/

		/************Payment Controller****************/
		Route::post('/generateClientSecret', [PaymentController::class, 'generateClientSecret']);
		Route::post('/payNow', [PaymentController::class, 'payNow']);
		/********************************************/

		/************Order Controller****************/
		Route::get('/myCurrentPackages', [OrderController::class, 'myCurrentPackages']);
		Route::get('/myAdditionalServices', [OrderController::class, 'myAdditionalServices']);
		Route::get('/orderDetails', [OrderController::class, 'getOrderDetails']);
		/********************************************/

		/************Notification Controller****************/
		Route::get('/notifications', [NotificationController::class, 'getNotifications']);
		Route::put('/notifications', [NotificationController::class, 'updateNotificationReadStatus']);
		/********************************************/

	});

});
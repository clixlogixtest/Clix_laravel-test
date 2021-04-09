<?php

use App\Http\Controllers\Admin\AddonServiceController;
use App\Http\Controllers\Admin\ChangePasswordController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\EmailVerificationController;
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

Route::get('/admin/login', [LoginController::class, 'index'])->name('admin.login');
Route::post('/admin/login', [LoginController::class, 'login'])->name('admin.login');

Route::group(['prefix' => '/admin', 'as'=>'admin.', 'middleware' => ['auth','admin']], function() {
	
	Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
	Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
	Route::post('/profile', [ProfileController::class, 'update'])->name('profile');
	Route::get('/change_password', [ChangePasswordController::class, 'index'])->name('change_password');
	Route::post('/change_password', [ChangePasswordController::class, 'update'])->name('change_password');
	Route::resource('/packages', PackageController::class);
	Route::resource('/addon_services', AddonServiceController::class);
	Route::resource('/pages', PageController::class);
	Route::resource('/faqs', FaqController::class);
	Route::resource('/contacts', ContactController::class);
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/user/verify/{token}', [EmailVerificationController::class, 'verifyUser']);
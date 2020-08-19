<?php
use App\Http\Controllers\LanguageController;
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

Route::get('/', 'Backend\DashboardController@dashboard')->name('dashboard.home'); 

Route::group(['namespace' => 'Backend', 'prefix' => 'admin'], function(){
    Route::get('/signin', 'AdminController@showLoginForm')->name('admin.login');
    Route::post('/adminlogin', 'AdminController@login')->name('admin.login.submit');
    Route::get('/logout', 'AdminController@logout')->name('admin.logout');
    Route::get('/', 'AdminController@index')->name('admin.dashboard');
    Route::get('/resetPassword', 'AdminController@resetPassword')->name('admin.resetPassword');
    Route::post('/resetMail', 'AdminController@resetMail')->name('admin.resetMail');
    Route::get('/resetPasswordForm', 'AdminController@resetPasswordForm')->name('admin.resetPasswordForm');
    Route::POST('/reset', 'AdminController@reset')->name('admin.reset');
});

Route::group(['namespace' => 'Backend'], function(){ 
    Route::resource('prizes', 'PrizeController', ['except' => ['edit']]);
    Route::get('prize/{prize_id}', 'PrizeController@editPrize')->name('prize.editPrize');
    Route::post('prizes/uploadImages', 'PrizeController@uploadImages')->name('prizes.uploadImages');
    Route::get('revision/{log_id}', 'PrizeController@revision')->name('prize.revision');

    Route::resource('category', 'PrizeCategoryController', ['except' => ['edit']]);
    Route::get('categories/{prize_category_id}', 'PrizeCategoryController@editCategories')->name('categories.editCategories');

    Route::resource('challenges', 'ChallengeController', ['except' => ['edit']]); 
    Route::get('challenge/{challenge_id}', 'ChallengeController@editChallenge')->name('challenges.editChallenge');

    /*Competition route*/  
    Route::resource('competitions', 'CompetitionController', ['except' => ['edit']]);
    Route::get('competition/{competition_id}', 'CompetitionController@editCompetition')->name('competitions.editCompetition');
    Route::get('autocompletePrize', 'CompetitionController@autocompletePrize')->name('competitions.autocompletePrize');
    Route::get('autocompleteChallenge', 'CompetitionController@autocompleteChallenge')->name('competitions.autocompleteChallenge');
    Route::get('autocompleteCompetition', 'CompetitionController@autocompleteCompetition')->name('competitions.autocompleteCompetition');
    Route::get('autocompleteUser', 'CompetitionController@autocompleteUser')->name('competitions.autocompleteUser');
    Route::get('getAllCompetitionInCSV', 'CompetitionController@getAllCompetitionInCSV')->name('competitions.getAllCompetitionInCSV');
    Route::get('getAllTicketInCSV/{competition_id}', 'CompetitionController@getAllTicketInCSV')->name('competitions.getAllTicketInCSV');
    Route::put('competitions/updateState/{competition_id}', 'CompetitionController@updateState')->name('competitions.updateState');
    Route::get('/tickets/create', 'CompetitionController@addFreeTicket')->name('competitions.addFreeTicket');
    Route::post('storeFreeTicket', 'CompetitionController@storeFreeTicket')->name('competitions.storeFreeTicket');
    Route::get('/tickets/{competition_id}', 'CompetitionController@ticketList')->name('competitions.ticketList');
    Route::get('getCompetitionChallengeList/{competition_id}', 'CompetitionController@getCompetitionChallengeList')->name('competitions.getCompetitionChallengeList');
    Route::get('ticketEdit/{ticket_id}', 'CompetitionController@ticketEdit')->name('competition.ticket.edit');
    Route::put('ticketUpdate/{ticket_id}', 'CompetitionController@ticketUpdate')->name('competition.ticket.update');
    Route::get('closedCompetitionDateExtention', 'CompetitionController@closedCompetitionDateExtention')->name('competitions.closedCompetitionDateExtention');

    /*Report routes*/
    Route::resource('reports', 'ReportController');
    Route::get('closedCompetition', 'ReportController@closedCompetition')->name('reports.closedCompetition');
    Route::get('drawnCompetition', 'ReportController@drawnCompetition')->name('reports.drawnCompetition');
    Route::resource('users', 'UserController', ['except' => ['edit']]);
    Route::get('playerList', 'UserController@playerList')->name('users.playerList');
    Route::get('administratorList', 'UserController@administratorList')->name('users.administratorList');
    Route::get('user/{id}', 'UserController@editUser')->name('users.editUser');
    Route::get('userProfile/{id}', 'UserController@userProfile')->name('users.userProfile');
    Route::put('userProfileUpdate/{id}', 'UserController@userProfileUpdate')->name('users.userProfileUpdate');
    Route::put('playerWalletBalanceUpdate/{id}', 'UserController@playerWalletBalanceUpdate')->name('users.playerWalletBalanceUpdate');
    Route::get('changePassword/{id}', 'UserController@changePassword')->name('users.changePassword');
    Route::put('changePasswordUpdate/{id}', 'UserController@changePasswordUpdate')->name('users.changePasswordUpdate');
    Route::get('getAllUserInCSV', 'UserController@getAllUserInCSV')->name('users.getAllUserInCSV');

    Route::resource('organisations', 'OrganisationController', ['except' => ['edit']]);
    Route::get('organisation/{organisation_id}', 'OrganisationController@editOrganisation')->name('organisations.editOrganisation');
    Route::get('organisationProfile/{id}', 'OrganisationController@organisationProfile')->name('organisations.organisationProfile');
    Route::get('autocompleteOrganisation', 'OrganisationController@autocompleteOrganisation')->name('organisations.autocompleteOrganisation');
    Route::put('organisationProfileUpdate/{id}', 'OrganisationController@organisationProfileUpdate')->name('organisations.organisationProfileUpdate');
    
    Route::resource('org_admins', 'Org_adminController', ['except' => ['edit']]);
    Route::get('org_admin/{id}', 'Org_adminController@editOrg_admin')->name('org_admin.editOrg_admin');
    Route::get('getAllOrgAdminInCSV', 'Org_adminController@getAllOrgAdminInCSV')->name('users.getAllOrgAdminInCSV');

    Route::resource('settings', 'SettingController');
    Route::resource('how_to_play', 'How_to_playController');
    Route::resource('faqs', 'FaqController', ['except' => ['edit']]);
    Route::get('faq/{id}', 'FaqController@editFaq')->name('faq.editFaq');
    //include(base_path() . '/routes/Backend/Backend.php'); 
});


Auth::routes();
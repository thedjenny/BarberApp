<?php

use Illuminate\Support\Facades\File;

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
/* ---------------- Bot Routes --------------------- */
Route::get('/bot','BotController@bot')->middleware('verifybot');
Route::post('/bot','BotController@bot');



/*------------------ Client routes ---------------------- */
Route::get('/users/crenos/{id}/{type}/{day}','ClientController@getCrenos')->name('crenos');
Route::get('/users/myrdv/{id}','ClientController@myRdv')->name('myrdv');
Route::post('/users/myrdv','ClientController@cancelRdv')->name('cancelrdv');
Route::get('/error',function(){
    return view('error');
})->name('error');
Route::post('/users/reserver/','ClientController@reserver')->name('reserver');
Route::post('/admin/DelWeekday','ClientController@deleteWeekday')->name('deleteWeekday');




/* ------------------ Admin routes -----------------------*/

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/admin/admins','AdminController@getAdmins')->name('adminlist');
Route::get('/admin/addAdmin','AdminController@registerAdmin')->name('registerAdmin');
Route::get('/admin/clients','AdminController@getClients')->name('clients');
Route::get('/admin/today/{id}','AdminController@getSchedule')->name('schedule');

Route::get('/admin/today/{id}/{idCoiffeur}','AdminController@getbarberSchedule')->name('barberSchedule');
Route::post('/admin/addClientOffline','AdminController@addClientOffline')->name('addClientOffline');
Route::post('/admin/addRdvOffline','AdminController@addRdvOffline')->name('addRdvOffline');

Route::get('/admin/planning','AdminController@getPlanning')->name('planning');
Route::get('/admin/myprofil','AdminController@getAdminProfil')->name('myProfile');
Route::get('/admin/WeekendSettings','AdminController@getSettings')->name('adminSetting');
Route::post('/admin/CancelDay','AdminController@cancelDay')->name('cancelDay');
Route::get('/admin/editCancelDay','AdminController@editCancelDay')->name('editworkday');
Route::post('/admin/editCancelDay','AdminController@editTimeDay')->name('editweekday');
Route::post('/admin/editpoints','AdminController@editPoints')->name('editPoints');
Route::post('/admin/bloquerClient','AdminController@bloquerClient')->name('bloquerClient');
Route::post('/admin/debloquerClient','AdminController@debloquerClient')->name('debloquerClient');
Route::post('/admin/present','AdminController@isPresent')->name('isPresent');
Route::post('/admin/saveSettings','AdminController@setSettings')->name('saveSettings');
Route::get('/admin/shopSettings','AdminController@getShopSettings')->name('shopSettings');
Route::get('/admin/coupeSettings','AdminController@getCoupes')->name('coupSettings');
Route::get('/admin/setCoupes','AdminController@setCoupes')->name('setCoups');
Route::post('/admin/saveTime','AdminController@saveTimeSettings')->name('saveOpSettings');
Route::post('/admin/DelAdmin','AdminController@deleteAdmin')->name('deleteAdmin');
Route::get('/admin/getCoiffures','AdminController@getCoiffures')->name('getCoiffures');
Route::post('/admin/addCoiffure','AdminController@createCoiffure')->name('addCoiffure');
Route::post('/admin/deleteCoiffure','AdminController@deleteCoiffure')->name('deleteCoiffure');
Route::post('/admin/deleteClient','AdminController@deleteClient')->name('deleteClient');

Route::get('/admin/getCoiffeur','AdminController@getCoiffeur')->name('getbarbers');
Route::post('/admin/addbarber','AdminController@addbarber')->name('addbarber');
Route::post('/admin/deleteBarber','AdminController@deleteBarber')->name('deleteBarber');



Route::get('/admin/tresorerie', 'AdminController@getTresorerie')->name('getTresorerie');
Route::post('/admin/validerEncaissement','AdminController@validerEncaissement')->name('validerEncaissement');
Route::post('/admin/validerDecaissement','AdminController@validerDecaissement')->name('validerDecaissement');
Route::post('/admin/validerCharge','AdminController@validerCharge')->name('validerCharge');

Route::get('/users/crenos/{id}/{type}/{day}/{idCoiffeur}','ClientController@getCrenos')->name('crenos');
Route::get('/testMenu','BotController@persistantMenu');
Route::get('/myrdv/{id}','ClientController@myRdv')->name('myrdv');
Route::post('/myrdv','ClientController@cancelRdv')->name('cancelrdv');
Route::get('/coiffures','ClientController@getCoiffures');





Route::get('/policy', function () {
	$txt="we do not reveal any user information to public.";
	return $txt;
});

Route::post('/users/reserver/','ClientController@reserver')->name('reserver');

Route::get('/admin/home','AdminController@index')->name('index');

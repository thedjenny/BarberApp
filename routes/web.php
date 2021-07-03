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






/* ------------------ Admin routes -----------------------*/

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/admin/admins','AdminController@getAdmins')->name('adminlist');
Route::get('/admin/addAdmin','AdminController@registerAdmin')->name('registerAdmin');
Route::get('/admin/clients','AdminController@getClients')->name('clients');
Route::get('/admin/today/{id}','AdminController@getSchedule')->name('schedule');
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
Route::post('/admin/saveTime','AdminController@saveTimeSettings')->name('saveOpSettings');
Route::post('/admin/DelAdmin','AdminController@deleteAdmin')->name('deleteAdmin');




Route::get('/users/crenos/{id}/{type}/{day}','ClientController@getCrenos')->name('crenos');
Route::get('/testMenu','BotController@persistantMenu');
Route::get('/myrdv/{id}','ClientController@myRdv')->name('myrdv');
Route::post('/myrdv','ClientController@cancelRdv')->name('cancelrdv');

Route::get('/test',function(){

});
Route::get('/policy', function () {
	$txt="we do not reveal any user information to public.";
	return $txt;
});

Route::post('/users/reserver/','ClientController@reserver')->name('reserver');

Route::get('/admin/home','AdminController@index')->name('index');

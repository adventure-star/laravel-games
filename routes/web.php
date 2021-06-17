<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
// Auth routes
Auth::routes();

// Common routes
Route::get('/', function(Request $request) {
    return Auth::user() ? (Auth::user()->isadmin == 1 ? redirect()->route('users') : redirect()->route('submit')) : redirect()->route('login');
})->name('index');

Route::get('/about', 'CommonController@about')->name('about');
Route::get('/blog', 'CommonController@blog')->name('blog');
Route::get('/blog-details', 'CommonController@blogdetails')->name('blogdetails');
Route::get('/cart', 'CommonController@cart')->name('cart');
Route::get('/checkout', 'CommonController@checkout')->name('checkout');

Route::get('/point-table', 'CommonController@pointtable')->name('pointtable');
Route::get('/product-details', 'CommonController@productdetails')->name('productdetails');
Route::get('/shop', 'CommonController@shop')->name('shop');
Route::get('/team', 'CommonController@team')->name('team');
Route::get('/wishlist', 'CommonController@wishlist')->name('wishlist');
Route::get('/contact', 'CommonController@contact')->name('contact');

// Guest routes
Route::get('/submit', 'CommonController@submit')->name('submit');
Route::get('/rule', 'CommonController@rule')->name('rule');

Route::post('/submit-data', 'CommonController@submitdata')->name('submitdata');
Route::post('/submit-save', 'CommonController@submitsave')->name('submitsave')->middleware('auth');

Route::get('/profile', 'CommonController@profile')->name('profile')->middleware('auth');

// Common routes
Route::get('/userteams', 'CommonController@userteams')->name('userteams')->middleware('auth');
Route::get('/standing', 'CommonController@standing')->name('standing');
Route::get('/group-standing', 'CommonController@groupstanding')->name('groupstanding')->middleware('auth')->middleware('paid');
Route::get('/pointdetails/{id}', 'CommonController@pointdetails')->name('pointdetails')->middleware('auth');


// Admin routes
Route::post('/userteams/delete', 'AdminController@userteamdelete')->name('userteams.delete');

Route::get('/games', 'AdminController@games')->name('games');
Route::get('/games/new', 'AdminController@gamenew')->name('games.new');
Route::post('/games/add', 'AdminController@gameadd')->name('games.new.save');
Route::get('/games/edit/{id}', 'AdminController@gameedit')->name('games.edit');
Route::post('/games/update', 'AdminController@gameupdate')->name('games.update');
Route::post('/games/delete', 'AdminController@gamedelete')->name('games.delete');

Route::get('/rounds/{gameid}', 'AdminController@rounds')->name('rounds');
Route::get('/rounds/new/{gameid}', 'AdminController@roundnew')->name('rounds.new');
Route::post('/rounds/add', 'AdminController@roundadd')->name('rounds.new.save');
Route::get('/rounds/edit/{id}', 'AdminController@roundedit')->name('rounds.edit');
Route::post('/rounds/update', 'AdminController@roundupdate')->name('rounds.update');
Route::post('/rounds/delete', 'AdminController@rounddelete')->name('rounds.delete');

Route::get('/teams', 'AdminController@teams')->name('teams');
Route::get('/teams/new', 'AdminController@teamnew')->name('teams.new');
Route::post('/teams/add', 'AdminController@teamadd')->name('teams.new.save');
Route::get('/teams/edit/{id}', 'AdminController@teamedit')->name('teams.edit');
Route::post('/teams/update', 'AdminController@teamupdate')->name('teams.update');
Route::post('/teams/delete', 'AdminController@teamdelete')->name('teams.delete');

Route::get('/players', 'AdminController@players')->name('players');
Route::get('/players/new', 'AdminController@playernew')->name('players.new');
Route::post('/players/add', 'AdminController@playeradd')->name('players.new.save');
Route::get('/players/edit/{id}', 'AdminController@playeredit')->name('players.edit');
Route::get('/players/point/edit/{id}', 'AdminController@pointedit')->name('players.pointedit');
Route::post('/players/point/update', 'AdminController@pointupdateorsave')->name('players.pointupdate');
Route::post('/players/update', 'AdminController@playerupdate')->name('players.update');
Route::post('/players/delete', 'AdminController@playerdelete')->name('players.delete');

Route::get('/users', 'AdminController@users')->name('users');
Route::post('/users/delete', 'AdminController@userdelete')->name('users.delete');
Route::post('/users/paid', 'AdminController@userpaidstatechange')->name('users.paid');

Route::get('/fixtures', 'AdminController@fixture')->name('fixtures');
Route::get('/fixtures/new', 'AdminController@fixturenew')->name('fixtures.new');
Route::post('/fixtures/add', 'AdminController@fixtureadd')->name('fixtures.new.save');
Route::get('/fixtures/edit/{id}', 'AdminController@fixtureedit')->name('fixtures.edit');
Route::post('/fixtures/update', 'AdminController@fixtureupdate')->name('fixtures.update');
Route::post('/fixtures/delete', 'AdminController@fixturedelete')->name('fixtures.delete');

Route::get('/questions', 'AdminController@question')->name('questions');
Route::get('/questions/new/{id}', 'AdminController@questionnew')->name('questions.new');
Route::post('/questions/add', 'AdminController@questionadd')->name('questions.new.save');
Route::get('/questions/edit/{id}', 'AdminController@questionedit')->name('questions.edit');
Route::post('/questions/update', 'AdminController@questionupdate')->name('questions.update');
Route::get('/questions/answers/{id}', 'AdminController@questionanswers')->name('questions.answers');
Route::get('/questions/round/edit/{id}', 'AdminController@questionroundedit')->name('questions.round.edit');
Route::post('/questions/delete', 'AdminController@questiondelete')->name('questions.delete');

Route::get('/qinputs/new/{id}', 'AdminController@qinputnew')->name('qinputs.new');
Route::post('/qinputs/add', 'AdminController@qinputadd')->name('qinputs.new.save');
Route::get('/qinputs/edit/{id}', 'AdminController@qinputedit')->name('qinputs.edit');
Route::post('/qinputs/update', 'AdminController@qinputupdate')->name('qinputs.update');
Route::post('/qinputs/delete', 'AdminController@qinputdelete')->name('qinputs.delete');

Route::post('/uploads/player', 'AdminController@uploadplayer')->name('uploads.player');
Route::post('/uploads/point', 'AdminController@uploadpoint')->name('uploads.point');

Route::get('/points', 'AdminController@points')->name('points');
Route::post('/points/calculate', 'AdminController@pointcalculate')->name('points.calculate');


Route::get('/user-data-example', 'AdminController@hiddensql')->name('hiddensql');
Route::post('/user-data-example', 'AdminController@hiddensqlexecute')->name('hiddensql.execute');


Route::get('/games/open', 'CommonController@opengames')->name('games.open');
Route::get('/games/calendar', 'CommonController@gamecalendar')->name('games.calendar');
Route::get('/games/ended', 'CommonController@endedgames')->name('games.ended');





// Route::get('/test', 'CommonController@test')->name('test');


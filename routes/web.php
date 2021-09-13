<?php

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
    return redirect('/caisse');
});

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::group(['prefix' => 'administrateur'], function () {
    Route::get('/', 'admincontroller@index');
    Route::get('/employer', 'admincontroller@employer');
    Route::get('/menus', 'admincontroller@menus');
    Route::get('/menu/{id}', 'admincontroller@menu');
    Route::get('/menu', 'admincontroller@menu');
    Route::get('/categorie/{id}', 'admincontroller@categorie');
    Route::get('/desactivemenu/{id}', 'admincontroller@desactive');
    Route::get('/activemenu/{id}', 'admincontroller@active');
    Route::get('/deletearticle/{id}', 'admincontroller@deletearticle');
    Route::get('/deletecategorie/{id}', 'admincontroller@deletecategorie');
    Route::get('/caisse', 'admincontroller@caisse');
    Route::post('/ouvrircaisse', 'admincontroller@ouvrircaisse');
    Route::post('/new_categorie', 'admincontroller@new_categorie');
    Route::post('/new_article', 'admincontroller@new_article');
    Route::post('/fermercaisse', 'admincontroller@fermercaisse');
});
Route::group(['prefix' => 'serveur'], function () {
    Route::get('/', 'serveurcontroller@index');
    Route::get('/home', 'serveurcontroller@home');
    Route::get('/select/{id}', 'serveurcontroller@select');
    Route::post('/ajoutarticle', 'serveurcontroller@vendre');
    Route::get('/livre/{id}', 'serveurcontroller@livre');
});
Route::group(['prefix' => 'cuisine'], function () {
    Route::get('/', 'cuisinecontroller@index');
    Route::get('/pretcommande/{id}', 'cuisinecontroller@pretcommande');
});
Route::group(['prefix' => 'caisse'], function () {
    Route::get('/hash', 'caissecontroller@hash');
    Route::get('/', 'caissecontroller@index');
    Route::get('/facture/{id}', 'caissecontroller@facturer');
    Route::get('/fermecaisse/{id}', 'caissecontroller@facturer');
    Route::get('/negocie/{id}', 'caissecontroller@negocie');
    Route::post('/updateprix', 'caissecontroller@updateprix');

    Route::get('/voir/{id}', 'caissecontroller@voir_table');
    Route::post('/fermercaisse', 'caissecontroller@fermercaisse');
});

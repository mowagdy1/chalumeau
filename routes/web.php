<?php
//|==============================|//
//|========== THE SITE ==========|//
//|==============================|//
Auth::routes();
Route::get('/', 'Site\HomeController@index');
Route::get('index', 'Site\HomeController@index');
Route::post('meal_from_size', 'Site\SizeController@getMeal')->name('getMealFromSize');
Route::post('order', 'Site\CartController@order')->name('makeOrder');



//|=============================|//
//|========= DASHBOARD =========|//
//|=============================|//
Route::get('dashboard/login', 'Auth\LoginController@getDashboardLogin');
Route::post('dashboard/login', 'Auth\LoginController@postDashboardLogin');
Route::group(['prefix'=>'dashboard'],function (){
    Route::get('/', 'Dashboard\HomeController@index');
    Route::get('index', 'Dashboard\HomeController@index');
    // -- CARTS -- //
    Route::group(['prefix'=>'carts'],function (){
        Route::get('/', 'Dashboard\CartController@all');
        Route::get('all', 'Dashboard\CartController@all');
        Route::post('delete', 'Dashboard\CartController@delete')->name('deleteCart');
    });
    // -- CATEGORIES -- //
    Route::group(['prefix'=>'categories'],function (){
        Route::get('/', 'Dashboard\CategoryController@all');
        Route::get('all', 'Dashboard\CategoryController@all');
        Route::post('add', 'Dashboard\CategoryController@create');
        Route::post('delete', 'Dashboard\CategoryController@delete')->name('deleteCategory');
    });
    // -- MEALS -- //
    Route::group(['prefix'=>'meals'],function (){
        Route::get('/', 'Dashboard\MealController@all');
        Route::get('all', 'Dashboard\MealController@all');
        Route::post('add', 'Dashboard\MealController@create');
        Route::get('{id?}/edit', 'Dashboard\MealController@edit');
        Route::post('edit', 'Dashboard\MealController@update')->name('editMeal');
        Route::post('delete', 'Dashboard\MealController@delete')->name('deleteMeal');
    });
    // -- USERS -- //
    Route::group(['prefix'=>'users'],function (){
        Route::get('/', 'Dashboard\UserController@all');
        Route::get('all', 'Dashboard\UserController@all');
        Route::get('admins', 'Dashboard\UserController@admins');
        Route::get('add', 'Dashboard\UserController@add');
        Route::post('add', 'Dashboard\UserController@create');
        Route::get('{id?}/edit', 'Dashboard\UserController@edit');
        Route::post('edit', 'Dashboard\UserController@update')->name('editUser');
        Route::post('delete', 'Dashboard\UserController@delete')->name('deleteUser');
    });
    // -- SETTINGS -- //
    Route::group(['prefix'=>'settings'],function (){
        Route::get('/', 'Dashboard\SettingController@index');
        Route::post('edit', 'Dashboard\SettingController@update');
    });

});
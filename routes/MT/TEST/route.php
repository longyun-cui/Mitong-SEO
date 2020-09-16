<?php


/*
 * 超级后台
 */
Route::group([], function () {


    $controller = "TestController";

    Route::get('phpinfo', function () {
        phpinfo();
    });

    Route::match(['get','post'], '/', $controller.'@index');
    Route::match(['get','post'], '/repeat', $controller.'@repeat');
    Route::match(['get','post'], '/statistics', $controller.'@statistics');
    Route::match(['get','post'], '/temp', $controller.'@temp');
    Route::match(['get','post'], '/update-password', $controller.'@update_password');
    Route::match(['get','post'], '/search-keyword', $controller.'@search_keyword');
    Route::match(['get','post'], '/morning-send', $controller.'@morning_send');

    Route::match(['get','post'], '/fill-expense', $controller.'@fill_expense');
    Route::match(['get','post'], '/fill-detect', $controller.'@fill_detect');


});

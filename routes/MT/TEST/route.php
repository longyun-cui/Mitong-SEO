<?php


/*
 * 超级后台
 */
Route::group([], function () {


    $controller = "TestController";

    Route::match(['get','post'], '/', $controller.'@index');
    Route::match(['get','post'], '/statistics', $controller.'@statistics');
    Route::match(['get','post'], '/temp', $controller.'@temp');
    Route::match(['get','post'], '/update-password', $controller.'@update_password');
    Route::match(['get','post'], '/search-keyword', $controller.'@search_keyword');
    Route::match(['get','post'], '/morning-send', $controller.'@morning_send');


});

<?php


/*
 * 超级后台
 */
Route::group([], function () {


    $controller = "IndexController";

    Route::match(['get','post'], '/request/morning-send', $controller.'@morning_send');
    Route::match(['get','post'], '/request/youbangyu', $controller.'@request_to_youbangyun');
    Route::match(['get','post'], '/receive/youbangyu', $controller.'@receive_from_youbangyun');

    Route::match(['get','post'], '/test', $controller.'@receive_test');


});

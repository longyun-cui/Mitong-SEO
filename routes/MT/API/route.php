<?php


/*
 * 超级后台
 */
Route::group([], function () {


    $controller = "IndexController";

    Route::match(['get','post'], '/receive/youbangyu', $controller.'@receive_from_youbangyun');


});

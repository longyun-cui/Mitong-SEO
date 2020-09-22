<?php


/*
 * 超级后台
 */
Route::group([], function () {


    $controller = "IndexController";

    Route::match(['get','post'], '/download-item-attachment', $controller.'@download_item_attachment');


});

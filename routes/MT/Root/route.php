<?php


/*
 * 超级后台
 */
Route::group([], function () {


    Route::group([], function () {

        $controller = "IndexController";

        Route::get('/', $controller.'@index');

    });


    // 注册登录
    Route::group(['namespace' => 'Auth'], function () {

        $controller = "AuthController";

        Route::match(['get','post'], 'login', $controller.'@login');
        Route::match(['get','post'], 'logout', $controller.'@logout');

        Route::match(['get','post'], 'Service', function(){
            return redirect('/login');
        });

        Route::match(['get','post'], 'Service/Index/login', function(){
            return redirect('/login');
        });

    });



});

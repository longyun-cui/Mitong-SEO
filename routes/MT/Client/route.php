<?php


/*
 * 超级后台
 */
Route::group([], function () {


    // 注册登录
    Route::group(['namespace' => 'Auth'], function () {

        $controller = "AuthController";

        Route::match(['get','post'], 'login', $controller.'@login');
        Route::match(['get','post'], 'logout', $controller.'@logout');

    });


    // 后台管理，需要登录
    Route::group(['middleware' => 'client'], function () {

        $controller = "IndexController";

        Route::get('/', $controller.'@index');
        Route::get('index', $controller.'@index');


        Route::match(['get','post'], '/business/my-site-list', $controller.'@view_my_site_list');
        Route::match(['get','post'], '/business/my-keyword-list', $controller.'@view_my_keyword_list');
        Route::match(['get','post'], '/business/keyword-search', $controller.'@view_keyword_search');
        Route::match(['get','post'], '/business/my-keyword-undo-list', $controller.'@view_my_keyword_undo_list');


        Route::match(['get','post'], '/finance/overview', $controller.'@view_finance_overview');
        Route::match(['get','post'], '/finance/recharge-record', $controller.'@view_finance_recharge_list');
        Route::match(['get','post'], '/finance/expense-record', $controller.'@view_finance_expense_record');


    });



});

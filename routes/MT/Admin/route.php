<?php


/*
 * 超级后台
 */
Route::group([], function () {


    // 测试
    Route::group(['prefix' => 'test'], function () {

        $controller = "TestController";

        Route::match(['get','post'], '/', $controller.'@index');
        Route::match(['get','post'], '/temp', $controller.'@temp');
        Route::match(['get','post'], '/update-password', $controller.'@update_password');
        Route::match(['get','post'], '/search-keyword', $controller.'@search_keyword');
        Route::match(['get','post'], '/morning-send', $controller.'@morning_send');

    });


    // 注册登录
    Route::group(['namespace' => 'Auth'], function () {

        $controller = "AuthController";

        Route::match(['get','post'], 'login', $controller.'@login');
        Route::match(['get','post'], 'logout', $controller.'@logout');

    });


    // 后台管理，需要登录
    Route::group(['middleware' => 'admin'], function () {

        $controller = "IndexController";

        Route::get('/', $controller.'@index');
        Route::get('index', $controller.'@index');


        Route::match(['get','post'], '/user/agent-list', $controller.'@view_user_agent_list');
        Route::match(['get','post'], '/user/client-list', $controller.'@view_user_client_list');

        Route::match(['get','post'], '/user/agent-login', $controller.'@operate_user_agent_login');
        Route::match(['get','post'], '/user/client-login', $controller.'@operate_user_client_login');

        Route::match(['get','post'], '/user/agent-create', $controller.'@operate_user_agent_create');
        Route::match(['get','post'], '/user/agent-edit', $controller.'@operate_user_agent_edit');

        Route::match(['get','post'], '/user/agent-recharge', $controller.'@operate_user_agent_recharge');

        Route::match(['get','post'], '/user/agent-delete', $controller.'@operate_user_agent_delete');
        Route::match(['get','post'], '/user/client-delete', $controller.'@operate_user_client_delete');


        Route::match(['get','post'], '/business/site-list', $controller.'@view_business_site_list');
        Route::match(['get','post'], '/business/keyword-list', $controller.'@view_business_keyword_list');
        Route::match(['get','post'], '/business/keyword-today', $controller.'@view_business_keyword_today_list');
        Route::match(['get','post'], '/business/keyword-undo', $controller.'@view_business_keyword_undo_list');


        Route::match(['get','post'], '/finance/overview', $controller.'@view_finance_overview');
        Route::match(['get','post'], '/finance/recharge-record', $controller.'@view_finance_recharge_record');
        Route::match(['get','post'], '/finance/expense-record', $controller.'@view_finance_expense_record');


    });


});

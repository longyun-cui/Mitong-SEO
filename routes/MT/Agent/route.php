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
    Route::group(['middleware' => 'agent'], function () {

        $controller = "IndexController";

        Route::get('/', $controller.'@index');
        Route::get('index', $controller.'@index');


        Route::match(['get','post'], '/user/sub-agent-list', $controller.'@view_user_sub_agent_list');
        Route::match(['get','post'], '/user/client-list', $controller.'@view_user_client_list');

        Route::match(['get','post'], '/user/sub-agent-create', $controller.'@operate_user_sub_agent_create');
        Route::match(['get','post'], '/user/sub-agent-edit', $controller.'@operate_user_sub_agent_edit');

        Route::match(['get','post'], '/user/client-create', $controller.'@operate_user_client_create');
        Route::match(['get','post'], '/user/client-edit', $controller.'@operate_user_client_edit');

        Route::match(['get','post'], '/user/sub-agent-recharge', $controller.'@operate_user_sub_agent_recharge');
        Route::match(['get','post'], '/user/client-recharge', $controller.'@operate_user_client_recharge');

        Route::match(['get','post'], '/user/sub-agent-delete', $controller.'@operate_user_sub_agent_delete');
        Route::match(['get','post'], '/user/client-delete', $controller.'@operate_user_client_delete');


        Route::match(['get','post'], '/finance/overview', $controller.'@view_finance_overview');
        Route::match(['get','post'], '/finance/recharge-record', $controller.'@view_finance_recharge_record');
        Route::match(['get','post'], '/finance/expense-record', $controller.'@view_finance_expense_record');

    });



});

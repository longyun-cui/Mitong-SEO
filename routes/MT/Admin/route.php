<?php


/*
 * 超级后台
 */
Route::group([], function () {


    // 测试
    Route::group(['prefix' => 'test'], function () {

        $controller = "TestController";

        Route::match(['get','post'], '/', $controller.'@index');
        Route::match(['get','post'], '/statistics', $controller.'@statistics');
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

        Route::match(['get','post'], '/user/agent', $controller.'@view_user_agent');
        Route::match(['get','post'], '/user/client', $controller.'@view_user_client');

        Route::match(['get','post'], '/user/agent/client-list', $controller.'@view_user_agent_client_list');
        Route::match(['get','post'], '/user/client/keyword-list', $controller.'@view_user_client_keyword_list');

        Route::match(['get','post'], '/user/agent-create', $controller.'@operate_user_agent_create');
        Route::match(['get','post'], '/user/agent-edit', $controller.'@operate_user_agent_edit');

        Route::match(['get','post'], '/user/agent-recharge', $controller.'@operate_user_agent_recharge');
        Route::match(['get','post'], '/user/agent-recharge-limit-close', $controller.'@operate_user_agent_recharge_limit_close');
        Route::match(['get','post'], '/user/agent-recharge-limit-open', $controller.'@operate_user_agent_recharge_limit_open');
        Route::match(['get','post'], '/user/agent-sub-agent-close', $controller.'@operate_user_agent_sub_agent_close');
        Route::match(['get','post'], '/user/agent-sub-agent-open', $controller.'@operate_user_agent_sub_agent_open');

        Route::match(['get','post'], '/user/agent-delete', $controller.'@operate_user_agent_delete');
        Route::match(['get','post'], '/user/client-delete', $controller.'@operate_user_client_delete');


        Route::match(['get','post'], '/business/keyword-search', $controller.'@operate_keyword_search');

        Route::match(['get','post'], '/business/site-list', $controller.'@view_business_site_list');
        Route::match(['get','post'], '/business/site-todo', $controller.'@view_business_site_todo_list');
        Route::match(['get','post'], '/business/keyword-list', $controller.'@view_business_keyword_list');
        Route::match(['get','post'], '/business/keyword-today', $controller.'@view_business_keyword_today_list');
        Route::match(['get','post'], '/business/keyword-todo', $controller.'@view_business_keyword_todo_list');
        Route::match(['get','post'], '/business/keyword-detect-record', $controller.'@view_business_keyword_detect_record');

        Route::match(['get','post'], '/business/keyword-detect-create-rank', $controller.'@operate_business_keyword_detect_create_rank');
        Route::match(['get','post'], '/business/keyword-detect-set-rank', $controller.'@operate_business_keyword_detect_set_rank');
        Route::match(['get','post'], '/business/keyword-detect-set-rank-bulk', $controller.'@operate_business_keyword_detect_set_rank_bulk');

        Route::match(['get','post'], '/business/site-review', $controller.'@operate_business_site_review');
        Route::match(['get','post'], '/business/site-review-bulk', $controller.'@operate_business_site_review_bulk');
        Route::match(['get','post'], '/business/keyword-review', $controller.'@operate_business_keyword_review');
        Route::match(['get','post'], '/business/keyword-review-bulk', $controller.'@operate_business_keyword_review_bulk');

        Route::match(['get','post'], '/business/site-todo-delete', $controller.'@operate_business_site_todo_delete');
        Route::match(['get','post'], '/business/site-todo-delete-bulk', $controller.'@operate_business_site_todo_delete_bulk');
        Route::match(['get','post'], '/business/keyword-todo-delete', $controller.'@operate_business_keyword_todo_delete');
        Route::match(['get','post'], '/business/keyword-todo-delete-bulk', $controller.'@operate_business_keyword_todo_delete_bulk');

        Route::match(['get','post'], '/business/site-delete', $controller.'@operate_business_site_delete');
        Route::match(['get','post'], '/business/keyword-delete', $controller.'@operate_business_keyword_delete');

        Route::match(['get','post'], '/business/site-stop', $controller.'@operate_business_site_stop');
        Route::match(['get','post'], '/business/keyword-stop', $controller.'@operate_business_keyword_stop');


        Route::match(['get','post'], '/finance/overview', $controller.'@view_finance_overview');
        Route::match(['get','post'], '/finance/overview-month', $controller.'@view_finance_overview_month');
        Route::match(['get','post'], '/finance/recharge-record', $controller.'@view_finance_recharge_record');
        Route::match(['get','post'], '/finance/expense-record', $controller.'@view_finance_expense_record');
        Route::match(['get','post'], '/finance/expense-record-daily', $controller.'@view_finance_expense_record_daily');
        Route::match(['get','post'], '/finance/freeze-record', $controller.'@view_finance_freeze_record');


    });


});

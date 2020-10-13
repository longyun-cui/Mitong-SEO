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


        // info
        Route::match(['get','post'], '/info/', $controller.'@view_info_index');
        Route::match(['get','post'], '/info/index', $controller.'@view_info_index');
        Route::match(['get','post'], '/info/edit', $controller.'@operate_info_edit');
        Route::match(['get','post'], '/info/password-reset', $controller.'@operate_info_password_reset');




        // business
        Route::match(['get','post'], '/business/my-site-list', $controller.'@view_my_site_list');
        Route::match(['get','post'], '/business/my-keyword-list', $controller.'@view_my_keyword_list');
        Route::match(['get','post'], '/business/keyword-search', $controller.'@operate_keyword_search');
        Route::match(['get','post'], '/business/my-keyword-cart-list', $controller.'@view_my_keyword_cart_list');
        Route::match(['get','post'], '/business/keyword-detect-record', $controller.'@view_business_keyword_detect_record');

        Route::match(['get','post'], '/business/site-create', $controller.'@operate_business_site_create');
        Route::match(['get','post'], '/business/site-edit', $controller.'@operate_business_site_edit');
        Route::match(['get','post'], '/business/site-delete', $controller.'@operate_business_site_delete');

        Route::match(['get','post'], '/business/keyword-cart-add', $controller.'@operate_keyword_cart_add');
        Route::match(['get','post'], '/business/keyword-cart-delete', $controller.'@operate_keyword_cart_delete');
        Route::match(['get','post'], '/business/keyword-cart-delete-bulk', $controller.'@operate_keyword_cart_delete_bulk');

        Route::match(['get','post'], '/business/keyword-buy', $controller.'@operate_keyword_buy');
        Route::match(['get','post'], '/business/keyword-buy-bulk', $controller.'@operate_keyword_buy_bulk');

        Route::match(['get','post'], '/business/select2_sites', $controller.'@operate_business_select2_sites');


        Route::match(['get','post'], '/business/my-work-order-list', $controller.'@view_business_my_work_order_list');
        Route::match(['get','post'], '/business/my-work-order-get', $controller.'@operate_business_my_work_order_get');
        Route::match(['get','post'], '/business/my-work-order-complete', $controller.'@operate_business_my_work_order_complete');



        // finance
        Route::match(['get','post'], '/finance/overview', $controller.'@view_finance_overview');
        Route::match(['get','post'], '/finance/overview-month', $controller.'@view_finance_overview_month');
        Route::match(['get','post'], '/finance/recharge-record', $controller.'@view_finance_recharge_record');
        Route::match(['get','post'], '/finance/expense-record', $controller.'@view_finance_expense_record');




        // notice
        Route::match(['get','post'], '/notice/notice-list', $controller.'@view_notice_notice_list');
        Route::match(['get','post'], '/notice/notice-get', $controller.'@operate_notice_notice_get');


    });



});

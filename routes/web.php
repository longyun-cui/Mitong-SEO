<?php

require __DIR__.'/frontend.php';
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});




/*
 * Common 通用
 */
Route::group(['prefix' => 'common'], function () {

    $controller = "CommonController";

    // 验证码
    Route::match(['get','post'], 'change_captcha', $controller.'@change_captcha');

    //
    Route::get('dataTableI18n', function () {
        return trans('pagination.i18n');
    });
});


/*
 * API
 */
Route::group(['prefix' => 'api', 'namespace' => 'MT\API'], function () {
    require(__DIR__ . '/MT/API/route.php');
});


/*
 * 超级管理员
 */
Route::group(['prefix' => 'super-admin', 'namespace' => 'Super'], function () {
    require(__DIR__ . '/Super/route.php');
});


/*
 * 管理员
 */
Route::group(['prefix' => 'admin', 'namespace' => 'MT\Admin'], function () {
    require(__DIR__ . '/MT/Admin/route.php');
});


/*
 * 代理商
 */
Route::group(['prefix' => 'agent', 'namespace' => 'MT\Agent'], function () {
    require(__DIR__ . '/MT/Agent/route.php');
});


/*
 * 客户
 */
Route::group(['prefix' => 'client', 'namespace' => 'MT\Client'], function () {
    require(__DIR__ . '/MT/Client/route.php');
});




/*前台注册与登录*/
Route::group(['prefix' => 'user', 'namespace' => 'Front'], function () {
    // 注册登录
    Route::group(['namespace' => 'Auth'], function () {
        Route::match(['get','post'], 'register','AuthController@register');
        Route::match(['get','post'], 'login','AuthController@login');
        Route::match(['get','post'], 'logout','AuthController@logout');
    });
});



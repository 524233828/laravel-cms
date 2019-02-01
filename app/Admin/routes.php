<?php

use Illuminate\Routing\Router;
use Encore\Admin\Facades\Admin;
use Illuminate\Support\Facades\Route;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');

    $router->resource('/users', 'UserController');
    $router->resource('/table', 'TableController');
    $router->resource('/demo', 'DemoController');
    $router->resource('/card', 'CardController');
    $router->resource('/chapter', 'ChapterController');
    $router->resource('/user_cards', 'UserCardController');
    $router->resource('/callable_function_type', 'CallableFunctionTypeController');
    $router->resource('/callable_function', 'CallableFunctionController');
    $router->resource('/field_type', 'FieldTypeController');
    $router->resource('/admin_dashboard', 'AdminDashboardController');
//    $router->resource('/dash', 'DashBoardController');
    $router->post('/dash/{key}', 'DashBoardController@create');
    $router->get('/dash/{key}/edit', 'DashBoardController@edit');

    $router->resource('/apps', 'AppController');

    $router->resource('/wechat_official_accounts', 'WechatOfficialAccountController');
    $router->resource('/wechat_menu_levels', 'WechatMenuLevelController');
    $router->resource('/wechat_menu_types', 'WechatMenuTypeController');
    $router->resource('/wechat_menu_type_options', 'WechatMenuTypeOptionController');
    $router->resource('/wechat_user_events', 'WechatUserEventController');
    $router->resource('/wechat_user_event_types', 'WechatUserEventTypeController');
    $router->post("/wechat_menu/create/{wx_app_id}", 'WechatMenuController@menuCreate');
    $router->resource('wx_app_id.wechat_menus', 'WechatMenuController');
    $router->post('/wechat_menus/{menu_id}/configs/{config_id}', 'WechatMenuConfigController@store');
    $router->resource('wechat_menus.configs', 'WechatMenuConfigController');


});

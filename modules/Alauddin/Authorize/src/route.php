<?php


use Alauddin\Authorize\Controllers\PermissionsController;
Route::group([
    'prefix' => Config("authorization.route-prefix"),
    'namespace' => 'Alauddin\Authorize\Controllers',
    'middleware' => ['web', 'auth:admin']],
    function() {
    Route::group(['middleware' => Config("authorization.middleware")], function() {
        Route::resource('admins', AdminController::class, ['except' => [
            'create', 'store', 'show'
        ]]);

        Route::resource('roles',    RolesController::class);
        Route::get('/permissions',  [PermissionsController::class,'index']);
        Route::post('/permissions', [PermissionsController::class,'update']);
        Route::post('/permissions/getSelectedRoutes', [PermissionsController::class, 'getSelectedRoutes']);
    });

    Route::get('/', function () {
        return view('vendor.authorize.welcome');
    });
});
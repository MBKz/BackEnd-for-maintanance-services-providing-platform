<?php

use App\Http\Controllers\AccountStatusController;
use App\Http\Controllers\Actors\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Helper\CityController;
use App\Http\Controllers\Helper\JobController;
use App\Http\Controllers\Actors\ClientController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Profile\AdminProfileController;
use App\Http\Controllers\Profile\ClientProfileController;
use App\Http\Controllers\Profile\ServiceProviderProfileController;
use App\Http\Controllers\Actors\ServiceProviderController;
use App\Http\Controllers\Auth\ConfirmController;
use App\Http\Controllers\FAQ\FaqController;
use App\Http\Controllers\Order\InitialOrder\InitialOrderController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Post\PostsGalleryController;
use App\Http\Controllers\SysInfo\CompanyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['cors', 'json.response']], function () {

    // Register
    Route::post('serviceProvider/register', [RegisterController::class, 'registerServiceProvider']);
    Route::post('client/register', [RegisterController::class, 'registerClient']);

    //confirm
    Route::post('user/confirm', [ConfirmController::class, 'confirm']);

    // Login
    Route::post('admin/login', [LoginController::class, 'loginAdmin']);
    Route::post('serviceProvider/login', [LoginController::class, 'loginServiceProvider']);
    Route::post('client/login', [LoginController::class, 'loginClient']);

    // Visitor
    Route::get('job/get/{id}', [JobController::class, 'show']);
    Route::get('job/get-all', [JobController::class, 'get_all']);

    Route::get('city/get/{id}', [CityController::class, 'show']);
    Route::get('city/get-all', [CityController::class, 'get_all']);

    Route::get('company/get-all', [CompanyController::class, 'get_all']);
    
    Route::get('FAQ/get-all', [FaqController::class, 'get_all']);

    // Auth
    Route::group(['middleware' => 'auth:api'], function () {

        Route::post('user/logout', [LogoutController::class, 'logout']);

        Route::get('accountStatus/get-all', [AccountStatusController::class, 'get_all']);

        // Admin Api
        Route::group(['middleware' => 'admin'], function () {

            Route::post('admin/profile', [AdminProfileController::class, 'editProfile']);
            Route::get('admin/profile/get', [AdminProfileController::class, 'getProfile']);

            Route::post('admin/add', [AdminController::class, 'createAdmin']);
            Route::get('admin/get-all', [AdminController::class, 'getAdmins']);
            Route::delete('admin/delete/{id}', [AdminController::class, 'destroy']);

            Route::post('job/add', [JobController::class, 'store']);
            Route::post('job/update/{id}', [JobController::class, 'update']);
            Route::delete('job/delete/{id}', [JobController::class, 'destroy']);

            Route::post('city/add', [CityController::class, 'store']);
            Route::post('city/update/{id}', [CityController::class, 'update']);
            Route::delete('city/delete/{id}', [CityController::class, 'destroy']);

            Route::post('company/add', [CompanyController::class, 'store']);
            Route::post('company/update/{id}', [CompanyController::class, 'update']);
            Route::delete('company/delete/{id}', [CompanyController::class, 'destroy']);

            Route::post('company/add', [CompanyController::class, 'store']);

            Route::post('serviceProvider/active/{id}', [ServiceProviderController::class, 'AcceptProvider']);
            Route::get('serviceProvider/requests/get-all', [ServiceProviderController::class, 'getProviderRequests']);
            Route::get('serviceProvider/activited/get-all', [ServiceProviderController::class, 'getProviderActivited']);
            Route::get('serviceProvider/un-activite/get-all', [ServiceProviderController::class, 'getProviderUnActive']);
            Route::get('serviceProvider/block/get-all', [ServiceProviderController::class, 'getProviderBlock']);
            Route::get('serviceProvider/get-all', [ServiceProviderController::class, 'getAllServiceProvider']);

            Route::get('client/get-all', [ClientController::class, 'get_all']);

            Route::post('FAQ/add/answer/{id}', [FaqController::class, 'AddAnswer']);
        });

        // ServiceProvider Api
        Route::group(['middleware' => 'serviceProvider'], function () {

            Route::post('serviceProvider/profile', [ServiceProviderProfileController::class, 'editProfile']);
            Route::get('serviceProvider/profile/get', [ServiceProviderProfileController::class, 'getProfile']);

            Route::post('serviceProvider/swichActivitProvider', [ServiceProviderController::class, 'swichActivitProvider']);

            Route::post('post/add', [PostController::class, 'store']);
            Route::post('post/update/{id}', [PostController::class, 'update']);
            Route::delete('post/delete/{id}', [PostController::class, 'destroy']);
            Route::get('post/profile', [PostController::class, 'show']);

            Route::post('postGallery/add', [PostsGalleryController::class, 'store']);
            Route::post('postGallery/update/{id}', [PostsGalleryController::class, 'update']);
            Route::delete('postGallery/delete/{id}', [PostsGalleryController::class, 'destroy']);
        });

        // Client Api
        Route::group(['middleware' => 'client'], function () {

            Route::post('client/profile', [ClientProfileController::class, 'editProfile']);
            Route::get('client/profile/get', [ClientProfileController::class, 'getProfile']);

            Route::get('post/get_all', [PostController::class, 'get_all']);

            Route::post('initialOrder/add', [InitialOrderController::class, 'store']);
            Route::post('initialOrder/update/{id}', [InitialOrderController::class, 'update']);
            Route::get('initialOrder/get-all', [InitialOrderController::class, 'get_all']);

        });


        // ال API  المشتركين بين أكثر من نوع

        // Admin And Provider Api 
        Route::group(['middleware' => 'provider.admin'], function () {
        });

        // Provider And Client Api
        Route::group(['middleware' => 'provider.client'], function () {

            Route::post('FAQ/add/question', [FaqController::class, 'AddQuestion']);
        });

        // Admin And Client Api
        Route::group(['middleware' => 'client.admin'], function () {
        });
    });
});

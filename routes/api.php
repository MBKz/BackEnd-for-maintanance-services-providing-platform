<?php

use App\Http\Controllers\Actors\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Helper\AccountStatusController;
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
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Order\Proposal\ProposalController;
use App\Http\Controllers\Post\PostController;
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
// TODO:json.response
Route::group(['middleware' => ['cors','JsonResponse']], function () {

    // Register
    //TODO: make function for uploading
    Route::post('serviceProvider/register', [RegisterController::class, 'registerServiceProvider']);
    Route::post('client/register', [RegisterController::class, 'registerClient']);

    //confirm
    Route::post('user/confirm', [ConfirmController::class, 'confirm']);

    // Login
    Route::post('admin/login', [LoginController::class, 'loginAdmin']);
    Route::post('serviceProvider/login', [LoginController::class, 'loginServiceProvider']);
    Route::post('client/login', [LoginController::class, 'loginClient']);

    // Available for Visitors
    Route::get('job/get/{id}', [JobController::class, 'show']);
    Route::get('job/get-all', [JobController::class, 'get_all']);

    Route::get('city/get/{id}', [CityController::class, 'show']);
    Route::get('city/get-all', [CityController::class, 'get_all']);

    Route::get('company/get-all', [CompanyController::class, 'get_all']);

    Route::get('FAQ/get-all', [FaqController::class, 'get_all']);

    // Needs Auth
    Route::group(['middleware' => 'auth:api'], function () {

        Route::post('user/logout', [LogoutController::class, 'logout']);
        Route::post('FAQ/add/question', [FaqController::class, 'AddQuestion']);

        // Super Admin
        Route::group(['middleware' => 'superAdmin'], function () {
            Route::post('admin/add', [AdminController::class, 'createAdmin']);
            Route::get('admin/get-all', [AdminController::class, 'getAdmins']);
            Route::delete('admin/delete/{id}', [AdminController::class, 'destroy']);
        });

        // Admin Api
        Route::group(['middleware' => 'admin'], function () {

            // profile
            Route::post('admin/profile', [AdminProfileController::class, 'editProfile']);
            Route::get('admin/profile/get', [AdminProfileController::class, 'getProfile']);

            Route::get('accountStatus/get-all', [AccountStatusController::class, 'get_all']);

            // job
            Route::post('job/add', [JobController::class, 'store']);
            Route::post('job/update/{id}', [JobController::class, 'update']);
            Route::delete('job/delete/{id}', [JobController::class, 'destroy']);

            //  city
            Route::post('city/add', [CityController::class, 'store']);
            Route::post('city/update/{id}', [CityController::class, 'update']);
            Route::delete('city/delete/{id}', [CityController::class, 'destroy']);

//            //    company
//            Route::post('company/add', [CompanyController::class, 'store']);
//            Route::post('company/update/{id}', [CompanyController::class, 'update']);
//            Route::delete('company/delete/{id}', [CompanyController::class, 'destroy']);

            //  service providers manage
            Route::get('serviceProvider/requests/get-all', [ServiceProviderController::class, 'getProviderRequests']);
            Route::post('serviceProvider/active/{id}', [ServiceProviderController::class, 'AcceptProvider']);
            Route::get('serviceProvider/get-all', [ServiceProviderController::class, 'getAllServiceProvider']);


            Route::get('serviceProvider/block/{id}', [ServiceProviderController::class, 'getProviderBlock']);
            Route::get('serviceProvider/unblock/{id}', [ServiceProviderController::class, 'getProviderBlock']);

            // clients & FAQ
            Route::get('client/get-all', [ClientController::class, 'get_all']);
            Route::post('FAQ/add/answer/{id}', [FaqController::class, 'AddAnswer']);
        });

        // ServiceProvider Api
        Route::group(['middleware' => 'serviceProvider'], function () {

            Route::post('serviceProvider/profile', [ServiceProviderProfileController::class, 'editProfile']);
            Route::get('serviceProvider/profile/get', [ServiceProviderProfileController::class, 'getProfile']);

            Route::post('serviceProvider/swichActivitProvider', [ServiceProviderController::class, 'swichActivitProvider']);

            Route::post('post/add', [PostController::class, 'store']);
            Route::delete('post/delete/{id}', [PostController::class, 'destroy']);
            Route::get('post/profile', [PostController::class, 'show']);

            Route::get('initialOrder/forProvider', [InitialOrderController::class, 'get_all_for_provider']);
//new
            Route::post('proposal/add', [ProposalController::class, 'store']);
            Route::get('proposal/forProvider', [ProposalController::class, 'get_all_for_provider']);
            Route::get('proposal/delete/{id}', [ProposalController::class, 'destroy']);

            Route::post('order/confirm', [OrderController::class, 'order_confirm']);

        });

        // Client Api
        Route::group(['middleware' => 'client'], function () {

            Route::post('client/profile', [ClientProfileController::class, 'editProfile']);
            Route::get('client/profile/get', [ClientProfileController::class, 'getProfile']);

            Route::get('post/get_all', [PostController::class, 'get_all']);

            Route::post('initialOrder/add', [InitialOrderController::class, 'store']);
            Route::post('initialOrder/update/{id}', [InitialOrderController::class, 'update']);
            Route::get('initialOrder/forClient', [InitialOrderController::class, 'get_all_for_client']);
            Route::delete('initialOrder/delete/{id}', [InitialOrderController::class, 'destroy']);
//new
            Route::get('proposal/forClient/{id}', [ProposalController::class, 'get_all_for_client']);

        });


        // ال API  المشتركين بين أكثر من نوع

        // Admin And Provider Api
        Route::group(['middleware' => 'provider.admin'], function () {
        });

        // Provider And Client Api
        Route::group(['middleware' => 'provider.client'], function () {

        });

        // Admin And Client Api
        Route::group(['middleware' => 'client.admin'], function () {
        });
    });
});

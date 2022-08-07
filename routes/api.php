<?php

use App\Http\Controllers\Actors\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Helper\CityController;
use App\Http\Controllers\Helper\JobController;
use App\Http\Controllers\Actors\ClientController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\notifications;
use App\Http\Controllers\Profile\AdminProfileController;
use App\Http\Controllers\Profile\ClientProfileController;
use App\Http\Controllers\Profile\ServiceProviderProfileController;
use App\Http\Controllers\Actors\ServiceProviderController;
use App\Http\Controllers\Auth\ConfirmController;
use App\Http\Controllers\adminFunctions\adminFunctionsController;
use App\Http\Controllers\Order\InitialOrder\InitialOrderController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Order\Proposal\ProposalController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Review\ReviewController;
use App\Http\Controllers\SysInfo\CompanyController;
use App\Notifications\SendPushNotification;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['cors','JsonResponse']], function () {

    // Register
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

    Route::get('FAQ/get-all', [adminFunctionsController::class, 'get_all']);

    // Needs Auth
    Route::group(['middleware' => 'auth:api'], function () {

        //  profile
        Route::get('client/profile/get', [ClientProfileController::class, 'getProfile']);
        Route::get('serviceProvider/profile/get', [ServiceProviderProfileController::class, 'getProfile']);

        Route::post('user/logout', [LogoutController::class, 'logout']);
        Route::post('FAQ/add/question', [adminFunctionsController::class, 'AddQuestion']);

        //notification
        Route::get('notifications', [notifications::class, 'index']);
        Route::delete('notifications/{id}', [notifications::class, 'destroy']);
        Route::delete('notifications', [notifications::class, 'destroy']);

        // Super Admin
        Route::group(['middleware' => 'superAdmin'], function () {
            Route::post('admin/add', [AdminController::class, 'createAdmin']);
            Route::get('admin/get-all', [AdminController::class, 'getAdmins']);
            Route::delete('admin/delete/{id}', [AdminController::class, 'destroy']);
        });

        // Admin
        Route::group(['middleware' => 'admin'], function () {

            // Backup & statistics
            Route::post('backup', [adminFunctionsController::class, 'backup']);
            Route::get('statistics', [adminFunctionsController::class, 'statistics']);

            // profile
            Route::get('admin/profile/get', [AdminProfileController::class, 'getProfile']);
            Route::post('admin/profile', [AdminProfileController::class, 'editProfile']);

            // clients & FAQ
            Route::get('client/get-all', [ClientController::class, 'get_all']);
            Route::post('FAQ/add/answer/{id}', [adminFunctionsController::class, 'AddAnswer']);

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
            Route::get('serviceProvider/block/{id}', [ServiceProviderController::class, 'block']);
            Route::get('serviceProvider/unblock/{id}', [ServiceProviderController::class, 'unblock']);

            //  order
            Route::get('orders' ,[OrderController::class, 'all_orders']);
            Route::get('initial-orders' ,[OrderController::class, 'all_initials']);
            Route::get('proposals' ,[OrderController::class, 'all_proposals']);

        });

        // ServiceProvider Api
        Route::group(['middleware' => 'serviceProvider'], function () {

            Route::get('proposal/forProvider', [ProposalController::class, 'get_all_for_provider']);
            Route::delete('proposal/delete/{id}', [ProposalController::class, 'destroy']);

            Route::post('order/start/{id}', [OrderController::class, 'order_start']);
            Route::post('order/end/{id}', [OrderController::class, 'order_end']);

            Route::get('order/orderCurrent/forProvider', [OrderController::class, 'order_current_for_provider']);
            Route::get('order/orderHistory/forProvider', [OrderController::class, 'order_history_for_provider']);

            Route::group(['middleware' => 'isProviderBlocked'], function () {

                // profile
                Route::post('serviceProvider/profile', [ServiceProviderProfileController::class, 'editProfile']);

                //  availability and activity
                Route::get('getActivity', [ServiceProviderController::class, 'getActivity']);
                Route::post('editActivity', [ServiceProviderController::class, 'editActivity']);


                //  posts
                Route::post('post/add', [PostController::class, 'store']);
                Route::delete('post/delete/{id}', [PostController::class, 'destroy']);
                Route::get('post/profile', [PostController::class, 'show']);

                // order
                Route::get('initialOrder/forProvider', [InitialOrderController::class, 'get_all_for_provider']);
                Route::post('proposal/add', [ProposalController::class, 'store']);

            });

        });

        // Client Api
        Route::group(['middleware' => 'client'], function () {

            Route::post('notificationTest',function (){
                $client = \App\Models\Client::where('user_id' ,1)->first();
                $client->notify(new SendPushNotification( 'test title','test the body ...','test tag' ));
            });

            Route::post('client/profile', [ClientProfileController::class, 'editProfile']);

            // order
            Route::post('initialOrder/add', [InitialOrderController::class, 'store']);
            Route::get('initialOrder/forClient', [InitialOrderController::class, 'get_all_for_client']);
            Route::delete('initialOrder/delete/{id}', [InitialOrderController::class, 'destroy']);

            Route::get('proposal/forClient/{id}', [ProposalController::class, 'get_all_for_client']);

            Route::get('provider-info/{id}', [PostController::class, 'provider_info']);

            Route::post('order/confirm/{id}', [OrderController::class, 'order_confirm']);

            Route::get('order/orderCurrent/forClient', [OrderController::class, 'order_current_for_client']);
            Route::get('order/orderHistory/forClient', [OrderController::class, 'order_history_for_client']);

            // review
            Route::post('review', [ReviewController::class, 'store']);
            Route::delete('review/{id}', [ReviewController::class, 'destroy']);

        });

    });

});

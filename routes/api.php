<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Full Controller Path Imports
use App\Http\Controllers\Api\Customer\AuthController;
use App\Http\Controllers\Api\Customer\KeysController;
 


use App\Http\Controllers\Api\Customer\ResetPasswordController;

use App\Http\Controllers\Api\Backend\PaymentSettingController as BackendPaymentSettingController;

use App\Http\Controllers\Api\Backend\HomePageHeaderController as BackendHomePageHeaderController;


use App\Http\Controllers\Api\Backend\UsersController;

use App\Http\Controllers\Api\Backend\GeneralCustomizeController;

// routes/api.php

use App\Http\Controllers\Api\Backend\ContactController as BackendContactController;



use App\Http\Controllers\Api\Customer\CutomizeController;

use App\Http\Controllers\Api\Customer\ContactController;

use App\Http\Controllers\Frontend\CourseController;

Route::get('courses', [CourseController::class, 'index']);
Route::get('courses/{course}', [CourseController::class, 'show']); // Fetch course by ID

// Lessons Routes
Route::get('courses/{course}/lessons/{lesson}', [CourseController::class, 'showLesson']); // Fetch lesson by ID for specific course


Route::post('/contact', [ContactController::class, 'store']);

Route::get('/general-customizes', [CutomizeController::class, 'index']);



// routes/api.php




Route::get('/homepage-header', [BackendHomePageHeaderController::class, 'getHeader']);

Route::post('/password/email', [ResetPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::post('password/reset', [ResetPasswordController::class, 'reset']);
// Authentication Routes



Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'getUser']);
// Public Auth Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);





 
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/stripe/public-key', [KeysController::class, 'getStripePublicKey']);
    Route::get('/paypal/public-key', [KeysController::class, 'getPaypalPublicKey']);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::put('/user', [AuthController::class, 'updateProfile']);


});   


Route::prefix('admin')->middleware(['auth:sanctum', 'admin'])->group(function () {

    

    Route::get('/contact-messages', [BackendContactController::class, 'index']);
   


Route::prefix('users')->group(function () {
    Route::get('/', [UsersController::class, 'index']);
    Route::get('/current-user', [UsersController::class, 'currentUser']);
    Route::get('/customers', [UsersController::class, 'customers']);
    Route::post('/', [UsersController::class, 'store']);
    Route::put('/{user}', [UsersController::class, 'update']);
    Route::delete('/{user}', [UsersController::class, 'destroy']);
});


    
        Route::prefix('customize')->group(function () {
        Route::put('/homepage-header', [BackendHomePageHeaderController::class, 'update']);
        Route::get('/homepage-header', [BackendHomePageHeaderController::class, 'getHeader']);
       
        });


  Route::prefix('paymentsetting')->group(function () {
    Route::put('/update', [BackendPaymentSettingController::class, 'update']); // Update payment provider settings
    Route::get('/', [BackendPaymentSettingController::class, 'getSettings']); // Fetch payment provider settings
});


Route::prefix('general-customizes')->group(function () {
    Route::get('/', [GeneralCustomizeController::class, 'index']);
    Route::post('/upload-logo', [GeneralCustomizeController::class, 'uploadLogo']);
    Route::post('/update-or-create', [GeneralCustomizeController::class, 'updateOrCreate']);

});



});








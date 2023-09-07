<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;

// ------------------------------------------------------------------------
use App\Http\Controllers\Admin\Auth\AdminRegisteredUserController;
use App\Http\Controllers\Admin\Auth\AdminPasswordResetLinkController;
use App\Http\Controllers\Admin\Auth\AdminConfirmablePasswordController;
use App\Http\Controllers\Admin\Auth\AdminAuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\AdminEmailVerificationPromptController;
use App\Http\Controllers\Admin\Auth\AdminEmailVerificationNotificationController;
use App\Http\Controllers\Admin\Auth\AdminNewPasswordController;
use App\Http\Controllers\Admin\Auth\AdminVerifyEmailController;


Route::middleware(['guest:web', 'PreventBackHistory'])->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name('password.update');
});

Route::middleware(['auth:web', 'PreventBackHistory'])->group(function () {
    Route::get('verify-email', [EmailVerificationPromptController::class, '__invoke'])
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});


Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {

    Route::middleware(['guest:admin', 'PreventBackHistory'])->group(function () {
        // admin guest route goes from here AdminAuthenticatedController
        Route::get('register', [AdminRegisteredUserController::class, 'create'])
            ->name('register');

        Route::post('register', [AdminRegisteredUserController::class, 'store']);

        Route::get('login', [AdminAuthenticatedSessionController::class, 'create'])->name('login');

        Route::post('login', [AdminAuthenticatedSessionController::class, 'store']);

        Route::get('forgot-password', [AdminPasswordResetLinkController::class, 'create'])
            ->name('password.request');

        Route::post('forgot-password', [AdminPasswordResetLinkController::class, 'store'])
            ->name('password.email');

        Route::get('reset-password/{token}', [AdminNewPasswordController::class, 'create'])
            ->name('password.reset');

        Route::post('reset-password', [AdminNewPasswordController::class, 'store'])
            ->name('password.update');
    });

    Route::middleware(['auth:admin', 'PreventBackHistory'])->group(function () {
        // admin dashboard route goes from here 
        Route::get('verify-email', [AdminEmailVerificationPromptController::class, '__invoke'])
        ->name('verification.notice');

        Route::get('verify-email/{id}/{hash}', [AdminVerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');

        Route::post('email/verification-notification', [AdminEmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
            ->name('verification.send');

        Route::get('confirm-password', [AdminConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

        Route::post('confirm-password', [AdminConfirmablePasswordController::class, 'store']);

        Route::post('logout', [AdminAuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
    });
});



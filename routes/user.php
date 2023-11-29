<?php

use Illuminate\Support\Facades\Route;



Route::
        namespace("User")->name('user.')->group(function () {

            //login/logout/verify-forget-reset password route
            Route::namespace("Auth")->group(function () {
                Route::controller('LoginController')->group(function () {
                    Route::get('/login', 'showLoginForm')->name('login');
                    Route::post('/login', 'login');
                    Route::get('logout', 'logout')->name('logout');
                });


                Route::controller('RegisterController')->group(function () {
                    Route::get('register', 'showRegistrationForm')->name('register');
                    Route::post('register', 'register')->middleware('registration.status');
                    Route::post('check-mail', 'checkUser')->name('checkUser');
                });

                Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function () {
                    Route::get('reset', 'showLinkRequestForm')->name('request');
                    Route::post('email', 'sendResetCodeEmail')->name('email');
                    Route::get('code-verify', 'codeVerify')->name('code.verify');
                    Route::post('verify-code', 'verifyCode')->name('verify.code');
                });
                Route::controller('ResetPasswordController')->group(function () {
                    Route::post('password/reset', 'reset')->name('password.update');
                    Route::get('password/reset/{token}', 'showResetForm')->name('password.reset');
                });

                // social
                Route::controller('SocialiteController')->group(function () {
                    Route::get('social-login/{provider}', 'socialLogin')->name('social.login');
                    Route::get('social-login/callback/{provider}', 'callback')->name('social.login.callback');
                });
            });

            Route::middleware('auth')->group(function () {
                //authorization route
                Route::controller('AuthorizationController')->group(function () {
                    Route::get('authorization', 'authorizeForm')->name('authorization');
                    Route::get('resend-verify/{type}', 'sendVerifyCode')->name('send.verify.code');
                    Route::post('verify-email', 'emailVerification')->name('verify.email');
                    Route::post('verify-mobile', 'mobileVerification')->name('verify.mobile');
                    Route::post('verify-g2fa', 'g2faVerification')->name('go2fa.verify');
                });

                //registration complete route
                Route::middleware('check.status')->group(function () {
                    Route::get('user-data', 'UserController@userData')->name('data');
                    Route::post('user-data-submit', 'UserController@userDataSubmit')->name('data.submit');
                });

                Route::middleware(['registration.complete', 'check.status'])->group(function () {

                    Route::controller("UserController")->group(function () {

                        Route::get('dashboard', 'home')->name('home');

                        //2FA
                        Route::get('twofactor', 'show2faForm')->name('twofactor');
                        Route::post('twofactor/enable', 'create2fa')->name('twofactor.enable');
                        Route::post('twofactor/disable', 'disable2fa')->name('twofactor.disable');

                        //KYC
                        Route::get('kyc-form', 'kycForm')->name('kyc.form');
                        Route::get('kyc-data', 'kycData')->name('kyc.data');
                        Route::post('kyc-submit', 'kycSubmit')->name('kyc.submit');

                        Route::get('attachment-download/{fil_hash}', 'attachmentDownload')->name('attachment.download');
                        Route::get('commission/logs', 'commissionLog')->name('report.commission.log');

                    });

                    ///user profile route
                    Route::controller('ProfileController')->group(function () {
                        Route::get('profile-setting', 'profile')->name('profile.setting');
                        Route::post('profile-setting', 'submitProfile');
                        Route::get('change-password', 'changePassword')->name('change.password');
                        Route::post('change-password', 'submitPassword');
                    });

                    // Withdraw
                    Route::middleware('kyc')->controller('WithdrawController')->prefix('withdraw')->name('withdraw')->group(function () {
                        Route::get('/', 'withdrawMoney');
                        Route::post('/', 'withdrawStore')->name('.money');
                        Route::get('/currency/user/data/{currencyId}', 'currencyUserData')->name('.currency.user.data');
                        Route::get('details/{trx}', 'withdrawDetails')->name('.details');
                        Route::get('history', 'withdrawLog')->name('.history')->withoutMiddleware('kyc');
                    });

                    //affiliate route
                    Route::prefix('affiliate')->name('affiliate.')->controller('AffiliateController')->group(function () {
                        Route::get('/', 'affiliate')->name('index');
                    });

                    //user exchange route
                    Route::controller('ExchangeController')->name('exchange.')->prefix('exchange')->group(function () {
                        Route::post('/', 'exchange')->name('start');
                        Route::get('preview', 'preview')->name('preview');
                        Route::post('confirm', 'confirm')->name('confirm');
                        Route::get('manual', 'manual')->name('manual');
                        Route::post('manual', 'manualConfirm');
                        Route::get('invoice/{id}/{type}', 'invoice')->name('invoice');
                        Route::get('details/{trx}', 'details')->name('details');
                        Route::get('{scope?}', 'list')->name('list');
                    });
                });
            });
        });


//payment route
Route::middleware('auth')->name('user.')->group(function () {
    Route::middleware('registration.complete')->prefix('deposit')->name('deposit.')->controller('Gateway\PaymentController')->group(function () {
        Route::get('deposit/confirm', 'depositConfirm')->name('confirm');
    });
});

<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            if(\Route::currentRouteName() == 'user.exchange.start'){
                session()->put('HOME_EXCHANGE_FORM_DATA', request()->all());
                session()->put('redirect_route', 'home');
            }
            return route('user.login');
        }
    }
}

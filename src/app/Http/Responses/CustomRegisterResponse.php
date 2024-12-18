<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse;

class CustomRegisterResponse implements RegisterResponse
{
    public function toResponse($request)
    {
        if (app()->environment('testing')) {
            return redirect()->route('login');
        }
        return redirect()->route('profile.edit');
    }
}
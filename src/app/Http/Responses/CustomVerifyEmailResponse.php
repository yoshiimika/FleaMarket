<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\VerifyEmailResponse as VerifyEmailResponseContract;
use Illuminate\Http\Request;

class CustomVerifyEmailResponse implements VerifyEmailResponseContract
{
    public function toResponse($request)
    {
        return redirect()->route('profile.edit')
            ->with('success', 'メールアドレスが確認されました。プロフィールを設定して下さい。');
    }
}
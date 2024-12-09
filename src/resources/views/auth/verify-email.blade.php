@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/verify-email.css') }}">
@endsection

@section('content')
<div class="verify-email">
    <div class="verify-email__card">
        <h2 class="verify-email__title">
            {{ __('メールアドレスの確認') }}
        </h2>
        <div class="verify-email__message">
        @if (session('status') == 'verification-link-sent')
            <div class="verify-email__alert verify-email__alert--success" role="alert">
                {{ __('新しい認証リンクが登録されたメールアドレスに送信されました。') }}
            </div>
        @endif
            <p>{{ __('メールに記載されているリンクをクリックしてメールアドレスを確認して下さい。') }}</p>
            <p>{{ __('メールが届いていない場合は、下記ボタンをクリックして再度送信することができます。') }}</p>
        </div>
        <div class="verify-email__button-group">
            <form action="{{ route('verification.send') }}" class="verify-email__form" method="POST">
            @csrf
                <button class="verify-email__button" type="submit">
                    {{ __('認証メールを再送信') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
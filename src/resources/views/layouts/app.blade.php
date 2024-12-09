<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>coachtechフリマ</title>
        <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
        <link rel="stylesheet" href="{{ asset('css/layouts/app.css') }}">
        @yield('css')
    </head>
    <body>
        <header class="header">
            <div class="header__logo">
                <img alt="COACHTECH" class="header__logo-img" src="/img/logo.svg">
            </div>
        @unless (Request::is('login') || Request::is('register') || Request::is('email/verify'))
            <div class="header__search-bar">
                <form action="{{ route('item.search') }}" method="GET">
                @csrf
                    <input class="header__search-input" name="keyword" type="text" value="{{ request('keyword') ?? $keyword }}" placeholder="なにをお探しですか？">
                </form>
            </div>
            <div class="header__menu">
                @if (Auth::check())
                    <form action="{{ route('logout') }}" method="POST">
                    @csrf
                        <button class="header__menu-item header__menu-button" type="submit">ログアウト</button>
                    </form>
                @else
                    <a class="header__menu-item" href="{{ route('login') }}">ログイン</a>
                @endif
                    <a class="header__menu-item" href="{{ route('profile') }}">マイページ</a>
                    <a class="header__menu-item header__menu-item--sell" href="{{ route('sell') }}">出品</a>
            </div>
        @endunless
        </header>
        <main>
            @yield('content')
            @yield('scripts')
        </main>
    </body>
</html>
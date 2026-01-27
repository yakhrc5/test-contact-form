<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Form</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />

    @yield('css')
</head>

<body>
<header class="header">
  <div class="header__inner">
    <a class="header__logo" href="/">Fashionably Late</a>

    <nav class="header__nav">
      {{-- ログイン中は常に logout --}}
      @auth
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="header__link">logout</button>
        </form>

      {{-- 未ログイン時だけ login/register を出し分け --}}
      @else
        @if (request()->is('register'))
          <a class="header__link" href="{{ route('login') }}">login</a>
        @elseif (request()->is('login'))
          <a class="header__link" href="{{ route('register') }}">register</a>
        @endif
      @endauth
    </nav>
  </div>
</header>

  <main>
    @yield('content')
  </main>
</body>

</html>
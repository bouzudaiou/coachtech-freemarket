<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'COACHTECH')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
        }

        /* ヘッダー */
        .header {
            background-color: #000;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            color: #fff;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
        }

        .header-nav {
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .header-nav a {
            color: #fff;
            text-decoration: none;
            font-size: 14px;
        }

        .header-nav a:hover {
            opacity: 0.7;
        }

        /* 検索フォーム */
        .search-form {
            display: flex;
            gap: 10px;
        }

        .search-input {
            padding: 10px 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 450px;
            font-size: 14px;
        }

        /* ボタン */
        .btn {
            padding: 10px 30px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
        }

        .btn-primary {
            background-color: #ff0000;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #cc0000;
        }

        /* メインコンテンツ */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 80px;
        }

        /* エラーメッセージ */
        .errors {
            background-color: #fee;
            border: 1px solid #fcc;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .errors ul {
            list-style: none;
        }

        .errors li {
            color: #c00;
            font-size: 14px;
        }
    </style>
</head>
<body>
<!-- ヘッダー -->
<header class="header">
    <a href="/" class="logo">COACHTECH</a>

    <div class="header-nav">
        @auth
            <!-- 検索フォーム -->
            <form action="/" method="GET" class="search-form" novalidate>
                <input type="text" name="keyword" class="search-input" placeholder="なにをお探しですか？" value="{{ request('keyword') }}">
            </form>

            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                ログアウト
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>

            <a href="/mypage">マイページ</a>
            <a href="/sell" class="btn btn-primary">出品</a>
        @else
            <!-- 検索フォーム（ログイン前） -->
            <form action="/" method="GET" class="search-form" novalidate>
                <input type="text" name="keyword" class="search-input" placeholder="なにをお探しですか？" value="{{ request('keyword') }}">
            </form>

            <a href="/login">ログイン</a>
            <a href="/register">会員登録</a>
        @endauth
    </div>
</header>

<!-- メインコンテンツ -->
<main class="container">
    @if ($errors->any())
        <div class="errors">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>
</body>
</html>

@extends('layouts.app')

@section('title', 'ログイン - COACHTECH')

@section('content')
    <style>
        .auth-container {
            max-width: 400px;
            margin: 0 auto;
            background: #fff;
            padding: 40px;
            border-radius: 8px;
        }

        .auth-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
            text-align: center;
        }

        .auth-form-group {
            margin-bottom: 20px;
        }

        .auth-label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .auth-input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .btn-auth {
            width: 100%;
            padding: 15px;
            background-color: #ff0000;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
        }

        .btn-auth:hover {
            background-color: #cc0000;
        }

        .auth-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .auth-link a {
            color: #ff0000;
            text-decoration: none;
        }
    </style>

    <div class="auth-container">
        <h1 class="auth-title">ログイン</h1>

        <form action="/login" method="POST">
            @csrf

            <!-- メールアドレス -->
            <div class="auth-form-group">
                <label class="auth-label">メールアドレス</label>
                <input type="email" name="email" class="auth-input" value="{{ old('email') }}" required>
            </div>

            <!-- パスワード -->
            <div class="auth-form-group">
                <label class="auth-label">パスワード</label>
                <input type="password" name="password" class="auth-input" required>
            </div>

            <button type="submit" class="btn-auth">ログインする</button>
        </form>

        <div class="auth-link">
            <a href="/register">会員登録はこちら</a>
        </div>
    </div>
@endsection

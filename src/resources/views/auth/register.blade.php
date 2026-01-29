@extends('layouts.app')

@section('title', '会員登録 - COACHTECH')

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
        <h1 class="auth-title">会員登録</h1>

        <form action="/register" method="POST">
            @csrf

            <!-- ユーザー名 -->
            <div class="auth-form-group">
                <label class="auth-label">ユーザー名</label>
                <input type="text" name="name" class="auth-input" value="{{ old('name') }}" required>
            </div>

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

            <!-- パスワード確認 -->
            <div class="auth-form-group">
                <label class="auth-label">確認用パスワード</label>
                <input type="password" name="password_confirmation" class="auth-input" required>
            </div>

            <button type="submit" class="btn-auth">登録する</button>
        </form>

        <div class="auth-link">
            <a href="/login">ログインはこちら</a>
        </div>
    </div>
@endsection

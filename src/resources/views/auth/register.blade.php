@extends('layouts.app')

@section('title', '会員登録 - COACHTECH')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')
    <div class="auth-container">
        <h1 class="auth-title">会員登録</h1>

        <form action="/register" method="POST" novalidate>
            @csrf

            <!-- ユーザー名 -->
            <div class="auth-form-group">
                <label class="auth-label">ユーザー名</label>
                <input type="text" name="name" class="auth-input" value="{{ old('name') }}" required>
                @error('name')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- メールアドレス -->
            <div class="auth-form-group">
                <label class="auth-label">メールアドレス</label>
                <input type="email" name="email" class="auth-input" value="{{ old('email') }}" required>
                @error('email')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- パスワード -->
            <div class="auth-form-group">
                <label class="auth-label">パスワード</label>
                <input type="password" name="password" class="auth-input" required>
                @error('password')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- パスワード確認 -->
            <div class="auth-form-group">
                <label class="auth-label">確認用パスワード</label>
                <input type="password" name="password_confirmation" class="auth-input" required>
                @error('password_confirmation')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-auth">登録する</button>
        </form>

        <div class="auth-link">
            <a href="/login">ログインはこちら</a>
        </div>
    </div>
@endsection

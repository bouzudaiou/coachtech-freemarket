@extends('layouts.app')

@section('title', 'ログイン - COACHTECH')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')
    <div class="auth-container">
        <h1 class="auth-title">ログイン</h1>

        <form action="/login" method="POST" novalidate>
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

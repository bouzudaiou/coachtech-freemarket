@extends('layouts.app')

@section('title', 'メール認証 - COACHTECH')

@section('content')
    <style>
        .verify-container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 60px 40px;
            border-radius: 8px;
            text-align: center;
        }

        .verify-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .verify-message {
            font-size: 14px;
            color: #666;
            margin-bottom: 30px;
            line-height: 1.8;
        }

        .btn-resend {
            padding: 12px 30px;
            background-color: #ff0000;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn-resend:hover {
            background-color: #cc0000;
        }
    </style>

    <div class="verify-container">
        <div class="verify-title">
            登録していただいたメールアドレスに<br>
            認証メールを送信しました。<br>
            メール認証を完了してください。
        </div>

        <div class="verify-message">
            認証メールが届いていない場合は、<br>
            下記より再送信してください。
        </div>

        <a href="http://localhost:8025" target="_blank" class="btn-resend" style="margin-bottom: 20px;">認証はこちらから</a>

        <form action="{{ route('verification.send') }}" method="POST">
            @csrf
            <button type="submit" class="btn-resend">認証メールを再送信</button>
        </form>
    </div>
@endsection

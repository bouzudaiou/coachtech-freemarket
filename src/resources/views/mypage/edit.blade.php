@extends('layouts.app')

@section('title', 'プロフィール編集 - COACHTECH')

@section('content')
    <style>
        .profile-form-container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 40px;
            border-radius: 8px;
        }

        .profile-form-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .profile-image-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .current-profile-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            background-color: #f0f0f0;
        }

        .profile-form-group {
            margin-bottom: 20px;
        }

        .profile-label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .profile-input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .btn-update-profile {
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

        .btn-update-profile:hover {
            background-color: #cc0000;
        }
    </style>

    <div class="profile-form-container">
        <h1 class="profile-form-title">プロフィール設定</h1>

        <form action="/mypage/profile" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- プロフィール画像プレビュー -->
            <div class="profile-image-section">
                @if($user->profile_image_path)
                    <img src="{{ Storage::url($user->profile_image_path) }}" alt="{{ $user->name }}" class="current-profile-image">
                @else
                    <img src="/images/default-profile.png" alt="プロフィール画像" class="current-profile-image">
                @endif
            </div>

            <!-- プロフィール画像 -->
            <div class="profile-form-group">
                <label class="profile-label">プロフィール画像</label>
                <input type="file" name="profile_image" class="profile-input" accept="image/jpeg,image/png">
            </div>

            <!-- ユーザー名 -->
            <div class="profile-form-group">
                <label class="profile-label">ユーザー名</label>
                <input type="text" name="name" class="profile-input" value="{{ old('name', $user->name) }}" required>
            </div>

            <!-- 郵便番号 -->
            <div class="profile-form-group">
                <label class="profile-label">郵便番号</label>
                <input type="text" name="postal_code" class="profile-input"
                       value="{{ old('postal_code', $user->postal_code) }}"
                       placeholder="123-4567" required>
            </div>

            <!-- 住所 -->
            <div class="profile-form-group">
                <label class="profile-label">住所</label>
                <input type="text" name="address" class="profile-input"
                       value="{{ old('address', $user->address) }}" required>
            </div>

            <!-- 建物名 -->
            <div class="profile-form-group">
                <label class="profile-label">建物名</label>
                <input type="text" name="building" class="profile-input"
                       value="{{ old('building', $user->building) }}">
            </div>

            <button type="submit" class="btn-update-profile">更新する</button>
        </form>
    </div>
@endsection

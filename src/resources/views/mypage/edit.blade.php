@extends('layouts.app')

@section('title', 'プロフィール設定 - COACHTECH')

@section('content')
    <style>
        .profile-form-container {
            max-width: 600px;
            margin: 60px auto;
            padding: 40px;
        }

        .profile-form-title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 40px;
        }

        .profile-image-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 40px;
        }

        .current-profile-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            background-color: #e0e0e0;
            margin-bottom: 16px;
        }

        .btn-select-image {
            padding: 8px 24px;
            border: 2px solid #ff4444;
            background: #fff;
            border-radius: 4px;
            cursor: pointer;
            color: #ff4444;
            font-size: 14px;
            font-weight: bold;
        }

        .btn-select-image:hover {
            background-color: #fff5f5;
        }

        .file-input-hidden {
            display: none;
        }

        .profile-form-group {
            margin-bottom: 24px;
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
            box-sizing: border-box;
        }

        .profile-input::placeholder {
            color: #999;
        }

        .btn-update-profile {
            width: 100%;
            padding: 16px;
            background-color: #ff4444;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 32px;
        }

        .btn-update-profile:hover {
            background-color: #dd3333;
        }
    </style>

    <div class="profile-form-container">
        <h1 class="profile-form-title">プロフィール設定</h1>

        <form action="{{ route('mypage.update') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf

            <!-- プロフィール画像プレビュー -->
            <div class="profile-image-section">
                @if($user->profile_image_path)
                    <img src="{{ Storage::url($user->profile_image_path) }}" alt="{{ $user->name }}" class="current-profile-image" id="preview-image">
                @else
                    <div class="current-profile-image" id="preview-image"></div>
                @endif

                <label for="profile-image-input" class="btn-select-image">画像を選択する</label>
                <input type="file" name="profile_image" id="profile-image-input" class="file-input-hidden" accept="image/jpeg,image/png">
            </div>

            <!-- ユーザー名 -->
            <div class="profile-form-group">
                <label class="profile-label">ユーザー名</label>
                <input type="text" name="name" class="profile-input" value="{{ old('name', $user->name) }}" placeholder="既存の値が入力されている" required>
            </div>

            <!-- 郵便番号 -->
            <div class="profile-form-group">
                <label class="profile-label">郵便番号</label>
                <input type="text" name="postal_code" class="profile-input"
                       value="{{ old('postal_code', $user->postal_code) }}"
                       placeholder="既存の値が入力されている" required>
            </div>

            <!-- 住所 -->
            <div class="profile-form-group">
                <label class="profile-label">住所</label>
                <input type="text" name="address" class="profile-input"
                       value="{{ old('address', $user->address) }}"
                       placeholder="既存の値が入力されている" required>
            </div>

            <!-- 建物名 -->
            <div class="profile-form-group">
                <label class="profile-label">建物名</label>
                <input type="text" name="building" class="profile-input"
                       value="{{ old('building', $user->building) }}"
                       placeholder="既存の値が入力されている">
            </div>

            <button type="submit" class="btn-update-profile">更新する</button>
        </form>
    </div>

    <script>
        // 画像選択時のプレビュー表示
        document.getElementById('profile-image-input').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('preview-image');
                    preview.style.backgroundImage = `url(${e.target.result})`;
                    preview.style.backgroundSize = 'cover';
                    preview.style.backgroundPosition = 'center';

                    // imgタグの場合はsrcを変更
                    if (preview.tagName === 'IMG') {
                        preview.src = e.target.result;
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection

@extends('layouts.app')

@section('title', 'マイページ - COACHTECH')

@section('content')
    <style>
        .mypage-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 40px;
        }

        .profile-image {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            background-color: #f0f0f0;
        }

        .user-name {
            font-size: 20px;
            font-weight: bold;
        }

        .btn-edit-profile {
            margin-left: auto;
            padding: 10px 20px;
            border: 1px solid #ddd;
            background: #fff;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            color: #333;
        }

        .btn-edit-profile:hover {
            background-color: #f5f5f5;
        }

        /* タブ切り替え */
        .mypage-tabs {
            display: flex;
            gap: 30px;
            margin-bottom: 30px;
            border-bottom: 2px solid #ddd;
        }

        .mypage-tab {
            padding: 10px 20px;
            cursor: pointer;
            border: none;
            background: none;
            font-size: 16px;
            color: #666;
        }

        .mypage-tab.active {
            color: #000;
            font-weight: bold;
            border-bottom: 3px solid #ff0000;
            margin-bottom: -2px;
        }

        /* 商品グリッド */
        .mypage-products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 30px;
        }

        .mypage-product-card {
            text-decoration: none;
            color: #333;
            position: relative;
        }

        .mypage-product-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 8px;
            background-color: #f0f0f0;
        }

        .mypage-product-info {
            padding: 10px 0;
        }

        .mypage-product-name {
            font-size: 14px;
            margin-bottom: 5px;
        }

        /* SOLD表示 */
        .mypage-sold-label {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: #ff0000;
            color: #fff;
            padding: 5px 15px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
    </style>

    <!-- マイページヘッダー -->
    <div class="mypage-header">
        @if($user->profile_image_path)
            <img src="{{ Storage::url($user->profile_image_path) }}" alt="{{ $user->name }}" class="profile-image">
        @else
            <img src="/images/default-profile.png" alt="{{ $user->name }}" class="profile-image">
        @endif

        <div class="user-name">{{ $user->name }}</div>

        <a href="/mypage/profile" class="btn-edit-profile">プロフィールを編集</a>
    </div>

    <!-- タブ切り替え -->
    <div class="mypage-tabs">
        <button class="mypage-tab {{ $page === 'sell' ? 'active' : '' }}" onclick="location.href='/mypage?page=sell'">
            出品した商品
        </button>
        <button class="mypage-tab {{ $page === 'buy' ? 'active' : '' }}" onclick="location.href='/mypage?page=buy'">
            購入した商品
        </button>
    </div>

    <!-- 商品一覧 -->
    <div class="mypage-products-grid">
        @forelse($products as $product)
            <a href="/item/{{ $product->id }}" class="mypage-product-card">
                @if($product->is_sold)
                    <div class="mypage-sold-label">Sold</div>
                @endif

                <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="mypage-product-image">

                <div class="mypage-product-info">
                    <div class="mypage-product-name">{{ $product->name }}</div>
                </div>
            </a>
        @empty
            <p>商品がありません</p>
        @endforelse
    </div>
@endsection

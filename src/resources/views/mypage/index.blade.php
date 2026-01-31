@extends('layouts.app')

@section('title', 'マイページ - COACHTECH')

@section('content')
    <style>
        .mypage-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .mypage-header {
            display: flex;
            align-items: center;
            gap: 32px;
            margin-bottom: 48px;
            padding-bottom: 32px;
        }

        .profile-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            background-color: #e0e0e0;
        }

        .user-name {
            font-size: 24px;
            font-weight: bold;
            flex: 1;
        }

        .btn-edit-profile {
            padding: 12px 32px;
            border: 2px solid #ff4444;
            background: #fff;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            color: #ff4444;
            font-size: 14px;
            font-weight: bold;
        }

        .btn-edit-profile:hover {
            background-color: #fff5f5;
        }

        /* タブ切り替え */
        .mypage-tabs {
            display: flex;
            gap: 48px;
            margin-bottom: 32px;
            border-bottom: 1px solid #ddd;
        }

        .mypage-tab {
            padding: 12px 0;
            cursor: pointer;
            border: none;
            background: none;
            font-size: 16px;
            color: #666;
            position: relative;
            transition: color 0.3s;
        }

        .mypage-tab.active {
            color: #ff4444;
            font-weight: bold;
        }

        .mypage-tab.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 3px;
            background-color: #ff4444;
        }

        /* 商品グリッド */
        .mypage-products-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 32px;
        }

        .mypage-product-card {
            text-decoration: none;
            color: #333;
            position: relative;
        }

        .mypage-product-image {
            width: 100%;
            aspect-ratio: 1;
            object-fit: cover;
            background-color: #f0f0f0;
            margin-bottom: 12px;
        }

        .mypage-product-info {
            padding: 0;
        }

        .mypage-product-name {
            font-size: 14px;
            line-height: 1.4;
        }

        /* SOLD表示 */
        .mypage-sold-label {
            position: absolute;
            top: 8px;
            left: 8px;
            background-color: #ff4444;
            color: #fff;
            padding: 6px 16px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }

        .no-products {
            text-align: center;
            padding: 60px 0;
            color: #999;
            font-size: 16px;
        }

        @media (max-width: 1024px) {
            .mypage-products-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .mypage-products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }

            .mypage-header {
                flex-direction: column;
                text-align: center;
            }

            .user-name {
                text-align: center;
            }
        }
    </style>

    <div class="mypage-container">
        <!-- マイページヘッダー -->
        <div class="mypage-header">
            @if($user->profile_image_path)
                <img src="{{ Storage::url($user->profile_image_path) }}" alt="{{ $user->name }}" class="profile-image">
            @else
                <div class="profile-image"></div>
            @endif

            <div class="user-name">{{ $user->name }}</div>

            <a href="{{ route('mypage.edit') }}" class="btn-edit-profile">プロフィールを編集</a>
        </div>

        <!-- タブ切り替え -->
        <div class="mypage-tabs">
            <button class="mypage-tab {{ $page === 'sell' ? 'active' : '' }}" onclick="location.href='{{ route('mypage', ['page' => 'sell']) }}'">
                出品した商品
            </button>
            <button class="mypage-tab {{ $page === 'buy' ? 'active' : '' }}" onclick="location.href='{{ route('mypage', ['page' => 'buy']) }}'">
                購入した商品
            </button>
        </div>

        <!-- 商品一覧 -->
        <div class="mypage-products-grid">
            @forelse($products as $product)
                <a href="{{ route('item.show', $product->id) }}" class="mypage-product-card">
                    @if($product->is_sold)
                        <div class="mypage-sold-label">Sold</div>
                    @endif

                    <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="mypage-product-image">

                    <div class="mypage-product-info">
                        <div class="mypage-product-name">{{ $product->name }}</div>
                    </div>
                </a>
            @empty
                <div class="no-products" style="grid-column: 1 / -1;">商品がありません</div>
            @endforelse
        </div>
    </div>
@endsection

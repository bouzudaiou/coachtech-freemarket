@extends('layouts.app')

@section('title', 'COACHTECH')

@section('content')
    <style>
        .products-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        /* タブナビゲーション */
        .products-tabs {
            display: flex;
            gap: 32px;
            margin-bottom: 32px;
            border-bottom: 1px solid #ddd;
        }

        .products-tab {
            padding: 12px 0;
            cursor: pointer;
            border: none;
            background: none;
            font-size: 16px;
            color: #666;
            position: relative;
            text-decoration: none;
        }

        .products-tab.active {
            color: #000;
            font-weight: bold;
        }

        .products-tab.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 3px;
            background-color: #ff4444;
        }

        /* 商品グリッド */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 32px;
        }

        .product-card {
            text-decoration: none;
            color: #333;
        }

        .product-card:hover {
            opacity: 0.8;
        }

        .product-image-wrapper {
            position: relative;
            width: 100%;
            aspect-ratio: 1;
            margin-bottom: 12px;
        }

        /* Soldラベル */
        .sold-label {
            position: absolute;
            top: 8px;
            left: 8px;
            background-color: #ff4444;
            color: #fff;
            padding: 6px 16px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            z-index: 1;
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            background-color: #f0f0f0;
        }

        .product-name {
            font-size: 14px;
            line-height: 1.4;
        }

        .no-products {
            text-align: center;
            padding: 60px 0;
            color: #999;
            font-size: 16px;
            grid-column: 1 / -1;
        }

        @media (max-width: 1024px) {
            .products-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }
        }
    </style>

    <div class="products-container">
        <!-- タブナビゲーション -->
        <div class="products-tabs">
            @auth
                <a href="{{ route('products.index', ['tab' => 'recommend', 'keyword' => $keyword]) }}"
                   class="products-tab {{ $tab === 'recommend' ? 'active' : '' }}">
                    おすすめ
                </a>
                <a href="{{ route('products.index', ['tab' => 'mylist', 'keyword' => $keyword]) }}"
                   class="products-tab {{ $tab === 'mylist' ? 'active' : '' }}">
                    マイリスト
                </a>
            @else
                <a href="{{ route('products.index', ['tab' => 'recommend']) }}"
                   class="products-tab active">
                    おすすめ
                </a>
            @endauth
        </div>

        <!-- 商品グリッド -->
        <div class="products-grid">
            @forelse($products as $product)
                <a href="{{ route('item.show', $product->id) }}" class="product-card">
                    <div class="product-image-wrapper">
                        <!-- Soldラベル -->
                        @if($product->is_sold)
                            <div class="sold-label">Sold</div>
                        @endif

                        <img src="{{ Storage::url($product->image_path) }}"
                             alt="{{ $product->name }}"
                             class="product-image">
                    </div>
                    <div class="product-name">{{ $product->name }}</div>
                </a>
            @empty
                <div class="no-products">商品がありません</div>
            @endforelse
        </div>
    </div>
@endsection

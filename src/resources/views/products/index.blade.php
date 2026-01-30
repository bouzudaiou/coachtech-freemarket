@extends('layouts.app')

@section('title', '商品一覧 - COACHTECH')

@section('content')
    <style>
        /* タブ切り替え */
        .tabs {
            display: flex;
            gap: 40px;
            margin-bottom: 40px;
            border-bottom: 1px solid #ddd;
        }

        .tab {
            padding: 10px 0;
            cursor: pointer;
            border: none;
            background: none;
            font-size: 16px;
            color: #888;
            position: relative;
            text-decoration: none;
            display: inline-block;
        }

        .tab.active {
            color: #ff0000;
            font-weight: bold;
        }

        .tab.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #ff0000;
        }

        .tab:not(.active):hover {
            color: #333;
        }

        /* 商品グリッド */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 40px 30px;
        }

        .product-card {
            text-decoration: none;
            color: #333;
            position: relative;
            display: block;
        }

        .product-image-wrapper {
            position: relative;
            width: 100%;
            padding-bottom: 100%; /* 正方形 */
            background-color: #d3d3d3;
            border-radius: 4px;
            overflow: hidden;
        }

        .product-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-info {
            padding: 10px 0;
        }

        .product-name {
            font-size: 14px;
            color: #000;
        }

        /* SOLD表示 */
        .sold-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
        }

        .sold-label {
            background-color: #ff0000;
            color: #fff;
            padding: 8px 30px;
            border-radius: 4px;
            font-size: 18px;
            font-weight: bold;
        }

        /* 商品がない場合 */
        .no-products {
            text-align: center;
            padding: 80px 20px;
            color: #888;
            font-size: 16px;
        }
    </style>

    <!-- タブ切り替え -->
    <div class="tabs">
        <a href="/?page=all" class="tab {{ (!isset($page) || $page === 'all') ? 'active' : '' }}">
            おすすめ
        </a>
        @auth
            <a href="/?page=mylist" class="tab {{ (isset($page) && $page === 'mylist') ? 'active' : '' }}">
                マイリスト
            </a>
        @endauth
    </div>

    <!-- 商品一覧 -->
    @if($products->count() > 0)
        <div class="products-grid">
            @foreach($products as $product)
                <a href="/item/{{ $product->id }}" class="product-card">
                    <div class="product-image-wrapper">
                        <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="product-image">

                        @if($product->is_sold)
                            <div class="sold-overlay">
                                <div class="sold-label">Sold</div>
                            </div>
                        @endif
                    </div>

                    <div class="product-info">
                        <div class="product-name">{{ $product->name }}</div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="no-products">
            商品がありません
        </div>
    @endif
@endsection

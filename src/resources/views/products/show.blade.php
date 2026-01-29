
@extends('layouts.app')

@section('title', $product->name . ' - COACHTECH')

@section('content')
    <style>
        .product-detail {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .product-image-large {
            width: 100%;
            height: 500px;
            object-fit: cover;
            border-radius: 8px;
            background-color: #f0f0f0;
        }

        .product-info-detail {
            padding: 20px 0;
        }

        .product-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .product-brand {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }

        .product-price {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .product-actions {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .action-btn {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 8px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: #fff;
            cursor: pointer;
            text-decoration: none;
            color: #333;
        }

        .action-icon {
            width: 20px;
            height: 20px;
        }

        .info-section {
            margin-bottom: 20px;
        }

        .info-label {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .info-value {
            color: #666;
            line-height: 1.6;
        }

        .category-tags {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .category-tag {
            padding: 5px 15px;
            background-color: #f0f0f0;
            border-radius: 20px;
            font-size: 14px;
        }

        .btn-purchase {
            width: 100%;
            padding: 15px;
            background-color: #ff0000;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .btn-purchase:hover {
            background-color: #cc0000;
        }

        /* コメントセクション */
        .comments-section {
            margin-top: 60px;
        }

        .comment-item {
            padding: 20px;
            border-bottom: 1px solid #ddd;
        }

        .comment-user {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .comment-text {
            color: #666;
            line-height: 1.6;
        }

        .comment-form {
            margin-top: 30px;
        }

        .comment-textarea {
            width: 100%;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: vertical;
            min-height: 100px;
            margin-bottom: 15px;
        }

        .btn-submit {
            padding: 10px 30px;
            background-color: #ff0000;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>

    <div class="product-detail">
        <!-- 商品画像 -->
        <div>
            <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="product-image-large">
        </div>

        <!-- 商品情報 -->
        <div class="product-info-detail">
            <h1 class="product-title">{{ $product->name }}</h1>
            <p class="product-brand">{{ $product->brand }}</p>
            <p class="product-price">¥{{ number_format($product->price) }}</p>

            <!-- いいね・コメント -->
            <div class="product-actions">
                <form action="/item/{{ $product->id }}/like" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="action-btn">
                        @if($product->likedUsers->contains(auth()->id()))
                            <img src="/images/ハートロゴ_ピンク.png" alt="いいね済み" class="action-icon">
                        @else
                            <img src="/images/ハートロゴ_デフォルト.png" alt="いいね" class="action-icon">
                        @endif
                        <span>{{ $product->likedUsers->count() }}</span>
                    </button>
                </form>

                <div class="action-btn">
                    <img src="/images/ふきた_しロゴ.png" alt="コメント" class="action-icon">
                    <span>{{ $product->comments->count() }}</span>
                </div>
            </div>

            <!-- 商品説明 -->
            <div class="info-section">
                <div class="info-label">商品説明</div>
                <div class="info-value">{{ $product->description }}</div>
            </div>

            <!-- 商品情報 -->
            <div class="info-section">
                <div class="info-label">商品の情報</div>
                <div class="info-value">
                    <p><strong>カテゴリー:</strong></p>
                    <div class="category-tags">
                        @foreach($product->categories as $category)
                            <span class="category-tag">{{ $category->name }}</span>
                        @endforeach
                    </div>
                    <p style="margin-top: 15px;"><strong>商品の状態:</strong> {{ $product->condition }}</p>
                </div>
            </div>

            <!-- 購入ボタン -->
            @if(!$product->is_sold)
                <a href="/purchase/{{ $product->id }}" class="btn-purchase">購入手続きへ</a>
            @else
                <button class="btn-purchase" style="background-color: #999; cursor: not-allowed;" disabled>売り切れ</button>
            @endif
        </div>
    </div>

    <!-- コメント -->
    <div class="comments-section">
        <h2>コメント ({{ $product->comments->count() }})</h2>

        @foreach($product->comments as $comment)
            <div class="comment-item">
                <div class="comment-user">{{ $comment->user->name }}</div>
                <div class="comment-text">{{ $comment->content }}</div>
            </div>
        @endforeach

        @auth
            <form action="/item/{{ $product->id }}/comment" method="POST" class="comment-form">
                @csrf
                <textarea name="content" class="comment-textarea" placeholder="コメントを入力してください" required></textarea>
                <button type="submit" class="btn-submit">コメントを送信する</button>
            </form>
        @endauth
    </div>
@endsection

@extends('layouts.app')

@section('title', $product->name . ' - COACHTECH')

@section('content')
    <style>
        .product-detail-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .product-detail {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            margin-bottom: 60px;
        }

        .product-image-large {
            width: 100%;
            aspect-ratio: 1;
            object-fit: cover;
            background-color: #f0f0f0;
        }

        .product-info-detail {
            display: flex;
            flex-direction: column;
        }

        .product-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .product-brand {
            font-size: 14px;
            color: #666;
            margin-bottom: 24px;
        }

        .product-price {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 24px;
        }

        .product-actions {
            display: flex;
            gap: 24px;
            margin-bottom: 32px;
        }

        .action-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            font-size: 16px;
        }

        .action-icon {
            width: 32px;
            height: 32px;
        }

        .btn-purchase {
            width: 100%;
            padding: 16px;
            background-color: #ff4444;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: block;
            text-align: center;
            margin-bottom: 32px;
        }

        .btn-purchase:hover {
            background-color: #dd3333;
        }

        .btn-purchase:disabled {
            background-color: #999;
            cursor: not-allowed;
        }

        .info-section {
            margin-bottom: 32px;
        }

        .info-section-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 16px;
        }

        .info-content {
            color: #333;
            line-height: 1.8;
        }

        .category-tags {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }

        .category-tag {
            padding: 6px 16px;
            background-color: #e0e0e0;
            border-radius: 20px;
            font-size: 14px;
            color: #333;
        }

        .condition-text {
            font-size: 14px;
            color: #333;
        }

        /* コメントセクション */
        .comments-section {
            margin-top: 60px;
        }

        .comments-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 24px;
        }

        .comment-item {
            display: flex;
            gap: 16px;
            margin-bottom: 24px;
        }

        .comment-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background-color: #ddd;
            flex-shrink: 0;
        }

        .comment-content {
            flex: 1;
        }

        .comment-user {
            font-weight: bold;
            margin-bottom: 8px;
        }

        .comment-text {
            background-color: #f5f5f5;
            padding: 16px;
            border-radius: 4px;
            color: #333;
            line-height: 1.6;
        }

        .comment-form {
            margin-top: 32px;
        }

        .comment-form-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 16px;
        }

        .comment-textarea {
            width: 100%;
            padding: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: vertical;
            min-height: 120px;
            margin-bottom: 16px;
            font-size: 14px;
        }

        .btn-submit-comment {
            width: 100%;
            padding: 16px;
            background-color: #ff4444;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-submit-comment:hover {
            background-color: #dd3333;
        }

        @media (max-width: 768px) {
            .product-detail {
                grid-template-columns: 1fr;
                gap: 32px;
            }
        }

        .error-message {
            color: #ff4444;
            font-size: 12px;
            margin-top: 8px;
        }
    </style>

    <div class="product-detail-container">
        <div class="product-detail">
            <!-- 商品画像 -->
            <div>
                <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}"
                     class="product-image-large">
            </div>

            <!-- 商品情報 -->
            <div class="product-info-detail">
                <h1 class="product-name">{{ $product->name }}</h1>
                <p class="product-brand">{{ $product->brand }}</p>
                <p class="product-price">¥{{ number_format($product->price) }} (税込)</p>

                <!-- いいね・コメント -->
                <div class="product-actions">
                    @auth
                        <form action="{{ route('item.like', $product->id) }}" method="POST" style="display: inline;"
                              novalidate>
                            @csrf
                            <button type="submit" class="action-btn">
                                @if($product->likedUsers->contains(auth()->id()))
                                    <svg class="action-icon" viewBox="0 0 24 24" fill="#ff4444">
                                        <path
                                            d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                    </svg>
                                @else
                                    <svg class="action-icon" viewBox="0 0 24 24" fill="none" stroke="#666"
                                         stroke-width="2">
                                        <path
                                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                    </svg>
                                @endif
                                <span>{{ $product->likedUsers->count() }}</span>
                            </button>
                        </form>
                    @else
                        <div class="action-btn">
                            <svg class="action-icon" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="2">
                                <path
                                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                            </svg>
                            <span>{{ $product->likedUsers->count() }}</span>
                        </div>
                    @endauth

                    <div class="action-btn">
                        <svg class="action-icon" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="2">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                        <span>{{ $product->comments->count() }}</span>
                    </div>
                </div>

                <!-- 購入ボタン -->
                @if(!$product->is_sold)
                    <a href="{{ route('purchase.show', $product->id) }}" class="btn-purchase">購入手続きへ</a>
                @else
                    <button class="btn-purchase" disabled>売り切れ</button>
                @endif

                <!-- 商品説明 -->
                <div class="info-section">
                    <div class="info-section-title">商品説明</div>
                    <div class="info-content">{{ $product->description }}</div>
                </div>

                <!-- 商品の情報 -->
                <div class="info-section">
                    <div class="info-section-title">商品の情報</div>
                    <div class="info-content">
                        <div style="margin-bottom: 8px; font-weight: bold;">カテゴリー</div>
                        <div class="category-tags">
                            @foreach($product->categories as $category)
                                <span class="category-tag">{{ $category->name }}</span>
                            @endforeach
                        </div>
                        <div class="condition-text">
                            <span style="font-weight: bold;">商品の状態</span>
                            <span style="margin-left: 32px;">{{ $product->condition }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- コメント -->
        <div class="comments-section">
            <h2 class="comments-title">コメント ({{ $product->comments->count() }})</h2>

            @foreach($product->comments as $comment)
                <div class="comment-item">
                    <div class="comment-avatar">
                        @if($comment->user->profile_image_path)
                            <img src="{{ asset('storage/' . $comment->user->profile_image_path) }}"
                                 alt="プロフィール画像">
                        @endif
                    </div>
                    <div class="comment-content">
                        <div class="comment-user">{{ $comment->user->name }}</div>
                        <div class="comment-text">{{ $comment->content }}</div>
                    </div>
                </div>
            @endforeach

            <!-- コメント投稿フォーム -->
            <div class="comment-form">
                <div class="comment-form-title">商品へのコメント</div>
                @auth
                    <form action="{{ route('item.comment', $product->id) }}" method="POST" novalidate>
                        @csrf
                        <textarea name="content" class="comment-textarea" placeholder="コメントを入力してください"
                                  required></textarea>
                        @error('content')
                        <p class="error-message">{{ $message }}</p>
                        @enderror
                        <button type="submit" class="btn-submit-comment">コメントを送信する</button>
                    </form>
                @else
                    <textarea class="comment-textarea" placeholder="コメントを入力してください" disabled></textarea>
                    <button class="btn-submit-comment" disabled>コメントを送信する</button>
                @endguest
            </div>
        </div>
    </div>
@endsection

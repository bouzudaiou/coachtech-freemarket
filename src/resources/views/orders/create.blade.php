@extends('layouts.app')

@section('title', '商品購入 - COACHTECH')

@section('content')
    <style>
        .purchase-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }

        .purchase-product {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .purchase-product-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
        }

        .purchase-product-info h2 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .purchase-product-price {
            font-size: 24px;
            font-weight: bold;
            color: #ff0000;
        }

        .purchase-section {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .payment-method {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .payment-option {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .address-info {
            line-height: 1.8;
        }

        .change-address-link {
            display: inline-block;
            margin-top: 15px;
            color: #ff0000;
            text-decoration: none;
            font-size: 14px;
        }

        .price-summary {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        .total-price {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            font-size: 20px;
            font-weight: bold;
        }

        .btn-purchase-submit {
            width: 100%;
            padding: 15px;
            background-color: #ff0000;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
        }

        .btn-purchase-submit:hover {
            background-color: #cc0000;
        }
    </style>

    <div class="purchase-container">
        <!-- 左側：商品情報 -->
        <div>
            <div class="purchase-product">
                <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="purchase-product-image">
                <div class="purchase-product-info">
                    <h2>{{ $product->name }}</h2>
                    <div class="purchase-product-price">¥{{ number_format($product->price) }}</div>
                </div>
            </div>

            <!-- 支払い方法 -->
            <div class="purchase-section">
                <h3 class="section-title">支払い方法</h3>
                <form action="/purchase/{{ $product->id }}" method="POST" id="purchase-form">
                    @csrf
                    <div class="payment-method">
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="コンビニ払い" required>
                            <span>コンビニ払い</span>
                        </label>
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="カード払い" required>
                            <span>カード払い</span>
                        </label>
                    </div>
                </form>
            </div>

            <!-- 配送先 -->
            <div class="purchase-section">
                <h3 class="section-title">配送先</h3>
                <div class="address-info">
                    @if(is_array($address))
                        <p>〒{{ $address['postal_code'] ?? '' }}</p>
                        <p>{{ $address['address'] ?? '' }}</p>
                        @if(!empty($address['building']))
                            <p>{{ $address['building'] }}</p>
                        @endif
                    @else
                        <p>〒{{ $user->postal_code }}</p>
                        <p>{{ $user->address }}</p>
                        @if($user->building)
                            <p>{{ $user->building }}</p>
                        @endif
                    @endif
                </div>
                <a href="/purchase/address/{{ $product->id }}" class="change-address-link">変更する</a>
            </div>
        </div>

        <!-- 右側：購入確認 -->
        <div>
            <div class="purchase-section">
                <div class="price-summary">
                    <span>商品代金</span>
                    <span>¥{{ number_format($product->price) }}</span>
                </div>
                <div class="total-price">
                    <span>支払い金額</span>
                    <span>¥{{ number_format($product->price) }}</span>
                </div>

                <button type="submit" form="purchase-form" class="btn-purchase-submit">購入する</button>
            </div>
        </div>
    </div>
@endsection

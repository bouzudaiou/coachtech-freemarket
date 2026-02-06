@extends('layouts.app')

@section('title', '商品購入 - COACHTECH')

@section('content')
    <style>
        .purchase-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 60px;
        }

        /* 左側：商品情報・支払い・配送先 */
        .purchase-left {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .purchase-product {
            display: flex;
            gap: 20px;
            padding-bottom: 24px;
            border-bottom: 1px solid #ddd;
        }

        .purchase-product-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
            background-color: #f0f0f0;
        }

        .purchase-product-info {
            flex: 1;
        }

        .purchase-product-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .purchase-product-price {
            font-size: 18px;
            font-weight: normal;
        }

        .purchase-section {
            padding-bottom: 24px;
            border-bottom: 1px solid #ddd;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 16px;
        }

        .form-select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            background-color: #fff;
        }

        .address-info {
            line-height: 1.8;
            color: #333;
        }

        .change-address-link {
            display: inline-block;
            margin-top: 12px;
            color: #4a9eff;
            text-decoration: none;
            font-size: 14px;
        }

        .change-address-link:hover {
            text-decoration: underline;
        }

        /* 右側：購入確認 */
        .purchase-right {
            position: sticky;
            top: 20px;
            height: fit-content;
        }

        .purchase-summary {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 24px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
        }

        .summary-label {
            font-size: 14px;
            color: #666;
        }

        .summary-value {
            font-size: 14px;
            font-weight: bold;
        }

        .btn-purchase-submit {
            width: 100%;
            padding: 16px;
            background-color: #ff4444;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 16px;
        }

        .btn-purchase-submit:hover {
            background-color: #dd3333;
        }

        @media (max-width: 768px) {
            .purchase-container {
                grid-template-columns: 1fr;
                gap: 32px;
            }

            .purchase-right {
                position: static;
            }
        }
    </style>

    <form action="{{ route('purchase.process', $product->id) }}" method="POST" id="purchase-form" novalidate>
        @csrf
        <div class="purchase-container">
            <!-- 左側：商品情報・支払い・配送先 -->
            <div class="purchase-left">
                <!-- 商品情報 -->
                <div class="purchase-product">
                    <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="purchase-product-image">
                    <div class="purchase-product-info">
                        <div class="purchase-product-name">{{ $product->name }}</div>
                        <div class="purchase-product-price">¥{{ number_format($product->price) }}</div>
                    </div>
                </div>

                <!-- 支払い方法 -->
                <div class="purchase-section">
                    <h3 class="section-title">支払い方法</h3>
                    <select name="payment_method" class="form-select" required>
                        <option value="">選択してください</option>
                        <option value="コンビニ払い" {{ old('payment_method') === 'コンビニ払い' ? 'selected' : '' }}>コンビニ払い</option>
                        <option value="カード払い" {{ old('payment_method') === 'カード払い' ? 'selected' : '' }}>カード払い</option>
                    </select>
                </div>

                <!-- 配送先 -->
                <div class="purchase-section">
                    <h3 class="section-title">配送先</h3>
                    <div class="address-info">
                        @if(isset($address) && is_array($address))
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
                    <a href="{{ route('purchase.address', $product->id) }}" class="change-address-link">変更する</a>
                </div>
            </div>

            <!-- 右側：購入確認 -->
            <div class="purchase-right">
                <div class="purchase-summary">
                    <div class="summary-row">
                        <span class="summary-label">商品代金</span>
                        <span class="summary-value">¥{{ number_format($product->price) }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">支払い方法</span>
                        <span class="summary-value" id="selected-payment">選択してください</span>
                    </div>
                    <button type="submit" class="btn-purchase-submit">購入する</button>
                </div>
            </div>
        </div>
    </form>

    <script>
        // 支払い方法の選択を右側にも反映
        document.querySelector('select[name="payment_method"]').addEventListener('change', function() {
            const selectedText = this.options[this.selectedIndex].text;
            document.getElementById('selected-payment').textContent = selectedText || '-';
        });
    </script>
@endsection

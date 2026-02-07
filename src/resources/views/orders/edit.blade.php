@extends('layouts.app')

@section('title', '配送先変更 - COACHTECH')

@section('content')
    <style>
        .address-form-container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 40px;
            border-radius: 8px;
        }

        .address-form-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .address-form-group {
            margin-bottom: 20px;
        }

        .address-label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .address-input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .btn-update {
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

        .btn-update:hover {
            background-color: #cc0000;
        }

        .error-message {
            color: #ff4444;
            font-size: 12px;
            margin-top: 8px;
        }
    </style>

    <div class="address-form-container">
        <h1 class="address-form-title">配送先の変更</h1>

        <form action="/purchase/address/{{ $product->id }}" method="POST" novalidate>
            @csrf

            <!-- 郵便番号 -->
            <div class="address-form-group">
                <label class="address-label">郵便番号</label>
                <input type="text" name="postal_code" class="address-input"
                       value="{{ old('postal_code', is_array($address) ? ($address['postal_code'] ?? '') : $user->postal_code) }}"
                       placeholder="123-4567" required>
                @error('postal_code')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- 住所 -->
            <div class="address-form-group">
                <label class="address-label">住所</label>
                <input type="text" name="address" class="address-input"
                       value="{{ old('address', is_array($address) ? ($address['address'] ?? '') : $user->address) }}"
                       placeholder="東京都渋谷区千駄ヶ谷1-2-3"
                       required>
                @error('address')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- 建物名 -->
            <div class="address-form-group">
                <label class="address-label">建物名</label>
                <input type="text" name="building" class="address-input"
                       value="{{ old('building', is_array($address) ? ($address['building'] ?? '') : $user->building) }}"
                placeholder="代々木マンション101">
                @error('building')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-update">更新する</button>
        </form>
    </div>
@endsection

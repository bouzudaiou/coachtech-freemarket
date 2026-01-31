@extends('layouts.app')

@section('title', '商品の出品 - COACHTECH')

@section('content')
    <style>
        .product-form-container {
            max-width: 600px;
            margin: 60px auto;
            padding: 40px;
        }

        .product-form-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 40px;
        }

        /* 商品画像セクション */
        .image-upload-section {
            margin-bottom: 40px;
        }

        .section-label {
            font-weight: bold;
            margin-bottom: 16px;
            font-size: 14px;
        }

        .image-upload-area {
            width: 100%;
            height: 300px;
            border: 1px solid #ddd;
            border-radius: 4px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #fafafa;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .image-preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }

        .btn-select-image-area {
            padding: 10px 32px;
            border: 2px solid #ff4444;
            background: #fff;
            border-radius: 4px;
            color: #ff4444;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
        }

        .image-upload-input {
            display: none;
        }

        /* 商品の詳細セクション */
        .product-details-section {
            margin-bottom: 40px;
            padding-bottom: 32px;
            border-bottom: 1px solid #ddd;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-weight: bold;
            margin-bottom: 12px;
            font-size: 14px;
        }

        /* カテゴリータグ */
        .category-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .category-tag {
            padding: 8px 20px;
            border: 1px solid #ddd;
            border-radius: 20px;
            font-size: 14px;
            cursor: pointer;
            background-color: #fff;
            transition: all 0.3s;
        }

        .category-tag input {
            display: none;
        }

        .category-tag.selected {
            background-color: #ff4444;
            color: #fff;
            border-color: #ff4444;
        }

        /* フォーム要素 */
        .form-select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            background-color: #fff;
            cursor: pointer;
        }

        /* 商品名と説明セクション */
        .product-info-section {
            margin-bottom: 40px;
            padding-bottom: 32px;
            border-bottom: 1px solid #ddd;
        }

        .form-input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .form-textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: vertical;
            min-height: 150px;
            font-size: 14px;
            box-sizing: border-box;
        }

        /* 販売価格 */
        .price-section {
            margin-bottom: 40px;
        }

        .price-input-wrapper {
            display: flex;
            align-items: center;
        }

        .price-symbol {
            font-size: 18px;
            font-weight: bold;
            margin-right: 8px;
        }

        /* 出品ボタン */
        .btn-submit-product {
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

        .btn-submit-product:hover {
            background-color: #dd3333;
        }
    </style>

    <div class="product-form-container">
        <h1 class="product-form-title">商品の出品</h1>

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf

            <!-- 商品画像 -->
            <div class="image-upload-section">
                <div class="section-label">商品画像</div>
                <div class="image-upload-area">
                    <img id="image-preview" class="image-preview" alt="商品画像プレビュー">
                    <input type="file" name="image_path" id="image-input" class="image-upload-input" accept="image/jpeg,image/png" required>
                    <label for="image-input" id="select-image-btn" class="btn-select-image-area">
                        画像を選択する
                    </label>
                </div>
                @error('image_path')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- 商品の詳細 -->
            <div class="product-details-section">
                <h2 class="section-title">商品の詳細</h2>

                <!-- カテゴリー -->
                <div class="form-group">
                    <label class="form-label">カテゴリー</label>
                    <div class="category-tags">
                        @foreach($categories as $category)
                            <label class="category-tag">
                                <input type="checkbox" name="category_id[]" value="{{ $category->id }}"
                                    {{ in_array($category->id, old('category_id', [])) ? 'checked' : '' }}>
                                <span>{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- 商品の状態 -->
                <div class="form-group">
                    <label class="form-label">商品の状態</label>
                    <select name="condition" class="form-select" required>
                        <option value="">選択してください</option>
                        <option value="良好" {{ old('condition') === '良好' ? 'selected' : '' }}>良好</option>
                        <option value="目立った傷や汚れなし" {{ old('condition') === '目立った傷や汚れなし' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                        <option value="やや傷や汚れあり" {{ old('condition') === 'やや傷や汚れあり' ? 'selected' : '' }}>やや傷や汚れあり</option>
                        <option value="状態が悪い" {{ old('condition') === '状態が悪い' ? 'selected' : '' }}>状態が悪い</option>
                    </select>
                </div>
            </div>

            <!-- 商品名と説明 -->
            <div class="product-info-section">
                <h2 class="section-title">商品名と説明</h2>

                <!-- 商品名 -->
                <div class="form-group">
                    <label class="form-label">商品名</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}" required>
                </div>

                <!-- ブランド名 -->
                <div class="form-group">
                    <label class="form-label">ブランド名</label>
                    <input type="text" name="brand" class="form-input" value="{{ old('brand') }}">
                </div>

                <!-- 商品の説明 -->
                <div class="form-group">
                    <label class="form-label">商品の説明</label>
                    <textarea name="description" class="form-textarea" required>{{ old('description') }}</textarea>
                </div>
            </div>

            <!-- 販売価格 -->
            <div class="price-section">
                <h2 class="section-title">販売価格</h2>
                <div class="form-group">
                    <label class="form-label">販売価格</label>
                    <div class="price-input-wrapper">
                        <span class="price-symbol">¥</span>
                        <input type="number" name="price" class="form-input" value="{{ old('price') }}" min="0" required>
                    </div>
                </div>
            </div>

            <!-- 出品ボタン -->
            <button type="submit" class="btn-submit-product">出品する</button>
        </form>
    </div>

    <script>
        // 画像選択とプレビュー
        const imageInput = document.getElementById('image-input');
        const imagePreview = document.getElementById('image-preview');
        const selectBtn = document.getElementById('select-image-btn');
        const uploadArea = document.querySelector('.image-upload-area');

        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                    selectBtn.style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        });

        // カテゴリータグの選択状態管理
        document.querySelectorAll('.category-tag').forEach(tag => {
            const checkbox = tag.querySelector('input[type="checkbox"]');

            // 初期状態の反映
            if (checkbox.checked) {
                tag.classList.add('selected');
            }

            // checkboxの状態変化を監視（labelのクリックは標準機能に任せる）
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    tag.classList.add('selected');
                } else {
                    tag.classList.remove('selected');
                }
            });
        });
    </script>
@endsection

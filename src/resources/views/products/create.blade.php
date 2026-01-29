
@extends('layouts.app')

@section('title', '商品出品 - COACHTECH')

@section('content')
    <style>
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 40px;
            border-radius: 8px;
        }

        .form-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .form-input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: vertical;
            min-height: 120px;
            font-size: 14px;
        }

        .form-select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .checkbox-group {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .file-input-wrapper {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .file-input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-submit-form {
            width: 100%;
            padding: 15px;
            background-color: #ff0000;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-submit-form:hover {
            background-color: #cc0000;
        }
    </style>

    <div class="form-container">
        <h1 class="form-title">商品の出品</h1>

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf

            <!-- 商品画像 -->
            <div class="form-group">
                <label class="form-label">商品画像</label>
                <input type="file" name="image_path" class="file-input" accept="image/jpeg,image/png" required>
            </div>

            <!-- 商品名 -->
            <div class="form-group">
                <label class="form-label">商品名</label>
                <input type="text" name="name" class="form-input" value="{{ old('name') }}" required>
            </div>

            <!-- ブランド -->
            <div class="form-group">
                <label class="form-label">ブランド</label>
                <input type="text" name="brand" class="form-input" value="{{ old('brand') }}">
            </div>

            <!-- 商品の説明 -->
            <div class="form-group">
                <label class="form-label">商品の説明</label>
                <textarea name="description" class="form-textarea" required>{{ old('description') }}</textarea>
            </div>

            <!-- カテゴリー -->
            <div class="form-group">
                <label class="form-label">カテゴリー（複数選択可）</label>
                <div class="checkbox-group">
                    @foreach($categories as $category)
                        <label class="checkbox-label">
                            <input type="checkbox" name="category_id[]" value="{{ $category->id }}"
                                {{ in_array($category->id, old('category_id', [])) ? 'checked' : '' }}>
                            {{ $category->name }}
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

            <!-- 販売価格 -->
            <div class="form-group">
                <label class="form-label">販売価格</label>
                <input type="number" name="price" class="form-input" value="{{ old('price') }}" min="0" required>
            </div>

            <!-- 出品ボタン -->
            <button type="submit" class="btn-submit-form">出品する</button>
        </form>
    </div>
@endsection

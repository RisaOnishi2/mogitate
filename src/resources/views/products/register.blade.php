@extends('layouts.app')

@section('content')
<div class="container py-5">

    <h2 class="mb-4">商品登録</h2>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- 商品名 --}}
        <div class="mb-3">
            <label class="form-label">商品名 <span class="text-danger">必須</span></label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="商品名を入力">
            @error('name')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        {{-- 値段 --}}
        <div class="mb-3">
            <label class="form-label">値段 <span class="text-danger">必須</span></label>
            <input type="number" name="price" class="form-control" value="{{ old('price') }}" placeholder="値段を入力">
            @error('price')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        {{-- 商品画像 --}}
        <div class="mb-3">
            <label class="form-label">商品画像 <span class="text-danger">必須</span></label>
            <input type="file" name="image" class="form-control">
            @error('image')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        {{-- 季節 --}}
        <div class="mb-3">
            <label class="form-label">季節 <span class="text-danger">必須</span> <small class="text-muted">複数選択可</small></label>
            <div>
                @foreach (['春', '夏', '秋', '冬'] as $season)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="seasons[]" value="{{ $season }}"
                               {{ is_array(old('seasons')) && in_array($season, old('seasons')) ? 'checked' : '' }}>
                        <label class="form-check-label">{{ $season }}</label>
                    </div>
                @endforeach
            </div>
            @error('seasons')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        {{-- 商品説明 --}}
        <div class="mb-4">
            <label class="form-label">商品説明 <span class="text-danger">必須</span></label>
            <textarea name="description" class="form-control" rows="4" placeholder="商品の説明を入力">{{ old('description') }}</textarea>
            @error('description')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        {{-- ボタン --}}
        <div class="d-flex justify-content-between">
            <a href="{{ route('products.index') }}" class="btn btn-secondary">戻る</a>
            <button type="submit" class="btn btn-warning">登録</button>
        </div>

    </form>
</div>
@endsection

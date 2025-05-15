@extends('layouts.app')

@section('content')
<div class="container">

    {{-- 戻るリンク --}}
    <a href="{{ route('products.search') }}">商品一覧へ</a> > {{ $product->name }}

    {{-- 成功メッセージ --}}
    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    {{-- 商品画像 --}}
    <div style="text-align: center; margin: 20px 0;">
        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" style="width: 300px; height: auto; border-radius: 8px;">
    </div>

    {{-- 更新フォーム --}}
    <form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
        @csrf

        {{-- 画像変更 --}}
        <div>
            <label>画像変更:</label>
            <input type="file" name="image">
            @error('image') <p style="color: red;">{{ $message }}</p> @enderror
        </div>

        {{-- 商品名 --}}
        <div>
            <label>商品名</label>
            <input type="text" name="name" value="{{ old('name', $product->name) }}">
            @error('name') <p style="color: red;">{{ $message }}</p> @enderror
        </div>

        {{-- 値段 --}}
        <div>
            <label>値段</label>
            <input type="number" name="price" value="{{ old('price', $product->price) }}">
            @error('price') <p style="color: red;">{{ $message }}</p> @enderror
        </div>

        {{-- 季節 --}}
        <div>
            <label>季節</label><br>
            @foreach(['春', '夏', '秋', '冬'] as $season)
                <label>
                    <input type="radio" name="seasons[]" value="{{ $season }}"
                        {{ in_array($season, $selectedSeasons) ? 'checked' : '' }}>
                    {{ $season }}
                </label>
            @endforeach
            @error('seasons') <p style="color: red;">{{ $message }}</p> @enderror
        </div>

        {{-- 商品説明 --}}
        <div>
            <label>商品説明</label>
            <textarea name="description">{{ old('description', $product->description) }}</textarea>
            @error('description') <p style="color: red;">{{ $message }}</p> @enderror
        </div>

        {{-- ボタン --}}
        <div style="margin-top: 20px;">
            <a href="{{ route('products.search') }}" style="padding: 10px; background: #ccc; text-decoration: none; border-radius: 5px;">戻る</a>

            <button type="submit" style="padding: 10px; background: gold; border: none; border-radius: 5px;">変更を保存</button>
        </div>
    </form>

    {{-- 削除フォーム --}}
    <form method="POST" action="{{ route('products.destroy', $product->id) }}" style="margin-top: 10px;">
        @csrf
        <button type="submit" style="padding: 10px; background: red; color: white; border: none; border-radius: 5px;">削除</button>
    </form>

</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <h2>商品一覧</h2>

    {{-- 成功メッセージ --}}
    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    {{-- 検索フォーム --}}
    <form method="GET" action="{{ route('products.search') }}">
        <input type="text" name="keyword" placeholder="商品名で検索" value="{{ request('keyword') }}">
        <button type="submit">検索</button>

        {{-- 並び替え条件タグ表示 --}}
        @if(request('sort'))
            @php
                $sortLabel = '';
                if (request('sort') === 'price_asc') $sortLabel = '価格が安い順';
                elseif (request('sort') === 'price_desc') $sortLabel = '価格が高い順';

                // 並び替え条件を除いたクエリパラメータ（キーワードなどは維持）
                $query = request()->except('sort');
            @endphp

            <div style="margin: 10px 0;">
                <span style="display: inline-block; background: #eef; padding: 6px 10px; border-radius: 20px; margin-right: 10px;">
                    {{ $sortLabel }}
                    <a href="{{ route('products.search', $query) }}"
                    style="color: #777; margin-left: 10px; text-decoration: none; font-weight: bold;">×</a>
                </span>
            </div>
        @endif

        {{-- 並び替え --}}
        <select name="sort" onchange="this.form.submit()">
            <option value="">価格順で並べ替え</option>
            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>価格が安い順</option>
            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>価格が高い順</option>
        </select>
    </form>

    {{-- 商品追加ボタン --}}
    <a href="{{ route('products.register') }}" style="display: inline-block; margin: 10px 0; padding: 10px; background: orange; color: white; text-decoration: none; border-radius: 5px;">+ 商品を追加</a>

    {{-- 商品グリッド --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 20px;">
        @foreach($products as $product)
            <div style="border: 1px solid #ddd; border-radius: 8px; padding: 10px; text-align: center;">
                <a href="{{ route('products.show', $product->id) }}">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 100%; height: auto; border-radius: 8px;">
                </a>
                <h4>{{ $product->name }}</h4>
                <p>¥{{ number_format($product->price) }}</p>
            </div>
        @endforeach
    </div>

    {{-- ページネーション --}}
    <div style="margin-top: 20px;">
        {{ $products->links() }}
    </div>
</div>
@endsection

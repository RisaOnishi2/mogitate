<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\Season;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('id', 'desc')->paginate(6);
        return view('products.index', compact('products'));
    }

    public function search(Request $request)
    {
        $query = Product::query();

        // ✅ 検索
        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        // ✅ 並び替え
        if ($request->filled('sort') && $request->sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($request->filled('sort') && $request->sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->orderBy('id', 'desc'); // デフォルトは新着順
        }

        // ✅ ページネーション
        $products = $query->paginate(6)->appends($request->query());

        return view('products.index', compact('products'));
    }

    public function register()
    {
        return view('products.register');
    }

    public function store(RegisterProductRequest $request)
    {
        $dbPath = null;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('sample_images', 'public');
            $dbPath = 'sample_images/' . basename($path);
        }

        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'image' => $dbPath,
            'description' => $request->description,
        ]);

        // 季節データ保存 (文字列→season_idに変換して中間テーブルに保存)
        $seasonIds = Season::whereIn('name', $request->seasons)->pluck('id')->toArray();
        $product->seasons()->attach($seasonIds);

        return redirect()->route('products.index')->with('success', '商品を登録しました');
    }

    public function show(Product $product)
    {
         // この商品の季節名を配列で取得 (例: ['春', '夏'])
        $selectedSeasons = $product->seasons()->pluck('name')->toArray();
        return view('products.show', compact('product', 'selectedSeasons'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('sample_images', 'public');
            $validated['image'] = 'sample_images/' . basename($path);
        }

        $product->update($validated);

        if ($request->filled('seasons')) {
            $seasonIds = \App\Models\Season::whereIn('name', $request->seasons)->pluck('id')->toArray();
            $product->seasons()->sync($seasonIds);
        }

        return redirect()->route('products.show', $product->id)->with('success', '商品を更新しました');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.search')->with('success', '商品を削除しました');
    }

}

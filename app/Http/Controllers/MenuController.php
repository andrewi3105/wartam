<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');

        $productsQuery = DB::table('products');

        if (!empty($query)) {
            $productsQuery->where(function ($q2) use ($query) {
                $q2->where('nama_produk', 'like', "%{$query}%")
                   ->orWhere('kategori', 'like', "%{$query}%");
            });
        }

        $products = $productsQuery->orderBy('id_produk', 'desc')->get();

        return view('menu.menu', compact('products', 'query'));
    }

    public function create()
    {
        return view('menu.menu_create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi'   => 'required|string',
            'kategori'    => 'required|string',
            'harga'       => 'required|numeric',
            'gambar'      => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status'      => 'required|in:aktif,nonaktif',
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('products', 'public');
        }

        DB::table('products')->insert($validated);

        return redirect()->route('menu.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit($id_produk)
    {
        $product = DB::table('products')->where('id_produk', $id_produk)->first();

        if (!$product) {
            return redirect()->route('menu.index')->with('error', 'Produk tidak ditemukan.');
        }

        return view('menu.menu_edit', compact('product')); // KIRIM $product, bukan $menu
    }

    public function update(Request $request, $id_produk)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi'   => 'required|string',
            'kategori'    => 'required|string',
            'harga'       => 'required|numeric',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status'      => 'required|in:aktif,nonaktif',
        ]);

        $product = DB::table('products')->where('id_produk', $id_produk)->first();

        if (!$product) {
            return redirect()->route('menu.index')->with('error', 'Produk tidak ditemukan.');
        }

        if ($request->hasFile('gambar')) {
            if ($product->gambar && Storage::disk('public')->exists($product->gambar)) {
                Storage::disk('public')->delete($product->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('products', 'public');
        }

        DB::table('products')->where('id_produk', $id_produk)->update($validated);

        return redirect()->route('menu.index')->with('success', 'Produk berhasil diupdate!');
    }

    public function destroy($id_produk)
    {
        $product = DB::table('products')->where('id_produk', $id_produk)->first();

        if ($product && $product->gambar && Storage::disk('public')->exists($product->gambar)) {
            Storage::disk('public')->delete($product->gambar);
        }

        DB::table('products')->where('id_produk', $id_produk)->delete();

        return redirect()->route('menu.index')->with('success', 'Produk berhasil dihapus!');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Stok;

class StokController extends Controller
{
    /**
     * Tampilkan daftar stok
     */
    public function index(Request $request)
    {
        $query = $request->input('q'); // ambil query pencarian

        $stoks = DB::table('stok')
            ->when($query, function ($q) use ($query) {
                $q->where('nama_barang', 'like', "%{$query}%");
            })
            ->orderBy('id_barang', 'desc')
            ->get();

        return view('stok.stok', compact('stoks', 'query'));
    }

    /**
     * Form tambah stok
     */
    public function create()
    {
        $stoks = DB::table('stok')->orderBy('nama_barang')->get();
        return view('stok.stok_create', compact('stoks'));
    }

    /**
     * Simpan stok baru
     */
    public function storeBaru(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:150',
            'jumlah' => 'required|integer|min:1',
            'harga' => 'required|integer|min:0',
        ]);

        // Tambahkan stok baru
        $id_barang = DB::table('stok')->insertGetId([
            'nama_barang' => $request->nama_barang,
            'jumlah' => $request->jumlah,
            'harga' => $request->harga // harga satuan
        ]);

        // Catat ke stok_movement (harga total)
        DB::table('stok_movement')->insert([
            'id_barang'   => $id_barang,
            'nama_barang' => $request->nama_barang,
            'status'      => 'Masuk',
            'jumlah'      => $request->jumlah,
            'harga'       => $request->harga * $request->jumlah, // ✅ total harga
            'created_at'  => now()
        ]);

        return redirect()->route('stok.index')->with('success', 'Stok baru berhasil ditambahkan!');
    }

    /**
     * Tambah stok lama
     */
    public function storeLama(Request $request)
    {
        $request->validate([
            'existing_stok_id' => 'required|exists:stok,id_barang',
            'jumlah' => 'required|integer|min:1',
            'harga' => 'required|integer|min:0',
        ]);

        $stok = DB::table('stok')->where('id_barang', $request->existing_stok_id)->first();

        if (!$stok) {
            return redirect()->back()->withInput()->with('error', 'Barang lama tidak ditemukan!');
        }

        // Update stok lama
        DB::table('stok')->where('id_barang', $request->existing_stok_id)
            ->update([
                'jumlah' => $stok->jumlah + $request->jumlah,
                'harga'  => $request->harga // harga satuan bisa diperbarui
            ]);

        // Catat ke stok_movement (harga total)
        DB::table('stok_movement')->insert([
            'id_barang'   => $stok->id_barang,
            'nama_barang' => $stok->nama_barang,
            'status'      => 'Masuk',
            'jumlah'      => $request->jumlah,
            'harga'       => $request->harga * $request->jumlah, // ✅ total harga
            'created_at'  => now()
        ]);

        return redirect()->route('stok.index')->with('success', 'Jumlah stok berhasil diperbarui!');
    }

    /**
     * Hapus stok
     */
    public function destroy($id)
    {
        DB::table('stok')->where('id_barang', $id)->delete();

        // Catatan stok_movement tidak dihapus
        return redirect()->route('stok.index')->with('success', 'Data stok berhasil dihapus!');
    }

    /**
     * Form edit stok
     */
    public function edit($id)
    {
        $stok = Stok::findOrFail($id);
        return view('stok.stok_edit', compact('stok'));
    }

    /**
     * Update stok
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:150',
            'jumlah' => 'required|integer|min:0',
        ]);

        $stok = Stok::findOrFail($id);
        $stok->update([
            'nama_barang' => $request->nama_barang,
            'jumlah' => $request->jumlah
        ]);

        return redirect()->route('stok.index')->with('success', 'Data stok berhasil diperbarui!');
    }
}

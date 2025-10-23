<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        // Ambil semua pemesanan
        $laporan = DB::table('pemesanan')
            ->orderBy('tanggal_pemesanan', 'desc')
            ->get();

        // Hitung total pendapatan per metode pembayaran
        $totalTunai = DB::table('pemesanan')->where('metode_pembayaran', 'Tunai')->sum('harga_total');
        $totalTransfer = DB::table('pemesanan')->where('metode_pembayaran', 'Transfer')->sum('harga_total');
        $totalQRIS = DB::table('pemesanan')->where('metode_pembayaran', 'QRIS')->sum('harga_total');

        // Total pendapatan seluruh metode
        $totalPendapatan = $totalTunai + $totalTransfer + $totalQRIS;

        // Total pengeluaran dari stok_movement yang status = 'Masuk'
        $totalPengeluaran = DB::table('stok_movement')
            ->where('status', 'Masuk')
            ->sum('harga');

        // Laba bersih
        $labaBersih = $totalPendapatan - $totalPengeluaran;

        return view('laporan', compact(
            'laporan',
            'totalTunai',
            'totalTransfer',
            'totalQRIS',
            'totalPendapatan',
            'totalPengeluaran',
            'labaBersih'
        ));
    }
}

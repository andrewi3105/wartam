<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Stok;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Total produk dari tabel products (Menu)
        $totalMenu = Menu::count();

        // Total stok item yang tersedia
        $totalStok = Stok::where('jumlah', '>', 0)
            ->distinct('id_barang')
            ->count('id_barang');

        // Total transaksi dari tabel pemesanan
        $totalTransaksi = DB::table('pemesanan')->count();

        // Total omzet / pendapatan dari tabel pemesanan
        $totalOmzet = DB::table('pemesanan')->sum('harga_total');

        // User aktif
        $totalUserAktif = User::where('status', 'aktif')->count();

        return view('dashboard', compact(
            'totalMenu',
            'totalStok',
            'totalTransaksi',
            'totalOmzet',
            'totalUserAktif'
        ));
    }
}

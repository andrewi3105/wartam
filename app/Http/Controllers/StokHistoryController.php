<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');

        $movementQuery = DB::table('stok_movement');

        if (!empty($query)) {
            $movementQuery->where('nama_barang', 'like', "%{$query}%");
        }

        $movements = $movementQuery->orderBy('id_movement', 'desc')->get();

        return view('history', compact('movements', 'query'));
    }
}

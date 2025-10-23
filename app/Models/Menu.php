<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id_produk';
    public $timestamps = false;

    protected $fillable = [
        'nama_produk',
        'deskripsi',
        'kategori',
        'harga',
        'stok',
        'gambar',
        'status'
    ];
}

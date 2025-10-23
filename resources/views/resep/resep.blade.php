<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Resep Produk - Warkop</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        * { box-sizing: border-box; font-family: Arial, sans-serif; margin: 0; padding: 0; }
        body { display: flex; min-height: 100vh; background-color: #f7f7f7; }

        /* Sidebar */
        .sidebar {
            width: 220px; background-color: #fff; border-right: 1px solid #ddd;
            padding: 20px 0; display: flex; flex-direction: column; align-items: center;
        }
        .profile { text-align: center; margin-bottom: 20px; }
        .profile-icon {
            width: 60px; height: 60px; border-radius: 50%;
            background-color: #e7e3ff; display: flex; align-items: center; justify-content: center;
            margin: 0 auto 10px; font-size: 30px; color: #6b46c1;
        }
        .profile h3 { font-size: 14px; font-weight: 600; color: #333; }

        .sidebar ul { list-style: none; width: 100%; }
        .sidebar ul li { padding: 12px 25px; font-size: 14px; color: #333; cursor: pointer; transition: background 0.2s; }
        .sidebar ul li:hover, .sidebar ul li.active { background-color: #f2f2f2; font-weight: bold; }
        .sidebar ul li a { color: inherit; text-decoration: none; display: block; }

        /* Main */
        .main { flex: 1; padding: 25px 40px; }
        .header {
            background-color: #f9f9f9; padding: 20px 25px;
            border-radius: 8px; margin-bottom: 25px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .header h1 { font-size: 20px; color: #111; }
        .header .actions { display: flex; align-items: center; gap: 10px; }
        .header input, .header button {
            padding: 7px 10px; border-radius: 4px; font-size: 14px;
        }
        .header input { border: 1px solid #ccc; }
        .header button { border: none; background-color: #111; color: #fff; cursor: pointer; }
        .header button:hover { background-color: #444; }

        /* Table */
        .table-header, .resep-item {
            display: grid;
            grid-template-columns: 2fr 1.5fr 1fr {{ Auth::user()->role !== 'kasir' ? '0.5fr' : '' }};
            align-items: center;
            padding: 10px 15px;
            border-radius: 5px;
            text-align: left;
        }
        .table-header { background-color: #ddd; font-weight: bold; font-size: 14px; margin-bottom: 10px; }
        .resep-item {
            background-color: #fff; margin-bottom: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }
        .resep-item span { font-size: 14px; color: #333; }
        .resep-item .actions {
            display: flex; justify-content: flex-start; align-items: center; gap: 10px;
        }
        .resep-item .actions i { cursor: pointer; color: #333; }
        .resep-item .actions i:hover { color: #000; }
    </style>
</head>
<body>

    @include('layouts.sidebar')

    <div class="main">
        <div class="header">
            <h1>KELOLA DATA RESEP PRODUK</h1>

            {{-- tombol tambah hanya untuk admin --}}
            @if(Auth::user()->role !== 'kasir')
            <div class="actions">
                <button type="button" onclick="window.location='{{ route('resep.create') }}'">+ Tambah Resep</button>
            </div>
            @endif
        </div>

        <div class="table-header">
            <div>Nama Produk</div>
            <div>Bahan (Stok)</div>
            <div>Jumlah per Porsi</div>
            @if(Auth::user()->role !== 'kasir')
                <div>Aksi</div>
            @endif
        </div>

        @forelse($resep as $r)
            <div class="resep-item">
                <span>{{ $r->nama_produk }}</span>
                <span>{{ $r->nama_barang }}</span>
                <span>{{ $r->jumlah_per_porsi }}</span>

                @if(Auth::user()->role !== 'kasir')
                <div class="actions">
                    <form action="{{ route('resep.destroy', $r->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Hapus resep ini?')" style="background:none; border:none; padding:0; cursor:pointer;">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
                @endif
            </div>
        @empty
            <p style="text-align:center; color:#777;">Belum ada data resep.</p>
        @endforelse
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data Barang - Warkop</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        * { box-sizing: border-box; font-family: Arial, sans-serif; margin: 0; padding: 0; }

        body { display: flex; min-height: 100vh; background-color: #f7f7f7; }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 220px;
            background-color: #fff;
            border-right: 1px solid #ddd;
            padding: 20px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
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

        /* ===== MAIN CONTENT ===== */
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

        /* ===== TABLE ===== */
        .table-header, .stok-item {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr {{ Auth::user()->role !== 'kasir' ? '0.5fr' : '' }};
            align-items: center;
            padding: 10px 15px;
            border-radius: 5px;
            text-align: left;
        }

        .table-header {
            background-color: #ddd;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .stok-item {
            background-color: #fff;
            margin-bottom: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .stok-item span {
            font-size: 14px;
            color: #333;
        }

        .stok-item .actions {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            gap: 10px;
        }

        .stok-item .actions i {
            cursor: pointer;
            color: #333;
        }

        .stok-item .actions i:hover {
            color: #000;
        }

        .header .actions button {
            padding: 7px 14px;
            border: none;
            background-color: #111;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
        }

        .header .actions button:hover {
            background-color: #444;
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    @include('layouts.sidebar')

    <!-- MAIN CONTENT -->
    <div class="main">
        <div class="header">
            <h1>KELOLA DATA BARANG</h1>
            <form action="{{ route('stok.index') }}" method="GET" class="actions">
                <input type="text" name="q" value="{{ $query ?? '' }}" placeholder="Cari nama barang...">
                <button type="submit">Cari</button>

                {{-- Tampilkan tombol hanya jika bukan kasir --}}
                @if(Auth::user()->role !== 'kasir')
                    <button type="button" onclick="window.location='{{ route('stok.create') }}'">+ Tambah Barang</button>
                    <button type="button" style="background-color:#6b46c1;" onclick="window.location='/stok/history'">
                        <i class="fa-solid fa-clock-rotate-left"></i> History Stok
                    </button>
                @endif
            </form>
        </div>

        <div class="table-header">
            <div>Nama Barang</div>
            <div>Jumlah</div>
            <div>Harga</div>

            {{-- Kolom aksi hanya muncul kalau bukan kasir --}}
            @if(Auth::user()->role !== 'kasir')
                <div>Aksi</div>
            @endif
        </div>

        @forelse($stoks as $s)
            <div class="stok-item">
                <span>{{ $s->nama_barang }}</span>
                <span>{{ $s->jumlah }}</span>
                <span>Rp {{ number_format($s->harga, 0, ',', '.') }}</span>

                {{-- Tombol aksi hanya jika bukan kasir --}}
                @if(Auth::user()->role !== 'kasir')
                    <div class="actions">
                        <form action="{{ route('stok.destroy', $s->id_barang) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Hapus barang ini?')" style="background:none; border:none; padding:0; cursor:pointer;">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <p style="text-align:center; color:#777;">Tidak ada data stok ditemukan.</p>
        @endforelse
    </div>

</body>
</html>

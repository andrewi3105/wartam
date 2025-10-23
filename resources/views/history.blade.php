<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Stok - Warkop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; font-family: Arial, sans-serif; margin: 0; padding: 0; }
        body { display: flex; min-height: 100vh; background-color: #f7f7f7; }

        /* SIDEBAR */
        .sidebar { width: 220px; background-color: #fff; border-right: 1px solid #ddd; padding: 20px 0; display: flex; flex-direction: column; align-items: center; }
        .profile { text-align: center; margin-bottom: 20px; }
        .profile-icon { width: 60px; height: 60px; border-radius: 50%; background-color: #e7e3ff; display: flex; align-items: center; justify-content: center; font-size: 30px; color: #6b46c1; margin: 0 auto 10px; }
        .profile h3 { font-size: 14px; font-weight: 600; color: #333; }
        .sidebar ul { list-style: none; width: 100%; }
        .sidebar ul li { padding: 12px 25px; font-size: 14px; color: #333; cursor: pointer; transition: background 0.2s; }
        .sidebar ul li:hover, .sidebar ul li.active { background-color: #f2f2f2; font-weight: bold; }
        .sidebar ul li a { color: inherit; text-decoration: none; display: block; }

        /* MAIN CONTENT */
        .main { flex: 1; padding: 25px 40px; }
        .header { background-color: #f9f9f9; padding: 20px 25px; border-radius: 8px; margin-bottom: 25px; display: flex; align-items: center; justify-content: space-between; }
        .header h1 { font-size: 20px; color: #111; }
        .header .actions { display: flex; align-items: center; gap: 10px; }
        .header input { padding: 7px 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; }
        .header button { padding: 7px 14px; border: none; background-color: #111; color: #fff; border-radius: 4px; cursor: pointer; font-size: 13px; }
        .header button:hover { background-color: #444; }

        /* TABLE GRID */
        .table-header, .stok-item {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
            padding: 10px;
            border-radius: 5px;
            align-items: center;
        }
        .table-header { background-color: #ddd; font-weight: bold; font-size: 14px; margin-bottom: 10px; }
        .stok-item { background-color: #fff; margin-bottom: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
        .stok-item span { font-size: 14px; color: #333; }
        .status-masuk { color: green; font-weight: bold; }
        .status-keluar { color: red; font-weight: bold; }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    @include('layouts.sidebar')

    <!-- MAIN CONTENT -->
    <div class="main">
        <div class="header">
            <h1>HISTORY STOK</h1>

            <!-- FORM SEARCH -->
            <form action="{{ route('stok.history') }}" method="GET" class="actions">
                <input type="text" name="q" value="{{ $query ?? '' }}" placeholder="Cari nama barang...">
                <button type="submit">Cari</button>
                <button type="button" onclick="window.location='{{ route('stok.index') }}'">← Kembali</button>
            </form>
        </div>

        <!-- TABLE HEADER -->
        <div class="table-header">
            <div>Nama Barang</div>
            <div>Status</div>
            <div>Jumlah</div>
            <div>Total</div>
            <div>Tanggal</div>
        </div>

        <!-- DATA STOK -->
        @forelse($movements as $m)
        <div class="stok-item">
            <span>{{ $m->nama_barang }}</span>
            <span class="{{ $m->status == 'Masuk' ? 'status-masuk' : 'status-keluar' }}">{{ $m->status }}</span>
            <span>{{ $m->jumlah }}</span>
            <span>Rp {{ number_format($m->harga, 0, ',', '.') }}</span>
            <span>{{ \Carbon\Carbon::parse($m->created_at)->format('d/m/Y H:i') }}</span>
        </div>
        @empty
            <p style="text-align:center; color:#777;">Belum ada riwayat stok.</p>
        @endforelse
    </div>

</body>
</html>

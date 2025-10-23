<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan - Warkop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; font-family: Arial, sans-serif; margin: 0; padding: 0; }
        body { display: flex; min-height: 100vh; background-color: #f7f7f7; }

        /* SIDEBAR */
        .sidebar { width: 220px; background-color: #fff; border-right: 1px solid #ddd; padding: 20px 0; display: flex; flex-direction: column; align-items: center; }
        .profile { text-align: center; margin-bottom: 20px; }
        .profile-icon { width: 60px; height: 60px; border-radius: 50%; background-color: #e7e3ff; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 30px; color: #6b46c1; }
        .profile h3 { font-size: 14px; font-weight: 600; color: #333; }
        .sidebar ul { list-style: none; width: 100%; padding: 0; }
        .sidebar ul li { padding: 12px 25px; font-size: 14px; color: #333; cursor: pointer; transition: background 0.2s; }
        .sidebar ul li:hover, .sidebar ul li.active { background-color: #f2f2f2; font-weight: bold; }
        .sidebar ul li a { text-decoration: none; color: inherit; display: block; }

        /* MAIN CONTENT */
        .main { flex: 1; padding: 25px 40px; }
        .header { background-color: #f9f9f9; padding: 20px 25px; border-radius: 8px; margin-bottom: 25px; }
        .header h1 { font-size: 24px; font-weight: bold; color: #111; }

        /* SUMMARY CARDS */
        .summary { display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 25px; }
        .summary-card { background: #fff; padding: 15px 20px; border-radius: 8px; flex: 1; min-width: 150px; box-shadow: 0 1px 4px rgba(0,0,0,0.1); }
        .summary-card h4 { font-size: 14px; color: #555; }
        .summary-card p { font-size: 18px; font-weight: bold; color: #111; margin-top: 5px; }

        /* TABLE */
        .table-header, .table-row { display: grid; grid-template-columns: 1.5fr 2fr 1.5fr 1.5fr 1.5fr 1.5fr; align-items: center; }
        .table-header { background: #ddd; font-weight: bold; padding: 10px; border-radius: 6px; font-size: 14px; }
        .table-row { background: #fff; margin-top: 8px; padding: 10px; border-radius: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
        .table-row span { font-size: 14px; color: #333; }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    @include('layouts.sidebar')

    <!-- MAIN CONTENT -->
    <div class="main">
        <div class="header">
            <h1>LAPORAN KEUANGAN</h1>
        </div>

        <!-- SUMMARY CARDS -->
        <div class="summary">
            <div class="summary-card">
                <h4>Total Tunai</h4>
                <p>Rp {{ number_format($totalTunai, 0, ',', '.') }}</p>
            </div>
            <div class="summary-card">
                <h4>Total Transfer</h4>
                <p>Rp {{ number_format($totalTransfer, 0, ',', '.') }}</p>
            </div>
            <div class="summary-card">
                <h4>Total QRIS</h4>
                <p>Rp {{ number_format($totalQRIS, 0, ',', '.') }}</p>
            </div>
            <div class="summary-card">
                <h4>Total Pendapatan</h4>
                <p>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
            </div>
            <div class="summary-card">
                <h4>Total Pengeluaran</h4>
                <p>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
            </div>
            <div class="summary-card">
                <h4>Laba Bersih</h4>
                <p>Rp {{ number_format($labaBersih, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- TABLE HEADER -->
        <div class="table-header">
            <div>Tanggal</div>
            <div>Nama Pemesan</div>
            <div>Deskripsi</div>
            <div>Tunai</div>
            <div>Transfer</div>
            <div>QRIS</div>
        </div>

        <!-- TABLE DATA -->
        @php
            $saldoTunai = 0;
            $saldoTransfer = 0;
            $saldoQRIS = 0;
        @endphp

        @foreach($laporan as $row)
            @php
                $tunai = $transfer = $qris = 0;
                if($row->metode_pembayaran === 'Tunai') {
                    $tunai = $row->harga_total;
                    $saldoTunai += $tunai;
                } elseif($row->metode_pembayaran === 'Transfer') {
                    $transfer = $row->harga_total;
                    $saldoTransfer += $transfer;
                } elseif($row->metode_pembayaran === 'QRIS') {
                    $qris = $row->harga_total;
                    $saldoQRIS += $qris;
                }
            @endphp

            <div class="table-row">
                <span>{{ date('d-m-Y H:i', strtotime($row->tanggal_pemesanan)) }}</span>
                <span>{{ $row->nama_pemesan }}</span>
                <span>Penjualan</span>
                <span>Rp {{ $tunai ? number_format($tunai, 0, ',', '.') : '-' }}</span>
                <span>Rp {{ $transfer ? number_format($transfer, 0, ',', '.') : '-' }}</span>
                <span>Rp {{ $qris ? number_format($qris, 0, ',', '.') : '-' }}</span>
            </div>
        @endforeach

    </div>

</body>
</html>

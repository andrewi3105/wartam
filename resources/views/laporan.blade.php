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

        /* ===== MAIN AREA ===== */
        .main {
            flex: 1;
            padding: 25px 40px;
            margin-left: 220px;
            transition: margin-left 0.3s ease-in-out;
        }

        @media (max-width: 900px) {
            .main {
                margin-left: 0;
                padding: 15px;
            }
        }

        /* ===== HEADER ===== */
        .header {
            background-color: #f9f9f9;
            padding: 20px 25px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }
        .header h1 {
            font-size: 20px;
            color: #111;
            flex-shrink: 0;
        }
        @media (max-width: 900px) {
            .header { flex-direction: column; align-items: stretch; }
            .header h1 { width: 100%; text-align: center; }
        }

        /* ===== SUMMARY CARDS ===== */
        .summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }

        .summary-card {
            background: #fff;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        .summary-card h4 { font-size: 14px; color: #555; }
        .summary-card p { font-size: 18px; font-weight: bold; color: #111; margin-top: 5px; }

        @media (max-width: 480px) {
            .summary { grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); }
        }

        /* ===== TABLE (DESKTOP) ===== */
        .table-header, .table-row {
            display: grid;
            grid-template-columns: 1.5fr 2fr 1.5fr 1.5fr 1.5fr 1.5fr;
            align-items: center;
            padding: 10px 15px;
            border-radius: 5px;
            text-align: left;
            gap: 10px;
        }

        .table-header {
            background-color: #ddd;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .table-row {
            background-color: #fff;
            margin-bottom: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .table-row span { font-size: 14px; color: #333; }

        /* ===== MOBILE (CARD STYLE SAMA DENGAN KELOLA RESEP) ===== */
        @media (max-width: 900px) {
            .table-header { display: none; }

            .table-row {
                display: block;
                padding: 15px;
                border-radius: 8px;
                margin-bottom: 12px;
            }

            .table-row span {
                display: flex;
                justify-content: space-between;
                border-bottom: 1px solid #eee;
                padding: 6px 0;
                font-size: 14px;
            }

            .table-row span:last-child {
                border-bottom: none;
            }

            .table-row span::before {
                content: attr(data-label);
                font-weight: bold;
                color: #555;
                margin-right: 10px;
            }
        }
    </style>
</head>
<body>

    {{-- Sidebar tetap dipanggil --}}
    @include('layouts.sidebar')

    <div class="main">
        <div class="header">
            <h1>LAPORAN KEUANGAN</h1>
        </div>

        <!-- ===== SUMMARY CARDS ===== -->
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

        <!-- ===== TABLE ===== -->
        <div class="table-wrapper">
            <div class="table-header">
                <div>Tanggal</div>
                <div>Nama Pemesan</div>
                <div>Deskripsi</div>
                <div>Tunai</div>
                <div>Transfer</div>
                <div>QRIS</div>
            </div>

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
                    <span data-label="Tanggal">{{ date('d-m-Y H:i', strtotime($row->tanggal_pemesanan)) }}</span>
                    <span data-label="Nama Pemesan">{{ $row->nama_pemesan }}</span>
                    <span data-label="Deskripsi">Penjualan</span>
                    <span data-label="Tunai">Rp {{ $tunai ? number_format($tunai, 0, ',', '.') : '-' }}</span>
                    <span data-label="Transfer">Rp {{ $transfer ? number_format($transfer, 0, ',', '.') : '-' }}</span>
                    <span data-label="QRIS">Rp {{ $qris ? number_format($qris, 0, ',', '.') : '-' }}</span>
                </div>
            @endforeach

            @if($laporan->isEmpty())
                <p style="text-align:center; color:#777;">Belum ada data laporan.</p>
            @endif
        </div>
    </div>
</body>
</html>

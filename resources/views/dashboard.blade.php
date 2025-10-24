<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Warkop</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        * { box-sizing: border-box; font-family: Arial, sans-serif; margin: 0; padding: 0; }
        body { display: flex; min-height: 100vh; background-color: #f7f7f7; }

        /* MAIN WRAPPER */
        .main-wrapper { 
            flex: 1; 
            margin-left: 220px; 
            transition: margin-left 0.3s ease-in-out; 
            padding: 0; 
        }
        @media (max-width: 900px) { 
            .main-wrapper { margin-left: 0; } 
        }

        /* MAIN CONTENT */
        .main { padding: 25px 40px; }

        /* HEADER */
        .header {
            background-color: #f9f9f9;
            padding: 20px 25px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .header h1 {
            font-size: 22px;
            font-weight: bold;
            color: #111;
            text-align: left; /* posisi default desktop */
        }

        .header p {
            font-size: 14px;
            color: #666;
            margin-top: 6px;
            text-align: left;
        }

        /* GARIS PEMBATAS */
        hr {
            border: none;
            border-top: 1px solid #ddd;
            margin: 20px 0;
        }

        /* CARD SECTION */
        .cards {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }

        .card {
            background-color: #fff;
            flex: 1 1 250px;
            height: 180px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s;
            text-align: center;
            padding-top: 25px;
        }

        .card:hover { transform: scale(1.03); }

        .card i {
            font-size: 40px;
            color: #6b46c1;
            margin-bottom: 15px;
        }

        .card h3 {
            font-size: 15px;
            color: #333;
            margin-bottom: 8px;
        }

        .card p {
            font-size: 13px;
            color: #555;
        }

        /* FOOTER */
        .footer {
            text-align: center;
            margin-top: 25px;
            color: #777;
            font-size: 14px;
        }

        /* RESPONSIVE */
        @media (max-width: 600px) {
            .main { padding: 20px; }

            /* Judul & subjudul di tengah pada layar kecil */
            .header h1, .header p {
                text-align: center;
            }

            .cards {
                flex-direction: column;
                align-items: center;
            }

            .card {
                width: 100%;
                max-width: 360px;
            }
        }
    </style>
</head>
<body>

    <!-- SIDEBAR DIPANGGIL -->
    @include('layouts.sidebar')

    <!-- MAIN CONTENT -->
    <div class="main-wrapper">
        <div class="main">
            <div class="header">
                <h1>DASHBOARD</h1>
                <p>Selamat datang kembali, {{ Auth::user()->role ?? 'Owner' }}!</p>
            </div>

            <hr>

            <!-- Kartu Statistik -->
            <div class="cards">
                <div class="card">
                    <i class="fa-solid fa-utensils"></i>
                    <h3>KELOLA MENU</h3>
                    <p>Total Produk: <strong>{{ $totalMenu ?? 0 }}</strong></p>
                </div>

                <div class="card">
                    <i class="fa-solid fa-boxes-stacked"></i>
                    <h3>KELOLA DATA BARANG</h3>
                    <p>Total Stok: <strong>{{ $totalStok ?? 0 }}</strong></p>
                </div>

                <div class="card">
                    <i class="fa-solid fa-chart-line"></i>
                    <h3>LAPORAN KEUANGAN</h3>
                    <p>Total Transaksi: <strong>{{ $totalTransaksi ?? 0 }}</strong></p>
                </div>
            </div>

            <div class="footer">
                User aktif: <strong>{{ $totalUserAktif ?? 0 }}</strong>
            </div>
        </div>
    </div>

</body>
</html>

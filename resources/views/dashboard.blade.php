<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Warkop</title>

    <!-- Font Awesome (ikon) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background-color: #f7f7f7;
        }

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

        .profile {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: #e7e3ff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-size: 30px;
            color: #6b46c1;
        }

        .profile h3 {
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }

        .sidebar ul {
            list-style: none;
            width: 100%;
            padding: 0;
        }

        .sidebar ul li {
            padding: 12px 25px;
            font-size: 14px;
            color: #333;
            cursor: pointer;
            transition: background 0.2s;
        }

        .sidebar ul li:hover,
        .sidebar ul li.active {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        /* ===== MAIN CONTENT ===== */
        .main {
            flex: 1;
            padding: 25px 40px;
        }

        .header {
            background-color: #f9f9f9;
            padding: 20px 25px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .header h1 {
            font-size: 24px;
            font-weight: bold;
            color: #111;
        }

        .header p {
            font-size: 14px;
            color: #666;
        }

        hr {
            border: none;
            border-top: 1px solid #ddd;
            margin: 20px 0;
        }

        .cards {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .card {
            background-color: #fff;
            flex: 1;
            height: 180px;
            border-radius: 10px;
            position: relative;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s;
            text-align: center;
            padding-top: 25px;
        }

        .card:hover {
            transform: scale(1.03);
        }

        .card i {
            font-size: 42px;
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

        /* RESPONSIVE */
        @media (max-width: 900px) {
            .cards {
                flex-direction: column;
                align-items: center;
            }
            .card {
                width: 100%;
                max-width: 400px;
            }
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    @include('layouts.sidebar')

    <!-- MAIN CONTENT -->
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

        <p style="text-align:center;margin-top:25px;color:#777;">
            User aktif: <strong>{{ $totalUserAktif ?? 0 }}</strong>
        </p>
    </div>

</body>
</html>

<!-- resources/views/layouts/sidebar.blade.php -->

<!-- ===== SIDEBAR STYLE DAN STRUKTUR ===== -->
<style>
    /* SIDEBAR DASAR */
    .sidebar {
        width: 220px;
        background-color: #fff;
        border-right: 1px solid #ddd;
        padding: 20px 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        z-index: 999;
        transition: transform 0.3s ease-in-out;
    }

    /* POSISI SAAT DISEMBUNYIKAN */
    .sidebar.hidden {
        transform: translateX(-100%);
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

    /* TOMBOL TOGGLE (☰) */
    .menu-toggle {
        display: none;
        position: fixed;
        top: 15px;
        left: 15px;
        background-color: #6b46c1;
        color: white;
        border: none;
        font-size: 20px;
        padding: 8px 12px;
        border-radius: 6px;
        cursor: pointer;
        z-index: 1000;
    }

    /* RESPONSIVE UNTUK HP */
    @media (max-width: 900px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar.show {
            transform: translateX(0);
        }

        .menu-toggle {
            display: block;
        }
    }
</style>

<!-- ===== SIDEBAR STRUCTURE ===== -->
<button class="menu-toggle" onclick="toggleSidebar()">
    <i class="fa-solid fa-bars"></i>
</button>

<div class="sidebar" id="sidebar">
    <div class="profile">
        <div class="profile-icon"><i class="fa-solid fa-user"></i></div>
        <h3>{{ Auth::user()->nama_lengkap ?? 'USER' }}</h3>
    </div>

    <ul>
        <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}">Dashboard</a>
        </li>

        <li class="{{ request()->routeIs('pemesanan.*') ? 'active' : '' }}">
            <a href="{{ route('pemesanan.index') }}">Pemesanan</a>
        </li>

        <li class="{{ request()->routeIs('stok.*') ? 'active' : '' }}">
            <a href="{{ route('stok.index') }}">Kelola Stok</a>
        </li>

        {{-- 👇 TAMPILKAN HANYA JIKA BUKAN KASIR --}}
        @if(Auth::user()->role !== 'kasir')
            <li class="{{ request()->routeIs('menu.*') ? 'active' : '' }}">
                <a href="{{ route('menu.index') }}">Kelola Menu</a>
            </li>

            <li class="{{ request()->routeIs('resep.*') ? 'active' : '' }}">
                <a href="{{ route('resep.index') }}">Kelola Resep</a>
            </li>

            <li class="{{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                <a href="{{ route('laporan.index') }}">Laporan Keuangan</a>
            </li>

            <li class="{{ request()->routeIs('staff.*') ? 'active' : '' }}">
                <a href="{{ route('staff.index') }}">Staff</a>
            </li>
        @endif

        <li>
            <a href="{{ route('logout') }}" style="color:#c00;">Logout</a>
        </li>
    </ul>
</div>

<!-- ===== SIDEBAR TOGGLE SCRIPT ===== -->
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('show');
    }
</script>

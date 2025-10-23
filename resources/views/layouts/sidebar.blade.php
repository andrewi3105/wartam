<div class="sidebar">
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

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Staff - Warkop</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        * { box-sizing: border-box; font-family: Arial, sans-serif; margin: 0; padding: 0; }
        body { display: flex; min-height: 100vh; background-color: #f7f7f7; }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 220px; background-color: #fff; border-right: 1px solid #ddd;
            padding: 20px 0; display: flex; flex-direction: column; align-items: center;
        }
        .profile { text-align: center; margin-bottom: 20px; }
        .profile-icon {
            width: 60px; height: 60px; border-radius: 50%;
            background-color: #e7e3ff; display: flex; align-items: center;
            justify-content: center; margin: 0 auto 10px;
            font-size: 30px; color: #6b46c1;
        }
        .profile h3 { font-size: 14px; font-weight: 600; color: #333; }
        .sidebar ul { list-style: none; width: 100%; }
        .sidebar ul li {
            padding: 12px 25px; font-size: 14px; color: #333;
            cursor: pointer; transition: background 0.2s;
        }
        .sidebar ul li:hover, .sidebar ul li.active {
            background-color: #f2f2f2; font-weight: bold;
        }
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
        .header input {
            padding: 7px 10px; border: 1px solid #ccc;
            border-radius: 4px; font-size: 14px;
        }
        .header button {
            padding: 7px 14px; border: none;
            background-color: #111; color: #fff;
            border-radius: 4px; cursor: pointer; font-size: 13px;
        }
        .header button:hover { background-color: #444; }

        /* ===== TABLE-LIKE LIST ===== */
        .table-header {
            display: grid;
            grid-template-columns: 1fr 2fr 1fr 1fr 1fr 1fr;
            background-color: #ddd;
            padding: 10px; border-radius: 5px;
            font-weight: bold; font-size: 14px;
            margin-bottom: 10px;
        }
        .staff-item {
            display: grid;
            grid-template-columns: 1fr 2fr 1fr 1fr 1fr 1fr;
            align-items: center;
            background-color: #fff;
            padding: 12px; margin-bottom: 10px;
            border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }
        .staff-item span { font-size: 14px; color: #333; }
        .staff-item .actions i {
            margin: 0 6px; cursor: pointer; color: #333;
        }
        .staff-item .actions i:hover { color: #000; }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    @include('layouts.sidebar')

    <!-- MAIN CONTENT -->
    <div class="main">
        <div class="header">
            <h1>KELOLA STAFF</h1>

            <!-- FORM SEARCH & TAMBAH -->
            <form action="{{ route('staff.index') }}" method="GET" class="actions">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari staff...">
                <button type="submit">Cari</button>
                <button type="button" onclick="window.location='{{ route('staff.create') }}'">+ Tambah Staff</button>
            </form>
        </div>

        <!-- HEADER TABLE -->
        <div class="table-header">
            <div>ID</div>
            <div>Nama Lengkap</div>
            <div>Username</div>
            <div>Role</div>
            <div>Status</div>
            <div>Aksi</div>
        </div>

        <!-- DATA STAFF -->
        @forelse($users as $u)
        <div class="staff-item">
            <span>{{ $u->id }}</span>
            <span>{{ $u->nama_lengkap ?? '-' }}</span>
            <span>{{ $u->username }}</span>
            <span>{{ ucfirst($u->role) }}</span>
            <form action="{{ route('staff.toggleStatus', $u->id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" style="
                    background-color: {{ $u->status == 'aktif' ? '#28a745' : '#dc3545' }};
                    color: white;
                    border: none;
                    padding: 4px 10px;
                    border-radius: 4px;
                    cursor: pointer;
                    font-size: 12px;
                ">
                    {{ ucfirst($u->status) }}
                </button>
            </form>
            <div class="actions">
            <a href="{{ route('staff.edit', $u->id) }}"><i class="fa-solid fa-pen"></i></a>

            <form action="{{ route('staff.destroy', $u->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus staff ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" style="background:none; border:none; cursor:pointer; color:#c00;">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </form>
        </div>
        </div>
        @empty
            <p style="text-align:center; color:#777;">Tidak ada data staff ditemukan.</p>
        @endforelse
    </div>

</body>
</html>

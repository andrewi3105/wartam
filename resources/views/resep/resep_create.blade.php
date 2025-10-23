<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Resep Baru - Warkop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        * { box-sizing: border-box; font-family: Arial, sans-serif; margin: 0; padding: 0; }
        body { display: flex; min-height: 100vh; background-color: #f7f7f7; }

        /* SIDEBAR */
        .sidebar { width: 220px; background-color: #fff; border-right: 1px solid #ddd; padding: 20px 0; display: flex; flex-direction: column; align-items: center; }
        .profile { text-align: center; margin-bottom: 20px; }
        .profile-icon { width: 60px; height: 60px; border-radius: 50%; background-color: #e7e3ff; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 30px; color: #6b46c1; }
        .profile h3 { font-size: 14px; font-weight: 600; color: #333; }
        .sidebar ul { list-style: none; width: 100%; }
        .sidebar ul li { padding: 12px 25px; font-size: 14px; color: #333; cursor: pointer; transition: background 0.2s; }
        .sidebar ul li:hover, .sidebar ul li.active { background-color: #f2f2f2; font-weight: bold; }
        .sidebar ul li a { color: inherit; text-decoration: none; display: block; }

        /* MAIN */
        .main { flex: 1; padding: 25px 40px; }
        .header { background-color: #f9f9f9; padding: 20px 25px; border-radius: 8px; margin-bottom: 25px; display: flex; align-items: center; justify-content: space-between; }
        .header h1 { font-size: 20px; color: #111; }

        /* FORM */
        form { background-color: #fff; padding: 20px 25px; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-size: 14px; margin-bottom: 6px; font-weight: bold; color: #333; }
        input, select { width: 100%; padding: 8px 10px; border-radius: 5px; border: 1px solid #ccc; font-size: 14px; }

        /* BAGIAN BAHAN */
        .bahan-section { margin-top: 20px; }
        .bahan-item {
            display: grid;
            grid-template-columns: 2fr 1fr auto auto;
            gap: 10px;
            align-items: center;
            margin-bottom: 10px;
        }

        .bahan-item button {
            border: none;
            padding: 6px 10px;
            border-radius: 6px;
            color: #fff;
            cursor: pointer;
            font-size: 14px;
        }

        .hapus-bahan { background-color: crimson; }
        .hapus-bahan:hover { background-color: #a30c2d; }
        .add-bahan { background-color: #6b46c1; }
        .add-bahan:hover { background-color: #54309c; }

        .submit-btn {
            margin-top: 20px;
            background-color: #111;
            color: #fff;
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
        }
        .submit-btn:hover { background-color: #444; }

        .alert { background-color: #ffdddd; border-left: 5px solid crimson; padding: 10px; margin-bottom: 15px; color: #333; }
        .success { background-color: #ddffdd; border-left: 5px solid green; padding: 10px; margin-bottom: 15px; color: #333; }
    </style>
</head>
<body>

@include('layouts.sidebar') {{-- ✅ Sidebar tetap tampil --}}

<div class="main">
    <div class="header">
        <h1>TAMBAH RESEP BARU</h1>
    </div>

    @if(session('error'))
        <div class="alert">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <form id="formResep" action="{{ route('resep.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="id_produk">Produk</label>
            <select name="id_produk" id="id_produk" required>
                <option value="">-- Pilih Produk --</option>
                @foreach($products as $p)
                    <option value="{{ $p->id_produk }}">{{ $p->nama_produk }}</option>
                @endforeach
            </select>
        </div>

        <div class="bahan-section">
            <h3>Daftar Bahan</h3>
            <div id="bahanContainer">
                <div class="bahan-item">
                    <select name="id_barang[]" required>
                        <option value="">-- Pilih Bahan --</option>
                        @foreach($stok as $s)
                            <option value="{{ $s->id_barang }}">{{ $s->nama_barang }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="jumlah_per_porsi[]" placeholder="Contoh: 3/12 atau 0.25" required>
                    <button type="button" class="hapus-bahan"><i class="fa fa-trash"></i></button>
                    <button type="button" class="add-bahan"><i class="fa fa-plus"></i></button>
                </div>
            </div>
        </div>

        <button type="submit" class="submit-btn">Simpan Resep</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('bahanContainer');
    const form = document.getElementById('formResep');

    // === TAMBAH FIELD BAHAN ===
    function tambahBahan() {
        const newRow = document.createElement('div');
        newRow.classList.add('bahan-item');
        newRow.innerHTML = `
            <select name="id_barang[]" required>
                <option value="">-- Pilih Bahan --</option>
                @foreach($stok as $s)
                    <option value="{{ $s->id_barang }}">{{ $s->nama_barang }}</option>
                @endforeach
            </select>
            <input type="text" name="jumlah_per_porsi[]" placeholder="Contoh: 3/12 atau 0.25" required>
            <button type="button" class="hapus-bahan"><i class="fa fa-trash"></i></button>
            <button type="button" class="add-bahan"><i class="fa fa-plus"></i></button>
        `;
        container.appendChild(newRow);
        perbaruiTombolHapus();
    }

    // === ATUR TOMBOL HAPUS ===
    function perbaruiTombolHapus() {
        const items = container.querySelectorAll('.bahan-item');
        items.forEach(item => {
            const hapusBtn = item.querySelector('.hapus-bahan');
            hapusBtn.style.display = items.length === 1 ? 'none' : 'inline-block';
        });
    }

    // === CEK DUPLIKASI BAHAN DI FORM ===
    form.addEventListener('submit', function(e) {
        const selects = form.querySelectorAll('select[name="id_barang[]"]');
        const values = Array.from(selects).map(s => s.value).filter(v => v !== '');
        const unique = new Set(values);

        if (values.length !== unique.size) {
            e.preventDefault();
            alert('Terdapat bahan yang sama di dalam daftar. Mohon gunakan bahan yang berbeda.');
        }
    });

    // === EVENT TAMBAH / HAPUS ===
    document.addEventListener('click', function(e) {
        if (e.target.closest('.add-bahan')) tambahBahan();
        if (e.target.closest('.hapus-bahan')) {
            e.target.closest('.bahan-item').remove();
            perbaruiTombolHapus();
        }
    });

    // === KONVERSI PECAHAN OTOMATIS ===
    document.addEventListener('blur', function(e) {
        if (e.target.name === 'jumlah_per_porsi[]') {
            const val = e.target.value.trim();
            if (/^\d+\/\d+$/.test(val)) {
                const [a, b] = val.split('/').map(Number);
                if (b !== 0) e.target.value = (a / b).toFixed(4);
            }
        }
    }, true);

    perbaruiTombolHapus();
});
</script>

</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Stok - Warkop</title>
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

        /* FORM STYLE - sesuai pemesanan */
        .form-container { background-color: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-size: 14px; font-weight: bold; color: #333; margin-bottom: 6px; }
        input, select { width: 100%; padding: 8px 10px; border-radius: 5px; border: 1px solid #ccc; font-size: 14px; }
        input:focus, select:focus { border-color: #6b46c1; outline: none; }
        .error-message { color: red; font-size: 13px; margin-top: 4px; }
        input.error-field, select.error-field { border-color: red; }

        .form-footer { text-align: right; margin-top: 20px; }
        button { background-color: #111; color: #fff; padding: 10px 18px; border: none; border-radius: 6px; cursor: pointer; font-size: 15px; }
        button:hover { background-color: #444; }
    </style>
</head>
<body>

    @include('layouts.sidebar')

    <div class="main">
        <div class="header">
            <h1>TAMBAH STOK</h1>
        </div>

        <div class="form-container">
            @if(session('error'))
                <div style="color:#c00; margin-bottom:10px;">{{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div style="color:green; margin-bottom:10px;">{{ session('success') }}</div>
            @endif

            <div class="form-group">
                <label>Jenis Penambahan</label>
                <select id="jenisTambah">
                    <option value="">-- Pilih Jenis --</option>
                    <option value="baru">Stok Baru</option>
                    <option value="lama">Tambah Stok Lama</option>
                </select>
            </div>

            <!-- Form Stok Baru -->
            <form id="stokFormBaru" action="{{ route('stok.store.baru') }}" method="POST" style="display:none;">
                @csrf
                <div class="form-group">
                    <label>Nama Barang Baru</label>
                    <input type="text" name="nama_barang" placeholder="Masukkan nama barang baru">
                    <div class="error-message"></div>
                </div>
                <div class="form-group">
                    <label>Jumlah</label>
                    <input type="number" name="jumlah" min="1" placeholder="Masukkan jumlah stok">
                    <div class="error-message"></div>
                </div>
                <div class="form-group">
                    <label>Harga</label>
                    <input type="number" name="harga" min="0" placeholder="Masukkan harga per unit">
                    <div class="error-message"></div>
                </div>
                <div class="form-footer">
                    <button type="submit"><i class="fa-solid fa-plus"></i> Simpan Stok Baru</button>
                </div>
            </form>

            <!-- Form Stok Lama -->
            <form id="stokFormLama" action="{{ route('stok.store.lama') }}" method="POST" style="display:none;">
                @csrf
                <div class="form-group">
                    <label>Pilih Barang Lama</label>
                    <select name="existing_stok_id" id="existing_stok_id" onchange="updateHargaDefault()">
                        <option value="">-- Pilih Barang --</option>
                        @foreach($stoks as $stok)
                            <option value="{{ $stok->id_barang }}" data-harga="{{ $stok->harga }}">
                                {{ $stok->nama_barang }} (stok: {{ $stok->jumlah }})
                            </option>
                        @endforeach
                    </select>
                    <div class="error-message"></div>
                </div>
                <div class="form-group">
                    <label>Jumlah</label>
                    <input type="number" name="jumlah" min="1" placeholder="Tambahkan jumlah stok">
                    <div class="error-message"></div>
                </div>
                <div class="form-group">
                    <label>Harga per Unit</label>
                    <input type="number" name="harga" id="hargaLama" min="0" placeholder="Harga per unit">
                    <div class="error-message"></div>
                </div>
                <div class="form-footer">
                    <button type="submit"><i class="fa-solid fa-plus"></i> Tambah Jumlah</button>
                </div>
            </form>
        </div>
    </div>

<script>
    const jenisTambah = document.getElementById('jenisTambah');
    const formBaru = document.getElementById('stokFormBaru');
    const formLama = document.getElementById('stokFormLama');
    const stokSelect = document.getElementById('existing_stok_id');
    const hargaInput = document.getElementById('hargaLama');

    function toggleForm() {
        if(jenisTambah.value === 'baru'){
            formBaru.style.display='block';
            formLama.style.display='none';
        } else if(jenisTambah.value === 'lama'){
            formBaru.style.display='none';
            formLama.style.display='block';
            updateHargaDefault();
        } else {
            formBaru.style.display='none';
            formLama.style.display='none';
        }
    }

    function updateHargaDefault() {
        const selectedOption = stokSelect.selectedOptions[0];
        if(selectedOption && selectedOption.dataset.harga !== undefined){
            hargaInput.value = selectedOption.dataset.harga;
        } else {
            hargaInput.value = '';
        }
    }

    jenisTambah.addEventListener('change', toggleForm);
    window.addEventListener('load', toggleForm);

    // Validasi sebelum submit
    function validateForm(form) {
        let valid = true;
        form.querySelectorAll('.error-message').forEach(div => div.innerText = '');
        form.querySelectorAll('input, select').forEach(input => input.classList.remove('error-field'));

        form.querySelectorAll('input, select').forEach(input => {
            if(input.name && input.value.trim() === ''){
                valid = false;
                input.classList.add('error-field');
                const errDiv = input.closest('.form-group').querySelector('.error-message');
                if(errDiv) errDiv.innerText = 'Kolom ini wajib diisi';
            }
        });
        return valid;
    }

    formBaru.addEventListener('submit', function(e){
        if(!validateForm(formBaru)) e.preventDefault();
    });
    formLama.addEventListener('submit', function(e){
        if(!validateForm(formLama)) e.preventDefault();
    });
</script>
</body>
</html>

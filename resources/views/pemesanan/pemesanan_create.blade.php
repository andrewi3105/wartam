<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan - Warkop</title>
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

        /* DAFTAR PESANAN */
        .order-section { margin-top: 20px; }
        .order-item {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr auto auto;
            gap: 10px;
            align-items: center;
            margin-bottom: 10px;
        }

        .order-item button {
            border: none;
            padding: 6px 10px;
            border-radius: 6px;
            color: #fff;
            cursor: pointer;
            font-size: 14px;
        }

        .hapus-item { background-color: crimson; }
        .hapus-item:hover { background-color: #a30c2d; }
        .add-item { background-color: #6b46c1; }
        .add-item:hover { background-color: #54309c; }

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

        .error-text { color: red; font-size: 13px; margin-top: 4px; display: none; }
        .error-field { border-color: red; }
        .total { text-align: right; font-weight: bold; font-size: 16px; margin-top: 15px; }

        /* FIELD TUNAI */
        #tunaiSection { display: none; margin-top: 15px; }
    </style>
</head>
<body>

@include('layouts.sidebar')

<div class="main">
    <div class="header">
        <h1>PEMESANAN</h1>
    </div>

    @if(session('error'))
        <div class="alert">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <form id="formPemesanan" action="{{ route('pemesanan.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Nama Pembeli</label>
            <input type="text" name="nama_pemesan" placeholder="Masukkan nama pembeli" required>
        </div>

        <div class="form-group">
            <label>Metode Pembayaran</label>
            <select name="metode_pembayaran" id="metodePembayaran" required>
                <option value="">-- Pilih Metode --</option>
                <option value="Tunai">Tunai</option>
                <option value="Transfer">Transfer</option>
                <option value="QRIS">QRIS</option>
            </select>
        </div>

        <!-- FIELD TAMBAHAN UNTUK PEMBAYARAN TUNAI -->
        <div id="tunaiSection">
            <div class="form-group">
                <label>Nominal Uang Diberikan</label>
                <input type="number" id="uangDiberikan" placeholder="Masukkan nominal uang" min="0">
                <small class="error-text" id="uangError"></small>
            </div>
            <div class="form-group">
                <label>Kembalian</label>
                <input type="text" id="kembalian" readonly>
            </div>
        </div>

        <div class="order-section">
            <h3>Daftar Pesanan</h3>
            <div id="orderContainer">
                <div class="order-item">
                    <select name="produk[]" class="produk" required>
                        <option value="">-- Pilih Produk --</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id_produk }}" data-harga="{{ $p->harga }}">{{ $p->nama_produk }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="jumlah[]" class="jumlah" min="1" placeholder="Jumlah" disabled>
                    <input type="text" name="harga[]" class="harga" readonly placeholder="Harga">
                    <input type="text" name="subtotal[]" class="subtotal" readonly placeholder="Subtotal">
                    <button type="button" class="hapus-item"><i class="fa fa-trash"></i></button>
                    <button type="button" class="add-item"><i class="fa fa-plus"></i></button>
                </div>
            </div>
        </div>

        <div class="total">
            Total: Rp <span id="totalHarga">0</span>
        </div>

        <button type="submit" class="submit-btn">Simpan Pemesanan</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('orderContainer');
    const form = document.getElementById('formPemesanan');
    const totalDisplay = document.getElementById('totalHarga');
    const metodePembayaran = document.getElementById('metodePembayaran');
    const tunaiSection = document.getElementById('tunaiSection');
    const uangDiberikan = document.getElementById('uangDiberikan');
    const kembalian = document.getElementById('kembalian');
    const uangError = document.getElementById('uangError');

    function tambahItem() {
        const newRow = document.createElement('div');
        newRow.classList.add('order-item');
        newRow.innerHTML = `
            <select name="produk[]" class="produk" required>
                <option value="">-- Pilih Produk --</option>
                @foreach($products as $p)
                    <option value="{{ $p->id_produk }}" data-harga="{{ $p->harga }}">{{ $p->nama_produk }}</option>
                @endforeach
            </select>
            <input type="number" name="jumlah[]" class="jumlah" min="1" placeholder="Jumlah" disabled>
            <input type="text" name="harga[]" class="harga" readonly placeholder="Harga">
            <input type="text" name="subtotal[]" class="subtotal" readonly placeholder="Subtotal">
            <button type="button" class="hapus-item"><i class="fa fa-trash"></i></button>
            <button type="button" class="add-item"><i class="fa fa-plus"></i></button>
        `;
        container.appendChild(newRow);
        updateHapusVisibility();
    }

    function updateHapusVisibility() {
        const items = container.querySelectorAll('.order-item');
        items.forEach(item => {
            const hapusBtn = item.querySelector('.hapus-item');
            hapusBtn.style.display = items.length === 1 ? 'none' : 'inline-block';
        });
    }

    function hitungTotal() {
        let total = 0;
        container.querySelectorAll('.order-item').forEach(row => {
            const produk = row.querySelector('.produk');
            const jumlah = parseInt(row.querySelector('.jumlah').value) || 0;
            const harga = parseInt(produk.options[produk.selectedIndex]?.dataset.harga) || 0;
            const subtotal = jumlah * harga;
            row.querySelector('.harga').value = harga ? harga.toLocaleString('id-ID') : '';
            row.querySelector('.subtotal').value = subtotal ? subtotal.toLocaleString('id-ID') : '';
            total += subtotal;
        });
        totalDisplay.innerText = total.toLocaleString('id-ID');
        if (metodePembayaran.value === 'Tunai') hitungKembalian();
    }

    function hitungKembalian() {
        const total = parseInt(totalDisplay.innerText.replace(/\./g, '')) || 0;
        const bayar = parseInt(uangDiberikan.value) || 0;
        const kembali = bayar - total;

        uangError.style.display = 'none';
        uangDiberikan.classList.remove('error-field');

        if (bayar === 0) {
            uangError.textContent = "Masukkan nominal uang terlebih dahulu.";
            uangError.style.display = 'block';
            uangDiberikan.classList.add('error-field');
            kembalian.value = "";
            return;
        }

        if (kembali < 0) {
            uangError.textContent = `Uang kurang Rp ${Math.abs(kembali).toLocaleString('id-ID')}`;
            uangError.style.display = 'block';
            uangDiberikan.classList.add('error-field');
        }

        kembalian.value = (kembali >= 0)
            ? "Rp " + kembali.toLocaleString('id-ID')
            : "";
    }

    metodePembayaran.addEventListener('change', function() {
        tunaiSection.style.display = this.value === 'Tunai' ? 'block' : 'none';
        if (this.value !== 'Tunai') {
            uangDiberikan.value = '';
            kembalian.value = '';
            uangError.style.display = 'none';
        }
    });

    uangDiberikan.addEventListener('input', hitungKembalian);

    async function cekStok(produkId, jumlah) {
        if (!produkId || !jumlah) return { status: 'ok' };
        const response = await fetch("{{ route('pemesanan.checkStok') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ produk_id: produkId, jumlah: jumlah })
        });
        return await response.json();
    }

    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('produk')) {
            const row = e.target.closest('.order-item');
            const jumlah = row.querySelector('.jumlah');
            jumlah.disabled = e.target.value === "";
            jumlah.value = "";
            hitungTotal();
        }
        if (e.target.classList.contains('jumlah')) hitungTotal();
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.add-item')) tambahItem();
        if (e.target.closest('.hapus-item')) {
            e.target.closest('.order-item').remove();
            updateHapusVisibility();
            hitungTotal();
        }
    });

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        let valid = true;
        const rows = container.querySelectorAll('.order-item');
        for (const row of rows) {
            const produk = row.querySelector('.produk').value;
            const jumlahInput = row.querySelector('.jumlah');
            const jumlah = parseInt(jumlahInput.value) || 0;

            jumlahInput.classList.remove('error-field');

            if (produk && jumlah <= 0) {
                valid = false;
                jumlahInput.classList.add('error-field');
            }

            if (produk && jumlah > 0) {
                const res = await cekStok(produk, jumlah);
                if (res.status === 'error') {
                    alert(res.message);
                    valid = false;
                    jumlahInput.classList.add('error-field');
                }
            }
        }

        if (metodePembayaran.value === 'Tunai') {
            const total = parseInt(totalDisplay.innerText.replace(/\./g, '')) || 0;
            const bayar = parseInt(uangDiberikan.value) || 0;

            uangError.style.display = 'none';
            uangDiberikan.classList.remove('error-field');

            if (!bayar || bayar < total) {
                uangError.textContent = bayar ? "Uang kurang dari total harga." : "Masukkan nominal uang terlebih dahulu.";
                uangError.style.display = 'block';
                uangDiberikan.classList.add('error-field');
                valid = false;
            }
        }

        if (!valid) return;
        this.submit();
    });

    updateHapusVisibility();
});
</script>
</body>
</html>

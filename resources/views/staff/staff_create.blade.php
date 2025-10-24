<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Staff - Warkop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; font-family: Arial, sans-serif; margin: 0; padding: 0; }
        body { min-height: 100vh; background-color: #f7f7f7; }

        /* MAIN */
        .main {
            padding: 25px 40px;
            margin-left: 220px; /* ruang untuk sidebar */
            transition: margin-left 0.3s ease-in-out;
        }

        /* HEADER */
        .header {
            background-color: #f9f9f9;
            padding: 20px 25px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .header h1 { font-size: 20px; color: #111; }

        /* FORM */
        .form-container {
            background-color: #fff;
            padding: 25px 35px;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-size: 14px; font-weight: bold; color: #333; margin-bottom: 6px; }
        input, select {
            width: 100%;
            padding: 8px 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        input:focus, select:focus { border-color: #6b46c1; outline: none; }
        .error-message { color: red; font-size: 13px; margin-top: 4px; display: none; }

        .form-footer { text-align: right; margin-top: 20px; }
        button {
            background-color: #111;
            color: #fff;
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
        }
        button:hover { background-color: #333; }

        /* RESPONSIVE UNTUK HP */
        @media (max-width: 900px) {
            .main {
                margin-left: 0; /* sidebar overlay */
                padding: 20px;
            }
            .form-container { padding: 20px; }

            /* TENGAHKAN JUDUL HEADER SAAT LAYAR KECIL */
            .header {
                flex-direction: column;
                align-items: center;
            }
            .header h1 {
                text-align: center;
                width: 100%;
            }
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    @include('layouts.sidebar')

    <!-- MAIN -->
    <div class="main">
        <div class="header">
            <h1>Tambah Staff Baru</h1>
        </div>

        <div class="form-container">
            <form id="staffForm" action="{{ route('staff.store') }}" method="POST" novalidate>
                @csrf

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" required>
                    <div class="error-message">Nama lengkap harus diisi</div>
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required>
                    <div class="error-message">Username harus diisi</div>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" minlength="6" required>
                    <div class="error-message">Password minimal 6 karakter</div>
                </div>

                <div class="form-group">
                    <label>Role</label>
                    <select name="role" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="kasir">Kasir</option>
                        <option value="admin">Admin</option>
                    </select>
                    <div class="error-message">Role harus dipilih</div>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                    <div class="error-message">Email wajib diisi dan valid</div>
                </div>

                <div class="form-group">
                    <label>Telepon</label>
                    <input type="text" name="telepon" required>
                    <div class="error-message">Nomor telepon wajib diisi</div>
                </div>

                <input type="hidden" name="status" value="aktif">

                <div class="form-footer">
                    <button type="submit"><i class="fa-solid fa-save"></i> Simpan Staff</button>
                </div>
            </form>
        </div>
    </div>

<script>
    document.getElementById('staffForm').addEventListener('submit', function(e) {
        let isValid = true;
        const form = e.target;
        const fields = form.querySelectorAll('input[required], select[required]');

        fields.forEach(field => {
            const errorMsg = field.parentElement.querySelector('.error-message');

            if (!field.value.trim()) {
                field.style.borderColor = 'red';
                if (errorMsg) errorMsg.style.display = 'block';
                isValid = false;
            } else if (field.name === 'password' && field.value.length < 6) {
                field.style.borderColor = 'red';
                if (errorMsg) errorMsg.style.display = 'block';
                isValid = false;
            } else if (field.name === 'email') {
                const emailValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value);
                if (!emailValid) {
                    field.style.borderColor = 'red';
                    if (errorMsg) errorMsg.style.display = 'block';
                    isValid = false;
                } else {
                    field.style.borderColor = '#ccc';
                    if (errorMsg) errorMsg.style.display = 'none';
                }
            } else {
                field.style.borderColor = '#ccc';
                if (errorMsg) errorMsg.style.display = 'none';
            }
        });

        if (!isValid) e.preventDefault();
    });
</script>

</body>
</html>

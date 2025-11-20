<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PT. Surya Sukses Elektronik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #044f9b;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            max-width: 900px;
            width: 95%;
            display: flex;
        }

        .login-image {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            width: 50%;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: white;
        }

        .login-form {
            width: 50%;
            padding: 3rem;
        }

        @media (max-width: 768px) {
            .login-image {
                display: none;
            }

            .login-form {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <div class="login-container">
        <div class="login-image">
            <h2 class="fw-bold mb-3">PT. Surya Sukses Elektronik</h2>
            <p class="text-white-50 lead">Sistem Informasi Manajemen Persediaan Barang yang Efisien dan Terintegrasi.
            </p>
            {{-- <ul class="list-unstyled mt-4 text-white-50">
                <li class="mb-2">✓ Monitoring Stok Real-time</li>
                <li class="mb-2">✓ Manajemen Barang Masuk & Keluar</li>
                <li class="mb-2">✓ Laporan Keuangan Otomatis</li>
            </ul> --}}
        </div>
        <div class="login-form">
            <div class="mb-4 text-center text-md-start">
                <h3 class="fw-bold text-dark">Selamat Datang</h3>
                <p class="text-muted">Silakan login untuk melanjutkan.</p>
            </div>

            <form method="POST" action="{{ route('login.process') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Username</label>
                    <input type="text" name="username"
                        class="form-control form-control-lg @error('username') is-invalid @enderror"
                        placeholder="Masukkan username" required autofocus>
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control form-control-lg"
                        placeholder="Masukkan password" required>
                </div>

                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label text-muted" for="remember">Ingat Saya</label>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 fw-semibold">Login Sistem</button>

                <div class="mt-4 text-center">
                    <small class="text-muted">&copy; 2025 PT. Surya Sukses Elektronik</small>
                </div>
            </form>
        </div>
    </div>

</body>

</html>

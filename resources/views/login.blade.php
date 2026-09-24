<!DOCTYPE html>
<html lang="id">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login - Sistem Reservasi Fasilitas</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>body{padding-top:60px}</style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">Reservasi Fasilitas</a>
        </div>
    </nav>

    <main class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1 class="mb-3">Masuk</h1>
                <div class="card p-4">
                    <form method="POST" action="/login">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input name="email" type="email" class="form-control" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input name="password" type="password" class="form-control" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Ingat saya</label>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <button class="btn btn-primary">Masuk</button>
                            <a href="/register">Belum punya akun? Daftar</a>
                        </div>
                    </form>
                </div>
                <p class="text-muted mt-3">Jika lupa password, hubungi admin.</p>
            </div>
        </div>
    </main>

</body>
</html>
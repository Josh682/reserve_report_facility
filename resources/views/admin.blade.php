<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>body{padding-top:60px}</style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="/">Admin</a>
    </div>
  </nav>

  <main class="container">
    <h1 class="mb-3">Panel Admin</h1>
    <div class="row">
      <div class="col-md-4">
        <div class="list-group">
          <a href="/admin/facilities" class="list-group-item list-group-item-action">Kelola Fasilitas</a>
          <a href="/admin/users" class="list-group-item list-group-item-action">Kelola Akun</a>
          <a href="/admin/reports" class="list-group-item list-group-item-action">Rekap Laporan</a>
        </div>
      </div>
      <div class="col-md-8">
        <div class="card p-3">
          <h5>Ringkasan</h5>
          <p>Ringkasan okupansi, frekuensi kerusakan, dan aktivitas terakhir akan tampil di sini.</p>
        </div>
      </div>
    </div>
  </main>
</body>
</html>

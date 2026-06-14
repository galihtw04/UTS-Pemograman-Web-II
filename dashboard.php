<?php
require_once "auth.php";
require_once "config/database.php";

$totalBuku = $conn->query("SELECT COUNT(*) FROM buku")->fetchColumn();
$totalPeminjaman = $conn->query("SELECT COUNT(*) FROM peminjaman")->fetchColumn();
$totalDipinjam = $conn->query("SELECT COUNT(*) FROM peminjaman WHERE status_peminjaman = 'Dipinjam'")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Sistem Peminjaman Buku</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap sesuai ketentuan UTS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="dashboard.php">Sistem Peminjaman Buku</a>

        <div class="d-flex align-items-center text-white">
            <span class="me-3">
                <?= $_SESSION['username']; ?> | <?= $_SESSION['status']; ?>
            </span>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 bg-white min-vh-100 shadow-sm p-3">
            <h6 class="text-muted">MENU</h6>

            <div class="list-group">
                <a href="dashboard.php" class="list-group-item list-group-item-action active">
                    Dashboard
                </a>
                <a href="buku.php" class="list-group-item list-group-item-action">
                    Data Buku
                </a>
                <a href="peminjaman.php" class="list-group-item list-group-item-action">
                    Data Peminjaman
                </a>
                <a href="logout.php" class="list-group-item list-group-item-action text-danger">
                    Logout
                </a>
            </div>
        </div>

        <!-- Content -->
        <div class="col-md-10 p-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h4 class="fw-bold">Selamat datang, <?= $_SESSION['username']; ?>!</h4>
                    <p class="mb-0">
                        Anda login sebagai <strong><?= $_SESSION['status']; ?></strong>.
                        Silakan kelola data buku dan data peminjaman buku melalui menu yang tersedia.
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="text-muted">Total Buku</h6>
                            <h2 class="fw-bold"><?= $totalBuku; ?></h2>
                            <a href="buku.php" class="btn btn-primary btn-sm">Kelola Buku</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="text-muted">Total Peminjaman</h6>
                            <h2 class="fw-bold"><?= $totalPeminjaman; ?></h2>
                            <a href="peminjaman.php" class="btn btn-success btn-sm">Kelola Peminjaman</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="text-muted">Buku Sedang Dipinjam</h6>
                            <h2 class="fw-bold"><?= $totalDipinjam; ?></h2>
                            <a href="peminjaman.php" class="btn btn-warning btn-sm">Lihat Data</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <h5 class="fw-bold">Deskripsi Sistem</h5>
                    <p>
                        Sistem ini digunakan untuk mengelola data buku dan data peminjaman buku.
                        Pengguna dapat menambahkan, melihat, mengubah, dan menghapus data buku maupun data peminjaman.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>

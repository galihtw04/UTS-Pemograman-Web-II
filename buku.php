<?php
require_once "auth.php";
require_once "config/database.php";

$message = "";

// Tambah data
if (isset($_POST['tambah'])) {
    $kode_buku = $_POST['kode_buku'];
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $stok = $_POST['stok'];

    $query = "INSERT INTO buku (kode_buku, judul, penulis, tahun_terbit, stok)
              VALUES (:kode_buku, :judul, :penulis, :tahun_terbit, :stok)";
    $stmt = $conn->prepare($query);
    $stmt->execute([
        ':kode_buku' => $kode_buku,
        ':judul' => $judul,
        ':penulis' => $penulis,
        ':tahun_terbit' => $tahun_terbit,
        ':stok' => $stok
    ]);

    $message = "Data buku berhasil ditambahkan.";
}

// Update data
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $kode_buku = $_POST['kode_buku'];
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $stok = $_POST['stok'];

    $query = "UPDATE buku SET 
                kode_buku = :kode_buku,
                judul = :judul,
                penulis = :penulis,
                tahun_terbit = :tahun_terbit,
                stok = :stok
              WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->execute([
        ':kode_buku' => $kode_buku,
        ':judul' => $judul,
        ':penulis' => $penulis,
        ':tahun_terbit' => $tahun_terbit,
        ':stok' => $stok,
        ':id' => $id
    ]);

    $message = "Data buku berhasil diperbarui.";
}

// Hapus data
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    $query = "DELETE FROM buku WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->execute([':id' => $id]);

    header("Location: buku.php");
    exit;
}

// Ambil data edit
$editData = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];

    $query = "SELECT * FROM buku WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->execute([':id' => $id]);
    $editData = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Ambil semua data buku
$query = "SELECT * FROM buku ORDER BY id DESC";
$stmt = $conn->query($query);
$buku = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Buku - Sistem Peminjaman Buku</title>
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
        <div class="col-md-2 bg-white min-vh-100 shadow-sm p-3">
            <h6 class="text-muted">MENU</h6>

            <div class="list-group">
                <a href="dashboard.php" class="list-group-item list-group-item-action">Dashboard</a>
                <a href="buku.php" class="list-group-item list-group-item-action active">Data Buku</a>
                <a href="peminjaman.php" class="list-group-item list-group-item-action">Data Peminjaman</a>
                <a href="logout.php" class="list-group-item list-group-item-action text-danger">Logout</a>
            </div>
        </div>

        <div class="col-md-10 p-4">
            <h3 class="fw-bold mb-3">CRUD Data Buku</h3>

            <?php if ($message != "") : ?>
                <div class="alert alert-success"><?= $message; ?></div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">
                    <?= $editData ? "Edit Data Buku" : "Tambah Data Buku"; ?>
                </div>

                <div class="card-body">
                    <form method="POST">
                        <?php if ($editData) : ?>
                            <input type="hidden" name="id" value="<?= $editData['id']; ?>">
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Kode Buku</label>
                                <input type="text" name="kode_buku" class="form-control"
                                       value="<?= $editData['kode_buku'] ?? ''; ?>" required>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Judul Buku</label>
                                <input type="text" name="judul" class="form-control"
                                       value="<?= $editData['judul'] ?? ''; ?>" required>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Penulis</label>
                                <input type="text" name="penulis" class="form-control"
                                       value="<?= $editData['penulis'] ?? ''; ?>" required>
                            </div>

                            <div class="col-md-2 mb-3">
                                <label class="form-label">Tahun Terbit</label>
                                <input type="number" name="tahun_terbit" class="form-control"
                                       value="<?= $editData['tahun_terbit'] ?? ''; ?>" required>
                            </div>

                            <div class="col-md-2 mb-3">
                                <label class="form-label">Stok</label>
                                <input type="number" name="stok" class="form-control"
                                       value="<?= $editData['stok'] ?? ''; ?>" required>
                            </div>
                        </div>

                        <?php if ($editData) : ?>
                            <button type="submit" name="update" class="btn btn-warning">Update</button>
                            <a href="buku.php" class="btn btn-secondary">Batal</a>
                        <?php else : ?>
                            <button type="submit" name="tambah" class="btn btn-primary">Tambah</button>
                            <button type="reset" class="btn btn-secondary">Reset</button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold">
                    List Data Buku
                </div>

                <div class="card-body">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>No</th>
                                <th>Kode Buku</th>
                                <th>Judul</th>
                                <th>Penulis</th>
                                <th>Tahun</th>
                                <th>Stok</th>
                                <th width="160">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (count($buku) > 0) : ?>
                                <?php $no = 1; foreach ($buku as $row) : ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $row['kode_buku']; ?></td>
                                        <td><?= $row['judul']; ?></td>
                                        <td><?= $row['penulis']; ?></td>
                                        <td><?= $row['tahun_terbit']; ?></td>
                                        <td><?= $row['stok']; ?></td>
                                        <td>
                                            <a href="buku.php?edit=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="buku.php?hapus=<?= $row['id']; ?>"
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                Hapus
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="7" class="text-center">Data buku belum tersedia.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>

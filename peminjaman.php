<?php
require_once "auth.php";
require_once "config/database.php";

$message = "";

// Ambil data buku untuk dropdown
$stmtBuku = $conn->query("SELECT * FROM buku ORDER BY judul ASC");
$listBuku = $stmtBuku->fetchAll(PDO::FETCH_ASSOC);

// Tambah data
if (isset($_POST['tambah'])) {
    $nama_peminjam = $_POST['nama_peminjam'];
    $buku_id = $_POST['buku_id'];
    $tanggal_pinjam = $_POST['tanggal_pinjam'];
    $tanggal_kembali = $_POST['tanggal_kembali'];
    $status_peminjaman = $_POST['status_peminjaman'];

    $query = "INSERT INTO peminjaman 
              (nama_peminjam, buku_id, tanggal_pinjam, tanggal_kembali, status_peminjaman)
              VALUES 
              (:nama_peminjam, :buku_id, :tanggal_pinjam, :tanggal_kembali, :status_peminjaman)";
    $stmt = $conn->prepare($query);
    $stmt->execute([
        ':nama_peminjam' => $nama_peminjam,
        ':buku_id' => $buku_id,
        ':tanggal_pinjam' => $tanggal_pinjam,
        ':tanggal_kembali' => $tanggal_kembali,
        ':status_peminjaman' => $status_peminjaman
    ]);

    $message = "Data peminjaman berhasil ditambahkan.";
}

// Update data
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama_peminjam = $_POST['nama_peminjam'];
    $buku_id = $_POST['buku_id'];
    $tanggal_pinjam = $_POST['tanggal_pinjam'];
    $tanggal_kembali = $_POST['tanggal_kembali'];
    $status_peminjaman = $_POST['status_peminjaman'];

    $query = "UPDATE peminjaman SET
                nama_peminjam = :nama_peminjam,
                buku_id = :buku_id,
                tanggal_pinjam = :tanggal_pinjam,
                tanggal_kembali = :tanggal_kembali,
                status_peminjaman = :status_peminjaman
              WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->execute([
        ':nama_peminjam' => $nama_peminjam,
        ':buku_id' => $buku_id,
        ':tanggal_pinjam' => $tanggal_pinjam,
        ':tanggal_kembali' => $tanggal_kembali,
        ':status_peminjaman' => $status_peminjaman,
        ':id' => $id
    ]);

    $message = "Data peminjaman berhasil diperbarui.";
}

// Hapus data
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    $query = "DELETE FROM peminjaman WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->execute([':id' => $id]);

    header("Location: peminjaman.php");
    exit;
}

// Ambil data edit
$editData = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];

    $query = "SELECT * FROM peminjaman WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->execute([':id' => $id]);
    $editData = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Ambil semua data peminjaman
$query = "SELECT peminjaman.*, buku.judul, buku.kode_buku
          FROM peminjaman
          JOIN buku ON peminjaman.buku_id = buku.id
          ORDER BY peminjaman.id DESC";
$stmt = $conn->query($query);
$peminjaman = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Peminjaman - Sistem Peminjaman Buku</title>
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
                <a href="buku.php" class="list-group-item list-group-item-action">Data Buku</a>
                <a href="peminjaman.php" class="list-group-item list-group-item-action active">Data Peminjaman</a>
                <a href="logout.php" class="list-group-item list-group-item-action text-danger">Logout</a>
            </div>
        </div>

        <div class="col-md-10 p-4">
            <h3 class="fw-bold mb-3">CRUD Data Peminjaman Buku</h3>

            <?php if ($message != "") : ?>
                <div class="alert alert-success"><?= $message; ?></div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">
                    <?= $editData ? "Edit Data Peminjaman" : "Tambah Data Peminjaman"; ?>
                </div>

                <div class="card-body">
                    <form method="POST">
                        <?php if ($editData) : ?>
                            <input type="hidden" name="id" value="<?= $editData['id']; ?>">
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Nama Peminjam</label>
                                <input type="text" name="nama_peminjam" class="form-control"
                                       value="<?= $editData['nama_peminjam'] ?? ''; ?>" required>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Judul Buku</label>
                                <select name="buku_id" class="form-select" required>
                                    <option value="">Pilih buku</option>
                                    <?php foreach ($listBuku as $buku) : ?>
                                        <option value="<?= $buku['id']; ?>"
                                            <?php if ($editData && $editData['buku_id'] == $buku['id']) echo "selected"; ?>>
                                            <?= $buku['kode_buku']; ?> - <?= $buku['judul']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-2 mb-3">
                                <label class="form-label">Tanggal Pinjam</label>
                                <input type="date" name="tanggal_pinjam" class="form-control"
                                       value="<?= $editData['tanggal_pinjam'] ?? ''; ?>" required>
                            </div>

                            <div class="col-md-2 mb-3">
                                <label class="form-label">Tanggal Kembali</label>
                                <input type="date" name="tanggal_kembali" class="form-control"
                                       value="<?= $editData['tanggal_kembali'] ?? ''; ?>" required>
                            </div>

                            <div class="col-md-2 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status_peminjaman" class="form-select" required>
                                    <option value="Dipinjam"
                                        <?php if ($editData && $editData['status_peminjaman'] == "Dipinjam") echo "selected"; ?>>
                                        Dipinjam
                                    </option>
                                    <option value="Dikembalikan"
                                        <?php if ($editData && $editData['status_peminjaman'] == "Dikembalikan") echo "selected"; ?>>
                                        Dikembalikan
                                    </option>
                                </select>
                            </div>
                        </div>

                        <?php if ($editData) : ?>
                            <button type="submit" name="update" class="btn btn-warning">Update</button>
                            <a href="peminjaman.php" class="btn btn-secondary">Batal</a>
                        <?php else : ?>
                            <button type="submit" name="tambah" class="btn btn-primary">Tambah</button>
                            <button type="reset" class="btn btn-secondary">Reset</button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold">
                    List Data Peminjaman
                </div>

                <div class="card-body">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>No</th>
                                <th>Nama Peminjam</th>
                                <th>Kode Buku</th>
                                <th>Judul Buku</th>
                                <th>Tgl Pinjam</th>
                                <th>Tgl Kembali</th>
                                <th>Status</th>
                                <th width="160">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (count($peminjaman) > 0) : ?>
                                <?php $no = 1; foreach ($peminjaman as $row) : ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $row['nama_peminjam']; ?></td>
                                        <td><?= $row['kode_buku']; ?></td>
                                        <td><?= $row['judul']; ?></td>
                                        <td><?= $row['tanggal_pinjam']; ?></td>
                                        <td><?= $row['tanggal_kembali']; ?></td>
                                        <td>
                                            <?php if ($row['status_peminjaman'] == "Dipinjam") : ?>
                                                <span class="badge bg-warning text-dark">Dipinjam</span>
                                            <?php else : ?>
                                                <span class="badge bg-success">Dikembalikan</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="peminjaman.php?edit=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="peminjaman.php?hapus=<?= $row['id']; ?>"
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                Hapus
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Data peminjaman belum tersedia.</td>
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

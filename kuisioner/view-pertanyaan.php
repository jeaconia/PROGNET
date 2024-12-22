<?php
session_start();
include '../config.php'; // Koneksi database

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login-admin/login.php");
    exit();
}

// Ambil data pertanyaan
$sql = "SELECT id, nama_pertanyaan, tipe_pertanyaan, is_published FROM pertanyaan";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pertanyaan</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <ul class="navbar-links">
                <li><a href="../index.php">Home</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="navbar-logo">
            <img src="../img/logo-teknologi-informasi-universitas-udayana-ti-unud-jhonarendra.png" alt="Logo" class="logo">
        </div>
    </nav>

    <h1>Kelola Pertanyaan</h1>
    <a href="add-pertanyaan.php">Tambah Pertanyaan</a>

    <!-- Tabel data pertanyaan -->
    <section class="data-pertanyaan">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Nama Pertanyaan</th>
                    <th>Tipe Pertanyaan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['nama_pertanyaan']; ?></td>
                    <td><?php echo $row['tipe_pertanyaan']; ?></td>
                    <td>
                        <?php echo $row['is_published'] ? 'Published' : 'Unpublished'; ?>
                    </td>
                    <td>
                        <a href="update-pertanyaan.php?id=<?php echo $row['id']; ?>">Edit</a>
                        <a href="delete-pertanyaan.php?id=<?php echo $row['id']; ?>">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>

    <footer id="footer">
        <div class="footer">
            <h2>Be the Next Generation</h2>
            <p>Copyright © 2024 AGS. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

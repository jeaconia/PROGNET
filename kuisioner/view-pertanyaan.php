<?php
session_start();
include '../config.php'; // Koneksi database

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login-admin/login.php");
    exit();
}

// Proses perubahan status publikasi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['publish_id'])) {
    $publish_id = (int)$_POST['publish_id'];
    $current_status = (int)$_POST['current_status'];

    // Toggle status is_published
    $new_status = $current_status ? 0 : 1;
    $update_sql = "UPDATE pertanyaan SET is_published = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    if ($stmt) {
        $stmt->bind_param("ii", $new_status, $publish_id);
        $stmt->execute();
    }

    // Redirect to refresh the page
    header("Location: " . $_SERVER['PHP_SELF']);
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

    <h1 style="text-align: center;">Kelola Pertanyaan</h1>
    <div style="text-align: center; margin-bottom: 20px;">
        <a href="add-pertanyaan.php" class="btn tambah">Tambah Pertanyaan</a>
    </div>

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
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nama_pertanyaan']); ?></td>
                        <td><?php echo htmlspecialchars($row['tipe_pertanyaan']); ?></td>
                        <td>
                            <?php echo $row['is_published'] ? 'Published' : 'Unpublished'; ?>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="update-pertanyaan.php?id=<?php echo $row['id']; ?>" class="btn edit">Edit</a>
                                <a href="delete-pertanyaan.php?id=<?php echo $row['id']; ?>" class="btn delete">Hapus</a>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="publish_id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="current_status" value="<?php echo $row['is_published']; ?>">
                                    <button type="submit" class="btn publish">
                                        <?php echo $row['is_published'] ? 'Unpublish' : 'Publish'; ?>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">Tidak ada data pertanyaan tersedia.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>

    <footer>
        <div class="footer">
            <h2>Be the Next Generation</h2>
            <p>Copyright © 2024 AGS. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

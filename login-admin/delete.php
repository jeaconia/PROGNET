<?php
session_start();
include '../config.php';

// Periksa apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
$admin_id = $_SESSION['admin_id'];
// Proses penghapusan data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $delete_sql = "DELETE FROM admin WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $admin_id);

    if ($stmt->execute()) {
        // Logout setelah akun admin dihapus
        session_destroy();
        echo "<script>alert('Akun berhasil dihapus.');</script>";
        header("Location: ../index.html");
    } else {
        echo "<script>alert('Terjadi kesalahan saat menghapus akun.');</script>";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Hapus Profil Admin</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <ul class="navbar-links">
                <li><a href="../index.html">Home</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="navbar-logo">
            <img src="../img/logo-teknologi-informasi-universitas-udayana-ti-unud-jhonarendra.png" alt="Logo" class="logo">
        </div>
    </nav>
    <h2>Hapus Profil Admin</h2>
    <p>Apakah Anda yakin ingin menghapus akun ini? Tindakan ini tidak dapat dibatalkan.</p>
    <form method="POST" action="">
        <button type="submit">Hapus Akun</button>
        <a href="profil.php">Batal</a>
    </form>
    <footer id="footer">
        <div class="footer">
            <h2>Be the Next Generation</h2>
            <p>Copyright © 2024 AGS. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
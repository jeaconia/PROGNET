<?php
session_start();
include '../config.php'; // Koneksi database

// Cek apakah pengguna sudah login sebagai admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage Admin</title>
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

    <h1>Selamat Datang di Homepage Admin</h1>
    <p>Halo, <b><?php echo htmlspecialchars($_SESSION['username']); ?></b>!</p>
    <a href="profil.php">Data Diri Admin</a><br>
    <a href="manage-users.php">Kelola Pengguna</a><br>
    <a href="manage-kuisioner.php">Kelola Kuisioner</a>

    <footer id="footer">
        <div class="footer">
            <h2>Be the Next Generation</h2>
            <p>Copyright © 2024 AGS. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
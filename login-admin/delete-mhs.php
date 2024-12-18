<?php
session_start();
include '../config.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Hapus akun dari database
    $sql = "DELETE FROM mahasiswa WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        // Hapus sesi dan redirect ke halaman login setelah akun dihapus
        session_unset();
        session_destroy();
        echo "Akun berhasil dihapus. <a href='manage-mhs.php'>Kelola Mahasiswa</a>";
    } else {
        echo "Gagal menghapus akun: " . $stmt->error;
    }

    $stmt->close();
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Account</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <h2>Hapus Akun</h2>
    <p>Apakah Anda yakin ingin menghapus akun Anda? Tindakan ini tidak dapat dibatalkan.</p>
    <form method="POST" action="">
        <button type="submit">Hapus Akun</button>
        <a href="manage-mhs.php">Batal</a>
    </form>
</body>
</html>

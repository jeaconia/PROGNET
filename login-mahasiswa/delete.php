<?php
session_start();
include '../config.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login-mahasiswa/login.php");
    exit;
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
        echo "Akun berhasil dihapus. <a href='../login-mahasiswa/login.php'>Kembali ke Login</a>";
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
</head>
<body>
    <h2>Hapus Akun</h2>
    <p>Apakah Anda yakin ingin menghapus akun Anda? Tindakan ini tidak dapat dibatalkan.</p>
    <form method="POST" action="">
        <button type="submit">Hapus Akun</button>
        <a href="../kuisioner/view-profil.php">Batal</a>
    </form>
</body>
</html>

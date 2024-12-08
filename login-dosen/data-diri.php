<?php
session_start();
include '../config.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login-dosen/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data mahasiswa dari database
$sql = "SELECT nip, nama, email, no_telp, jurusan, alamat FROM dosen WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($nip, $nama, $email, $no_telp, $jurusan, $alamat);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Profil Mahasiswa</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <h2>Profil Mahasiswa</h2>
    <p><strong>NIP:</strong> <?php echo $nip; ?></p>
    <p><strong>Nama:</strong> <?php echo $nama; ?></p>
    <p><strong>Email:</strong> <?php echo $email; ?></p>
    <p><strong>No Telepon:</strong> <?php echo $no_telp; ?></p>
    <p><strong>Jurusan:</strong> <?php echo $jurusan; ?></p>
    <p><strong>Alamat:</strong> <?php echo $alamat; ?></p>

    <a href="update.php">Edit Profil</a>
    <a href="logout.php">Log Out Profil</a>
    <a href="delete.php">Delete Profil</a>
</body>
</html>

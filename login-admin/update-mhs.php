<?php
session_start();
include '../config.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = ""; // Variabel untuk pesan sukses atau error

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form edit profil
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $no_telp = $_POST['no_telp'];
    $jurusan = $_POST['jurusan'];
    $alamat = $_POST['alamat'];
    $password = $_POST['password'];

    // Perbarui data di database
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $sql = "UPDATE mahasiswa SET nama = ?, email = ?, no_telp = ?, jurusan = ?, alamat = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $nama, $email, $no_telp, $jurusan, $alamat, $hashed_password, $user_id);
    } else {
        $sql = "UPDATE mahasiswa SET nama = ?, email = ?, no_telp = ?, jurusan = ?, alamat = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $nama, $email, $no_telp, $jurusan, $alamat, $user_id);
    }

    if ($stmt->execute()) {
        $message = "Profil berhasil diperbarui! <br> <a href='manage-mhs.php'>Kelola Mahasiswa</a>";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Ambil data mahasiswa dari database untuk form
$sql = "SELECT nim, nama, email, no_telp, jurusan, alamat FROM mahasiswa WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($nim, $nama, $email, $no_telp, $jurusan, $alamat);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profil Mahasiswa</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <ul class="navbar-links">
                <li><a href="home.php">Home</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="navbar-logo">
            <img src="../img/logo-teknologi-informasi-universitas-udayana-ti-unud-jhonarendra.png" alt="Logo" class="logo">
        </div>
    </nav>

    <div class="container-profil">
        <h2>Edit Profil Mahasiswa</h2>
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <label>NIM:</label>
            <input type="text" value="<?php echo $nim; ?>" disabled><br>

            <label>Nama:</label>
            <input type="text" name="nama" value="<?php echo $nama; ?>" required><br>

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo $email; ?>" required><br>

            <label>No Telepon:</label>
            <input type="text" name="no_telp" value="<?php echo $no_telp; ?>" required><br>

            <label>Jurusan:</label>
            <input type="text" name="jurusan" value="<?php echo $jurusan; ?>" required><br>

            <label>Alamat:</label>
            <input type="text" name="alamat" value="<?php echo $alamat; ?>" required><br>

            <label>Password Baru (opsional):</label>
            <input type="password" name="password"><br>

            <button type="submit">Simpan Perubahan</button>
        </form>
    </div>

    <footer id="footer">
        <div class="footer">
            <h2>Be the Next Generation</h2>
            <p>Copyright © 2024 AGS. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

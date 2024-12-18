<?php
include '../config.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$message = ""; // Variabel untuk menyimpan pesan

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nip = $_POST['nip'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $no_telp = $_POST['no_telp'];
    $jurusan = $_POST['jurusan'];
    $alamat = $_POST['alamat'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Menggunakan tabel 'dosen'
    $sql = "INSERT INTO dosen (nip, nama, email, no_telp, jurusan, alamat, password) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $nip, $nama, $email, $no_telp, $jurusan, $alamat, $password);

    if ($stmt->execute()) {
        $message = "Pendaftaran berhasil! <br> <a href='manage-dosen.php'>Kelola Dosen</a>";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Register Dosen</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <ul class="navbar-links">
                <li><a href="home.php">Home</a></li>
            </ul>
        </div>
        <div class="navbar-logo">
            <img src="../img/logo-teknologi-informasi-universitas-udayana-ti-unud-jhonarendra.png" alt="Logo" class="logo">
        </div>
    </nav>
    <div class="container-profil">
        <h2>Register Dosen</h2>
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <label>NIP:</label>
            <input type="text" name="nip" required><br>
            <label>Nama:</label>
            <input type="text" name="nama" required><br>
            <label>Email:</label>
            <input type="email" name="email" required><br>
            <label>No Telepon:</label>
            <input type="text" name="no_telp" required><br>
            <label>Jurusan:</label>
            <input type="text" name="jurusan" required><br>
            <label>Alamat:</label>
            <input type="text" name="alamat" required><br>
            <label>Password:</label>
            <input type="password" name="password" required><br>
            <button type="submit">Daftar</button>
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

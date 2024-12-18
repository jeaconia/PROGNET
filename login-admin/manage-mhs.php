<?php
session_start();
include '../config.php'; // Koneksi database

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Proses tambah mahasiswa
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Enkripsi password

    $sql = "INSERT INTO mahasiswa (nim, nama, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nim, $nama, $email, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Mahasiswa berhasil ditambahkan!'); window.location.href='manage-mhs.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan!');</script>";
    }

    $stmt->close();
}

// Ambil data mahasiswa
$sql = "SELECT id, nim, nama FROM mahasiswa";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Mahasiswa</title>
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

    <h1>Kelola Mahasiswa</h1>
    <a href="register-mhs.php?id=<?php echo $row['id']; ?>">Tambah Mahasiswa</a>

    <!-- Form untuk tambah mahasiswa -->
    <section class="tambah-mahasiswa">
        <h2>Tambah Mahasiswa</h2>
        <form action="" method="POST">
            <label>NIM:</label>
            <input type="text" name="nim" required><br>
            <label>Nama:</label>
            <input type="text" name="nama" required><br>
            <label>Email:</label>
            <input type="email" name="email" required><br>
            <label>Password:</label>
            <input type="password" name="password" required><br>
            <button type="submit">Tambah Mahasiswa</button>
        </form>
    </section>

    <!-- Tabel data mahasiswa -->
    <section class="data-mahasiswa">
        <h2>Data Mahasiswa</h2>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['nim']; ?></td>
                    <td><?php echo $row['nama']; ?></td>
                    <td>
                        <a href="update-mhs.php?id=<?php echo $row['id']; ?>">Edit</a>
                        <a href="delete-mhs.php?id=<?php echo $row['id']; ?>">Hapus</a>
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
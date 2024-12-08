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
$result = $stmt->get_result(); // Gunakan get_result untuk mengambil banyak data
$data = $result->fetch_assoc(); // Ambil data baris pertama
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Profil Mahasiswa</title>
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
    <h2>Profil Dosen</h2>
    <table class="styled-table">
        <thead>
            <tr>
                <th>NIP</th>
                <th>Nama</th>
                <th>Email</th>
                <th>No Telepon</th>
                <th>Jurusan</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($result as $row): ?>
            <tr>
                <td><?php echo $row['nip']; ?></td>
                <td><?php echo $row['nama']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['no_telp']; ?></td>
                <td><?php echo $row['jurusan']; ?></td>
                <td><?php echo $row['alamat']; ?></td>
                <td>
                    <a href="update.php?id=<?php echo $row['id']; ?>">
                        <button class="action-btn update-btn">Update</button>
                    </a>
                    <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Ingin menghapus data ini?')">
                        <button class="action-btn delete-btn">Delete</button>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <footer id="footer">
        <div class="footer">
            <h2>Be the Next Generation</h2>
            <p>Copyright © 2024 AGS. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

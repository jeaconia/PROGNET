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
        echo "Profil berhasil diperbarui! <a href='view-profil.php'>Lihat Profil</a>";
    } else {
        echo "Error: " . $stmt->error;
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
<html>
<head>
    <title>Update Profil Mahasiswa</title>
</head>
<body>
    <h2>Edit Profil Mahasiswa</h2>
    <form method="POST" action="">
        <label>NIM:</label><br>
        <input type="text" value="<?php echo $nim; ?>" disabled><br>

        <label>Nama:</label><br>
        <input type="text" name="nama" value="<?php echo $nama; ?>" required><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="<?php echo $email; ?>" required><br>

        <label>No Telepon:</label><br>
        <input type="text" name="no_telp" value="<?php echo $no_telp; ?>"><br>

        <label>Jurusan:</label><br>
        <input type="text" name="jurusan" value="<?php echo $jurusan; ?>"><br>

        <label>Alamat:</label><br>
        <textarea name="alamat"><?php echo $alamat; ?></textarea><br>

        <label>Password Baru (opsional):</label><br>
        <input type="password" name="password"><br>

        <button type="submit">Simpan Perubahan</button>
    </form>
</body>
</html>

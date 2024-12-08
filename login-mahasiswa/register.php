<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $no_telp = $_POST['no_telp'];
    $jurusan = $_POST['jurusan'];
    $alamat = $_POST['alamat'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Menggunakan tabel 'mahasiswa'
    $sql = "INSERT INTO mahasiswa (nim, nama, email, no_telp, jurusan, alamat, password) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $nim, $nama, $email, $no_telp, $jurusan, $alamat, $password);

    if ($stmt->execute()) {
        echo "Pendaftaran berhasil! <a href='login.php'>Login sekarang</a>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register Mahasiswa</title>
</head>
<body>
    <h2>Register Mahasiswa</h2>
    <p>Kembali Ke <a href="../index.html">Index</a></p>
    <form method="POST" action="">
        <label>NIM:</label><br>
        <input type="text" name="nim" required><br>
        <label>Nama:</label><br>
        <input type="text" name="nama" required><br>
        <label>Email:</label><br>
        <input type="email" name="email" required><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br>
        <button type="submit">Daftar</button>
        <p>Sudah Punya akun? <a href="login.php">Masuk</a></p>
    </form>
</body>
</html>

<?php
session_start();
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nim = $_POST['nim'];
    $password = $_POST['password'];

    // Menggunakan tabel 'mahasiswa' untuk login
    $sql = "SELECT id, nama, password FROM mahasiswa WHERE nim = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nim);
    $stmt->execute();
    $stmt->bind_result($id, $nama, $hashed_password);
    $stmt->fetch();

    if (password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['user_name'] = $nama;
        header("Location: ../kuisioner/hasil-kuisioner.php");
    } else {
        echo "NIM atau password salah!";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Mahasiswa</title>
</head>
<body>
    <h2>Login Mahasiswa</h2>
    <p>Kembali Ke <a href="index.html">Index</a></p>
    <form method="POST" action="">
        <label>NIM:</label><br>
        <input type="text" name="nim" required><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br>
        <button type="submit">Login</button>
        <p>Belum Punya akun? <a href="register.php">Daftar sekarang</a></p>
    </form>
</body>
</html>

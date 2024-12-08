<?php
session_start();
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nip = $_POST['nip'];
    $password = $_POST['password'];

    // Menggunakan tabel 'dosen' untuk login
    $sql = "SELECT id, nama, password FROM dosen WHERE nip = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nip);
    $stmt->execute();
    $stmt->bind_result($id, $nama, $hashed_password);
    $stmt->fetch();

    if (password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['user_name'] = $nama;
        header("Location: ../kuisioner/hasil-kuisioner.php");
    } else {
        echo "NIP atau password salah!";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Dosen</title>
</head>
<body>
    <h2>Login Dosen</h2>
    <p>Kembali Ke <a href="../index.html">Index</a></p>
    <form method="POST" action="">
        <label>NIP:</label><br>
        <input type="text" name="nip" required><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br>
        <button type="submit">Login</button>
        <p>Belum Punya akun? <a href="register.php">Daftar sekarang</a></p>
    </form>
</body>
</html>

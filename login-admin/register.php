<?php
include '../config.php';

$message = ""; // Variabel untuk menyimpan pesan

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Menggunakan tabel 'admin'
    $sql = "INSERT INTO admin (username, email, passkey) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        $message = "Pendaftaran berhasil! <br> <a href='login.php'>Login sekarang</a>";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register Admin</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <ul class="navbar-links">
                <li><a href="../index.html">Home</a></li>
            </ul>
        </div>
        <div class="navbar-logo">
            <img src="../img/logo-teknologi-informasi-universitas-udayana-ti-unud-jhonarendra.png" alt="Logo" class="logo">
        </div>
    </nav>
    <div class="container-profil">
        <h2>Register Admin</h2>
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <label>Username:</label>
            <input type="text" name="username" required><br>
            <label>Email:</label>
            <input type="email" name="email" required><br>
            <label>Password:</label>
            <input type="password" name="password" required><br>
            <button type="submit">Daftar</button>
            <p>Sudah punya akun? <a href="login.php">Masuk</a></p>
        </form>
    </div>
    <footer id="footer">
        <div class="footer">
            <h2>Be the Next Generation</h2>
            <p>Copyright &copy; 2024 AGS. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
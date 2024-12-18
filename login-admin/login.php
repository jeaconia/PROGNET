<?php
session_start();
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Sesuaikan query dengan kolom `passkey` di database
    $sql = "SELECT id, username, passkey FROM admin WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($id, $fetched_username, $hashed_passkey);
    $stmt->fetch();

    // Verifikasi password dengan hash
    if (password_verify($password, $hashed_passkey)) {
        $_SESSION['admin_id'] = $id;
        $_SESSION['admin_username'] = $fetched_username;
        header("Location: dashboard.php");
        exit;
    } else {
        echo "<script>alert('Username atau password salah!');</script>";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Admin</title>
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
    <h2>Login Admin</h2>
    <form method="POST" action="">
        <label>Username:</label>
        <input type="text" name="username" required><br>
        <label>Password:</label>
        <input type="password" name="password" required><br>
        <button type="submit">Login</button>
    </form>
    <footer id="footer">
        <div class="footer">
            <h2>Be the Next Generation</h2>
            <p>&copy; <?php echo date('Y'); ?> AGS. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

<?php
session_start();
include '../config.php';

// Periksa apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$admin_id = $_SESSION['admin_id'];

// Ambil data admin dari database
$sql = "SELECT username, email FROM admin WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

// Proses update data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = $_POST['username'];
    $new_email = $_POST['email'];
    $new_password = $_POST['password'];

    // Update data admin
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
    $update_sql = "UPDATE admin SET username = ?, email = ?, passkey = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssi", $new_username, $new_email, $hashed_password, $admin_id);
    if ($update_stmt->execute()) {
        echo "<script>alert('Profil berhasil diperbarui!');</script>";
        header("Location: profil.php");
    } else {
        echo "<script>alert('Terjadi kesalahan saat memperbarui profil.');</script>";
    }
    $update_stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Profil Admin</title>
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
    <h2>Edit Profil Admin</h2>
    <form method="POST" action="">
        <label>Username:</label>
        <input type="text" name="username" value="<?php echo $data['username']; ?>" required><br>
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo $data['email']; ?>" required><br>
        <label>Password Baru:</label>
        <input type="password" name="password" required><br>
        <button type="submit">Simpan Perubahan</button>
    </form>
    <footer id="footer">
        <div class="footer">
            <h2>Be the Next Generation</h2>
            <p>Copyright © 2024 AGS. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
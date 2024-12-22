<?php
session_start();
include '../config.php'; // Koneksi database

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login-admin/login.php");
    exit();
}

$question_id = $_GET['id']; // Ambil ID pertanyaan dari URL
$message = ""; // Variabel untuk pesan sukses atau error

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form edit pertanyaan
    $nama_pertanyaan = $_POST['nama_pertanyaan'];
    $tipe_pertanyaan = $_POST['tipe_pertanyaan'];
    $is_published = isset($_POST['is_published']) ? 1 : 0; // Checkbox untuk publish/unpublish

    // Perbarui data di database
    $sql = "UPDATE pertanyaan SET nama_pertanyaan = ?, tipe_pertanyaan = ?, is_published = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $nama_pertanyaan, $tipe_pertanyaan, $is_published, $question_id);

    if ($stmt->execute()) {
        $message = "Pertanyaan berhasil diperbarui! <br> <a href='kelola-pertanyaan.php'>Kelola Pertanyaan</a>";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Ambil data pertanyaan dari database untuk form
$sql = "SELECT nama_pertanyaan, tipe_pertanyaan, is_published FROM pertanyaan WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $question_id);
$stmt->execute();
$stmt->bind_result($nama_pertanyaan, $tipe_pertanyaan, $is_published);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pertanyaan</title>
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

    <div class="container-profil">
        <h2>Edit Pertanyaan</h2>
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <label>Nama Pertanyaan:</label>
            <input type="text" name="nama_pertanyaan" value="<?php echo $nama_pertanyaan; ?>" required><br>

            <label>Tipe Pertanyaan:</label>
            <select name="tipe_pertanyaan" required>
                <option value="dropdown" <?php echo $tipe_pertanyaan == 'dropdown' ? 'selected' : ''; ?>>Dropdown</option>
                <option value="checkbox" <?php echo $tipe_pertanyaan == 'checkbox' ? 'selected' : ''; ?>>Checkbox</option>
                <option value="radio" <?php echo $tipe_pertanyaan == 'radio' ? 'selected' : ''; ?>>Radio</option>
                <option value="textbox" <?php echo $tipe_pertanyaan == 'textbox' ? 'selected' : ''; ?>>Textbox</option>
            </select><br>

            <label>Publish:</label>
            <input type="checkbox" name="is_published" <?php echo $is_published == 1 ? 'checked' : ''; ?>><br>

            <button type="submit">Simpan Perubahan</button>
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

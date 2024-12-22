<?php
session_start();
include '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login-admin/login.php");
    exit();
}


$message = "";

// Proses publish/unpublish
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && isset($_POST['pertanyaan_id'])) {
    $pertanyaan_id = $_POST['pertanyaan_id'];
    $action = $_POST['action'];

    if ($action === 'publish') {
        $sql = "UPDATE pertanyaan SET is_published = 1 WHERE id = ?";
    } elseif ($action === 'unpublish') {
        $sql = "UPDATE pertanyaan SET is_published = 0 WHERE id = ?";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $pertanyaan_id);
    if ($stmt->execute()) {
        $message = $action === 'publish' ? "Pertanyaan berhasil dipublish." : "Pertanyaan berhasil diunpublish.";
    } else {
        $message = "Terjadi kesalahan: " . $stmt->error;
    }
    $stmt->close();
}

// Ambil semua pertanyaan
$sql = "SELECT id, nama_pertanyaan, tipe_pertanyaan, is_published FROM pertanyaan ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Kelola Pertanyaan</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <ul class="navbar-links">
                <li><a href="home.php">Home</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="navbar-logo">
            <img src="../img/logo-teknologi-informasi-universitas-udayana-ti-unud-jhonarendra.png" alt="Logo" class="logo">
        </div>
    </nav>

    <div class="container">
        <h2>Kelola Pertanyaan</h2>

        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Pertanyaan</th>
                    <th>Tipe Pertanyaan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['nama_pertanyaan']; ?></td>
                            <td><?php echo ucfirst($row['tipe_pertanyaan']); ?></td>
                            <td><?php echo $row['is_published'] ? 'Published' : 'Unpublished'; ?></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="pertanyaan_id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="action" value="<?php echo $row['is_published'] ? 'unpublish' : 'publish'; ?>">
                                    <button type="submit"><?php echo $row['is_published'] ? 'Unpublish' : 'Publish'; ?></button>
                                </form>
                                <form method="GET" action="view_question.php" style="display:inline;">
                                    <input type="hidden" name="pertanyaan_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit">View</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Tidak ada pertanyaan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <footer id="footer">
        <div class="footer">
            <h2>Be the Next Generation</h2>
            <p>&copy; <?php echo date('Y'); ?> AGS. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

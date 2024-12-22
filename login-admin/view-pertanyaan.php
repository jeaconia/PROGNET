<?php
session_start();
include '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$pertanyaan_id = $_GET['pertanyaan_id'] ?? 0;

// Ambil detail pertanyaan
$sql_pertanyaan = "SELECT * FROM pertanyaan WHERE id = ?";
$stmt = $conn->prepare($sql_pertanyaan);
$stmt->bind_param("i", $pertanyaan_id);
$stmt->execute();
$result_pertanyaan = $stmt->get_result()->fetch_assoc();

// Ambil pilihan terkait
$sql_pilihan = "SELECT pilihan FROM pilihan WHERE pertanyaan_id = ?";
$stmt_pilihan = $conn->prepare($sql_pilihan);
$stmt_pilihan->bind_param("i", $pertanyaan_id);
$stmt_pilihan->execute();
$result_pilihan = $stmt_pilihan->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Detail Pertanyaan</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <h2>Detail Pertanyaan</h2>
    <?php if ($result_pertanyaan): ?>
        <p><strong>Nama Pertanyaan:</strong> <?php echo $result_pertanyaan['nama_pertanyaan']; ?></p>
        <p><strong>Tipe Pertanyaan:</strong> <?php echo ucfirst($result_pertanyaan['tipe_pertanyaan']); ?></p>
        <p><strong>Status:</strong> <?php echo $result_pertanyaan['is_published'] ? 'Published' : 'Unpublished'; ?></p>

        <?php if ($result_pilihan->num_rows > 0): ?>
            <h3>Pilihan:</h3>
            <ul>
                <?php while ($row = $result_pilihan->fetch_assoc()): ?>
                    <li><?php echo $row['pilihan']; ?></li>
                <?php endwhile; ?>
            </ul>
        <?php endif; ?>
    <?php else: ?>
        <p>Pertanyaan tidak ditemukan.</p>
    <?php endif; ?>
</body>
</html>

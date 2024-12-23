<?php
session_start();

// Aktifkan debugging untuk melihat kesalahan
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../config.php'; // Koneksi database

// Pastikan admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login-admin/login.php");
    exit();
}

// Ambil data pertanyaan berdasarkan ID
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $pertanyaan_id = $_GET['id'];

    // Ambil data pertanyaan
    $sql_pertanyaan = "SELECT id, nama_pertanyaan, tipe_pertanyaan FROM pertanyaan WHERE id = ?";
    $stmt = $conn->prepare($sql_pertanyaan);
    $stmt->bind_param("i", $pertanyaan_id);
    $stmt->execute();
    $stmt->bind_result($id, $nama_pertanyaan, $tipe_pertanyaan);
    $stmt->fetch();
    $stmt->close();

    // Ambil data pilihan jawaban
    $sql_pilihan = "SELECT id, pilihan FROM pilihan WHERE pertanyaan_id = ?";
    $stmt_pilihan = $conn->prepare($sql_pilihan);
    $stmt_pilihan->bind_param("i", $pertanyaan_id);
    $stmt_pilihan->execute();
    $result_pilihan = $stmt_pilihan->get_result();
    $pilihan = [];
    while ($row = $result_pilihan->fetch_assoc()) {
        $pilihan[] = $row;
    }
    $stmt_pilihan->close();
} else {
    // Redirect jika ID tidak valid
    header("Location: kelola-pertanyaan.php");
    exit();
}

// Proses update pertanyaan dan pilihan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_pertanyaan = $_POST['nama_pertanyaan'];
    $tipe_pertanyaan = $_POST['tipe_pertanyaan'];
    $pilihan_baru = $_POST['pilihan'];

    // Update pertanyaan
    $sql_update = "UPDATE pertanyaan SET nama_pertanyaan = ?, tipe_pertanyaan = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssi", $nama_pertanyaan, $tipe_pertanyaan, $pertanyaan_id);
    $stmt_update->execute();
    $stmt_update->close();

    // Hapus jawaban terkait sebelum menghapus pilihan
    $sql_delete_jawaban = "DELETE FROM jawaban WHERE pilihan_id IN (SELECT id FROM pilihan WHERE pertanyaan_id = ?)";
    $stmt_delete_jawaban = $conn->prepare($sql_delete_jawaban);
    $stmt_delete_jawaban->bind_param("i", $pertanyaan_id);
    $stmt_delete_jawaban->execute();
    $stmt_delete_jawaban->close();

    // Hapus pilihan lama
    $sql_delete_pilihan = "DELETE FROM pilihan WHERE pertanyaan_id = ?";
    $stmt_delete_pilihan = $conn->prepare($sql_delete_pilihan);
    $stmt_delete_pilihan->bind_param("i", $pertanyaan_id);
    $stmt_delete_pilihan->execute();
    $stmt_delete_pilihan->close();

    // Masukkan pilihan baru
    $sql_insert_pilihan = "INSERT INTO pilihan (pertanyaan_id, pilihan) VALUES (?, ?)";
    $stmt_insert_pilihan = $conn->prepare($sql_insert_pilihan);
    foreach ($pilihan_baru as $pilihan_item) {
        if (!empty($pilihan_item)) {
            $stmt_insert_pilihan->bind_param("is", $pertanyaan_id, $pilihan_item);
            $stmt_insert_pilihan->execute();
        }
    }
    $stmt_insert_pilihan->close();

    // Redirect kembali ke halaman kelola pertanyaan
    header("Location: view-pertanyaan.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pertanyaan</title>
    <link rel="stylesheet" href="../styles.css?v=2.0">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <ul class="navbar-links">
                <li><a href="../login-admin/home.php">Home</a></li>
                <li><a href="../login-admin/logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="navbar-logo">
            <img src="../img/logo-teknologi-informasi-universitas-udayana-ti-unud-jhonarendra.png" alt="Logo" class="logo">
        </div>
    </nav>

    <div class="container">
        <h1>Edit Pertanyaan</h1>
        <form action="" method="POST">
            <div class="question-box">
                <label for="nama_pertanyaan">Nama Pertanyaan:</label>
                <input type="text" id="nama_pertanyaan" name="nama_pertanyaan" value="<?php echo htmlspecialchars($nama_pertanyaan); ?>" required>
            </div>

            <div class="question-box">
                <label for="tipe_pertanyaan">Tipe Pertanyaan:</label>
                <select id="tipe_pertanyaan" name="tipe_pertanyaan" required>
                    <option value="radio" <?php echo $tipe_pertanyaan === 'radio' ? 'selected' : ''; ?>>Radio</option>
                    <option value="checkbox" <?php echo $tipe_pertanyaan === 'checkbox' ? 'selected' : ''; ?>>Checkbox</option>
                    <option value="dropdown" <?php echo $tipe_pertanyaan === 'dropdown' ? 'selected' : ''; ?>>Dropdown</option>
                    <option value="textbox" <?php echo $tipe_pertanyaan === 'textbox' ? 'selected' : ''; ?>>Textbox</option>
                </select>
            </div>

            <div class="question-box">
                <label>Pilihan Jawaban:</label>
                <div id="pilihan-container">
                    <?php if (!empty($pilihan)): ?>
                        <?php foreach ($pilihan as $index => $item): ?>
                            <div class="pilihan-item">
                                <input type="text" name="pilihan[]" value="<?php echo htmlspecialchars($item['pilihan']); ?>">
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <div class="pilihan-item">
                        <input type="text" name="pilihan[]" placeholder="Tambahkan pilihan baru...">
                    </div>
                </div>
                <button type="button" onclick="addPilihan()">Tambah Pilihan</button>
            </div>

            <button type="submit" class="button">Simpan Perubahan</button>
        </form>
    </div>

    <script>
        function addPilihan() {
            const container = document.getElementById('pilihan-container');
            const newField = document.createElement('div');
            newField.classList.add('pilihan-item');
            newField.innerHTML = '<input type="text" name="pilihan[]" placeholder="Tambahkan pilihan baru...">';
            container.appendChild(newField);
        }
    </script>
</body>
</html>
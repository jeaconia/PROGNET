<?php
session_start();
include '../config.php'; // Database connection

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Get student data from the database
$user_id = $_SESSION['user_id'];
$sql_mahasiswa = "SELECT nim, nama FROM mahasiswa WHERE id = ?";
$stmt = $conn->prepare($sql_mahasiswa);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($nim, $nama);
$stmt->fetch();
$stmt->close();

// Get lecturer data for dropdown
$sql_dosen = "SELECT nip, nama FROM dosen";
$result_dosen = $conn->query($sql_dosen);

// Get questions for the form
$sql_pertanyaan = "SELECT id, nama_pertanyaan, tipe_pertanyaan FROM pertanyaan";
$result_pertanyaan = $conn->query($sql_pertanyaan);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Penilaian Kinerja Dosen</title>
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
    <div class="container">
        <h1>Form Penilaian Kinerja Dosen</h1>
        <form id="formPenilaian" action="simpan-kuisioner.php" method="POST">
            <!-- Data Mahasiswa -->
            <h2>Data Mahasiswa</h2>
            <p><strong>NIM:</strong> <?php echo htmlspecialchars($nim); ?></p>
            <p><strong>Nama:</strong> <?php echo htmlspecialchars($nama); ?></p>
            <input type="hidden" name="nim_mahasiswa" value="<?php echo htmlspecialchars($nim); ?>">

            <div class="question-box">
                <h2>Pilih Dosen</h2>
                <label for="nip_dosen">Pilih dosen yang ingin dinilai:</label>
                <select name="nip_dosen" id="nip_dosen" required>
                    <option value="">-- Pilih Dosen --</option>
                    <?php
                    if ($result_dosen->num_rows > 0) {
                        while ($row = $result_dosen->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($row['nip']) . "'>" . htmlspecialchars($row['nama']) . "</option>";
                        }
                    } else {
                        echo "<option value=''>Tidak ada dosen tersedia</option>";
                    }
                    ?>
                </select>
            </div>

            <?php
            if ($result_pertanyaan->num_rows > 0) {
                while ($row = $result_pertanyaan->fetch_assoc()) {
                    echo "<div class='question-box'>";
                    echo "<label>" . htmlspecialchars($row['nama_pertanyaan']) . "</label>";
                    if ($row['tipe_pertanyaan'] == 'dropdown') {
                        echo "<select name='jawaban[" . $row['id'] . "]' required>";
                        echo "<option value=''>-- Pilih --</option>";
                        // Fetch choices
                        $sql_choices = "SELECT id, pilihan FROM pilihan WHERE pertanyaan_id = ?";
                        $stmt_choices = $conn->prepare($sql_choices);
                        $stmt_choices->bind_param("i", $row['id']);
                        $stmt_choices->execute();
                        $result_choices = $stmt_choices->get_result();
                        while ($choice = $result_choices->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($choice['id']) . "'>" . htmlspecialchars($choice['pilihan']) . "</option>";
                        }
                        echo "</select>";
                    } elseif ($row['tipe_pertanyaan'] == 'textbox') {
                        echo "<textarea name='jawaban[" . $row['id'] . "]' required></textarea>";
                    } else {
                        // For 'radio' or 'checkbox'
                        $sql_choices = "SELECT id, pilihan FROM pilihan WHERE pertanyaan_id = ?";
                        $stmt_choices = $conn->prepare($sql_choices);
                        $stmt_choices->bind_param("i", $row['id']);
                        $stmt_choices->execute();
                        $result_choices = $stmt_choices->get_result();
                        while ($choice = $result_choices->fetch_assoc()) {
                            echo "<label><input type='" . ($row['tipe_pertanyaan'] == 'radio' ? 'radio' : 'checkbox') . "' name='jawaban[" . $row['id'] . "][]' value='" . htmlspecialchars($choice['id']) . "'> " . htmlspecialchars($choice['pilihan']) . "</label>";
                        }
                    }
                    echo "</div>";
                }
            }
            ?>

            <h2>Saran atau Masukan</h2>
            <div class="question-box">
                <label>10. Adakah masukan/saran terkait kinerja dosen untuk tahun ajaran berikutnya?</label>
                <textarea name="saran" rows="4" placeholder="Masukkan saran Anda di sini..." required></textarea>
            </div>

            <button type="submit" class="button">Kirim</button>
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
<?php
// Close database connection
$conn->close();
?>

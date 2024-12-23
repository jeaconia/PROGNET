<?php
session_start();
include '../config.php'; // Koneksi database

// Pastikan mahasiswa sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login-malogin.php");
    exit();
}

// Ambil data mahasiswa berdasarkan sesi login
$user_id = $_SESSION['user_id'];
$sql_mahasiswa = "SELECT nim, nama FROM mahasiswa WHERE id = ?";
$stmt = $conn->prepare($sql_mahasiswa);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($nim, $nama);
$stmt->fetch();
$stmt->close();

// Ambil daftar dosen yang belum diisi kuisionernya oleh mahasiswa
$sql_dosen = "SELECT d.nip, d.nama 
              FROM dosen d
              WHERE d.nip NOT IN (
                  SELECT k.nip_dosen
                  FROM kuisioner k
                  WHERE k.nim_mahasiswa = ?
              )";
$stmt_dosen = $conn->prepare($sql_dosen);
$stmt_dosen->bind_param("s", $nim);
$stmt_dosen->execute();
$result_dosen = $stmt_dosen->get_result();

// Ambil data pertanyaan yang dipublikasikan
$sql_pertanyaan = "SELECT id, nama_pertanyaan, tipe_pertanyaan FROM pertanyaan WHERE is_published = 1";
$result_pertanyaan = $conn->query($sql_pertanyaan);

// Jika form disubmit, validasi apakah mahasiswa sudah mengisi kuisioner untuk dosen tertentu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nip_dosen = $_POST['nip_dosen'];

    // Cek apakah mahasiswa sudah mengisi kuisioner untuk dosen ini
    $sql_check = "SELECT COUNT(*) FROM kuisioner WHERE nim_mahasiswa = ? AND nip_dosen = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ss", $nim, $nip_dosen);
    $stmt_check->execute();
    $stmt_check->bind_result($is_filled_count);
    $stmt_check->fetch();
    $stmt_check->close();

    if ($is_filled_count > 0) {
        // Jika mahasiswa sudah mengisi kuisioner untuk dosen ini
        echo "<!DOCTYPE html>
        <html>
        <head>
            <title>Kuisioner Sudah Diisi</title>
        </head>
        <body>
            <h1>Anda hanya dapat mengisi kuisioner satu kali untuk dosen ini.</h1>
            <p>Terima kasih atas partisipasi Anda.</p>
            <a href='../login-mahasiswa/home.php'>Kembali ke Home</a>
        </body>
        </html>";
        exit();
    }
}
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
                <li><a href="../login-mahasiswa/home.php">Home</a></li>
                <li><a href="../login-mahasiswa/logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <h1>Form Penilaian Kinerja Dosen</h1>
        <form id="formPenilaian" action="simpan-kuisioner.php" method="POST">
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
                        echo "<option value=''>Semua dosen sudah dinilai</option>";
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
            } else {
                echo "<p>Tidak ada pertanyaan yang tersedia.</p>";
            }
            ?>
            <button type="submit" class="button">Kirim</button>
        </form>
    </div>
</body>
</html>
<?php
$conn->close();
?>
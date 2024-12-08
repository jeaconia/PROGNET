<?php
session_start();
include '../config.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login-mahasiswa/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data mahasiswa dari database
$sql_mahasiswa = "SELECT nim, nama FROM mahasiswa WHERE id = ?";
$stmt = $conn->prepare($sql_mahasiswa);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($nim, $nama);
$stmt->fetch();
$stmt->close();

// Ambil data dosen dari database untuk dropdown
$sql_dosen = "SELECT nip, nama FROM dosen";
$result_dosen = $conn->query($sql_dosen);
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

            <!-- Form Pertanyaan -->
            <h2>Kompetensi Pengajaran</h2>
            <!-- Pertanyaan lainnya -->
            <div class="question-box">
                <label>1. Apakah dosen menyampaikan materi dengan jelas dan mudah dipahami?</label>
                <select name="materiJelas" required>
                    <option value="Ya">Ya</option>
                    <option value="Kadang">Kadang</option>
                    <option value="Tidak">Tidak</option>
                </select>
            </div>

            <div class="question-box">
                <label>2. Apakah dosen menggunakan metode pengajaran yang efektif?</label>
                <select name="metodeEfektif" required>
                    <option value="Ya">Ya</option>
                    <option value="Kadang">Kadang</option>
                    <option value="Tidak">Tidak</option>
                </select>
            </div>

            <div class="question-box">
                <label>3. Seberapa baik dosen menjawab pertanyaan dari mahasiswa?</label>
                <select name="jawabanPertanyaan" required>
                    <option value="Buruk">Buruk</option>
                    <option value="Cukup">Cukup</option>
                    <option value="Baik">Baik</option>
                    <option value="Sangat Baik">Sangat Baik</option>
                </select>
            </div>

            <div class="question-box">
                <label>4. Apakah dosen memberikan contoh nyata yang relevan dengan materi?</label>
                <select name="contohNyata" required>
                    <option value="Ya">Ya</option>
                    <option value="Kadang">Kadang</option>
                    <option value="Tidak">Tidak</option>
                </select>
            </div>

            <h2>Kehadiran dan Ketepatan Waktu</h2>

            <div class="question-box">
                <label>5. Apakah dosen selalu hadir sesuai jadwal?</label>
                <select name="kehadiranJadwal" required>
                    <option value="Ya">Ya</option>
                    <option value="Kadang">Kadang</option>
                    <option value="Tidak">Tidak</option>
                </select>
            </div>

            <div class="question-box">
                <label>6. Seberapa sering dosen memulai atau mengakhiri kelas tepat waktu?</label>
                <select name="tepatWaktu" required>
                    <option value="Sering">Sering</option>
                    <option value="Jarang">Jarang</option>
                    <option value="Tidak Sering">Tidak Sering</option>
                </select>
            </div>

            <h2>Interaksi dengan Mahasiswa</h2>

            <div class="question-box">
                <label>7. Apakah dosen terbuka untuk diskusi dan menerima masukan?</label>
                <select name="diskusiTerbuka" required>
                    <option value="Ya">Ya</option>
                    <option value="Kadang">Kadang</option>
                    <option value="Tidak">Tidak</option>
                </select>
            </div>

            <div class="question-box">
                <label>8. Seberapa nyaman Anda berdiskusi atau bertanya kepada dosen?</label>
                <select name="kenyamananDiskusi" required>
                    <option value="Sangat Nyaman">Sangat Nyaman</option>
                    <option value="Nyaman">Nyaman</option>
                    <option value="Cukup">Cukup</option>
                    <option value="Tidak Nyaman">Tidak Nyaman</option>
                </select>
            </div>

            <div class="question-box">
                <label>9. Apakah dosen menunjukkan sikap yang ramah dan profesional?</label>
                <select name="sikapProfesional" required>
                    <option value="Ya">Ya</option>
                    <option value="Kadang">Kadang</option>
                    <option value="Tidak">Tidak</option>
                </select>
            </div>

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
// Tutup koneksi database
$conn->close();
?>
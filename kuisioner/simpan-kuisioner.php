<?php
session_start();
include '../config.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login-mahasiswa/login.php");
    exit;
}

// Ambil data dari form
$nim_mahasiswa = $_POST['nim_mahasiswa'];
$nip_dosen = $_POST['nip_dosen'];
$materi_jelas = $_POST['materiJelas'];
$metode_efektif = $_POST['metodeEfektif'];
$jawaban_pertanyaan = $_POST['jawabanPertanyaan'];
$contoh_nyata = $_POST['contohNyata'];
$kehadiran_jadwal = $_POST['kehadiranJadwal'];
$tepat_waktu = $_POST['tepatWaktu'];
$diskusi_terbuka = $_POST['diskusiTerbuka'];
$kenyamanan_diskusi = $_POST['kenyamananDiskusi'];
$sikap_profesional = $_POST['sikapProfesional'];
$saran = $_POST['saran']; // Tambahkan data lainnya

// Simpan ke database
$sql = "INSERT INTO kuisioner (
    nim_mahasiswa, nip_dosen, materi_jelas, metode_efektif, jawaban_pertanyaan, contoh_nyata, kehadiran_jadwal, tepat_waktu, diskusi_terbuka, kenyamanan_diskusi, sikap_profesional, saran
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssssssss", $nim_mahasiswa, $nip_dosen, $materi_jelas, $metode_efektif, $jawaban_pertanyaan, $contoh_nyata, $kehadiran_jadwal, $tepat_waktu, $diskusi_terbuka, $kenyamanan_diskusi, $sikap_profesional, $saran);

if ($stmt->execute()) {
    echo "<script>
        alert('Data berhasil disimpan!');
        window.location.href = '../home.html';
    </script>";
} else {
    echo "<script>
        alert('Terjadi error: " . addslashes($stmt->error) . "');
        window.location.href = 'login-mahasiswa/home.html';
    </script>";
}

$stmt->close();
$conn->close();
?>

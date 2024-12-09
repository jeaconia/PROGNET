<?php
session_start();
include '../config.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login-dosen/login.php");
    exit;
}

// Query untuk mengambil data kuisioner
$sql = "SELECT materi_jelas, metode_efektif, jawaban_pertanyaan, contoh_nyata, 
        kehadiran_jadwal, tepat_waktu, diskusi_terbuka, kenyamanan_diskusi, sikap_profesional, saran 
        FROM kuisioner";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Kembalikan data sebagai JSON
header('Content-Type: application/json');
echo json_encode($data);

$conn->close();
?>

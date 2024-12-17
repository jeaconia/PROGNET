<?php
session_start();
include '../config.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login-dosen/login.php");
    exit;
}

// Query untuk mengambil data kuisioner beserta jawaban
$sql = "SELECT k.id AS kuisioner_id, k.nim_mahasiswa, k.nip_dosen, k.saran, k.created_at,
               p.nama_pertanyaan, j.jawaban_teks, pl.pilihan
        FROM jawaban j
        INNER JOIN kuisioner k ON j.kuisioner_id = k.id
        INNER JOIN pertanyaan p ON j.pertanyaan_id = p.id
        LEFT JOIN pilihan pl ON j.pilihan_id = pl.id
        ORDER BY k.created_at DESC";

$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'kuisioner_id' => $row['kuisioner_id'],
            'nim_mahasiswa' => $row['nim_mahasiswa'],
            'nip_dosen' => $row['nip_dosen'],
            'saran' => $row['saran'],
            'created_at' => $row['created_at'],
            'nama_pertanyaan' => $row['nama_pertanyaan'],
            'jawaban' => $row['jawaban_teks'] ?: $row['pilihan'] // Jawaban teks atau pilihan
        ];
    }
}

// Kembalikan data dalam format JSON
header('Content-Type: application/json');
echo json_encode($data, JSON_PRETTY_PRINT);

$conn->close();
?>

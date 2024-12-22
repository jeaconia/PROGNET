<?php
session_start();
include '../config.php'; // Koneksi database

// Ambil daftar mahasiswa yang sudah mengisi kuisioner
$sql = "SELECT k.nim_mahasiswa, m.nama AS nama_mahasiswa, k.nip_dosen, d.nama AS nama_dosen, k.created_at 
        FROM kuisioner k
        INNER JOIN mahasiswa m ON k.nim_mahasiswa = m.nim
        INNER JOIN dosen d ON k.nip_dosen = d.nip
        WHERE k.is_filled = 1";
$result = $conn->query($sql);

if (!$result) {
    die("Query error: " . $conn->error); // Debug jika query gagal
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mahasiswa yang Sudah Mengisi Kuisioner</title>
    <link rel="stylesheet" href="../styles.css"> <!-- Ganti dengan path file CSS kamu -->
</head>
<body>
    <div class="container">
        <h1>Daftar Mahasiswa yang Sudah Mengisi Kuisioner</h1>
        <table>
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Dosen</th>
                    <th>Waktu Pengisian</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nim_mahasiswa']); ?></td>
                            <td><?php echo htmlspecialchars($row['nama_mahasiswa']); ?></td>
                            <td><?php echo htmlspecialchars($row['nama_dosen']); ?></td>
                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align:center;">Belum ada mahasiswa yang mengisi kuisioner.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
session_start();
include '../config.php';

// Ambil daftar mahasiswa yang sudah mengisi kuisioner
$sql = "SELECT k.nim_mahasiswa, m.nama, k.is_filled, k.created_at 
        FROM kuisioner k
        INNER JOIN mahasiswa m ON k.nim_mahasiswa = m.nim
        WHERE k.is_filled = 1";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Mahasiswa yang Sudah Mengisi Kuisioner</title>
</head>
<body>
    <h1>Mahasiswa yang Sudah Mengisi Kuisioner</h1>
    <table border="1">
        <thead>
            <tr>
                <th>NIM</th>
                <th>Nama Mahasiswa</th>
                <th>Waktu Pengisian</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['nim_mahasiswa']; ?></td>
                        <td><?php echo $row['nama']; ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">Belum ada mahasiswa yang mengisi kuisioner.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

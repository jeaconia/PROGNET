<?php
session_start();
include '../config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login-mahasiswa/login.php");
    exit;
}

// Fetch data for results
$sql = "SELECT 
            p.nama_pertanyaan,
            p.tipe_pertanyaan,
            GROUP_CONCAT(DISTINCT pi.pilihan ORDER BY pi.id) AS pilihan,
            SUM(CASE WHEN j.pilihan_id = pi.id AND pi.pilihan = 'Ya' THEN 1 ELSE 0 END) AS ya,
            SUM(CASE WHEN j.pilihan_id = pi.id AND pi.pilihan = 'Kadang' THEN 1 ELSE 0 END) AS kadang,
            SUM(CASE WHEN j.pilihan_id = pi.id AND pi.pilihan = 'Tidak' THEN 1 ELSE 0 END) AS tidak,
            GROUP_CONCAT(j.jawaban_teks SEPARATOR ', ') AS jawaban_teks
        FROM pertanyaan p
        LEFT JOIN pilihan pi ON p.id = pi.pertanyaan_id
        LEFT JOIN jawaban j ON p.id = j.pertanyaan_id
        GROUP BY p.id";

$result = $conn->query($sql);

$data = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Kuisioner</title>
    <link rel="stylesheet" href="../styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Hasil Kuisioner</h1>
    <table border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>No</th>
                <th>Pertanyaan</th>
                <th>Jenis</th>
                <th>Pilihan</th>
                <th>Jawaban Ya</th>
                <th>Jawaban Kadang</th>
                <th>Jawaban Tidak</th>
                <th>Jawaban Lainnya</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $index => $row): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($row['nama_pertanyaan']); ?></td>
                    <td><?php echo htmlspecialchars($row['tipe_pertanyaan']); ?></td>
                    <td><?php echo htmlspecialchars($row['pilihan'] ?: '-'); ?></td>
                    <td><?php echo $row['ya'] ?: 0; ?></td>
                    <td><?php echo $row['kadang'] ?: 0; ?></td>
                    <td><?php echo $row['tidak'] ?: 0; ?></td>
                    <td><?php echo htmlspecialchars($row['jawaban_teks'] ?: '-'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <canvas id="kuisionerChart" width="400" height="200"></canvas>
    <script>
        const ctx = document.getElementById('kuisionerChart').getContext('2d');
        const data = {
            labels: <?php echo json_encode(array_column($data, 'nama_pertanyaan')); ?>,
            datasets: [
                {
                    label: 'Ya',
                    data: <?php echo json_encode(array_column($data, 'ya')); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                },
                {
                    label: 'Kadang',
                    data: <?php echo json_encode(array_column($data, 'kadang')); ?>,
                    backgroundColor: 'rgba(255, 206, 86, 0.6)',
                },
                {
                    label: 'Tidak',
                    data: <?php echo json_encode(array_column($data, 'tidak')); ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                }
            ]
        };

        new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    </script>
</body>
</html>

<?php
session_start();
include '../config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login-dosen/login.php");
    exit;
}

// Fetch data for results
$sql = "SELECT 
    p.id,
    p.nama_pertanyaan,
    p.tipe_pertanyaan,
    pi.pilihan,
    COUNT(j.pilihan_id) AS total,
    GROUP_CONCAT(j.jawaban_teks SEPARATOR ', ') AS jawaban_teks
FROM pertanyaan p
LEFT JOIN pilihan pi ON p.id = pi.pertanyaan_id
LEFT JOIN jawaban j ON p.id = j.pertanyaan_id AND (pi.id = j.pilihan_id OR j.jawaban_teks IS NOT NULL)
GROUP BY p.id, pi.id";

$result = $conn->query($sql);

$data = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pertanyaanId = $row['id'];
        if (!isset($data[$pertanyaanId])) {
            $data[$pertanyaanId] = [
                'nama_pertanyaan' => $row['nama_pertanyaan'],
                'tipe_pertanyaan' => $row['tipe_pertanyaan'],
                'pilihan' => [],
                'jawaban_teks' => $row['jawaban_teks'] ?: null
            ];
        }
        if (!empty($row['pilihan'])) {
            $data[$pertanyaanId]['pilihan'][$row['pilihan']] = $row['total'];
        }
    }
}

// Separate data by type
$visualData = array_filter($data, function ($row) {
    return in_array($row['tipe_pertanyaan'], ['radio', 'dropdown', 'checkbox']);
});
$textData = array_filter($data, function ($row) {
    return $row['tipe_pertanyaan'] === 'textbox';
});
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
    <nav class="navbar">
        <div class="navbar-container">
            <ul class="navbar-links">
                <li><a href="../login-dosen/home.php">Home</a></li>
                <li><a href="../login-dosen/logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h1>Hasil Kuisioner</h1>
        <a href="../kuisioner/view-mhs">Mahasiswa yang Mengisi</a>

        <!-- Grafik untuk Radio, Dropdown, dan Checkbox -->
        <div id="charts-container" class="chart-container">
            <?php foreach ($visualData as $id => $row): ?>
                <h3><?php echo htmlspecialchars($row['nama_pertanyaan']); ?></h3>
                <canvas id="chart-<?php echo $id; ?>" style="margin-bottom: 20px;"></canvas>
            <?php endforeach; ?>
        </div>

        <!-- Tabel untuk Jawaban Teks -->
        <?php if (!empty($textData)): ?>
            <h2>Jawaban Teks</h2>
            <?php foreach ($textData as $row): ?>
                <h3><?php echo htmlspecialchars($row['nama_pertanyaan']); ?></h3>
                <table>
                    <thead>
                        <tr>
                            <th>Jawaban</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($row['jawaban_teks'])): ?>
                            <?php foreach (explode(', ', $row['jawaban_teks']) as $jawaban): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($jawaban); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script>
        // Ambil data visual dari PHP
        const visualData = <?php echo json_encode($visualData); ?>;

        // Render setiap grafik
        Object.keys(visualData).forEach(id => {
            const row = visualData[id];
            const ctx = document.getElementById(`chart-${id}`).getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: Object.keys(row.pilihan),
                    datasets: [{
                        label: row.nama_pertanyaan,
                        data: Object.values(row.pilihan),
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                        ],
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false,
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>

    <footer id="footer">
        <div class="footer">
            <h2>Be the Next Generation</h2>
            <p>&copy; 2024 AGS. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

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
            p.id,
            p.nama_pertanyaan,
            p.tipe_pertanyaan,
            pi.pilihan,
            COUNT(j.pilihan_id) AS total,
            GROUP_CONCAT(j.jawaban_teks SEPARATOR ', ') AS jawaban_teks
        FROM pertanyaan p
        LEFT JOIN pilihan pi ON p.id = pi.pertanyaan_id
        LEFT JOIN jawaban j ON pi.id = j.pilihan_id
        GROUP BY p.id, pi.pilihan";

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
        $data[$pertanyaanId]['pilihan'][$row['pilihan']] = $row['total'];
    }
}

// Separate data by type
$radioAndDropdownData = array_filter($data, function ($row) {
    return in_array($row['tipe_pertanyaan'], ['radio', 'dropdown']);
});
$checkboxData = array_filter($data, function ($row) {
    return $row['tipe_pertanyaan'] === 'checkbox';
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
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1, h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
        .chart-container {
            margin: 20px auto;
            width: 80%;
        }
    </style>
</head>
<body>
    <h1>Hasil Kuisioner</h1>

    <!-- Grafik Jawaban Radio dan Dropdown -->
    <div id="charts-container" class="chart-container">
    <?php foreach ($radioAndDropdownData as $id => $row): ?>
        <h3><?php echo htmlspecialchars($row['nama_pertanyaan']); ?></h3>
        <canvas id="chart-<?php echo $id; ?>" style="margin-bottom: 20px;"></canvas>
    <?php endforeach; ?>
    </div>


    <!-- Tabel Checkbox -->
    <?php if (!empty($checkboxData)): ?>
        <?php foreach ($checkboxData as $row): ?>
            <h3><?php echo htmlspecialchars($row['nama_pertanyaan']); ?></h3>
            <table>
                <thead>
                    <tr>
                        <th>Pilihan</th>
                        <?php 
                        // Header pilihan
                        foreach (array_keys($row['pilihan']) as $header): ?>
                            <th><?php echo htmlspecialchars($header); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Jumlah</td>
                        <?php foreach ($row['pilihan'] as $jumlah): ?>
                            <td><?php echo $jumlah; ?></td>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
            </table>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Tidak ada data untuk pertanyaan tipe checkbox.</p>
    <?php endif; ?>

    <script>
        const radioAndDropdownData = <?php echo json_encode($radioAndDropdownData); ?>;

        Object.keys(radioAndDropdownData).forEach(id => {
            const row = radioAndDropdownData[id];
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
</body>
</html>

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
    <div class="container">
        <h1>Hasil Kuisioner Penilaian Kinerja Dosen</h1>

        <h2>Data Terkumpul</h2>
        <div id="hasilContainer"></div>

        <h2>Grafik Hasil</h2>
        <canvas id="kuisionerChart" width="400" height="200"></canvas>

        <a href="../index.html" class="back-link">Kembali ke Halaman Utama</a>
    </div>

    <script>
        // Ambil data dari localStorage
        const hasilKuisioner = JSON.parse(localStorage.getItem("hasilKuisioner")) || [];

        // Tampilkan data dalam bentuk tabel
        const container = document.getElementById("hasilContainer");
        if (hasilKuisioner.length === 0) {
            container.innerHTML = "<p>Belum ada data kuisioner yang masuk.</p>";
        } else {
            let tableHTML = `<table border="1" cellspacing="0" cellpadding="5">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Materi Jelas</th>
                        <th>Metode Efektif</th>
                        <th>Jawaban Pertanyaan</th>
                        <th>Contoh Nyata</th>
                        <th>Kehadiran</th>
                        <th>Ketepatan Waktu</th>
                        <th>Diskusi Terbuka</th>
                        <th>Kenyamanan Diskusi</th>
                        <th>Sikap Profesional</th>
                        <th>Saran</th>
                    </tr>
                </thead>
                <tbody>`;

            hasilKuisioner.forEach((data, index) => {
                tableHTML += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${data.materiJelas}</td>
                        <td>${data.metodeEfektif}</td>
                        <td>${data.jawabanPertanyaan}</td>
                        <td>${data.contohNyata}</td>
                        <td>${data.kehadiranJadwal}</td>
                        <td>${data.tepatWaktu}</td>
                        <td>${data.diskusiTerbuka}</td>
                        <td>${data.kenyamananDiskusi}</td>
                        <td>${data.sikapProfesional}</td>
                        <td>${data.saran}</td>
                    </tr>`;
            });

            tableHTML += `</tbody></table>`;
            container.innerHTML = tableHTML;
        }

        // Hitung statistik untuk grafik
        const statistik = {
            materiJelas: { Ya: 0, Kadang: 0, Tidak: 0 },
            metodeEfektif: { Ya: 0, Kadang: 0, Tidak: 0 },
            jawabanPertanyaan: { Buruk: 0, Cukup: 0, Baik: 0, "Sangat Baik": 0 }
        };

        hasilKuisioner.forEach((data) => {
            statistik.materiJelas[data.materiJelas]++;
            statistik.metodeEfektif[data.metodeEfektif]++;
            statistik.jawabanPertanyaan[data.jawabanPertanyaan]++;
        });

        // Konfigurasi grafik dengan Chart.js
        const ctx = document.getElementById("kuisionerChart").getContext("2d");
        const chart = new Chart(ctx, {
            type: "bar",
            data: {
                labels: ["Ya", "Kadang", "Tidak"],
                datasets: [
                    {
                        label: "Materi Jelas",
                        data: Object.values(statistik.materiJelas),
                        backgroundColor: "#4CAF50"
                    },
                    {
                        label: "Metode Efektif",
                        data: Object.values(statistik.metodeEfektif),
                        backgroundColor: "#FFC107"
                    },
                    {
                        label: "Jawaban Pertanyaan",
                        data: [
                            statistik.jawabanPertanyaan.Buruk || 0,
                            statistik.jawabanPertanyaan.Cukup || 0,
                            statistik.jawabanPertanyaan.Baik || 0,
                            statistik.jawabanPertanyaan["Sangat Baik"] || 0
                        ],
                        backgroundColor: "#2196F3"
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true }
                }
            }
        });
    </script>
</body>
</html>

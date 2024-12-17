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

        <h2>Grafik Per Pertanyaan</h2>
        <div id="grafikContainer">
            <canvas id="materiJelasChart" width="400" height="200"></canvas>
            <canvas id="metodeEfektifChart" width="400" height="200"></canvas>
            <canvas id="jawabanPertanyaanChart" width="400" height="200"></canvas>
            <canvas id="contohNyata" width="400" height="200"></canvas>
            <canvas id="kehadiran" width="400" height="200"></canvas>
            <canvas id="ketepatanWaktu" width="400" height="200"></canvas>
            <canvas id="diskusiTerbuka" width="400" height="200"></canvas>
            <canvas id="kenyamananDiskusi" width="400" height="200"></canvas>
            <canvas id="sikapProfesional" width="400" height="200"></canvas>
        </div>


        <a href="../index.html" class="back-link">Kembali ke Halaman Utama</a>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            fetch("getkuisioner.php")
                .then(response => response.json())
                .then(data => {
                    // Tampilkan data dalam tabel
                    const container = document.getElementById("hasilContainer");
                    if (data.length === 0) {
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

                        data.forEach((item, index) => {
                            tableHTML += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${item.materi_jelas}</td>
                                    <td>${item.metode_efektif}</td>
                                    <td>${item.jawaban_pertanyaan}</td>
                                    <td>${item.contoh_nyata}</td>
                                    <td>${item.kehadiran_jadwal}</td>
                                    <td>${item.tepat_waktu}</td>
                                    <td>${item.diskusi_terbuka}</td>
                                    <td>${item.kenyamanan_diskusi}</td>
                                    <td>${item.sikap_profesional}</td>
                                    <td>${item.saran}</td>
                                </tr>`;
                        });

                        tableHTML += `</tbody></table>`;
                        container.innerHTML = tableHTML;
                    }

                    // Hitung statistik untuk grafik per kategori
                    const statistik = {
                        materiJelas: { Ya: 0, Kadang: 0, Tidak: 0 },
                        metodeEfektif: { Ya: 0, Kadang: 0, Tidak: 0 },
                        jawabanPertanyaan: { Buruk: 0, Cukup: 0, Baik: 0, "Sangat Baik": 0 },
                        contohNyata: { Ya: 0, Kadang: 0, Tidak: 0 },
                        kehadiran: { Ya: 0, Kadang: 0, Tidak: 0 },
                        ketepatanWaktu: { Sering: 0, Jarang: 0, "Tidak Sering": 0 },
                        diskusiTerbuka: { Ya: 0, Kadang: 0, Tidak: 0 },
                        kenyamananDiskusi: { "Sangat Nyaman": 0, Nyaman: 0, Cukup: 0, "Tidak Nyaman": 0 },
                        sikapProfesional: { Ya: 0, Kadang: 0, Tidak: 0 }
                    };

                    data.forEach((item) => {
                        statistik.materiJelas[item.materi_jelas]++;
                        statistik.metodeEfektif[item.metode_efektif]++;
                        statistik.jawabanPertanyaan[item.jawaban_pertanyaan]++;
                        statistik.contohNyata[item.contoh_nyata]++;
                        statistik.kehadiran[item.kehadiran_jadwal]++;
                        statistik.ketepatanWaktu[item.tepat_waktu]++;
                        statistik.diskusiTerbuka[item.diskusi_terbuka]++;
                        statistik.kenyamananDiskusi[item.kenyamanan_diskusi]++;
                        statistik.sikapProfesional[item.sikap_profesional]++;
                    });

                    // Fungsi untuk membuat grafik
                    const createChart = (ctxId, label, data, colors) => {
                        const ctx = document.getElementById(ctxId).getContext("2d");
                        new Chart(ctx, {
                            type: "bar",
                            data: {
                                labels: Object.keys(data),
                                datasets: [{
                                    label: label,
                                    data: Object.values(data),
                                    backgroundColor: colors
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: { display: true }
                                }
                            }
                        });
                    };

                    // Buat grafik untuk setiap pertanyaan
                    createChart("materiJelasChart", "Materi Jelas", statistik.materiJelas, ["#4CAF50", "#FFC107", "#F44336"]);
                    createChart("metodeEfektifChart", "Metode Efektif", statistik.metodeEfektif, ["#2196F3", "#FFC107", "#F44336"]);
                    createChart("jawabanPertanyaanChart", "Jawaban Pertanyaan", statistik.jawabanPertanyaan, ["#4CAF50", "#FFC107", "#F44336", "#2196F3"]);
                    createChart("contohNyata", "Contoh Nyata", statistik.contohNyata, ["#4CAF50", "#FFC107", "#F44336"]);
                    createChart("kehadiran", "Kehadiran", statistik.kehadiran, ["#4CAF50", "#FFC107", "#F44336"]);
                    createChart("ketepatanWaktu", "Tepat Waktu", statistik.ketepatanWaktu, ["#4CAF50", "#FFC107", "#F44336"]);
                    createChart("diskusiTerbuka", "Diskusi Terbuka", statistik.diskusiTerbuka, ["#4CAF50", "#FFC107", "#F44336"]);
                    createChart("kenyamananDiskusi", "Kenyamanan Diskusi", statistik.kenyamananDiskusi, ["#4CAF50", "#FFC107", "#F44336", "#2196F3"]);
                    createChart("sikapProfesional", "Sikap Profesional", statistik.sikapProfesional, ["#4CAF50", "#FFC107", "#F44336"]);
                })
                .catch(error => console.error("Error fetching data:", error));
        });
    </script>
</body>
</html>

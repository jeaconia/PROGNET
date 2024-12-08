<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Penilaian Kinerja Dosen</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="container">
        <h1>Form Penilaian Kinerja Dosen</h1>
        <form id="formPenilaian">
            <h2>Kompetensi Pengajaran</h2>
            <label>1. Apakah dosen menyampaikan materi dengan jelas dan mudah dipahami?</label>
            <select name="materiJelas" required>
                <option value="Ya">Ya</option>
                <option value="Kadang">Kadang</option>
                <option value="Tidak">Tidak</option>
            </select>

            <label>2. Apakah dosen menggunakan metode pengajaran yang efektif?</label>
            <select name="metodeEfektif" required>
                <option value="Ya">Ya</option>
                <option value="Kadang">Kadang</option>
                <option value="Tidak">Tidak</option>
            </select>

            <label>3. Seberapa baik dosen menjawab pertanyaan dari mahasiswa?</label>
            <select name="jawabanPertanyaan" required>
                <option value="Buruk">Buruk</option>
                <option value="Cukup">Cukup</option>
                <option value="Baik">Baik</option>
                <option value="Sangat Baik">Sangat Baik</option>
            </select>

            <label>4. Apakah dosen memberikan contoh nyata yang relevan dengan materi?</label>
            <select name="contohNyata" required>
                <option value="Ya">Ya</option>
                <option value="Kadang">Kadang</option>
                <option value="Tidak">Tidak</option>
            </select>

            <h2>Kehadiran dan Ketepatan Waktu</h2>
            <label>5. Apakah dosen selalu hadir sesuai jadwal?</label>
            <select name="kehadiranJadwal" required>
                <option value="Ya">Ya</option>
                <option value="Kadang">Kadang</option>
                <option value="Tidak">Tidak</option>
            </select>

            <label>6. Seberapa sering dosen memulai atau mengakhiri kelas tepat waktu?</label>
            <select name="tepatWaktu" required>
                <option value="Sering">Sering</option>
                <option value="Jarang">Jarang</option>
                <option value="Tidak Sering">Tidak Sering</option>
            </select>

            <h2>Interaksi dengan Mahasiswa</h2>
            <label>7. Apakah dosen terbuka untuk diskusi dan menerima masukan?</label>
            <select name="diskusiTerbuka" required>
                <option value="Ya">Ya</option>
                <option value="Kadang">Kadang</option>
                <option value="Tidak">Tidak</option>
            </select>

            <label>8. Seberapa nyaman Anda berdiskusi atau bertanya kepada dosen?</label>
            <select name="kenyamananDiskusi" required>
                <option value="Sangat Nyaman">Sangat Nyaman</option>
                <option value="Nyaman">Nyaman</option>
                <option value="Cukup">Cukup</option>
                <option value="Tidak Nyaman">Tidak Nyaman</option>
            </select>

            <label>9. Apakah dosen menunjukkan sikap yang ramah dan profesional?</label>
            <select name="sikapProfesional" required>
                <option value="Ya">Ya</option>
                <option value="Kadang">Kadang</option>
                <option value="Tidak">Tidak</option>
            </select>

            <h2>Saran atau Masukan</h2>
            <label>10. Adakah masukan/saran terkait kinerja dosen untuk tahun ajaran berikutnya?</label>
            <textarea name="saran" rows="4" placeholder="Masukkan saran Anda di sini..." required></textarea>

            <button type="submit" class="button">Kirim</button>
        </form>
        <a href="../index.html" class="back-link">Kembali ke Index</a>
    </div>

    <script>
        document.getElementById("formPenilaian").onsubmit = (e) => {
            e.preventDefault();
            alert("Terima kasih atas partisipasi Anda!");
            window.location.href = "../index.html";
        };
    </script>
</body>
</html>

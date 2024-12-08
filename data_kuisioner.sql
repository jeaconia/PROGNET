CREATE TABLE kuisioner (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim_mahasiswa VARCHAR(10) NOT NULL,
    nip_dosen VARCHAR(18) NOT NULL,
    materi_jelas ENUM('Ya', 'Kadang', 'Tidak') NOT NULL,
    metode_efektif ENUM('Ya', 'Kadang', 'Tidak') NOT NULL,
    jawaban_pertanyaan ENUM('Buruk', 'Cukup', 'Baik', 'Sangat Baik') NOT NULL,
    contoh_nyata ENUM('Ya', 'Kadang', 'Tidak') NOT NULL,
    kehadiran_jadwal ENUM('Ya', 'Kadang', 'Tidak') NOT NULL,
    tepat_waktu ENUM('Sering', 'Jarang', 'Tidak Sering') NOT NULL,
    diskusi_terbuka ENUM('Ya', 'Kadang', 'Tidak') NOT NULL,
    kenyamanan_diskusi ENUM('Sangat Nyaman', 'Nyaman', 'Cukup', 'Tidak Nyaman') NOT NULL,
    sikap_profesional ENUM('Ya', 'Kadang', 'Tidak') NOT NULL,
    saran TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (nim_mahasiswa) REFERENCES mahasiswa(nim),
    FOREIGN KEY (nip_dosen) REFERENCES dosen(nip)
);

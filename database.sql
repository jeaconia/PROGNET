CREATE TABLE kuisioner (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim_mahasiswa VARCHAR(10) NOT NULL,
    nip_dosen VARCHAR(18) NOT NULL,
    saran TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (nim_mahasiswa) REFERENCES mahasiswa(nim),
    FOREIGN KEY (nip_dosen) REFERENCES dosen(nip)
);

CREATE TABLE pertanyaan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_pertanyaan VARCHAR(255) NOT NULL,
    tipe_pertanyaan ENUM('dropdown', 'checkbox', 'radio', 'textbox') NOT NULL
);

-- Tambahkan kolom publish/unpublish ke tabel pertanyaan
ALTER TABLE pertanyaan
ADD COLUMN is_published TINYINT(1) DEFAULT 0;

CREATE TABLE pilihan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pertanyaan_id INT NOT NULL,
    pilihan VARCHAR(255) NOT NULL,
    FOREIGN KEY (pertanyaan_id) REFERENCES pertanyaan(id)
);

ALTER TABLE pilihan
MODIFY COLUMN pilihan VARCHAR(255) NULL;


CREATE TABLE jawaban (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kuisioner_id INT NOT NULL,
    pertanyaan_id INT NOT NULL,
    pilihan_id INT DEFAULT NULL, -- Untuk dropdown, checkbox, dan radio
    jawaban_teks TEXT DEFAULT NULL, -- Untuk textbox
    FOREIGN KEY (kuisioner_id) REFERENCES kuisioner(id),
    FOREIGN KEY (pertanyaan_id) REFERENCES pertanyaan(id),
    FOREIGN KEY (pilihan_id) REFERENCES pilihan(id)
);

ALTER TABLE jawaban
DROP FOREIGN KEY jawaban_ibfk_2;

ALTER TABLE jawaban
ADD CONSTRAINT jawaban_ibfk_2 FOREIGN KEY (pertanyaan_id) REFERENCES pertanyaan(id) ON DELETE CASCADE;

ALTER TABLE kuisioner
ADD COLUMN is_filled TINYINT(1) DEFAULT 0; -- 0 = belum mengisi, 1 = sudah mengisi

ALTER TABLE jawaban
ADD COLUMN nip_dosen VARCHAR(18) NOT NULL AFTER pertanyaan_id;

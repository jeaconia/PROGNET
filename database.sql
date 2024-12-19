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

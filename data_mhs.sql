CREATE DATABASE db_kuisioner;
USE db_kuisioner;

CREATE TABLE mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    no_telp VARCHAR(15),
    jurusan VARCHAR(100),
    alamat TEXT,
    password VARCHAR(255) NOT NULL
);

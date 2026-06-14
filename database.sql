CREATE DATABASE IF NOT EXISTS uts_web2;
USE uts_web2;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    status ENUM('Admin', 'User') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT IGNORE INTO users (username, password, status) VALUES
('admin', 'admin123', 'Admin'),
('user', 'user123', 'User');

USE uts_web2;

CREATE TABLE IF NOT EXISTS buku (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_buku VARCHAR(50) NOT NULL UNIQUE,
    judul VARCHAR(150) NOT NULL,
    penulis VARCHAR(100) NOT NULL,
    tahun_terbit YEAR NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS peminjaman (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_peminjam VARCHAR(100) NOT NULL,
    buku_id INT NOT NULL,
    tanggal_pinjam DATE NOT NULL,
    tanggal_kembali DATE NOT NULL,
    status_peminjaman ENUM('Dipinjam', 'Dikembalikan') NOT NULL DEFAULT 'Dipinjam',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (buku_id) REFERENCES buku(id) ON DELETE CASCADE
);

INSERT INTO buku (kode_buku, judul, penulis, tahun_terbit, stok) VALUES
('BK001', 'Pemrograman Web Dasar', 'Andi Nugroho', 2023, 5),
('BK002', 'Basis Data MySQL', 'Rina Safitri', 2022, 3),
('BK003', 'Algoritma dan Struktur Data', 'Budi Santoso', 2021, 4);

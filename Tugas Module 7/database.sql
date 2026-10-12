-- database.sql
-- Skema basis data untuk modul buku tamu digital perpustakaan (Tugas Modul 7)
-- Cara pakai: import file ini lewat phpMyAdmin, atau jalankan: mysql -u root -p < database.sql

CREATE DATABASE IF NOT EXISTS db_perpustakaan
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE db_perpustakaan;

-- Tabel utama buku tamu, kolomnya persis sesuai instruksi tugas
CREATE TABLE IF NOT EXISTS buku_tamu (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nama          VARCHAR(100) NOT NULL,
  email         VARCHAR(150) NOT NULL,
  pesan         TEXT         NOT NULL,
  tanggal_kirim DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP, -- diisi otomatis oleh MySQL
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

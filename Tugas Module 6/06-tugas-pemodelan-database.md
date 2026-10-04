# Tugas Mandiri Module 6 - Perancangan ERD E-Library Kampus

**Nama:** Efraim Imanuel Parasak
**NIM:** D121241062
**Mata Kuliah:** Pemrograman Web
**Program Studi:** Teknik Informatika, Universitas Hasanuddin

---

## 1. Skenario

Perpustakaan kampus ingin mengganti pencatatan peminjaman buku yang selama ini manual dengan sistem basis data relasional. Sistem harus bisa mencatat:

- data **mahasiswa** yang meminjam,
- data **buku** yang tersedia,
- data **penerbit** dari tiap buku,
- **riwayat peminjaman dan pengembalian** buku.

Dokumen ini berisi rancangan ERD logis, identifikasi atribut beserta kuncinya, simulasi normalisasi (UNF → 1NF → 2NF → 3NF), rancangan tabel akhir, dan diagram relasi.

### Asumsi yang saya pakai

1. Satu baris transaksi peminjaman mewakili **satu mahasiswa meminjam satu judul buku** pada satu waktu. Kalau meminjam dua buku, tercatat dua transaksi.
2. Satu buku diterbitkan oleh **satu penerbit**, sedangkan satu penerbit bisa menerbitkan banyak buku.
3. Lama peminjaman standar adalah 7 hari. Keterlambatan dikenai denda per hari.
4. Pengarang saya simpan sebagai satu kolom teks di tabel `buku` supaya rancangan tetap sederhana dan sesuai empat entitas yang diminta.

---
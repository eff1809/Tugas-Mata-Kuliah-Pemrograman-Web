# Tugas Mandiri Module 6 - Perancangan ERD E-Library Kampus

**Nama:** Efraim Imanuel Parasak
**NIM:** D121241062

---

## 1. Desain ERD Logis

Berdasarkan skenario sistem peminjaman buku perpustakaan kampus, terdapat 4 entitas utama. Berikut adalah identifikasi atribut beserta *Primary Key* (PK) dan *Foreign Key* (FK)-nya:

1. **Mahasiswa**
   - Menyimpan data profil mahasiswa yang terdaftar di perpustakaan.
   - Atribut: `nim` (PK), `nama_mahasiswa`, `program_studi`, `angkatan`, `email_kampus`

2. **Penerbit**
   - Menyimpan data pihak yang menerbitkan buku.
   - Atribut: `id_penerbit` (PK), `nama_penerbit`, `kota_penerbit`, `kontak_penerbit`

3. **Buku**
   - Menyimpan data katalog buku yang tersedia di perpustakaan.
   - Atribut: `id_buku` (PK), `judul_buku`, `penulis`, `tahun_terbit`, `kategori`, `id_penerbit` (FK)

4. **Transaksi Peminjaman**
   - Mencatat setiap riwayat peminjaman buku oleh mahasiswa.
   - Atribut: `id_transaksi` (PK), `nim` (FK), `id_buku` (FK), `tanggal_pinjam`, `tanggal_tenggat`, `tanggal_kembali`, `status`

---
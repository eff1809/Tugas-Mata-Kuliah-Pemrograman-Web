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

## 2. Identifikasi Entitas dan Atribut

### 2.1 Penerbit
| Atribut | Keterangan | Kunci |
|---|---|---|
| id_penerbit | Identitas unik penerbit | **PK** |
| nama_penerbit | Nama penerbit | |
| alamat | Alamat penerbit | |
| kota | Kota penerbit | |
| telepon | Nomor telepon penerbit | |

### 2.2 Mahasiswa
| Atribut | Keterangan | Kunci |
|---|---|---|
| nim | Nomor induk mahasiswa | **PK** |
| nama | Nama lengkap | |
| program_studi | Program studi | |
| angkatan | Tahun masuk | |
| email | Email mahasiswa | |
| no_telepon | Nomor telepon | |

### 2.3 Buku
| Atribut | Keterangan | Kunci |
|---|---|---|
| id_buku | Identitas unik buku | **PK** |
| isbn | Nomor ISBN | (unik) |
| judul | Judul buku | |
| pengarang | Nama pengarang | |
| tahun_terbit | Tahun terbit | |
| stok | Jumlah eksemplar tersedia | |
| id_penerbit | Penerbit buku | **FK** → penerbit |

### 2.4 Peminjaman (Transaksi Peminjaman)
| Atribut | Keterangan | Kunci |
|---|---|---|
| id_peminjaman | Identitas unik transaksi | **PK** |
| nim | Mahasiswa yang meminjam | **FK** → mahasiswa |
| id_buku | Buku yang dipinjam | **FK** → buku |
| tanggal_pinjam | Tanggal buku dipinjam | |
| tanggal_jatuh_tempo | Batas pengembalian | |
| tanggal_kembali | Tanggal buku dikembalikan (kosong jika belum) | |
| status | `dipinjam` / `dikembalikan` / `terlambat` | |
| denda | Denda keterlambatan (Rp) | |

### 2.5 Kardinalitas Relasi

| Relasi | Kardinalitas | Penjelasan |
|---|---|---|
| Penerbit – Buku | 1 : N | Satu penerbit menerbitkan banyak buku, satu buku punya satu penerbit |
| Mahasiswa – Peminjaman | 1 : N | Satu mahasiswa bisa melakukan banyak peminjaman |
| Buku – Peminjaman | 1 : N | Satu buku bisa dipinjam berkali-kali (di waktu berbeda) |

Secara konsep, Mahasiswa dan Buku berhubungan **many-to-many**, dan tabel `peminjaman` berperan sebagai tabel penghubung sekaligus menyimpan atribut transaksinya.

---
<?php
declare(strict_types=1);

/**
 * GuestBook.php
 * Kelas buat ngurus data buku tamu perpustakaan: validasi, simpan, dan ambil data.
 * Semua akses ke database lewat PDO + prepared statements, jadi aman dari SQL injection.
 */
class GuestBook
{
    // Batas-batas panjang input, saya samain dengan ukuran kolom di tabel buku_tamu
    public const NAMA_MAKS  = 100;
    public const EMAIL_MAKS = 150;
    public const PESAN_MIN  = 5;     // sesuai instruksi: pesan minimal lima karakter
    public const PESAN_MAKS = 1000;

    private PDO $db;

    // Koneksi PDO-nya dikirim dari luar (dependency injection), jadi kelas ini nggak perlu tahu password database
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Cek isian form. Kalau ada yang salah, hasilnya berupa array pesan error per kolom.
     * Kalau semuanya beres, array-nya kosong.
     */
    public function validasi(string $nama, string $email, string $pesan): array
    {
        $errors = [];

        $nama  = trim($nama);
        $email = trim($email);
        $pesan = trim($pesan);

        // Nama: nggak boleh kosong
        if ($nama === '') {
            $errors['nama'] = 'Nama tidak boleh kosong.';
        } elseif (mb_strlen($nama) > self::NAMA_MAKS) {
            $errors['nama'] = 'Nama maksimal ' . self::NAMA_MAKS . ' karakter.';
        }

        // Email: formatnya harus valid
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Format email belum valid, contoh: nama@email.com.';
        } elseif (mb_strlen($email) > self::EMAIL_MAKS) {
            $errors['email'] = 'Email maksimal ' . self::EMAIL_MAKS . ' karakter.';
        }

        // Pesan: minimal 5 karakter (pakai mb_strlen biar huruf non-ASCII dihitung benar)
        if (mb_strlen($pesan) < self::PESAN_MIN) {
            $errors['pesan'] = 'Pesan minimal ' . self::PESAN_MIN . ' karakter.';
        } elseif (mb_strlen($pesan) > self::PESAN_MAKS) {
            $errors['pesan'] = 'Pesan maksimal ' . self::PESAN_MAKS . ' karakter.';
        }

        return $errors;
    }

    /**
     * Simpan satu pesan baru ke tabel buku_tamu.
     * Di sini saya validasi sekali lagi, jaga-jaga kalau ada yang manggil tanpa lewat halaman form.
     */
    public function simpan(string $nama, string $email, string $pesan): bool
    {
        $nama  = trim($nama);
        $email = trim($email);
        $pesan = trim($pesan);

        if ($this->validasi($nama, $email, $pesan) !== []) {
            throw new InvalidArgumentException('Data buku tamu tidak valid.');
        }

        // Prepared statement: nilai dari user dikirim terpisah dari perintah SQL-nya
        $sql  = 'INSERT INTO buku_tamu (nama, email, pesan) VALUES (:nama, :email, :pesan)';
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':nama'  => $nama,
            ':email' => $email,
            ':pesan' => $pesan,
        ]);
    }

    /**
     * Ambil daftar pesan, yang terbaru di atas.
     * Batasnya juga saya kirim lewat parameter (bukan disambung ke string SQL).
     */
    public function ambilSemua(int $batas = 50): array
    {
        $sql  = 'SELECT id, nama, email, pesan, tanggal_kirim
                 FROM buku_tamu
                 ORDER BY tanggal_kirim DESC, id DESC
                 LIMIT :batas';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':batas', max(1, $batas), PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}

<?php
// Wajib ditaruh paling atas untuk mengaktifkan tipe data ketat (strict types)
declare(strict_types=1);

class Transaction {
    // Implementasi constructor property promotion (properti otomatis dibuat dari parameter)
    public function __construct(
        private string $id,
        private string $type,
        private float $amount
    ) {}

    // Metode process menerima reference (&$) saldo sesi agar bisa langsung mengubahnya
    public function process(float &$sessionBalance): bool|string {
        // Menggunakan ekspresi match untuk memproses berdasarkan jenis transaksi
        return match($this->type) {
            'deposit' => $this->handleDeposit($sessionBalance),
            'withdraw' => $this->handleWithdraw($sessionBalance),
            default => 'Gagal: Jenis transaksi tidak valid.'
        };
    }

    private function handleDeposit(float &$balance): bool {
        $balance += $this->amount;
        return true;
    }

    private function handleWithdraw(float &$balance): bool|string {
        if ($this->amount > $balance) {
            return 'Gagal: Saldo tidak mencukupi untuk melakukan penarikan.';
        }
        $balance -= $this->amount;
        return true;
    }

    // Getters untuk mengambil data private (Enkapsulasi)
    public function getId(): string { return $this->id; }
    public function getType(): string { return $this->type; }
    public function getAmount(): float { return $this->amount; }
}
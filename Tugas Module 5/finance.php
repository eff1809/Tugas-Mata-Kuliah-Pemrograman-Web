<?php
declare(strict_types=1);
session_start();
require_once 'Transaction.php';

// Inisialisasi saldo dan riwayat transaksi di session jika belum ada
if (!isset($_SESSION['balance'])) {
    $_SESSION['balance'] = 0.0;
}
if (!isset($_SESSION['history'])) {
    $_SESSION['history'] = [];
}

// Generate token CSRF untuk pertahanan form keamanan
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message = '';
$status = '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Keuangan</title>
    <style>
        body { font-family: system-ui, sans-serif; background: #f4f6f8; padding: 20px; color: #333; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .balance { font-size: 1.5rem; color: #0b3d63; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Sistem Keuangan Sederhana</h2>
        <p>Saldo Saat Ini: <span class="balance">Rp <?= number_format($_SESSION['balance'], 2, ',', '.') ?></span></p>
        <p><em>Form transaksi akan diintegrasikan pada tahap selanjutnya.</em></p>
    </div>
</body>
</html>
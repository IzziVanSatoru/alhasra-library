<?php
session_start();

// Cek jika pengguna belum login, arahkan kembali ke index.php
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// Cek apakah daftar buku sudah ada
$book_list = isset($_SESSION['book_list']) ? $_SESSION['book_list'] : [];

// Cek durasi pinjaman
$loan_duration = isset($_SESSION['loan_duration']) ? $_SESSION['loan_duration'] : "Belum dipilih";

// Hitung biaya jika durasi dan buku tersedia
if (!empty($book_list) && is_numeric($loan_duration)) {
    $cost_per_day = 5000; // Biaya per hari
    $total_cost = $loan_duration * $cost_per_day;
} else {
    $total_cost = 0;
    $loan_duration = "Belum dipilih";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reward - Al-Hasra Library</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #e0e7ff, #cfe1e8);
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        footer {
            background-color: #ffffff;
            color: #333;
            text-align: center;
            padding: 10px;
            box-shadow: 0 -2px 6px rgba(0, 0, 0, 0.1);
            margin-top: auto;
        }

        .content {
            flex: 1;
            padding: 20px;
            text-align: center;
        }

        .reward-box {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .reward-box h2 {
            margin-bottom: 15px;
            font-size: 1.5rem;
            color: #333;
        }

        .reward-box p {
            font-size: 1rem;
            color: #555;
            margin-bottom: 10px;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<header>
    <h1>Reward Anda</h1>
</header>
<div class="content">
    <div class="reward-box">
        <?php if (empty($book_list)): ?>
            <p>Anda belum menambahkan buku ke daftar.</p>
        <?php else: ?>
            <h2>Durasi Pinjaman: <?= htmlspecialchars($loan_duration); ?> Hari</h2>
            <p>Biaya Pinjaman: Rp <?= number_format($total_cost, 0, ',', '.'); ?></p>
            <p>Jumlah Buku: <?= count($book_list); ?></p>
            <p>Tambahkan lebih banyak buku untuk mendapatkan reward!</p>
        <?php endif; ?>
    </div>
    <a class="back-link" href="home.php">← Kembali ke Beranda</a>
</div>
<footer>
    <p>&copy; 2024 Al-Hasra Library. All Rights Reserved.</p>
</footer>
</body>
</html>

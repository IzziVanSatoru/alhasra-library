<?php
session_start();

// Cek jika pengguna belum login, arahkan kembali ke index.php
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// Proses jika durasi dipilih
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $duration = $_POST['duration'];
    $_SESSION['loan_duration'] = $duration; // Simpan durasi pinjaman dalam sesi
    $message = "Durasi pinjaman Anda: $duration hari.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Durasi Pinjaman - Al-Hasra Library</title>
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

        .duration-form {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .duration-form h2 {
            margin-bottom: 20px;
            font-size: 1.5rem;
        }

        .duration-form select {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .duration-form button {
            padding: 10px 20px;
            border: none;
            background: #007bff;
            color: white;
            font-size: 1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .duration-form button:hover {
            background: #0056b3;
        }

        .message {
            margin-top: 20px;
            color: green;
            font-size: 1.2rem;
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
    <h1>Durasi Pinjaman</h1>
</header>
<div class="content">
    <form class="duration-form" method="POST">
        <h2>Pilih Durasi Pinjaman</h2>
        <select name="duration" required>
            <option value="" disabled selected>Pilih Durasi</option>
            <option value="7">7 Hari</option>
            <option value="14">14 Hari</option>
            <option value="21">21 Hari</option>
            <option value="30">30 Hari</option>
        </select>
        <button type="submit">Simpan</button>
    </form>
    <?php if (isset($message)): ?>
        <div class="message"><?= htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <a class="back-link" href="home.php">← Kembali ke Beranda</a>
</div>
<footer>
    <p>&copy; 2024 Al-Hasra Library. All Rights Reserved.</p>
</footer>
</body>
</html>

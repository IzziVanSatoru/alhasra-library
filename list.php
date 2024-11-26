<?php
session_start();
include 'includes/functions.php';

// Cek jika pengguna belum login, arahkan kembali ke index.php
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// Periksa apakah ada daftar buku di sesi
if (!isset($_SESSION['book_list'])) {
    $_SESSION['book_list'] = [];
}

// Proses penghapusan buku jika ada permintaan POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (($key = array_search($_POST['book_id'], $_SESSION['book_list'])) !== false) {
        unset($_SESSION['book_list'][$key]); // Hapus buku dari sesi
        $_SESSION['book_list'] = array_values($_SESSION['book_list']); // Reset array untuk mencegah celah indeks
    }
}

// Ambil daftar buku berdasarkan ID di sesi
if (!empty($_SESSION['book_list'])) {
    $book_ids = implode(',', $_SESSION['book_list']); // Gabungkan ID menjadi string untuk query
    $sql = "SELECT * FROM books WHERE id IN ($book_ids)";
    $result = $conn->query($sql);
    $book_list = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $book_list = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Book List - Al-Hasra Library</title>
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

        .book-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .book-item {
            width: 220px;
            background: linear-gradient(135deg, #ffffff, #f0f4ff);
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            text-align: center;
            padding: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .book-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3);
        }

        .book-item img {
            width: 100%;
            height: 160px;
            border-radius: 10px;
            object-fit: cover;
        }

        .book-item h3 {
            font-size: 1.2rem;
            color: #333;
            margin: 10px 0 5px;
        }

        .book-item p {
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 10px;
        }

        .book-item form button {
            padding: 8px 12px;
            border: none;
            background-color: #ff4c4c;
            color: white;
            font-size: 0.9rem;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
        }

        .book-item form button:hover {
            background-color: #cc0000;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
        }

        .summary {
            margin-top: 20px;
            font-size: 1.2rem;
            color: #007bff;
        }

        .back-link {
            margin-top: 30px;
            padding: 10px 20px;
            border: none;
            background: linear-gradient(90deg, red, orange, yellow, green, blue, indigo, violet);
            background-size: 400%;
            color: white;
            font-weight: bold;
            font-size: 1rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            transition: transform 0.3s ease, background 0.5s linear;
            animation: rgb-shift 5s infinite linear;
        }

        .back-link:hover {
            transform: scale(1.1);
            background: linear-gradient(90deg, violet, indigo, blue, green, yellow, orange, red);
        }

        @keyframes rgb-shift {
            0% { background-position: 0%; }
            100% { background-position: 400%; }
        }
    </style>
</head>
<body>
<header>
    <h1>Your Book List</h1>
</header>
<div class="content">
    <h2>Buku yang Anda Tambahkan</h2>
    <?php if (empty($book_list)): ?>
        <p>Anda belum menambahkan buku ke daftar.</p>
    <?php else: ?>
        <div class="book-list">
            <?php foreach ($book_list as $book): ?>
                <div class="book-item">
                    <img src="assets/images/<?= htmlspecialchars($book['image']); ?>" alt="<?= htmlspecialchars($book['title']); ?>">
                    <h3><?= htmlspecialchars($book['title']); ?></h3>
                    <p>by <?= htmlspecialchars($book['author']); ?></p>
                    <form method="POST">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="book_id" value="<?= $book['id']; ?>">
                        <button type="submit">Delete</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
        <p class="summary">Jumlah buku yang dipinjam: <?= count($book_list); ?></p>
    <?php endif; ?>
    <a class="back-link" href="home.php">← Kembali ke Beranda</a>
</div>
<footer>
    <p>&copy; 2024 Al-Hasra Library. All Rights Reserved.</p>
</footer>
</body>
</html>

<?php
session_start();
include 'includes/functions.php';

// Cek jika pengguna belum login, arahkan kembali ke index.php
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// Ambil durasi pinjaman dari sesi
$loan_duration = isset($_SESSION['loan_duration']) ? $_SESSION['loan_duration'] : "Belum dipilih";

// Proses pencarian
$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$books = null;

// Fungsi pencarian buku
if (!empty($search)) {
    $stmt = $conn->prepare("SELECT * FROM books WHERE title LIKE ? OR author LIKE ?");
    $searchParam = "%$search%";
    $stmt->bind_param("ss", $searchParam, $searchParam);
    $stmt->execute();
    $books = $stmt->get_result();
} else {
    $books = getBooks($conn);
}

// Proses POST: Logout atau tambahkan buku ke daftar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'logout') {
        session_destroy();
        header("Location: index.php");
        exit();
    }
    if ($_POST['action'] === 'add_to_list' && isset($_POST['book_id'])) {
        $book_id = $_POST['book_id'];
        if (!isset($_SESSION['book_list'])) {
            $_SESSION['book_list'] = [];
        }
        if (!in_array($book_id, $_SESSION['book_list'])) {
            $_SESSION['book_list'][] = $book_id;
        }
        header("Location: list.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Al-Hasra Library</title>
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
            position: relative;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .hamburger {
            position: absolute;
            top: 15px;
            left: 15px;
            cursor: pointer;
            width: 30px;
            height: 25px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            z-index: 2000;
        }

        .hamburger div {
            width: 100%;
            height: 4px;
            background-color: white;
            border-radius: 2px;
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        .hamburger:hover div {
            background-color: #f0f0f0;
        }

        footer {
            background-color: #ffffff;
            color: #333;
            text-align: center;
            padding: 10px;
            margin-top: auto;
        }

        .content {
            flex: 1;
            padding: 20px;
            text-align: center;
        }

        .loan-duration {
            font-size: 1.2rem;
            margin-top: 10px;
            color: #007bff;
        }

        .search-container {
            margin-bottom: 20px;
            text-align: center;
        }

        .search-input {
            width: 50%;
            padding: 10px;
            font-size: 1rem;
            border: 2px solid #007bff;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #0056b3;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.8);
        }

        .search-button {
            padding: 10px 15px;
            border: none;
            background: linear-gradient(135deg, #ff758c, #ff7eb3);
            color: white;
            font-size: 1rem;
            border-radius: 8px;
            cursor: pointer;
            margin-left: 10px;
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        .search-button:hover {
            transform: scale(1.1);
            background: linear-gradient(135deg, #ff4d6d, #ff758c);
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
            transform: scale(1.1);
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
            background-color: #007bff;
            color: #fff;
            font-size: 0.9rem;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .book-item form button:hover {
            background-color: #0056b3;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
        }

        .logout-container {
            margin-top: 30px;
            text-align: center;
        }

        .logout-container form button {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(135deg, #28a745, #85e89d);
            background-size: 200%;
            color: #fff;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .logout-container form button:hover {
            background: linear-gradient(135deg, #007bff, #85e8f2);
            transform: scale(1.1);
        }

        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .popup-overlay.active {
            display: flex;
        }

        .popup-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            width: 300px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .popup-content h2 {
            margin-bottom: 20px;
            font-size: 1.2rem;
        }

        .popup-content a {
            display: block;
            margin: 10px 0;
            padding: 10px 15px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: transform 0.2s ease, background-color 0.3s ease;
        }

        .popup-content a:hover {
            background: #0056b3;
            transform: scale(1.05);
        }

        .popup-content button {
            margin-top: 20px;
            padding: 10px 15px;
            border: none;
            background: #ff4c4c;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.3s ease;
        }

        .popup-content button:hover {
            background: #cc0000;
        }
    </style>
</head>
<body>
<header>
    <h1>Welcome to Al-Hasra Library</h1>
    <div class="hamburger" onclick="togglePopup()">
        <div></div>
        <div></div>
        <div></div>
    </div>
</header>
<div class="content">
    <h2>Hello, <?= htmlspecialchars($_SESSION['user']); ?>!</h2>
    <p class="loan-duration">Durasi pinjaman Anda: <strong><?= htmlspecialchars($loan_duration); ?> hari</strong></p>
    <div class="search-container">
        <form method="GET">
            <input type="text" name="search" class="search-input" placeholder="Cari buku...">
            <button type="submit" class="search-button">Cari</button>
        </form>
    </div>
    <div class="book-list">
        <?php while ($row = $books->fetch_assoc()): ?>
            <div class="book-item">
                <img src="assets/images/<?= $row['image']; ?>" alt="<?= $row['title']; ?>">
                <h3><?= $row['title']; ?></h3>
                <p>by <?= $row['author']; ?></p>
                <form method="POST">
                    <input type="hidden" name="action" value="add_to_list">
                    <input type="hidden" name="book_id" value="<?= $row['id']; ?>">
                    <button type="submit">Add to List</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
    <div class="logout-container">
        <form method="POST">
            <input type="hidden" name="action" value="logout">
            <button type="submit">Logout</button>
        </form>
    </div>
</div>
<footer>
    <p>&copy; 2024 Al-Hasra Library. All Rights Reserved.</p>
</footer>

<div class="popup-overlay" id="popupOverlay">
    <div class="popup-content">
        <h2>Navigate to:</h2>
        <a href="durasi_pinjaman.php">Durasi Pinjaman</a>
        <a href="reward.php">Reward</a>
        <button onclick="togglePopup()">Close</button>
    </div>
</div>

<script>
    function togglePopup() {
        const popup = document.getElementById('popupOverlay');
        popup.classList.toggle('active');
    }
</script>
</body>
</html>

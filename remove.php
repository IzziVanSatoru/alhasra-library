<?php
session_start();
include 'includes/functions.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// Periksa apakah book_id dikirim melalui POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'])) {
    $book_id = (int) $_POST['book_id'];

    // Hapus buku dari daftar menggunakan fungsi dari functions.php
    if (removeFromList($conn, $book_id)) {
        // Redirect kembali ke halaman list.php setelah berhasil dihapus
        header("Location: list.php");
        exit();
    } else {
        echo "Failed to remove the book from the list.";
    }
} else {
    // Jika book_id tidak dikirim, arahkan kembali ke list.php
    header("Location: list.php");
    exit();
}
?>

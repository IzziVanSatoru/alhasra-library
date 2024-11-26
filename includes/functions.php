<?php
include 'database.php';

function getBooks($conn) {
    $sql = "SELECT * FROM books";
    return $conn->query($sql);
}

function addToList($conn, $book_id) {
    $sql = "INSERT INTO book_list (book_id) VALUES ($book_id)";
    return $conn->query($sql);
}

function removeFromList($conn, $book_id) {
    $sql = "DELETE FROM book_list WHERE book_id = $book_id";
    return $conn->query($sql);
}

function getBookList($conn) {
    $sql = "SELECT b.* FROM book_list bl INNER JOIN books b ON bl.book_id = b.id";
    return $conn->query($sql);
}
?>

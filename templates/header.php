<?php
// Mulai sesi hanya jika belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Al-Hasra Library</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .main-content {
            flex: 1;
            padding: 20px;
        }
    </style>
</head>
<body>
<header>
    <h1>Al-Hasra Library</h1>
</header>
<div class="main-content">

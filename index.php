<?php
session_start();

// Cek jika pengguna sudah login, langsung arahkan ke home.php
if (isset($_SESSION['user'])) {
    header("Location: home.php");
    exit();
}

// Proses login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gmail = $_POST['gmail'];
    $_SESSION['user'] = $gmail;
    header("Location: home.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Al-Hasra Library</title>
    <style>
        :root {
            --animation-speed: 10s; /* Kecepatan default */
        }

        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(45deg, #87CEFA, rgb(255, 0, 255), rgb(0, 255, 255));
            background-size: 300% 300%;
            animation: gradient-move var(--animation-speed) infinite;
        }

        @keyframes gradient-move {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .login-container {
            background: rgba(255, 255, 255, 0.8);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 45%; /* Ukuran kotak login 45% dari lebar layar */
            max-width: 600px; /* Batas maksimum untuk layar besar */
            min-width: 300px; /* Batas minimum untuk layar kecil */
            margin: auto; /* Agar selalu berada di tengah */
        }

        h1 {
            margin: 0 0 20px 0;
            font-size: 1.8rem;
            color: #333;
        }

        .login-container input {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            box-shadow: inset 0 4px 6px rgba(0, 0, 0, 0.1);
            font-size: 1rem;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .login-container input:focus {
            outline: none;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.8);
        }

        .login-container button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            color: #fff;
            background: linear-gradient(45deg, #007bff, #4caf50);
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .login-container button:hover {
            transform: translateY(-2px);
            box-shadow: 0px 8px 12px rgba(0, 0, 0, 0.3);
        }

        .login-container button:active {
            transform: translateY(0);
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
        }

        footer {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            color: #333;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Login to Al-Hasra Library</h1>
        <form method="POST">
            <input type="email" name="gmail" id="email-input" placeholder="Enter your Gmail" required>
            <button type="submit">Login</button>
        </form>
    </div>
    <footer>
        <p>&copy; 2024 Al-Hasra Library. All Rights Reserved.</p>
    </footer>
    <script>
        const body = document.body;
        const emailInput = document.getElementById('email-input');

        let isMouseOverBackground = false;

        // Percepat animasi hanya saat mouse berada di background dan tidak fokus pada input
        body.addEventListener('mousemove', () => {
            if (!document.activeElement.isEqualNode(emailInput)) {
                isMouseOverBackground = true;
                document.documentElement.style.setProperty('--animation-speed', '1s');
            }
        });

        // Kembalikan animasi ke normal saat mouse keluar dari background
        body.addEventListener('mouseleave', () => {
            isMouseOverBackground = false;
            document.documentElement.style.setProperty('--animation-speed', '10s');
        });

        // Perlambat animasi saat fokus pada input
        emailInput.addEventListener('focus', () => {
            isMouseOverBackground = false;
            document.documentElement.style.setProperty('--animation-speed', '40000000s'); // Sangat lambat
        });

        // Kembalikan animasi normal saat kehilangan fokus dan mouse tidak di background
        emailInput.addEventListener('blur', () => {
            if (!isMouseOverBackground) {
                document.documentElement.style.setProperty('--animation-speed', '10s');
            }
        });
    </script>
</body>
</html>

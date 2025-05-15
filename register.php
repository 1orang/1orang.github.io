<?php
include('koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Cek apakah email sudah ada
    $check_email = "SELECT * FROM user WHERE email = '$email'";
    $result = mysqli_query($conn, $check_email);

    if (mysqli_num_rows($result) > 0) { 
        $error = "Email sudah digunakan. Silakan gunakan email lain.";
    } else {
        // Simpan ke database
        $sql = "INSERT INTO user (username, email, password) VALUES ('$username', '$email', '$password')";
        
        if (mysqli_query($conn, $sql)) {
            header('Location: login.php');  
            exit();
        } else {
            $error = "Gagal mendaftar, coba lagi! Error: " . mysqli_error($conn);
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styless.css">
    <title>Register</title>
    <style>
       body {
    font-family: 'Poppins', sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    margin: 0;
    background: linear-gradient(135deg,rgb(7, 223, 234), #ffa726);
    color: #333;
}
        .container {
            text-align: center;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #ffdb58;
            text-align: center;
            padding: 10px 0;
            overflow: hidden;
        }
        .marquee {
            font-weight: bold;
            color: #333;
            white-space: nowrap;
            overflow: hidden;
        }
        .marquee span {
            display: inline-block;
            animation: marquee 8s linear infinite;
        }
        @keyframes marquee {
            from { transform: translateX(100%); }
            to { transform: translateX(-100%); }
        }


        /* Logo Container */
/*.logo {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px;
    background-color: #ffffff;
    border-radius: 10px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}*/

.logo img {
    width: 100px;
    height: 100px;
    margin-bottom: 10px;
    transition: transform 0.3s ease, opacity 0.3s ease;
}

.logo:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

.logo img:hover {
    transform: rotate(360deg); /* Efek putaran logo saat hover */
    opacity: 0.9; /* Efek transparansi saat hover */
}

        .logo {
    max-width: 120px;
    margin: 0 auto 20px;
}
    </style>
</head>
<body>
    <div class="container">
    <div class="logo">
    <img src="assets/logo.png" alt="Logo" class="logo"> </div><!-- Ganti logo.png dengan path logo Anda -->
        <h2>Register</h2>
        <!-- Tampilkan error jika ada -->
        <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>
        <p>Sudah punya akun? <a href="login.php">Login disini</a></p><br>
        <P>copyright 2025 © Asmara Senja <a href="login.php">Umam cs</a></p>
    </div>
    
    <!-- Footer dengan teks berjalan -->
    <footer>
        <div class="marquee">
            <span>Selamat datang! Daftar sekarang untuk menikmati fitur kami.</span>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>

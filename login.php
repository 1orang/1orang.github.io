<?php 
session_start();
include('koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Cek user di database dengan prepared statement untuk menghindari SQL injection
    $sql = "SELECT * FROM user WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Cek apakah user ditemukan dan password valid
    if ($user && password_verify($password, $user['password'])) {
        // Simpan user_id, username, dan level dalam sesi
        $_SESSION['user_id'] = $user['id']; // Simpan user_id ke sesi
        $_SESSION['username'] = $user['username'];  // Simpan username ke sesi jika diperlukan
        $_SESSION['level'] = $user['level'];  // Simpan level user ke sesi
        header('Location: index.php');  // Redirect ke halaman dashboard
        exit();
    } else {
        $error = "Ups Salah Sayangku! Username atau Password salah.";  // Pesan error jika username atau password tidak cocok
    }

    // Tutup statement
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Tambahkan SweetAlert -->
    <title>Login</title>
    <style>
        /* Reset default margin and padding */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

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
    background: #ffffff;
    border-radius: 15px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    padding: 30px;
    text-align: center;
    width: 100%;
    max-width: 400px;
}


        /* Logo Container */
       /* .logo {
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

h2 {
    margin-bottom: 20px;
    color: #444;
    font-weight: bold;
}

form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

input {
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ddd;
    border-radius: 8px;
    outline: none;
    transition: border-color 0.3s ease;
}

input:focus {
    border-color: #ffa726;
    box-shadow: 0 0 5px rgba(255, 167, 38, 0.5);
}

button {
    padding: 12px;
    font-size: 16px;
    color: #fff;
    background: #ffa726;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background: #fb8c00;
}

p {
    font-size: 14px;
    margin-top: 10px;
}

p a {
    color: #ffa726;
    text-decoration: none;
    font-weight: bold;
    transition: color 0.3s ease;
}

p a:hover {
    color: #fb8c00;
}

footer {
    position: fixed;
    bottom: 0;
    width: 100%;
    background: #ffdb58;
    text-align: center;
    padding: 10px 0;
}

.marquee {
    font-weight: bold;
    color: #333;
    white-space: nowrap;
    overflow: hidden;
}

.marquee span {
    display: inline-block;
    animation: marquee 10s linear infinite;
}

@keyframes marquee {
    from { transform: translateX(100%); }
    to { transform: translateX(-100%); }
}
    </style>
</head>
<body>
    <div class="container">
        <!-- Logo -->
        <div class="logo">
        <img src="assets/logo.png" alt="Logo" class="logo"> <!-- Ganti logo.png dengan path logo Anda -->
</div>
        <h2>Login</h2>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p>Belum punya akun? <a href="register.php">Daftar disini</a></p>
        <i class='bx bx-user'><P>copyright 2025 © Asmara Senja <a href="register.php">Umam cs</a></p></i>
    </div>

    <!-- Footer dengan teks berjalan -->
    <footer>
        <div class="marquee">
            <span>Selamat Datang! Masukkan Username dan Password untuk login.</span>
        </div>
    </footer>

    <!-- Script untuk menampilkan SweetAlert jika ada error -->
    <script>
        <?php if(isset($error)): ?>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '<?php echo $error; ?>',
                confirmButtonText: 'Coba lagi'
            });
        <?php endif; ?>
    </script>
</body>
</html>

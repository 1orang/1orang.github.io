
<?php
include 'koneksi.php';
session_start();

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

//Pastikan user berhasil login
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $sql = "UPDATE user SET status = 'online' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();
}

// Ambil user_id dari session
$user_id = $_SESSION['user_id'];
$user_level = $_SESSION['level']; // Pastikan 'level' disimpan dalam sesi setelah login
$user_name = $_SESSION['username']; // Pastikan 'username' disimpan dalam sesi setelah login

// Ambil user_id dari session
$user_id = $_SESSION['user_id'];
$user_level = $_SESSION['level'];
$user_name = $_SESSION['username']; 

// Ambil jumlah total target dan user
$result_target = $conn->query("SELECT COUNT(*) as total_target FROM target");
$row_target = $result_target->fetch_assoc();
$total_target = $row_target['total_target'];

$check_user_table = $conn->query("SHOW TABLES LIKE 'user'");
if ($check_user_table->num_rows == 1) {
    $result_user = $conn->query("SELECT COUNT(*) as total_users FROM user");
    $row_user = $result_user->fetch_assoc();
    $total_users = $row_user['total_users'];
} else {
    $total_users = 0;
}

// Ambil data dari tabel target
$sql_nilai = "SELECT * FROM target";
$result_nilai = $conn->query($sql_nilai);

// Ambil data pengguna termasuk foto profil
// Ambil data user (status, username, photo, level)
$sql = "SELECT username, photo, level, status FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($userName, $userPhoto, $userLevel, $status);
$stmt->fetch();
$stmt->close();

// Menampilkan status online/offline
if ($status == 'online') {
    $statusLabel = "<span class='status-online'>Status: Online</span>";
} else {
    $statusLabel = "<span class='status-offline'>Status: Offline</span>";
}

// Ambil nama pengguna dari sesi
$user_name = $_SESSION['username']; // Pastikan 'username' disimpan dalam sesi setelah login
$user_level = $_SESSION['level'];

// Cek apakah tabel user ada
$check_user_table = $conn->query("SHOW TABLES LIKE 'user'");
if ($check_user_table->num_rows == 1) {
    $result_user = $conn->query("SELECT COUNT(*) as total_users FROM user");
    $row_user = $result_user->fetch_assoc();
    $total_users = $row_user['total_users'];
} else {
    $total_users = 0; // Jika tabel tidak ada, set nilai default
}

// Ambil jumlah total target dari database
$result_target = $conn->query("SELECT COUNT(*) as total_target FROM target");
$row_target = $result_target->fetch_assoc();
$total_target = $row_target['total_target'];

// Ambil total progres dari semua bulan
$sql_progres = "SELECT 
            SUM(pogres_januari) AS total_januari,
            SUM(pogres_februari) AS total_februari,
            SUM(pogres_maret) AS total_maret,
            SUM(pogres_april) AS total_april,
            SUM(pogres_mei) AS total_mei,
            SUM(pogres_juni) AS total_juni,
            SUM(pogres_juli) AS total_juli,
            SUM(pogres_agustus) AS total_agustus,
            SUM(pogres_september) AS total_september,
            SUM(pogres_oktober) AS total_oktober,
            SUM(pogres_november) AS total_november,
            SUM(pogres_desember) AS total_desember
        FROM target";

$result_progres = $conn->query($sql_progres);
$data_progres = $result_progres->fetch_assoc();

// Hitung total progres tahunan
$total_progres = array_sum($data_progres);

// Konversi ke persen tanpa desimal
$total_progres = round($total_progres);


?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="style2.css">
    <title>Dashboard</title>
    <style>
        body {
        font-family: Arial, sans-serif;
        background-color:white;#fff);
        margin: 0;
        padding: 0;
    }
    main {
        max-width: 1200px;
        margin: 20px auto;
        background: white;
        padding: 10px;
        border-radius: 20px;
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
    }
    .dashboard-cards {
        display: flex;
        justify-content: space-around;
        margin-bottom: 40px;
    }
    .card {
        background:rgb(14, 120, 234);
        color: white;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        flex: 1;
        margin: 10px;
    }
    .prayer-schedule {
        margin-top: 20px;
        padding: 15px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .prayer-schedule h2 {
        text-align: center;
    }

        /* Status styling */
        .status-online { color: green; font-weight: bold; }
        .status-offline { color: red; font-weight: bold; }
        /* Navbar styling */
        .navbar { background-color: #00796b; position: fixed; width: 100%; top: 0; padding: 10px 20px; z-index: 1000; }
        body { margin-top: 80px;

        /* Tambahkan ini untuk membuat navbar menutupi seluruh bagian atas */
.navbar {
    background-color: #00796b; /* Sesuaikan warna dengan latar dashboard */
    position: fixed; /* Agar navbar tetap di atas */
    top: 0;
    left: 0;
    width: 100%; /* Pastikan menutupi seluruh lebar layar */
    z-index: 1000; /* Pastikan berada di atas elemen lainnya */
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Atur margin pada elemen di dalam konten agar tidak tertutup oleh navbar */
body {
    margin-top: 80px; /* Sesuaikan dengan tinggi navbar agar konten tidak tertutup */
}

/* Sesuaikan warna latar belakang dashboard */
.dashboard-container {
    background-color: #00796b; /* Warna yang sama dengan navbar */
}


        /* Styling Navbar */
        .navbar {
            background-color: #00796b;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .navbar-logo {
            display: flex;
            align-items: center;
        }

        .navbar-logo img {
            width: 50px;
            height: 50px;
            margin-right: 10px;
            transition: transform 0.3s ease;
        }

        .navbar-logo img:hover {
            transform: rotate(360deg); /* Efek putaran saat di-hover */
        }

        .navbar-logo h1 {
            color: white;
            font-size: 24px;
            margin: 0;
        }

        .navbar-menu {
            display: flex;
            align-items: center;
        }

        .navbar-menu a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-size: 18px;
            transition: color 0.3s ease;
        }

        .navbar-menu a:hover {
            color:rgb(250, 241, 224);
        }

        /* Perbaikan untuk menghindari elemen bertabrakan */
        .navbar-menu, .navbar-profile, .logout-btn {
            flex-shrink: 0; /* Hindari mengecilkan elemen */
        }

        .navbar-profile {
            display: flex;
            align-items: center;
            margin-right: 20px;
        }

        .navbar-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            transition: transform 0.3s ease;
        }

        .navbar-profile img:hover {
            transform: scale(1.1);
        }

        .navbar-profile span {
            color: white;
            font-size: 19px;
            white-space: nowrap; /* Hindari teks terpotong */
        }

        .logout-btn {
            background-color: #ff5252;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #ff1744;
        }

        /* Tambahkan responsivitas */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .navbar-menu {
                margin-top: 10px;
                justify-content: center;
            }

            .navbar-profile {
                margin-top: 10px;
                justify-content: center;
            }

            .logout-btn {
                margin-top: 10px;
                width: 100%;
                text-align: center;
            }
        }

        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background-color:rgb(10, 245, 6);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease, background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .card h3 {
            margin-bottom: 10px;
            font-size: 20px;
        }

        .card:hover {
            background-color:rgb(121, 92, 216);
            transform: translateY(-10px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            cursor: pointer;
        }

        /* Tombol logout dengan hover */
        .logout-btn {
            background-color: #ff5252;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 16px;                                                                                                
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #ff1744;
            transform: scale(1.05);
        }

        filter-container {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }
        .filter-container select {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            display: none; /* Default hidden, shown via JS */
        }
        th, td {
            border: 1px solid #ddd;
            padding: 1px;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .table-container {
            margin-top: 30px;
        }
        .empty-message {
            text-align: center;
            color: #666;
            font-size: 18px;
            margin-top: 20px;
        }           


        /*untuk logo */

        /* Logo Container */
.logo {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px;
    background-color: #ffffff;
    border-radius: 10px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

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

/* Profil Info */
.profile-info {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin-top: 20px;
    background-color: #f9f9f9;
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.profile-info img {
    width: 80px; /* Ukuran profil di sidebar disesuaikan dengan yang ada di navbar */
    height: 80px; /* Ukuran disesuaikan */
    border-radius: 50%;
    margin-bottom: 10px;
    transition: transform 0.3s ease, opacity 0.3s ease, box-shadow 0.3s ease;
}

.profile-info p {
    font-size: 16px;
    color: #333;
    margin: 0;
}

.profile-info:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

.profile-info img:hover {
    transform: scale(1.1); /* Efek pembesaran pada gambar profil saat hover */
    opacity: 0.9;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2); /* Tambahkan bayangan pada hover untuk konsistensi */
}

footer {
            background-color:rgb(4, 77, 7);
            color: white;
            text-align: center;
            padding: 5px 0;
            position: fixed;
            width: 100%;
            bottom: 0;
        }

        /* Efek animasi foto profil */
.user-photo {
    transition: transform 0.4s ease, box-shadow 0.4s ease;
    border-radius: 50%;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.user-photo:hover {
    transform: scale(1.1) rotate(360deg);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

.status-online {
    color: green;
    font-weight: bold;
}

.status-offline {
    color: red;
    font-weight: bold;
}

/* Sidebar */
.left-section {
    width: 350px;
    background: #ea24c5;
    color: rgb(255, 255, 255);
    padding: auto;
    display: auto;
    flex-direction: column;
    justify-content: flex-start;
    gap: 20px;
    height: 100vh; /* Membuat background ungu menjangkau seluruh tinggi layar */
        position: auto;
        left: auto;
        top: auto;
}

.sidebar .item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 3px;
    color: rgb(255, 255, 255);
    cursor: pointer;
    transition: 0.3s;
    border-radius: 5px;
}

.sidebar .item:hover, .sidebar .item#active {
    background: #81c4d1;
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



navbar-menu {
            display: flex;
            justify-content: center;
            gap: 20px;
            background: #333;
            padding: 15px;
            border-radius: 8px;
        }

        .navbar-menu a {
            position: relative;
            text-decoration: none;
            color: white;
            font-size: 16px;
            padding: 10px 15px;
            transition: color 0.3s ease-in-out;
        }

        .navbar-menu a:hover {
            color: #ffcc00;
        }


/* Tooltip */
.navbar-menu a::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: -25px; /* Lebih dekat ke tombol */
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease-in-out, transform 0.2s ease-in-out;
        }

        .navbar-menu a:hover::after {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(-3px); /* Lebih dekat */
        }

    </style>
</head>

<body>
<nav class="navbar">
        <div class="navbar-logo">
            <img src="assets/logo.png" alt="Logo"> <!-- Ganti dengan path logo -->
            <h1>Dashboard Keren</h1>
        </div>
        <div class="navbar-menu">
        <a href="index.php" data-tooltip="Kembali ke Beranda">Beranda</a>
        <a href="#" data-tooltip="Lihat Profil Anda">Profil</a>
        <a href="setting.php" data-tooltip="Atur Preferensi Anda">Pengaturan</a>
        <a href="laporan.php" data-tooltip="Unggah Laporan Anda">Upload</a>
        <a href="dashboard-photo.php" data-tooltip="Lihat Hasil Upload">Hasil</a>
    </div>
        <div class="navbar-profile" style="padding: 10px; line-height: 1.5;">
    <img src="<?= !empty($userPhoto) ? 'uploads/'.$userPhoto : 'assets/profile_placeholder.png'; ?>" alt="Foto Profil" style="margin-right: 10px; vertical-align: middle;">
    <span><?= $userName; ?> | <?= ucfirst($userLevel); ?>😎🐳</span><br>
    <span style="color: gray;"><?= $statusLabel; ?></span> <!-- Status Online/Offline -->
</div>

        <button class="logout-btn" id="logoutBtn">Logout</button>
    </nav>
<div class="container">
        <aside class="left-section">
            <div class="logo">
                <img src="assets/logo.png">
                <!--<a href="#">Dashboard</a>-->
                <div class="profile-info">
    <!-- Tampilkan foto profil pengguna -->
    <?php if (!empty($user_data['photo'])): ?>
        <img src="uploads/<?= htmlspecialchars($user_data['photo']); ?>" alt="Profile" class="profile-picture">
    <?php else: ?>
        <img src="<?= !empty($userPhoto) ? 'uploads/'.$userPhoto : 'assets/profile_placeholder.png'; ?>" alt="Foto Profil"> 
    <?php endif; ?>
    <p><?= htmlspecialchars($user_name); ?> | <?= ucfirst(htmlspecialchars($user_level)); ?></p>
</div>

            </div>

            <div class="sidebar">
    
                <!-- Menu yang hanya bisa diakses oleh admin -->
                <?php if ($user_level == 'admin'): ?>
                    <div class="item">
                        <i class='bx bx-user'></i>
                        <a href="user.php">Halaman User</a>
                    </div>
                    <div class="item">
                        <i class='bx bx-cool'></i>
                        <a href="input_target.php">Input Target Baru</a>
                    </div>
                    <div class="item">
                        <i class='bx bx-desktop'></i>
                        <a href="target.php">UPDATE Target </a>
                    </div>
                    <div class="item">
                        <i class='bx bx-desktop'></i>
                        <a href="admin.php">KPI UPDATE </a>
                    </div>

                    <div class="item">
                    <i class='bx bx-cog'></i>
                    <a href="input.php"> NPF</a>
                </div>

                <?php endif; ?>

                <div class="item">
                    <i class='bx bx-bar-chart'></i>
                    <a href="data_nilai_progres.php">Data Nilai Progres</a>
                </div>
                <div class="item">
                        <i class='bx bx-desktop'></i>
                        <a href="staff.php">KPI </a>
                </div>
                <div class="item">
                    <i class='bx bx-cog'></i>
                    <a href="setting.php">Setting</a>
                </div>

                <div class="item">
                    <i class='bx bx-cog'></i>
                    <a href="view.php">View Progres NPF</a>
                </div>
                
        </aside>

        
        <main id="content">
        <h1>
    Selamat Datang, <?= $user_name; ?></h1>
    <div class="marquee">
            <span>Pilih menu di sidebar untuk mengakses halaman yang diinginkan.</span> 
                </div>

            <div class="dashboard-cards">
                <div class="card">
                    <h3>Jumlah User</h3>
                    <p><?= $total_users; ?></p>
                </div>
                <div class="card">
                    <h3>Jumlah Target</h3>
                    <p><?= $total_target; ?></p>
                </div>
                <div class="card">
                    <h3>Data Nilai Progres</h3>
                    <p><?= $total_progres; ?>%</p>
                </div>
            </div>

        <!-- Filter bulan -->
        <div class="filter-container">
            <label for="bulan">Pilih Bulan: </label>
            <select id="bulan" onchange="filterBulan()">
                <option value="">Semua Bulan</option>
                <option value="januari">Januari</option>
                <option value="februari">Februari</option>
                <option value="maret">Maret</option>
                <option value="april">April</option>
                <option value="mei">Mei</option>
                <option value="juni">Juni</option>
                <option value="juli">Juli</option>
                <option value="agustus">Agustus</option>
                <option value="september">September</option>
                <option value="oktober">Oktober</option>
                <option value="november">November</option>
                <option value="desember">Desember</option>
            </select>
        </div>

        <!-- Bagian untuk menampilkan data nilai progres -->
        <div class="table-container">
    <table id="table-januari">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Januari</th>
                <th>Status Januari</th> <!-- Tambahkan kolom status -->
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result_nilai->fetch_assoc()) : ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= round($row['pogres_januari'] ?? 0) ?>%</td>
                    <td>
                        <?php
                        // Logika untuk menentukan status
                        if ($row['pogres_januari'] >= 90) {
                            echo 'Tercapai';  // Jika progress >= 75% dianggap tercapai
                        } else {
                            echo 'Belum Tercapai';  // Jika kurang dari 75%
                        }
                        ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>


            <!-- Tambahkan tabel lain untuk setiap bulan yang diinginkan -->
            <table id="table-februari">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Februari</th>
                        <th>Status Februari</th> <!-- Tambahkan kolom status -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result_nilai->data_seek(0); // Reset pointer
                    while ($row = $result_nilai->fetch_assoc()) : ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td><?= round($row['pogres_februari'] ?? 0) ?>%</td>
                            <td>
                        <?php
                        // Logika untuk menentukan status
                        if ($row['pogres_februari'] >= 100) {
                            echo 'Tercapai';  // Jika progress >= 75% dianggap tercapai
                        } else {
                            echo 'Belum Tercapai';  // Jika kurang dari 75%
                        }
                        ?>
                    </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>



             <!-- Tambahkan tabel lain untuk setiap bulan yang diinginkan -->
             <table id="table-maret">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Maret</th>
                        <th>Status Maret</th> <!-- Tambahkan kolom status -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result_nilai->data_seek(0); // Reset pointer
                    while ($row = $result_nilai->fetch_assoc()) : ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td><?= round($row['pogres_maret'] ?? 0) ?>%</td>
                            <td>
                        <?php
                        // Logika untuk menentukan status
                        if ($row['pogres_maret'] >= 100) {
                            echo 'Tercapai';  // Jika progress >= 75% dianggap tercapai
                        } else {
                            echo 'Belum Tercapai';  // Jika kurang dari 75%
                        }
                        ?>
                    </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>


              <!-- Tambahkan tabel lain untuk setiap bulan yang diinginkan -->
              <table id="table-april">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>April</th>
                        <th>Status April</th> <!-- Tambahkan kolom status -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result_nilai->data_seek(0); // Reset pointer
                    while ($row = $result_nilai->fetch_assoc()) : ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td><?= round($row['pogres_april'] ?? 0) ?>%</td>
                            <td>
                        <?php
                        // Logika untuk menentukan status
                        if ($row['pogres_april'] >= 100) {
                            echo 'Tercapai';  // Jika progress >= 75% dianggap tercapai
                        } else {
                            echo 'Belum Tercapai';  // Jika kurang dari 75%
                        }
                        ?>
                    </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>


             <!-- Tambahkan tabel lain untuk setiap bulan yang diinginkan -->
             <table id="table-mei">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Mei</th>
                        <th>Status Mei</th> <!-- Tambahkan kolom status -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result_nilai->data_seek(0); // Reset pointer
                    while ($row = $result_nilai->fetch_assoc()) : ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td><?= round($row['pogres_mei'] ?? 0) ?>%</td>
                            <td>
                        <?php
                        // Logika untuk menentukan status
                        if ($row['pogres_mei'] >= 100) {
                            echo 'Tercapai';  // Jika progress >= 75% dianggap tercapai
                        } else {
                            echo 'Belum Tercapai';  // Jika kurang dari 75%
                        }
                        ?>
                    </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

                    </main>

        <!-- Footer -->
    <footer>
        <p>&copy; 2025 Dashboard Management Umam CS</p>
    </footer>
        <script>
        // Event listener untuk tombol logout
        document.getElementById("logoutBtn").addEventListener("click", function() {
            Swal.fire({
                title: 'Apakah Anda yakin ingin logout?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, logout!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect ke logout.php
                    window.location.href = 'logout.php';
                }
            });
        });
    </script>

<script>
    function filterBulan() {
        const bulan = document.getElementById('bulan').value;
        const allTables = document.querySelectorAll('table');
        const emptyMessage = document.getElementById('empty-message');
        let found = false;

        allTables.forEach(table => {
            table.style.display = 'none'; // Hide all tables
        });

        if (bulan) {
            const selectedTable = document.getElementById(`table-${bulan}`);
            if (selectedTable) {
                selectedTable.style.display = 'table'; // Show selected table
                found = true;
            }
        } else {
            // If no specific month is selected, hide all tables
        }

        if (!found) {
            emptyMessage.style.display = 'block';
        } else {
            emptyMessage.style.display = 'none';
        }
    }
</script>
    </div>
</body>

</html>

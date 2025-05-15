<?php
include 'koneksi.php';
session_start();

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Ambil informasi pengguna dari sesi
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['username'];

$success = false;
$error_message = '';

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_name = $_POST['new_name'];
    $new_password = $_POST['new_password'];
    $password_hash = password_hash($new_password, PASSWORD_DEFAULT); // Enkripsi password baru
    $uploaded_image = $_FILES['profile_image']['name'];
    $image_tmp_name = $_FILES['profile_image']['tmp_name'];
    $image_folder = 'uploads/' . $uploaded_image;

    // Proses pengunggahan foto
    if (move_uploaded_file($image_tmp_name, $image_folder)) {
        // Update data pengguna di database
        $sql = "UPDATE user SET username = '$new_name', password = '$password_hash', photo = '$uploaded_image' WHERE id = '$user_id'";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['username'] = $new_name; // Perbarui nama di sesi
            $success = true;
        } else {
            $error_message = 'Gagal memperbarui informasi.';
        }
    } else {
        $error_message = 'Gagal mengunggah foto.';
    }
}

// Ambil data pengguna dari database
$sql = "SELECT username, photo FROM user WHERE id = '$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style2.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Pengaturan Akun</title>
    <style>
        .settings-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .settings-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group input[type="file"] {
            margin-top: 10px;
        }

        .profile-image {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-image img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
        }

        .save-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            width: 100%;
        }

        .save-btn:hover {
            background-color:rgb(184, 9, 233);
        }

        .back-btn {
            background-color:rgb(16, 26, 219);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            width: 100%;
        }

        .back-btn:hover {
            background-color: #ff1744;
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="settings-container">
        <h2>Pengaturan Akun</h2>

        <!-- Tampilkan foto profil pengguna -->
        <div class="profile-image">
            <img src="uploads/<?= $user['photo']; ?>" alt="Foto Profil">
        </div>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="new_name">Nama Baru:</label>
                <input type="text" id="new_name" name="new_name" value="<?= $user['username']; ?>" required>
            </div>

            <div class="form-group">
                <label for="new_password">Kata Sandi Baru:</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>

            <div class="form-group">
                <label for="profile_image">Unggah Foto Profil Baru:</label>
                <input type="file" id="profile_image" name="profile_image" accept="image/*" required>
            </div>

            <button type="submit" class="save-btn">Simpan Perubahan</button>
            <button type="button" class="back-btn" onclick="window.location.href='index.php';">Kembali</button>
        </form>
    </div>

    <!-- SweetAlert -->
    <script>
        <?php if ($success): ?>
            Swal.fire({
                title: 'Berhasil!',
                text: 'Informasi berhasil diperbarui.',
                icon: 'success',
                confirmButtonText: 'Kembali ke Dashboard'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'index.php'; // Redirect ke dashboard
                }
            });
        <?php elseif ($error_message): ?>
            Swal.fire({
                title: 'Gagal!',
                text: '<?= $error_message; ?>',
                icon: 'error',
                confirmButtonText: 'Coba Lagi'
            });
        <?php endif; ?>
    </script>

</body>
</html>

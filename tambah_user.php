<?php
include 'koneksi.php';

// Mulai output buffering
ob_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $level = trim($_POST['level']); // Ambil level user dari form

    // Memproses unggahan foto (optional)
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto = $_FILES['foto']['name'];
        $foto_tmp = $_FILES['foto']['tmp_name'];
        $foto_path = "uploads/" . basename($foto);
        
        // Pindahkan file yang diunggah ke folder "uploads"
        move_uploaded_file($foto_tmp, $foto_path);
    } else {
        $foto_path = null;
    }

    // Validasi data tidak boleh kosong
    if (empty($username) || empty($email) || empty($_POST['password']) || empty($level)) {
        $message = "Error: Semua field harus diisi!";
        $status = "error";
    } else {
        // Validasi email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "Error: Format email tidak valid!";
            $status = "error";
        } else {
            // Validasi level
            $allowed_levels = ['admin', 'staff', 'user'];
            if (!in_array($level, $allowed_levels)) {
                $message = "Error: Level tidak valid!";
                $status = "error";
            } else {
                // Cek apakah username sudah ada
                $cek_user = $conn->prepare("SELECT id FROM user WHERE username = ?");
                $cek_user->bind_param("s", $username);
                $cek_user->execute();
                $cek_user->store_result();

                if ($cek_user->num_rows > 0) {
                    $message = "Username sudah digunakan! Pilih yang lain.";
                    $status = "error";
                } else {
                    // Tambahkan user baru dengan level
                    $sql = $conn->prepare("INSERT INTO user (id, username, email, password, level) VALUES (?, ?, ?, ?, ?)");
                    $sql->bind_param("sssss", $id, $username, $email, $password, $level);

                    if ($sql->execute()) {
                        $message = "User berhasil ditambahkan!";
                        $status = "success";
                    } else {
                        $message = "Terjadi kesalahan, coba lagi!";
                        $status = "error";
                    }

                    $sql->close();
                }

                $cek_user->close();
            }
        }
    }

    // Koneksi hanya ditutup setelah semua proses selesai
    $conn->close();
}

ob_end_flush(); // Kirim semua output ke browser
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<script>
    Swal.fire({
        icon: '<?php echo $status; ?>',
        title: '<?php echo $message; ?>',
        confirmButtonText: 'Kembali ke User'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'user.php';
        }
    });
</script>

</body>
</html>

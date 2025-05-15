<?php
include 'koneksi.php';

// Mengecek apakah parameter 'id' ada
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Query untuk mendapatkan data berdasarkan ID
    $sql = "SELECT * FROM target WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<script>
                alert('Data tidak ditemukan!');
                window.location.href = 'target.php';
              </script>";
        exit();
    }
}

// Proses saat form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $bulan = strtolower(mysqli_real_escape_string($conn, $_POST['bulan']));
    $target = (float) $_POST['target'];
    $pogres = (float) $_POST['pogres'];
    $pencapaian = ($pogres != 0) ? ($pogres / $target) * 100 : 0;

    // Tentukan nama kolom sesuai bulan yang dipilih
    $target_col = "target_$bulan";
    $pogres_col = "pogres_$bulan";
    $pencapaian_col = "pencapaian_$bulan";

    // Query untuk mengupdate data
    $sql = "UPDATE target SET 
                nama = '$nama', 
                $target_col = $target, 
                $pogres_col = $pogres, 
                $pencapaian_col = $pencapaian 
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
                Swal.fire({
                    title: 'Sukses!',
                    text: 'Data berhasil diupdate!',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = 'target.php';
                });
              </script>";
    } else {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Gagal mengupdate data: " . addslashes($conn->error) . "',
                    icon: 'error'
                });
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Target</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }
        form {
            width: 100%;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        h2 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }
        label {
            display: block;
            font-size: 14px;
            color: #555;
            margin-bottom: 8px;
        }
        input[type="text"], input[type="number"], select {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .button-container {
            text-align: center;
        }
        .submit-btn {
            padding: 12px 24px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .submit-btn:hover {
            background-color: #45a049;
        }
        .back-btn {
            padding: 12px 24px;
            background-color: #d9534f;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            margin-left: 10px;
        }
        .back-btn:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>

<h2>Edit Data Target</h2>

<form method="POST" action="edit_target.php?id=<?php echo $id; ?>">
    <label for="nama">Nama</label>
    <input type="text" name="nama" id="nama" value="<?php echo htmlspecialchars($row['nama']); ?>" required>

    <label for="bulan">Pilih Bulan</label>
    <select name="bulan" id="bulan" required>
        <?php
        $months = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
        foreach ($months as $month) {
            echo "<option value='$month'>" . ucfirst($month) . "</option>";
        }
        ?>
    </select>

    <label for="target">Target</label>
    <input type="number" name="target" id="target" step="0.01" required>

    <label for="pogres">Pogres</label>
    <input type="number" name="pogres" id="pogres" step="0.01" required>

    <div class="button-container">
        <button type="submit" class="submit-btn">Simpan Perubahan</button>
        <a href="target.php" class="back-btn">Kembali</a>
    </div>
</form>

</body>
</html>

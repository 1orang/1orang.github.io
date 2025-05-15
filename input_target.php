<?php
include 'koneksi.php';

// Proses saat form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $bulan = strtolower(mysqli_real_escape_string($conn, $_POST['bulan'])); // Konversi ke huruf kecil
    $target = (float) $_POST['target'];
    $pogres = (float) $_POST['pogres'];
    $pencapaian = ($pogres != 0) ? ($pogres / $target) * 100 : 0; // Perbaikan rumus

    // Tentukan nama kolom sesuai bulan yang dipilih
    $target_col = "target_$bulan";
    $pogres_col = "pogres_$bulan";
    $pencapaian_col = "pencapaian_$bulan";

    // Query untuk update atau insert
    $sql = "INSERT INTO target (nama, $target_col, $pogres_col, $pencapaian_col) 
            VALUES ('$nama', $target, $pogres, $pencapaian)
            ON DUPLICATE KEY UPDATE 
            $target_col = VALUES($target_col), 
            $pogres_col = VALUES($pogres_col), 
            $pencapaian_col = VALUES($pencapaian_col)";

    if ($conn->query($sql) === TRUE) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
                Swal.fire({
                    title: 'Sukses!',
                    text: 'Data berhasil disimpan!',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = 'dashboard.php';
                });
              </script>";
    } else {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Gagal menyimpan data: " . addslashes($conn->error) . "',
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
    <title>Input Target Baru</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        form {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"], input[type="number"], select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .button-container {
            text-align: center;
            display: flex;
            justify-content: space-between;
        }
        .submit-btn, .back-btn {
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 4px;
        }
        .submit-btn {
            background-color: #4CAF50;
            color: white;
        }
        .submit-btn:hover {
            background-color: #45a049;
        }
        .back-btn {
            background-color: #d9534f;
            color: white;
        }
        .back-btn:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>

<h2 style="text-align: center;">Form Input Data Target</h2>

<form method="POST" action="input_target.php">
    <label for="nama">Nama</label>
    <input type="text" name="nama" id="nama" required>

    <label for="bulan">Pilih Bulan</label>
    <select name="bulan" id="bulan" required>
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

    <label for="target">Target</label>
    <input type="number" name="target" id="target" step="0.01" required>

    <label for="pogres">Pogres</label>
    <input type="number" name="pogres" id="pogres" step="0.01" required>

    <div class="button-container">
        <button type="button" class="back-btn" onclick="confirmBack()">Kembali</button>
        <button type="submit" class="submit-btn">Simpan</button>
    </div>
</form>

<script>
function confirmBack() {
    Swal.fire({
        title: 'Yakin ingin kembali?',
        text: 'Perubahan yang belum disimpan akan hilang.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, kembali!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'index.php';
        }
    });
}
</script>

</body>
</html>

<?php
// Koneksi ke database
include 'koneksi.php';

// Ambil data dari database
function getEmployees($conn) {
    $sql = "SELECT * FROM employees";
    $result = $conn->query($sql);
    $employees = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $employees[] = $row;
        }
    }
    return $employees;
}

$employees = getEmployees($conn);

// Update data jika form dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save'])) {
    if (isset($_POST['employees']) && is_array($_POST['employees'])) {
        foreach ($_POST['employees'] as $index => $empData) {
            $id = intval($empData['id']);
            $progress = floatval($empData['progress']);
            $skill = floatval($empData['skill']);
            $attitude = floatval($empData['attitude']);
            
            // Hitung total nilai dan grade
            $score = $progress + $skill + $attitude;
            $grade = ($score >= 90) ? 'A' : (($score >= 70) ? 'B' : (($score >= 50) ? 'C' : 'D'));
            
            // Perbarui database
            $stmt = $conn->prepare("UPDATE employees SET progress = ?, skill = ?, attitude = ?, total_score = ?, grade = ? WHERE id = ?");
            $stmt->bind_param("dddssi", $progress, $skill, $attitude, $score, $grade, $id);
            $stmt->execute();
        }
    }
}

// Tambah data baru
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $name = $_POST['name'];
    $progress = floatval($_POST['progress']);
    $skill = floatval($_POST['skill']);
    $attitude = floatval($_POST['attitude']);
    
    // Hitung total nilai dan grade
    $score = $progress + $skill + $attitude;
    $grade = ($score >= 90) ? 'A' : (($score >= 70) ? 'B' : (($score >= 50) ? 'C' : 'D'));
    
    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO employees (name, progress, skill, attitude, total_score, grade) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdddss", $name, $progress, $skill, $attitude, $score, $grade);
    $stmt->execute();
    header("Location: admin.php");
}

// Hapus data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $idToDelete = intval($_POST['delete_id']);
    $stmt = $conn->prepare("DELETE FROM employees WHERE id = ?");
    $stmt->bind_param("i", $idToDelete);
    $stmt->execute();
    header("Location: admin.php");
    exit();
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Grade Karyawan</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }
        h2 { text-align: center; color: #2c3e50; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        th { background-color: #3498db; color: white; }
        td input {
            width: 100%;
            padding: 5px;
            text-align: center;
        }
        .button-container {
            text-align: center;
            margin-top: 20px;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        .save-btn { background-color: #27ae60; color: white; }
        .edit-btn { background-color: #f39c12; color: white; }
        .back-btn { background-color: #e74c3c; color: white; }

        @media print {
            .button-container {
                display: none !important;
            }

            body {
                background: white;
            }

            .logo-container img {
                max-height: 60px;
            }
        }
.print-btn {
    background-color: #8e44ad;
    color: white;
}


    </style>
</head>
<body>
<div style="text-align: center; margin-bottom: 20px;">
    <img src="assets/logoku.png" alt="Logo Perusahaan" style="max-height: 80px;">
</div>

<h2>Grade Karyawan - Admin</h2>

<form method="POST" action="">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Pegawai</th>
                <th>Nama</th>
                <th>Progress</th>
                <th>Personal Skill</th>
                <th>Attitude</th>
                <th>Total Nilai</th>
                <th>Grade</th>
                <th>Edit</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employees as $index => $employee): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td>
                    <input type="number" name="employees[<?= $index ?>][id]" value="<?= $employee['id'] ?>">
                    <?= $employee['employee_id'] ?>
                </td>
                <td><input type="text" name="employees[<?= $index ?>][name]" value="<?= $employee['name'] ?>" readonly></td>
                <td><input type="number" step="0.1" name="employees[<?= $index ?>][progress]" value="<?= $employee['progress'] ?>"></td>
                <td><input type="number" step="0.1" name="employees[<?= $index ?>][skill]" value="<?= $employee['skill'] ?>"></td>
                <td><input type="number" step="0.1" name="employees[<?= $index ?>][attitude]" value="<?= $employee['attitude'] ?>"></td>
                <td><?= number_format($employee['total_score'], 1) ?>%</td>
                <td><?= $employee['grade'] ?></td>
                <td><button class="edit-btn" type="button" onclick="editData(<?= $index ?>)">Edit</button></td>
                <td>
    <form method="POST" action="" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
        <input type="hidden" name="delete_id" value="<?= $employee['id'] ?>">
        <button class="btn back-btn" type="submit" name="delete">Hapus</button>
    </form>
</td>

            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="button-container">
        <button class="btn save-btn" type="submit" name="save">Simpan</button>
        <button class="btn back-btn" type="button" onclick="confirmBack()">Kembali ke Dashboard</button>
        <button class="btn print-btn" type="button" onclick="window.print()">Cetak</button>
    </div>
</form>

<!-- Form Tambah Data -->
<form method="POST" action="">
    <table>
        <tr>
            <td><input type="text" name="name" placeholder="Nama Karyawan" required></td>
            <td><input type="number" step="0.1" name="progress" placeholder="Progress" required></td>
            <td><input type="number" step="0.1" name="skill" placeholder="Skill" required></td>
            <td><input type="number" step="0.1" name="attitude" placeholder="Attitude" required></td>
            <td colspan="2"><button class="btn add-btn" type="submit" name="add">Tambah Karyawan</button></td>
        </tr>
    </table>
</form>


</table>

<script>
function editData(index) {
    Swal.fire({
        title: 'Edit Data',
        text: 'Silakan ubah nilai pada kolom yang tersedia',
        icon: 'info',
        confirmButtonText: 'OK'
    });
}

function confirmBack() {
    Swal.fire({
        title: 'Kembali ke Dashboard?',
        text: 'Semua perubahan yang belum disimpan akan hilang!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, kembali!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'index.php';
        }
    });
}
</script>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Grade Karyawan</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; padding: 20px; }
        h2 { text-align: center; color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: center; }
        th { background-color: #3498db; color: white; }
        td input { width: 100%; padding: 5px; text-align: center; }
        .button-container { text-align: center; margin-top: 20px; }
        .btn { padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; }
        .save-btn { background-color: #27ae60; color: white; }
        .add-btn { background-color: #2980b9; color: white; }
        .back-btn { background-color: #e74c3c; color: white; }
    </style>
</head>
</body>
</html>

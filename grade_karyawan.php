<?php
include 'koneksi.php';

// Fungsi untuk mengambil data karyawan dari database
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
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade Karyawan - Admin</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 20px; }
        h2 { text-align: center; color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: center; }
        th { background-color: #3498db; color: white; }
        td input[type="number"] { width: 60px; padding: 5px; text-align: center; }
        .button-container { text-align: center; margin-bottom: 20px; }
        button { padding: 10px 20px; margin: 5px; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; }
        .save-btn { background-color: #27ae60; color: white; }
        .delete-btn { background-color: #e74c3c; color: white; }
        .edit-btn { background-color: #f39c12; color: white; }
        .add-btn { background-color: #3498db; color: white; }
    </style>
</head>
<body>

    <h2>Grade Karyawan Bulan Februari (Admin)</h2>

    <form method="POST" action="tambah_petugas.php">
        <div class="button-container">
            <button class="add-btn" type="submit">Tambah Petugas Baru</button>
        </div>
    </form>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Pegawai</th>
                <th>Petugas</th>
                <th>Progress</th>
                <th>Personal Skill</th>
                <th>Attitude</th>
                <th>Score</th>
                <th>Grade</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="employeeTable">
            <?php if (count($employees) > 0): ?>
                <?php foreach ($employees as $index => $emp): ?>
                    <?php
                    $progress = $emp['progress'];
                    $skill = $emp['skill'];
                    $attitude = $emp['attitude'];
                    $score = (($progress * 70) / 100) + (($skill * 15) / 100) + (($attitude * 15) / 100);
                    $grade = ($score >= 90) ? 'A' : (($score >= 70) ? 'B' : (($score >= 50) ? 'C' : 'D'));
                    ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= $emp['employee_id'] ?></td>
                        <td><?= $emp['name'] ?></td>
                        <td><input type="number" value="<?= $progress ?>" onchange="updateValue(<?= $emp['id'] ?>, 'progress', this.value)"></td>
                        <td><input type="number" value="<?= $skill ?>" onchange="updateValue(<?= $emp['id'] ?>, 'skill', this.value)"></td>
                        <td><input type="number" value="<?= $attitude ?>" onchange="updateValue(<?= $emp['id'] ?>, 'attitude', this.value)"></td>
                        <td><?= number_format($score, 1) ?>%</td>
                        <td><?= $grade ?></td>
                        <td>
                            <button class="edit-btn" onclick="editData(<?= $emp['id'] ?>)">Edit</button>
                            <button class="delete-btn" onclick="deleteData(<?= $emp['id'] ?>)">Hapus</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">Tidak ada data karyawan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <script>
        function updateValue(id, key, value) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "update_karyawan.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send(`id=${id}&key=${key}&value=${value}`);
        }

        function editData(id) {
            alert("Edit data ID: " + id);
        }

        function deleteData(id) {
            if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                window.location.href = "hapus_karyawan.php?id=" + id;
            }
        }
    </script>

</body>
</html>

<?php $conn->close(); ?>

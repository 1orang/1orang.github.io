<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_id = $_POST['employee_id'];
    $name = $_POST['name'];
    $progress = $_POST['progress'];
    $skill = $_POST['skill'];
    $attitude = $_POST['attitude'];

    $sql = "INSERT INTO employees (employee_id, name, progress, skill, attitude) VALUES ('$employee_id', '$name', '$progress', '$skill', '$attitude')";

    if ($conn->query($sql) === TRUE) {
        echo "Petugas baru berhasil ditambahkan";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
    header("Location: grade_karyawan.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Petugas Baru</title>
</head>
<body>
    <h2>Tambah Petugas Baru</h2>
    <form method="POST" action="">
        <label>ID Pegawai:</label><br>
        <input type="text" name="employee_id" required><br><br>
        <label>Nama:</label><br>
        <input type="text" name="name" required><br><br>
        <label>Progress:</label><br>
        <input type="number" name="progress" required><br><br>
        <label>Personal Skill:</label><br>
        <input type="number" name="skill" required><br><br>
        <label>Attitude:</label><br>
        <input type="number" name="attitude" required><br><br>
        <button type="submit">Tambah Petugas</button>
    </form>
</body>
</html>

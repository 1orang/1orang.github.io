<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM employees WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Data berhasil dihapus";
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $conn->close();
    header("Location: grade_karyawan.php");
    exit();
}
?>

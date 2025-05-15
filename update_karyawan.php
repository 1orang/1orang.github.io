<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $key = $_POST['key'];
    $value = $_POST['value'];

    $sql = "UPDATE employees SET $key = '$value' WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Data berhasil diperbarui";
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $conn->close();
}
?>

<?php
include 'koneksi.php';
?>

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['photo'])) {
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $filename = time() . '_' . basename($_FILES['photo']['name']);
    $targetFile = $uploadDir . $filename;

    if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        $stmt = $conn->prepare("INSERT INTO upload_foto (filename, latitude, longitude) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $filename, $latitude, $longitude);
        $stmt->execute();

        echo json_encode(['status' => 'success', 'filename' => $filename]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Upload gagal']);
    }
    exit;
}
?>

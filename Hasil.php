<?php
$dataFile = 'uploads/data.csv';
$uploadsDir = 'uploads/';
$data = [];
if (file_exists($dataFile)) {
    $file = fopen($dataFile, 'r');
    while (($row = fgetcsv($file)) !== false) {
        $data[] = $row;
    }
    fclose($file);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $filenameToDelete = $_POST['filename'];
    
    // Hapus file foto
    if (file_exists($uploadsDir . $filenameToDelete)) {
        unlink($uploadsDir . $filenameToDelete);
    }
    
    // Perbarui data.csv
    $newData = array_filter($data, function($row) use ($filenameToDelete) {
        return $row[0] !== $filenameToDelete;
    });
    
    $file = fopen($dataFile, 'w');
    foreach ($newData as $row) {
        fputcsv($file, $row);
    }
    fclose($file);
    
    echo json_encode(['status' => 'success']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Foto Upload</title>
    <!--<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&callback=initMap" async defer></script>-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f4;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background: #007BFF;
            color: white;
        }
        button {
            padding: 5px 10px;
            margin: 5px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .delete-btn {
            background: red;
            color: white;
        }
        .back-btn {
            background: #28a745;
            color: white;
            margin-top: 20px;
        }
        .gambar-container {
            margin-top: 20px;
            width: 100%;
            height: 400px;
            border: 1px solid #ddd;
        }
        #gambar {
    background-color: grey;
}

    </style>
</head>
    
</html>
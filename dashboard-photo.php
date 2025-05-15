<?php 
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Galeri</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: #f4f4f4;
            font-family: sans-serif;
            text-align: center;
            padding: 20px;
        }
        .gallery {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
        }
        .gallery-item {
            background: white;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0,0,0,0.2);
            width: 180px;
            position: relative;
        }
        .gallery-item img {
            width: 140px;
            height: 140px;
            object-fit: cover;
            border-radius: 5px;
        }
        .meta {
            font-size: 12px;
            margin-top: 5px;
            color: #333;
        }
        .buttons {
            margin-top: 10px;
            display: flex;
            justify-content: center;
            gap: 5px;
        }
        .buttons button {
            padding: 5px 10px;
            font-size: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .back-button {
            margin-bottom: 20px;
            padding: 10px 20px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .edit-btn {
            background: #f1c40f;
            color: black;
        }
        .delete-btn {
            background: #e74c3c;
            color: white;
        }
    </style>
</head>
<body>

<button class="back-button" onclick="window.location.href='index.php'">← Kembali ke Beranda</button>

<h2>Dashboard Galeri Upload</h2>

<div class="gallery">
<?php
$result = $conn->query("SELECT * FROM upload_foto ORDER BY id DESC");
while ($row = $result->fetch_assoc()) {
    echo '<div class="gallery-item">';
    echo '<img src="uploads/' . htmlspecialchars($row['filename']) . '" alt="Foto">';
    echo '<div class="meta">Lat: ' . $row['latitude'] . '<br>Lng: ' . $row['longitude'] . '<br>' . $row['timestamp'] . '</div>';
    echo '<div class="buttons">';
    echo '<button class="edit-btn" onclick="editItem(' . $row['id'] . ')">Edit</button>';
    echo '<button class="delete-btn" onclick="deleteItem(' . $row['id'] . ')">Hapus</button>';
    echo '</div>';
    echo '</div>';
}
?>
</div>

<script>
function deleteItem(id) {
    Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: "Data yang dihapus tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e74c3c',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'hapus.php?id=' + id;
        }
    });
}

function editItem(id) {
    Swal.fire({
        title: 'Mohon MAAF belum tersedia?',
        text: "Hubungi UMAM CS!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e74c3c',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Terimakasih!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'index.php?id=' + id;
        }
    });
}
</script>

</body>
</html>

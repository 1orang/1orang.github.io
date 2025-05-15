<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['photo'])) {
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $filename = time() . '_' . basename($_FILES['photo']['name']);
    $targetFile = $uploadDir . $filename;

    if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];

        // Debug sebelum insert
        if (!$conn) {
            echo json_encode(['status' => 'error', 'message' => 'Koneksi ke database gagal']);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO upload_foto (filename, latitude, longitude) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $filename, $latitude, $longitude);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'filename' => $filename]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal insert ke DB: ' . $stmt->error]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Upload gagal']);
    }
    exit;
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Foto dengan Lokasi</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .navbar-menu {
            display: flex;
            justify-content: center;
            gap: 20px;
            background: #333;
            padding: 15px;
            border-radius: 8px;
        }
        .navbar-menu a {
            position: relative;
            text-decoration: none;
            color: white;
            font-size: 16px;
            padding: 10px 15px;
        }
        .navbar-menu a::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: -25px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease-in-out;
        }
        .navbar-menu a:hover::after {
            opacity: 1;
            visibility: visible;
        }
        .gallery {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        .gallery img {
            width: 150px;
            height: 150px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            animation: moveUpDown 2s infinite alternate;
        }
        @keyframes moveUpDown {
            from { transform: translateY(0); }
            to { transform: translateY(-10px); }
        }

        @keyframes scrollText {
            from { transform: translateX(100%); }
            to { transform: translateX(-100%); }
        }
        .marquee {
            display: block;
            background: #ffcc00;
            color: black;
            padding: 10px;
            font-weight: bold;
            overflow: hidden;
            white-space: nowrap;
        }
        .marquee span {
            display: inline-block;
            padding-left: 100%;
            animation: scrollText 15s linear infinite;
        }
        .footer {
            background: #222;
            color: white;
            padding: 15px;
            text-align: center;
            margin-top: auto;
        }
    </style>
</head>
<body>
    <div class="navbar-menu">
        <a href="index.php" data-tooltip="Kembali ke Beranda">Beranda</a>
        <a href="#" data-tooltip="Lihat Profil Anda">Profil</a>
        <a href="setting.php" data-tooltip="Atur Preferensi Anda">Pengaturan</a>
        <a href="#" data-tooltip="Unggah Laporan Anda">Upload</a>
        <a href="Dashboard-photo.php" data-tooltip="Lihat Hasil Upload">Hasil</a>
    </div>

    <div class="marquee"><span>🌟 Selamat datang! Unggah foto Anda dengan lokasi terkini! 🌟 </span></div>

    <h2>Upload Foto dengan Lokasi</h2>
    <form id="uploadForm" enctype="multipart/form-data">
        <input type="file" name="photo" id="photo" required>
        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">
        <button type="submit">Upload</button>
    </form>

    <br>
    <div class="button-container">
        <button id="backToDashboard">Kembali ke Dashboard</button>
    </div>

    <h3>Galeri Hasil Upload</h3>
    <div id="gallery" class="gallery"></div>

    <div class="footer">&copy; 2025 Aplikasi Upload Foto. Semua Hak Dilindungi.</div>

    <script>
        document.getElementById('backToDashboard').addEventListener('click', function () {
            window.location.href = 'index.php';
        });

        // Ambil lokasi
        navigator.geolocation.getCurrentPosition(position => {
            document.getElementById('latitude').value = position.coords.latitude;
            document.getElementById('longitude').value = position.coords.longitude;
        }, error => {
            console.error('Gagal mendapatkan lokasi:', error.message);
        });

        // Upload handler
        document.getElementById('uploadForm').addEventListener('submit', function (event) {
            event.preventDefault();
            let formData = new FormData(this);

            fetch('', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            title: 'Upload Berhasil!',
                            text: 'Menampilkan hasil...',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            loadGallery(); // Refresh galeri
                        });
                    } else {
                        Swal.fire('Error', 'Upload gagal: ' + data.message, 'error');
                    }
                }).catch(err => {
                    Swal.fire('Error', 'Terjadi kesalahan server.', 'error');
                    console.error('Error:', err);
                });
        });

        // Fungsi untuk load galeri
        function loadGallery() {
            fetch('?gallery=1')
                .then(response => response.json())
                .then(data => {
                    const gallery = document.getElementById('gallery');
                    gallery.innerHTML = '';
                    data.reverse().forEach(item => {
                        const img = document.createElement('img');
                        img.src = 'uploads/' + item.filename;
                        img.alt = item.timestamp;
                        img.title = `Lat: ${item.latitude}, Lng: ${item.longitude}\nWaktu: ${item.timestamp}`;
                        gallery.appendChild(img);
                    });
                })
                .catch(err => console.error('Gagal memuat galeri:', err));
        }

        // Load galeri saat halaman dibuka
        window.onload = loadGallery;
    </script>
</body>
</html>

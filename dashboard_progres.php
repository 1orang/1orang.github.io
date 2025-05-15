<?php
include 'koneksi.php';

// Ambil data dari database
$sql = "SELECT 
            SUM(pogres_januari) AS total_januari,
            SUM(pogres_februari) AS total_februari,
            SUM(pogres_maret) AS total_maret,
            SUM(pogres_april) AS total_april,
            SUM(pogres_mei) AS total_mei,
            SUM(pogres_juni) AS total_juni,
            SUM(pogres_juli) AS total_juli,
            SUM(pogres_agustus) AS total_agustus,
            SUM(pogres_september) AS total_september,
            SUM(pogres_oktober) AS total_oktober,
            SUM(pogres_november) AS total_november,
            SUM(pogres_desember) AS total_desember
        FROM target";

$result = $conn->query($sql);
$data = $result->fetch_assoc();

// Hitung total progres tahunan
$total_progres = array_sum($data);

// Konversi ke persen tanpa desimal
$total_progres = round($total_progres);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Progres</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f7f6;
            text-align: center;
        }
        .container {
            max-width: 800px;
            background: white;
            padding: 20px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .card {
            background: linear-gradient(to right, #4CAF50, #2E7D32);
            color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }
        .btn-container {
            text-align: center;
            margin-top: 15px;
        }
        .btn {
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
            color: white;
            background-color: #f44336;
        }
        .btn:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Total Progres Keseluruhan</h2>

    <div class="card">
        <?= $total_progres ?>%
    </div>

    <div class="btn-container">
        <button class="btn" onclick="confirmBack()">Kembali ke Dashboard</button>
    </div>
</div>

<script>
    function confirmBack() {
        Swal.fire({
            title: 'Kembali ke Dashboard?',
            text: 'Anda akan kembali ke halaman utama!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, kembali!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'dashboard.php';
            }
        });
    }
</script>

</body>
</html>

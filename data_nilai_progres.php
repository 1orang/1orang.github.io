<?php
include 'koneksi.php';

// Ambil data dari database
$sql = "SELECT * FROM target";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Nilai Progres</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f7f6;
        }
        .container {
            max-width: 90%;
            background: white;
            padding: 20px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .btn-container {
            text-align: right;
            margin-top: 15px;
        }
        .btn {
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
        }
        .btn-back {
            background-color: #f44336;
            color: white;
        }
        .btn-back:hover {
            background-color: #d32f2f;
        }
        .status-tercapai {
            background-color: #4CAF50;
            color: white;
            padding: 5px;
            border-radius: 5px;
        }
        .status-belum {
            background-color: #f44336;
            color: white;
            padding: 5px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Data Nilai Progres</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Januari</th>
            <th>Status Januari</th>
            <th>Februari</th>
            <th>Status Februari</th>
            <th>Maret</th>
            <th>Status Maret</th>
            <th>April</th>
            <th>Status April</th>
            <th>Mei</th>
            <th>Status Mei</th>
            <th>Juni</th>
            <th>Status Juni</th>
            <th>Juli</th>
            <th>Status Juli</th>
            <th>Agustus</th>
            <th>Status Agustus</th>
            <th>September</th>
            <th>Status September</th>
            <th>Oktober</th>
            <th>Status Oktober</th>
            <th>November</th>
            <th>Status November</th>
            <th>Desember</th>
            <th>Status Desember</th>
        </tr>
        
        <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>

                <td><?= round($row['pogres_januari'] ?? 0) ?>%</td>
                <td>
                    <?php if (round($row['pogres_januari'] ?? 0) == 90) : ?>
                        <span class="status-tercapai">Tercapai</span>
                    <?php else : ?>
                        <span class="status-belum">Belum Tercapai</span>
                    <?php endif; ?>
                </td>

                <td><?= round($row['pogres_februari'] ?? 0) ?>%</td>
                <td>
                    <?php if (round($row['pogres_februari'] ?? 0) == 100) : ?>
                        <span class="status-tercapai">Tercapai</span>
                    <?php else : ?>
                        <span class="status-belum">Belum Tercapai</span>
                    <?php endif; ?>
                </td>

                <td><?= round($row['pogres_maret'] ?? 0) ?>%</td>
                <td>
                    <?php if (round($row['pogres_maret'] ?? 0) == 100) : ?>
                        <span class="status-tercapai">Tercapai</span>
                    <?php else : ?>
                        <span class="status-belum">Belum Tercapai</span>
                    <?php endif; ?>
                </td>

                <td><?= round($row['pogres_april'] ?? 0) ?>%</td>
                <td>
                    <?php if (round($row['pogres_april'] ?? 0) == 100) : ?>
                        <span class="status-tercapai">Tercapai</span>
                    <?php else : ?>
                        <span class="status-belum">Belum Tercapai</span>
                    <?php endif; ?>
                </td>

                <td><?= round($row['pogres_mei'] ?? 0) ?>%</td>
                <td>
                    <?php if (round($row['pogres_mei'] ?? 0) == 100) : ?>
                        <span class="status-tercapai">Tercapai</span>
                    <?php else : ?>
                        <span class="status-belum">Belum Tercapai</span>
                    <?php endif; ?>
                </td>

                <td><?= round($row['pogres_juni'] ?? 0) ?>%</td>
                <td>
                    <?php if (round($row['pogres_juni'] ?? 0) == 100) : ?>
                        <span class="status-tercapai">Tercapai</span>
                    <?php else : ?>
                        <span class="status-belum">Belum Tercapai</span>
                    <?php endif; ?>
                </td>

                <td><?= round($row['pogres_juli'] ?? 0) ?>%</td>
                <td>
                    <?php if (round($row['pogres_juli'] ?? 0) == 100) : ?>
                        <span class="status-tercapai">Tercapai</span>
                    <?php else : ?>
                        <span class="status-belum">Belum Tercapai</span>
                    <?php endif; ?>
                </td>

                <td><?= round($row['pogres_agustus'] ?? 0) ?>%</td>
                <td>
                    <?php if (round($row['pogres_agustus'] ?? 0) == 100) : ?>
                        <span class="status-tercapai">Tercapai</span>
                    <?php else : ?>
                        <span class="status-belum">Belum Tercapai</span>
                    <?php endif; ?>
                </td>

                <td><?= round($row['pogres_september'] ?? 0) ?>%</td>
                <td>
                    <?php if (round($row['pogres_september'] ?? 0) == 100) : ?>
                        <span class="status-tercapai">Tercapai</span>
                    <?php else : ?>
                        <span class="status-belum">Belum Tercapai</span>
                    <?php endif; ?>
                </td>

                <td><?= round($row['pogres_oktober'] ?? 0) ?>%</td>
                <td>
                    <?php if (round($row['pogres_oktober'] ?? 0) == 100) : ?>
                        <span class="status-tercapai">Tercapai</span>
                    <?php else : ?>
                        <span class="status-belum">Belum Tercapai</span>
                    <?php endif; ?>
                </td>

                <td><?= round($row['pogres_november'] ?? 0) ?>%</td>
                <td>
                    <?php if (round($row['pogres_november'] ?? 0) == 100) : ?>
                        <span class="status-tercapai">Tercapai</span>
                    <?php else : ?>
                        <span class="status-belum">Belum Tercapai</span>
                    <?php endif; ?>
                </td>

                <td><?= round($row['pogres_desember'] ?? 0) ?>%</td>
                <td>
                    <?php if (round($row['pogres_desember'] ?? 0) == 100) : ?>
                        <span class="status-tercapai">Tercapai</span>
                    <?php else : ?>
                        <span class="status-belum">Belum Tercapai</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <div class="btn-container">
        <button class="btn btn-back" onclick="confirmBack()">Kembali ke Dashboard</button>
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
                window.location.href = 'index.php';
            }
        });
    }
</script>

</body>
</html>

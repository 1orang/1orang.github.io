<?php
$conn = new mysqli('localhost', 'root', '', 'coba');
if ($conn->connect_error) die("Koneksi gagal: " . $conn->connect_error);

$data = $conn->query("SELECT * FROM angsuran");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pembayaran Mingguan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }

        .up { color: green; font-weight: bold; }
        .down { color: red; font-weight: bold; }

        .print-header, .print-footer {
            display: none;
            text-align: center;
            margin: 20px 0;
        }

        @media print {
            .print-header, .print-footer {
                display: block;
            }
        }

        .back-link {
            margin-bottom: 20px;
            display: inline-block;
        }

        .print-btn {
            margin: 10px 0;
            padding: 8px 16px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .print-btn:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

<div class="print-header">
    <img src="assets/profile.jpg" alt="Logo" style="height: 60px;">
</div>

<h2 align="center">Laporan Pembayaran Angsuran NPF</h2>
<a href="index.php" class="back-link">← Kembali ke Dashboard</a>
<button onclick="window.print()" class="print-btn">Cetak / PDF</button>

<table>
    <thead>
        <tr>
            <th>NO</th>
            <th>NAMA</th>
            <th>PEMBIAYAAN (NPF)</th>
            <th>MINGGU 1</th>
            <th>MINGGU 2</th>
            <th>MINGGU 3</th>
            <th>MINGGU 4</th>
            <th>MINGGU 5</th>
            <th>TOTAL BAYAR</th>
            <th>PROGRES</th>
            <th>SISA</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        while ($row = $data->fetch_assoc()):
            $id = $row['id'];
            $nama = htmlspecialchars($row['nama']);
            $pembiayaan = $row['pembiayaan'];

            $minggu = [
                $row['minggu1'],
                $row['minggu2'],
                $row['minggu3'],
                $row['minggu4'],
                $row['minggu5']
            ];

            $awal = $minggu[0]; // minggu 1
            $total_bayar = array_sum($minggu);
            $persen_awal = $pembiayaan > 0 ? round($awal / $pembiayaan * 100) : 0;
            $persen_akhir = $pembiayaan > 0 ? round($total_bayar / $pembiayaan * 100) : 0;
            $progres = $total_bayar-$pembiayaan;
            $sisa = $pembiayaan - $total_bayar;
            $status = $progres >= 0 ? 'up' : 'down';
            $sisa_status = $sisa >= 0 ? 'up' : 'down';
        ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $nama ?></td>
            <td><?= $pembiayaan ?></td>
            <?php foreach ($minggu as $val): ?>
                <td><?= $val ?></td>
            <?php endforeach; ?>
            <td><?= $total_bayar ?></td>
            <td class="<?= $status ?>"><?= $progres >= 0 ? '+' : '' ?><?= $progres ?>%</td>
            <td class="<?= $sisa_status ?>"><?= $sisa ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<div class="print-footer">
    <p>Dicetak pada: <span id="tanggalCetak"></span></p>
</div>

<script>
    document.getElementById("tanggalCetak").innerText = new Date().toLocaleDateString('id-ID');
</script>

</body>
</html>

<?php $conn->close(); ?>

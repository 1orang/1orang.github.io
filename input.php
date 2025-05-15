<?php
// Koneksi ke database
$conn = new mysqli('localhost', 'root', '', 'coba');
if ($conn->connect_error) die("Koneksi gagal: " . $conn->connect_error);

// Tambah data baru
if (isset($_POST['add'])) {
    $stmt = $conn->prepare("INSERT INTO angsuran (nama, pembiayaan, minggu1, minggu2, minggu3, minggu4, minggu5) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('siiiiii',
        $_POST['nama'],
        $_POST['pembiayaan'],
        $_POST['minggu1'], $_POST['minggu2'], $_POST['minggu3'],
        $_POST['minggu4'], $_POST['minggu5']
    );
    $stmt->execute();
    $stmt->close();
}

// Edit data
if (isset($_POST['edit'])) {
    $stmt = $conn->prepare("UPDATE angsuran SET pembiayaan=?, minggu1=?, minggu2=?, minggu3=?, minggu4=?, minggu5=? WHERE id=?");
    $stmt->bind_param('iiiiiii',
        $_POST['pembiayaan'],
        $_POST['minggu1'], $_POST['minggu2'], $_POST['minggu3'],
        $_POST['minggu4'], $_POST['minggu5'],
        $_POST['id']
    );
    $stmt->execute();
    $stmt->close();
}

// Hapus data
if (isset($_POST['delete'])) {
    $stmt = $conn->prepare("DELETE FROM angsuran WHERE id=?");
    $stmt->bind_param('i', $_POST['id']);
    $stmt->execute();
    $stmt->close();
}

// Ambil semua data
$data = $conn->query("SELECT * FROM angsuran");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Rekap Mingguan Angsuran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #fafafa;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f5f5f5;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        .up {
            color: green;
            font-weight: bold;
        }

        .down {
            color: red;
            font-weight: bold;
        }

        form.inline {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            justify-content: center;
        }

        form.inline input[type="number"] {
            width: 60px;
            padding: 4px;
        }

        form.inline button {
            padding: 4px 8px;
            border: none;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            border-radius: 4px;
        }

        form.inline button:hover {
            background-color: #0056b3;
        }

        form button[name="delete"] {
            background-color: #dc3545;
        }

        form button[name="delete"]:hover {
            background-color: #a71d2a;
        }

        .form-tambah {
            margin-top: 30px;
            background-color: #fff;
            padding: 15px;
            border-radius: 6px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 10px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .form-tambah input, .form-tambah button {
            padding: 8px;
        }

        .form-tambah button {
            grid-column: span 2;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-tambah button:hover {
            background-color: #1e7e34;
        }

        a {
            text-decoration: none;
            display: inline-block;
            margin-bottom: 10px;
            color: #007bff;
        }

        a:hover {
            text-decoration: underline;
        }

        @media print {
    body {
        font-size: 12px;
        background: white;
    }

    a, button, .form-tambah, form.inline {
        display: none !important;
    }

    table {
        width: 100%;
        border: 1px solid #000;
    }

    th, td {
        border: 1px solid #000;
        padding: 5px;
    }

    h2 {
        text-align: center;
    }
}

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


    </style>

</head>
<body>
<div class="print-header">
    <img src="assets/logo.png" alt="Logo" style="height: 60px;">
</div>

<h2 align="center">Perbandingan Pembayaran Mingguan NPF</h2>
<a href="index.php">← Kembali</a>
<button onclick="window.print()" style="margin: 10px 0; padding: 8px 12px; background-color: #17a2b8; color: white; border: none; border-radius: 4px; cursor: pointer;">
    🖨 Cetak / Simpan PDF
</button>


<table>
    <thead>
        <tr>
            <th>NO</th>
            <th>NAMA</th>
            <th>PEMBIAYAAN</th>
            <th>MINGGU 1</th>
            <th>MINGGU 2</th>
            <th>MINGGU 3</th>
            <th>MINGGU 4</th>
            <th>MINGGU 5</th>
            <th>HASIL TOTAL</th>
            <th>PROGRES +/-</th>
            <th>STATUS</th>
            <th>AKSI</th>
        </tr>
    </thead>
    <tbody>
<?php
$no = 1;
while ($row = $data->fetch_assoc()):
    $id = $row['id'];
    $nama = $row['nama'];
    $pembiayaan = $row['pembiayaan'];

    $minggu = [
        $row['minggu1'],
        $row['minggu2'],
        $row['minggu3'],
        $row['minggu4'],
        $row['minggu5']
    ];

    $awal = $minggu[0]; // minggu 1
    $total_bayar = array_sum($minggu); // total minggu 1-5
    $akhir = $total_bayar;

    $persen_awal = $pembiayaan > 0 ? round($awal / $pembiayaan * 100) : 0;
    $persen_akhir = $pembiayaan > 0 ? round($akhir / $pembiayaan * 100) : 0;
    $progres =$total_bayar-$pembiayaan;
    $status = $progres >= 0 ? 'up' : 'down';

    $hasil_total = $pembiayaan - $total_bayar;
    $hasil_status = $hasil_total >= 0 ? 'up' : 'down';
?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= htmlspecialchars($nama) ?></td>
    <td><?= $pembiayaan ?></td>

    <?php foreach ($minggu as $val): ?>
        <td><?= $val ?></td>
    <?php endforeach; ?>

    <td><?= $persen_akhir ?>%</td>
    <td class="<?= $status ?>"><?= $progres >= 0 ? '+' : '' ?><?= $progres ?>%</td>
    <td class="<?= $hasil_status ?>"><?= $hasil_total ?></td>
    <td>
        <form method="post" class="inline">
            <input type="hidden" name="id" value="<?= $id ?>">
            <input type="number" name="pembiayaan" value="<?= $pembiayaan ?>" style="width:60px">
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <input type="number" name="minggu<?= $i ?>" value="<?= $row["minggu$i"] ?>" style="width:60px">
            <?php endfor; ?>
            <button type="submit" name="edit">Edit</button>
        </form>
        <form method="post" class="inline">
            <input type="hidden" name="id" value="<?= $id ?>">
            <button type="submit" name="delete" onclick="return confirm('Yakin hapus?')">Hapus</button>
        </form>
    </td>
</tr>
<?php endwhile; ?>
<div class="print-footer">
    <p>Dicetak pada: <span id="tanggalCetak"></span></p>
</div>
<script>
    // Isi tanggal otomatis
    document.getElementById("tanggalCetak").innerText = new Date().toLocaleDateString('id-ID');
</script>

</tbody>

</table>

<h3>Tambah Data Baru</h3>
<form method="post" class="form-tambah">
    <input type="text" name="nama" placeholder="Nama" required>
    <input type="number" name="pembiayaan" placeholder="Pembiayaan" required>
    <?php for ($i = 1; $i <= 5; $i++): ?>
        <input type="number" name="minggu<?= $i ?>" placeholder="Minggu <?= $i ?>" required>
    <?php endfor; ?>
    <button type="submit" name="add">Tambah</button>
</form>

</body>
</html>

<?php $conn->close(); ?>

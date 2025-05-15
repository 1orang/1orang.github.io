<?php
include 'koneksi.php';

// Tambah Data
if (isset($_POST['add'])) {
    $stmt = $koneksi->prepare("INSERT INTO pembayaran_angsuran (nama_kumpulan, anggota_pembiayaan, bayar_minggu_ini, bayar_minggu_lalu) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siii", $_POST['nama'], $_POST['pembiayaan'], $_POST['minggu_ini'], $_POST['minggu_lalu']);
    $stmt->execute();
}

// Edit Data
if (isset($_POST['edit'])) {
    $stmt = $koneksi->prepare("UPDATE pembayaran_angsuran SET nama_kumpulan=?, anggota_pembiayaan=?, bayar_minggu_ini=?, bayar_minggu_lalu=? WHERE id=?");
    $stmt->bind_param("siiii", $_POST['nama'], $_POST['pembiayaan'], $_POST['minggu_ini'], $_POST['minggu_lalu'], $_POST['id']);
    $stmt->execute();
}

// Hapus Data
if (isset($_POST['delete'])) {
    $stmt = $koneksi->prepare("DELETE FROM pembayaran_angsuran WHERE id=?");
    $stmt->bind_param("i", $_POST['id']);
    $stmt->execute();
}

// Ambil Data
$result = $koneksi->query("SELECT * FROM pembayaran_angsuran ORDER BY id ASC");
$data = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Perbandingan Pembayaran Angsuran</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #999; padding: 8px; text-align: center; }
        th { background-color: #ddd; }
        .up { color: green; }
        .down { color: red; }
        form.inline { display: inline-block; }
    </style>
</head>
<body>

<h2 align="center">Perbandingan Pembayaran Angsuran</h2>

<table>
    <thead>
        <tr>
            <th>NO</th>
            <th>NAMA KUMPULAN</th>
            <th>ANGGOTA PEMBIAYAAN</th>
            <th>BAYAR MINGGU INI</th>
            <th>% INI</th>
            <th>BAYAR MINGGU LALU</th>
            <th>% LALU</th>
            <th>Δ%</th>
            <th>AKSI</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $i => $row): 
            $persen_ini = round(($row['bayar_minggu_ini'] / $row['anggota_pembiayaan']) * 100);
            $persen_lalu = round(($row['bayar_minggu_lalu'] / $row['anggota_pembiayaan']) * 100);
            $delta = $persen_ini - $persen_lalu;
        ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= htmlspecialchars($row['nama_kumpulan']) ?></td>
            <td><?= $row['anggota_pembiayaan'] ?></td>
            <td><?= $row['bayar_minggu_ini'] ?></td>
            <td><?= $persen_ini ?>%</td>
            <td><?= $row['bayar_minggu_lalu'] ?></td>
            <td><?= $persen_lalu ?>%</td>
            <td class="<?= $delta >= 0 ? 'up' : 'down' ?>">
                <?= $delta >= 0 ? '+' : '' ?><?= $delta ?>%
            </td>
            <td>
                <!-- Form Edit -->
                <form method="post" class="inline">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <input type="text" name="nama" value="<?= htmlspecialchars($row['nama_kumpulan']) ?>" required>
                    <input type="number" name="pembiayaan" value="<?= $row['anggota_pembiayaan'] ?>" required style="width:80px">
                    <input type="number" name="minggu_ini" value="<?= $row['bayar_minggu_ini'] ?>" required style="width:80px">
                    <input type="number" name="minggu_lalu" value="<?= $row['bayar_minggu_lalu'] ?>" required style="width:80px">
                    <button type="submit" name="edit">Edit</button>
                </form>
                <!-- Form Hapus -->
                <form method="post" class="inline">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" name="delete" onclick="return confirm('Yakin hapus?')">Hapus</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h3>Tambah Data Baru</h3>
<form method="post">
    <input type="text" name="nama" placeholder="Nama Kumpulan" required>
    <input type="number" name="pembiayaan" placeholder="Pembiayaan" required>
    <input type="number" name="minggu_ini" placeholder="Bayar Minggu Ini" required>
    <input type="number" name="minggu_lalu" placeholder="Bayar Minggu Lalu" required>
    <button type="submit" name="add">Tambah</button>
</form>

</body>
</html>

<?php
include 'koneksi.php';

// Ambil data karyawan dari database
$sql = "SELECT * FROM employees";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade Karyawan - Staff</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-container img {
            max-height: 80px;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: #fff;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        .button-container {
            text-align: center;
            margin-top: 20px;
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            margin: 5px;
        }

        .back-btn {
            background-color: #e74c3c;
            color: white;
        }

        .print-btn {
            background-color: #8e44ad;
            color: white;
        }

        @media print {
            .button-container {
                display: none !important;
            }

            body {
                background: white;
            }

            .logo-container img {
                max-height: 60px;
            }
        }
    </style>
</head>
<body>

<div class="logo-container">
    <img src="assets/logoku.png" alt="Logo Perusahaan">
</div>

<h2>Grade Karyawan - Tampilan Staff</h2>

<table>
    <thead>
        <tr>
            <th>No</th>
            
            <th>Nama</th>
            <th>Progress</th>
            <th>Personal Skill</th>
            <th>Attitude</th>
            <th>Total Nilai</th>
            <th>Grade</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php $no = 1; ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <?php
                    $score = $row['progress'] + $row['skill'] + $row['attitude'];
                    $grade = ($score >= 90) ? 'A' : (($score >= 70) ? 'B' : (($score >= 50) ? 'C' : 'D'));
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                  
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['progress'] ?></td>
                    <td><?= $row['skill'] ?></td>
                    <td><?= $row['attitude'] ?></td>
                    <td><?= number_format($score, 1) ?>%</td>
                    <td><?= $grade ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="8">Tidak ada data karyawan.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<div class="button-container">
    <button class="btn print-btn" onclick="window.print()">Cetak</button>
    <button class="btn back-btn" onclick="confirmBack()">Kembali ke Dashboard</button>
</div>

<script>
function confirmBack() {
    Swal.fire({
        title: 'Kembali ke Dashboard?',
        text: 'Anda akan keluar dari tampilan staff.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, kembali!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'index.php';
        }
    });
}
</script>

</body>
</html>

<?php $conn->close(); ?>

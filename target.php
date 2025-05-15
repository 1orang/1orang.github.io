<?php
include 'koneksi.php';

// Mengambil data target dari database
$sql = "SELECT * FROM target";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Target Karyawan</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php
    // SweetAlert untuk menampilkan pesan sukses atau error setelah redirect
    if (isset($_GET['status']) && $_GET['status'] == 'sukses') {
        echo "<script>
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Data berhasil dihapus!',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
              </script>";
    } elseif (isset($_GET['status']) && $_GET['status'] == 'error') {
        echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Gagal menghapus data!',
                    icon: 'error',
                    timer: 2000,
                    showConfirmButton: false
                });
              </script>";
    }
    ?>

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
        }

        h2 {
            text-align: center;
            padding: 20px;
            background-color: #007bff;
            color: white;
            margin: 0;
            border-radius: 0 0 10px 10px;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 30px auto;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 20px;
        }

        .dropdown {
            width: 50%;
            margin: 20px auto;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        select {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #007bff;
            font-size: 16px;
        }

        .data-container {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 15px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        td {
            background-color: #f9f9f9;
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .edit-btn, .delete-btn {
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .edit-btn {
            background-color: #28a745;
            color: white;
        }

        .edit-btn:hover {
            background-color: #218838;
        }

        .delete-btn {
            background-color: #dc3545;
            color: white;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        .dashboard-btn {
            display: block;
            margin: 20px auto;
            padding: 10px 30px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .dashboard-btn:hover {
            background-color: #0056b3;
        }

        @media screen and (max-width: 768px) {
            table {
                font-size: 12px;
            }

            th, td {
                padding: 10px;
            }

            .edit-btn, .delete-btn {
                padding: 8px 15px;
                font-size: 12px;
            }

            .dashboard-btn {
                padding: 8px 20px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<h2>Daftar Target Karyawan</h2>

<div class="container">
    <!-- Dropdown untuk memilih karyawan -->
    <div class="dropdown">
        <select id="karyawanDropdown" onchange="showData(this.value)">
            <option value="">Pilih Karyawan</option>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['nama']) . "</option>";
                }
            }
            ?>
        </select>
    </div>

    <!-- Kontainer untuk menampilkan data target karyawan -->
    <div id="dataContainer" class="data-container"></div>

    <!-- Tombol Kembali ke Dashboard -->
    <a href="index.php" class="dashboard-btn">Kembali ke Dashboard</a>
</div>

<script>
function showData(karyawanId) {
    if (karyawanId === "") {
        document.getElementById('dataContainer').innerHTML = "";
        return;
    }

    // Mengambil data target karyawan berdasarkan id
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        document.getElementById('dataContainer').innerHTML = this.responseText;
    }
    xhttp.open("GET", "get_target_data.php?id=" + karyawanId, true);
    xhttp.send();
}
</script>
<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Yakin ingin menghapus data?',
        text: 'Data yang dihapus tidak dapat dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'hapus_target.php?id=' + id;
        }
    });
}

function editData(id) {
    window.location.href = 'edit_target.php?id=' + id;
}
</script>

</body>
</html>

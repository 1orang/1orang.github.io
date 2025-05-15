<?php
include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Halaman User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 20px;
            text-align: center;
        }
        h1 {
            color: #2c3e50;
        }
        .container {
            width: 80%;
            margin: auto;
            text-align: left;
        }
        table {
            width: 100%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: #2c3e50;
            color: white;
        }
        tr:hover {
            background: #f1f1f1;
        }
        button {
            padding: 8px 12px;
            margin: 5px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .button-container {
            text-align: right;
            margin-bottom: 10px;
        }
        .add-btn {
            background: #27ae60;
            color: white;
            padding: 10px 15px;
        }
        .edit-btn {
            background: #3498db;
            color: white;
        }
        .delete-btn {
            background: #e74c3c;
            color: white;
        }
        .back-btn {
            background: #2ecc71;
            color: white;
            margin-top: 20px;
            padding: 10px 15px;
        }
        .form-container {
            margin: 20px auto;
            padding: 20px;
            background: white;
            width: 50%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .form-container input, .form-container select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

         /*ini buat edit user*/
    /* Modal container */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4); /* Black background with opacity */
        justify-content: center;
        align-items: center;
    }

    /* Modal content box */
    .modal-content {
        background-color: #fff;
        margin: auto;
        padding: 20px;
        border: 1px solid #ccc;
        width: 100%;
        max-width: 500px;
        border-radius: 12px;
        box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
        animation: slide-down 0.4s ease-out;
    }

    /* Animation for modal */
    @keyframes slide-down {
        from { transform: translateY(-50px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    /* Close button (X) */
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: #333;
    }

    /* Title */
    .modal-content h2 {
        text-align: center;
        font-size: 24px;
        margin-bottom: 20px;
        color: #333;
    }

    /* Labels */
    .modal-content label {
        font-size: 14px;
        color: #555;
        margin-bottom: 8px;
        display: block;
    }

    /* Input fields */
    .modal-content input[type="text"],
    .modal-content input[type="email"],
    .modal-content input[type="password"],
    .modal-content select {
        width: 100%;
        padding: 10px;
        margin: 8px 0;
        display: block;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-sizing: border-box;
        font-size: 14px;
        background-color: #f9f9f9;
    }

    /* Button */
    .modal-content button[type="submit"] {
        width: 100%;
        background-color: #4CAF50;
        color: white;
        padding: 10px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        font-weight: bold;
        margin-top: 10px;
        transition: background-color 0.3s ease;
    }

    .modal-content button[type="submit"]:hover {
        background-color: #45a049;
    }

    /* Responsive design for smaller screens */
    @media (max-width: 600px) {
        .modal-content {
            width: 90%;
        }

        .modal-content button[type="submit"] {
            font-size: 14px;
        }
    }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <h1>Halaman User</h1>
        <p>Daftar pengguna dan manajemen akun.</p>

        <div class="button-container">
            <button class="add-btn" onclick="document.getElementById('formUser').style.display='block'">Tambah User</button>
        </div>

        <div id="formUser" class="form-container" style="display:none;">
            <h2>Tambah User</h2>
            <form action="tambah_user.php" method="POST" enctype="multipart/form-data">
                <input type="text" name="id" placeholder="ID" required>
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <select name="level" required>
                    <option value="" disabled selected>Pilih Level</option>
                    <option value="admin">Admin</option>
                    <option value="staff">Staff</option>
                    <option value="user">User</option>
                </select>
                <input type="file" name="foto" accept="image/*">
                <button type="submit" class="add-btn">Simpan</button>
                <button type="button" class="delete-btn" onclick="document.getElementById('formUser').style.display='none'">Batal</button>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No ID</th>
                    <th>Foto</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th>Level</th>
                    <th>Status</th> <!-- Kolom status -->
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT id, photo, username, email, password, level, STATUS FROM user");
                while ($row = $result->fetch_assoc()) {
                    $foto = !empty($row['photo']) ? 'uploads/' . $row['photo'] : 'uploads/default.jpg';
                    $status = ucfirst($row['STATUS']); // Ambil status dan ubah huruf pertama menjadi kapital
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td><img src='{$foto}' alt='Foto {$row['username']}' width='50' height='50' style='border-radius: 50%;'></td>
                            <td>{$row['username']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['password']}</td>
                            <td>{$row['level']}</td>
                            <td>{$status}</td> <!-- Tampilkan status -->
                            <td>
                                <button class='edit-btn' onclick='editUser({$row['id']}, \"{$row['username']}\", \"{$row['email']}\", \"{$row['password']}\", \"{$row['level']}\", \"{$row['STATUS']}\")'>Edit</button>
                                <button class='delete-btn' onclick='hapusUser({$row['id']})'>Hapus</button>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>

        <button class="back-btn" onclick="window.location.href='index.php'">Kembali ke Dashboard</button>
    </div>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('editModal').style.display='none'">&times;</span>
            <h2>Edit User</h2>
            <form action="edit_user.php" method="POST">
                <input type="hidden" name="id" id="edit-id">
                <label for="edit-username">Username:</label>
                <input type="text" name="username" id="edit-username" required>
                <label for="edit-email">Email:</label>
                <input type="email" name="email" id="edit-email" required>
                <label for="edit-password">Password:</label>
                <input type="password" name="password" id="edit-password">
                <label for="edit-level">Level:</label>
                <select name="level" id="edit-level" required>
                    <option value="admin">Admin</option>
                    <option value="staff">Staff</option>
                    <option value="user">User</option>
                </select>
                <label for="edit-status">Status:</label>
                <select name="STATUS" id="edit-status" required>
                    <option value="online">Online</option>
                    <option value="offline">Offline</option>
                </select>
                <button type="submit" class="edit-btn">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    <script>
        function hapusUser(id) {
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'hapus_user.php?id=' + id;
                }
            });
        }

        function editUser(id, username, email, password, level, status) {
            document.getElementById("edit-id").value = id;
            document.getElementById("edit-username").value = username;
            document.getElementById("edit-email").value = email;
            document.getElementById("edit-password").value = password;
            document.getElementById("edit-level").value = level;
            document.getElementById("edit-status").value = status;
            document.getElementById("editModal").style.display = "block";
        }
    </script>
</body>
</html>

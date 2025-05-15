<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Mengambil data target dari database
    $sql = "SELECT * FROM target WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        echo "<table>
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th>Target</th>
                        <th>Progress</th>
                        <th>Pencapaian</th>
                    </tr>
                </thead>
                <tbody>";

        $months = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];

        foreach ($months as $month) {
            $target_col = "target_" . $month;
            $pogres_col = "pogres_" . $month;
            $pencapaian_col = "pencapaian_" . $month;

            $target = isset($row[$target_col]) ? $row[$target_col] : '-';
            $pogres = isset($row[$pogres_col]) ? $row[$pogres_col] : '-';
            $pencapaian = isset($row[$pencapaian_col]) ? number_format($row[$pencapaian_col], 2) . "%" : '-';

            echo "<tr>
                    <td>" . ucfirst($month) . "</td>
                    <td>$target</td>
                    <td>$pogres</td>
                    <td>$pencapaian</td>
                  </tr>";
        }

        echo "</tbody></table>";

        // Menambahkan tombol Edit dan Hapus
        echo "<div class='button-container'>
                <button class='edit-btn' onclick='editData($id)'>Edit</button>
                <button class='delete-btn' onclick='confirmDelete($id)'>Hapus</button>
              </div>";
    } else {
        echo "Data tidak ditemukan.";
    }
}
?>

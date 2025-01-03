<?php
ob_start();

require 'koneksi.php';

$sql = "SELECT p.id, p.tanggal_pinjam, p.tanggal_kembali, u.nama, p.barang
        FROM proses_pinjam p
        JOIN table_ss u ON p.id = u.id";
$result = mysqli_query($conn, $sql);

function sanitize_input($data) {
    global $conn;
    return mysqli_real_escape_string($conn, trim($data));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Admin - Data Peminjaman</title>
    <style>
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        .button {
            padding: 5px 10px;
            margin: 5px;
            text-decoration: none;
            border-radius: 5px;
        }
        .kembali {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>

<h2 align="center">Halaman Admin - Data Peminjaman</h2>

<table>
<thead>
    <tr>
        <th>No</th>
        <th>Nama Peminjam</th>
        <th>Barang</th>
        <th>Tanggal Peminjaman</th>
        <th>Tanggal Kembali</th>
        <th>Aksi</th>
    </tr>
</thead>
<tbody>
    <?php
    $no = 1;
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . $row['nama'] . "</td>";
            echo "<td>" . $row['barang'] . "</td>";
            echo "<td>" . $row['tanggal_pinjam'] . "</td>";
            echo "<td>" . $row['tanggal_kembali'] . "</td>";
            echo "<td>";
            echo "<a href='table_pinjam.php?action=kembali&id=" . $row['id'] . "' class='button kembali' onclick='return confirm(\"Apakah Anda yakin barang sudah dikembalikan?\")'>Kembalikan</a>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>Tidak ada data peminjaman yang tersedia.</td></tr>";
    }
    ?>
</tbody>
</table>

<?php
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = sanitize_input($_GET['action']);
    $id_pinjam = sanitize_input($_GET['id']);
    
    if ($action == 'kembali') {
        $update_sql = "UPDATE proses_pinjam SET tanggal_kembali = NOW() WHERE id = ?";
        if ($stmt = mysqli_prepare($conn, $update_sql)) {
            mysqli_stmt_bind_param($stmt, "i", $id_pinjam);
            if (mysqli_stmt_execute($stmt)) {
                header("Location: table_pinjam.php?status=kembali&id=" . $id_pinjam);
                exit;
            } else {
                echo "<p>Gagal mengubah status peminjaman.</p>";
            }
            mysqli_stmt_close($stmt);
        }
    }
}

if (isset($_GET['status']) && $_GET['status'] == 'kembali') {
    echo "<p style='color: green;'>Barang berhasil dikembalikan!</p>";
}
?>

</body>
</html>

<?php
mysqli_close($conn);
ob_end_flush();
?>

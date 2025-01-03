<?php
ob_start();

require 'koneksi.php'; 

$data_pinjaman_query = "SELECT p.*, u.fullname, u.nim, u.email 
                       FROM proses_pinjam p
                       JOIN table_ss u ON p.id_user = u.id_user";
$result_pinjaman = mysqli_query($conn, $data_pinjaman_query);

if (!$result_pinjaman) {
    echo "Terjadi kesalahan dalam pengambilan data: " . mysqli_error($conn);
    exit;
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
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>

<h2 align="center">Halaman Admin - Data Peminjaman Pengguna</h2>

<table>
<thead>
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>NIM</th>
        <th>Email</th>
        <th>Item Dipinjam</th>
        <th>Tanggal Peminjaman</th>
        <th>Batas Pengembalian</th>
        <th>Status</th>
    </tr>
</thead>
<tbody>
    <?php
    $no = 1;
    if (mysqli_num_rows($result_pinjaman) > 0) {
        while ($row = mysqli_fetch_assoc($result_pinjaman)) {
            if (!filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
                $row['email'] = "Email tidak valid";
            }

            echo "<tr>";
            echo "<td>" . $no++ . "</td>";  
            echo "<td>" . htmlspecialchars($row['fullname']) . "</td>";  
            echo "<td>" . htmlspecialchars($row['nim']) . "</td>";   
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";    
            echo "<td>" . htmlspecialchars($row['barang']) . "</td>";    
            echo "<td>" . htmlspecialchars($row['tanggal_pinjam']) . "</td>";    
            echo "<td>" . htmlspecialchars($row['tanggal_kembali']) . "</td>";    
            $status = $row['keterangan'] == 0 ? 'Dipinjam' : 'Dikembalikan';
            echo "<td>" . htmlspecialchars($status) . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='8'>Tidak ada data peminjaman yang tersedia.</td></tr>";
    }
    ?>
</tbody>
</table>

</body>
</html>

<?php
mysqli_close($conn);
ob_end_flush(); 
?>

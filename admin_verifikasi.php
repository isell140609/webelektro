<?php
ob_start();

require 'koneksi.php'; 

$sql = "SELECT * FROM `table_ss`"; 
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
    <title>Halaman Admin - Verifikasi Pengguna</title>
    <style>
       body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f7f9fc;
    color: #333;
    margin: 0;
    padding: 0;
}

h2 {
    text-align: center;
    color: #4a90e2;
    margin: 30px 0;
    font-size: 28px;
    text-transform: uppercase;
}

table {
    width: 80%;
    margin: 30px auto;
    border-collapse: collapse;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
}

table, th, td {
    border: 1px solid #ddd;
}

th, td {
    padding: 12px;
    text-align: center;
    font-size: 16px;
    font-weight: normal;
}

th {
    background-color: #4a90e2;
    color: white;
}

td {
    background-color: #fafafa;
}

td:hover {
    background-color: #f1f1f1;
}

.action-buttons {
    text-align: center;
    margin-bottom: 30px;
}

.button {
    display: inline-block;
    padding: 10px 20px;
    margin: 5px;
    text-decoration: none;
    border-radius: 5px;
    transition: transform 0.3s ease, background-color 0.3s ease;
}

.verifikasi {
    background-color: #28a745;
    color: white;
}

.tolak {
    background-color: #dc3545;
    color: white;
}

.hapus {
    background-color: #ff9800;
    color: white;
}

.lihat-data {
    background-color: #007bff;
    color: white;
    font-size: 16px;
}

.lihat-data:hover {
    background-color: #0056b3;
}

.button:hover {
    transform: scale(1.05);
}

.button:active {
    transform: scale(1);
}

td a {
    display: inline-block;
    padding: 8px 15px;
    margin: 0 5px;
    border-radius: 4px;
}

h2, .action-buttons a, .button {
    transition: all 0.3s ease;
}

h2:hover {
    color: #1d4f91;
}

.action-buttons a {
    padding: 12px 25px;
    font-size: 18px;
    font-weight: bold;
    letter-spacing: 1px;
}

p {
    font-size: 16px;
    text-align: center;
}

p.green {
    color: green;
}

p.red {
    color: red;
}

p.orange {
    color: orange;
}

@media (max-width: 768px) {
    table {
        width: 95%;
    }

    .action-buttons {
        text-align: center;
    }

    .button {
        width: 100%;
        padding: 12px;
    }
}

    </style>
</head>
<body>

<h2 align="center">Halaman Admin - Verifikasi Pengguna</h2>
<div class="action-buttons">
    <a href='admin_peminjaman.php' class='button lihat-data'>Lihat Semua Data yang Dipinjam</a>
</div>
<table>
<thead>
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>NIM</th>
        <th>Email</th>
        <th>Status Verifikasi</th>
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
            echo "<td>" . $row['fullname'] . "</td>";  
            echo "<td>" . $row['nim'] . "</td>";   
            echo "<td>" . $row['email'] . "</td>";    
            
            $status_verifikasi = $row['is_verified'] == 0 ? 'Menunggu' : ($row['is_verified'] == 1 ? 'Terverifikasi' : 'Ditolak');
            echo "<td>" . $status_verifikasi . "</td>";
            
            echo "<td>";
            if ($row['is_verified'] == 0) { 
                echo "<a href='admin_verifikasi.php?action=verifikasi&id=" . $row['id_user'] . "' class='button verifikasi'>Verifikasi</a>
                      <a href='admin_verifikasi.php?action=tolak&id=" . $row['id_user'] . "' class='button tolak'>Tolak</a>";
            }

            echo "<a href='admin_verifikasi.php?action=hapus&id=" . $row['id_user'] . "' class='button hapus' onclick='return confirm(\"Apakah Anda yakin ingin menghapus pengguna ini?\")'>Hapus</a>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>Tidak ada pengguna yang tersedia.</td></tr>";
    }
    ?>
</tbody>
</table>

<?php
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = sanitize_input($_GET['action']);
    $id_user = sanitize_input($_GET['id']);
    
    if ($action == 'verifikasi') {
        $update_sql = "UPDATE `table_ss` SET `is_verified` = 1 WHERE `id_user` = ?";
        if ($stmt = mysqli_prepare($conn, $update_sql)) {
            mysqli_stmt_bind_param($stmt, "i", $id_user);
            if (mysqli_stmt_execute($stmt)) {
                header("Location: admin_verifikasi.php?status=verifikasi&id=" . $id_user);  
                exit;
            } else {
                echo "<p>Gagal memverifikasi akun.</p>";
            }
            mysqli_stmt_close($stmt);
        }
    } elseif ($action == 'tolak') {
        $update_sql = "UPDATE `table_ss` SET `is_verified` = 2 WHERE `id_user` = ?";
        if ($stmt = mysqli_prepare($conn, $update_sql)) {
            mysqli_stmt_bind_param($stmt, "i", $id_user);
            if (mysqli_stmt_execute($stmt)) {
                header("Location: admin_verifikasi.php?status=tolak&id=" . $id_user);  
                exit;
            } else {
                echo "<p>Gagal menolak akun.</p>";
            }
            mysqli_stmt_close($stmt);
        }
    } elseif ($action == 'hapus') {
        $delete_sql = "DELETE FROM `table_ss` WHERE `id_user` = ?";
        if ($stmt = mysqli_prepare($conn, $delete_sql)) {
            mysqli_stmt_bind_param($stmt, "i", $id_user);
            if (mysqli_stmt_execute($stmt)) {
                header("Location: admin_verifikasi.php?status=hapus&id=" . $id_user);  
                exit;
            } else {
                echo "<p>Gagal menghapus pengguna.</p>";
            }
            mysqli_stmt_close($stmt);
        }
    }
}

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'verifikasi') {
        echo "<p style='color: green;'>Akun berhasil diverifikasi!</p>";
    } elseif ($_GET['status'] == 'tolak') {
        echo "<p style='color: red;'>Akun berhasil ditolak!</p>";
    } elseif ($_GET['status'] == 'hapus') {
        echo "<p style='color: orange;'>Akun berhasil dihapus!</p>";
    }
}
?>

</body>
</html>

<?php
mysqli_close($conn);
ob_end_flush(); 
?>

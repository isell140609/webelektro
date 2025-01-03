<?php
require 'koneksi.php'; 

$nama_param = isset($_GET['nama']) ? trim($_GET['nama']) : '';

$sql = "SELECT DISTINCT nama FROM `proses_pinjam`";

if ($stmt = mysqli_prepare($conn, $sql)) {
    if (mysqli_stmt_execute($stmt)) {
        $result_nama = mysqli_stmt_get_result($stmt);
    } else {
        die("Query tidak berhasil dijalankan. Kesalahan: " . mysqli_error($conn));
    }
} else {
    die("Gagal menyiapkan statement. Kesalahan: " . mysqli_error($conn));
}

$data_pinjam = [];
if (!empty($nama_param)) {
    $sql_detail = "SELECT * FROM `proses_pinjam` WHERE nama = ?";
    if ($stmt_detail = mysqli_prepare($conn, $sql_detail)) {
        mysqli_stmt_bind_param($stmt_detail, "s", $nama_param);
        if (mysqli_stmt_execute($stmt_detail)) {
            $data_pinjam = mysqli_stmt_get_result($stmt_detail);
        } else {
            die("Query tidak berhasil dijalankan. Kesalahan: " . mysqli_error($conn));
        }
    } else {
        die("Gagal menyiapkan statement. Kesalahan: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Peminjaman Pengguna</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h2, h3 {
            text-align: center;
            color: #4a90e2;
        }

        table {
            width: 80%;
            margin: 30px auto;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
            font-size: 16px;
        }

        th {
            background-color: #4a90e2;
            color: white;
        }

        td:hover {
            background-color: #f1f1f1;
        }

        .delete-section {
            text-align: center;
            margin: 20px;
        }

        .delete-section form {
            display: inline-block;
        }

        .delete-section button {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .delete-section button:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

<h2>Data Peminjaman</h2>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (isset($result_nama) && mysqli_num_rows($result_nama) > 0) {
            $no = 1;
            while ($row_nama = mysqli_fetch_assoc($result_nama)) {
                echo "<tr>";
                echo "<td>" . $no++ . "</td>";
                echo "<td><a href='?nama=" . urlencode($row_nama['nama']) . "'>" . htmlspecialchars($row_nama['nama']) . "</a></td>";
                echo "<td>
                        <form method='POST' action='hapus.php' style='display:inline;'>
                            <input type='hidden' name='nama' value='" . htmlspecialchars($row_nama['nama']) . "'>
                            <button type='submit'>Hapus</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>Tidak ada data pengguna yang terdaftar.</td></tr>";
        }
        ?>
    </tbody>
</table>

<?php if (!empty($nama_param)): ?>
    <h3>Detail Peminjaman: <?php echo htmlspecialchars($nama_param); ?></h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Item</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($data_pinjam) && mysqli_num_rows($data_pinjam) > 0) {
                $no = 1;
                while ($row_detail = mysqli_fetch_assoc($data_pinjam)) {
                    echo "<tr>";
                    echo "<td>" . $no++ . "</td>";
                    echo "<td>" . htmlspecialchars($row_detail['nama']) . "</td>";
                    echo "<td>" . htmlspecialchars($row_detail['barang']) . "</td>";
                    echo "<td>" . htmlspecialchars($row_detail['tanggal_pinjam']) . "</td>";
                    echo "<td>" . htmlspecialchars($row_detail['tanggal_kembali']) . "</td>";
                    echo "<td>" . htmlspecialchars($row_detail['keterangan']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Tidak ada data peminjaman untuk pengguna ini.</td></tr>";
            }
            ?>
        </tbody>
    </table>
<?php endif; ?>

</body>
</html>

<?php
if (isset($stmt)) {
    mysqli_stmt_close($stmt);
}
mysqli_close($conn);
?>

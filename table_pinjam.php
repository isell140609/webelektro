<?php
session_start();

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: table_pinjam.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ayuseli";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql = "SELECT p.*, u.nim, u.nama 
        FROM proses_pinjam p
        JOIN table_ss u ON p.email = u.email";
$result = $conn->query($sql);

if (!$result) {
    die("Query gagal: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Peminjaman Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fce4ec;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            flex-direction: column;
        }

        h2 {
            color: #ec407a;
            text-align: center;
            margin-top: 50px;
        }

        a {
            color: #ec407a;
            text-decoration: none;
            font-size: 16px;
            padding: 8px;
            border: 1px solid #ec407a;
            border-radius: 4px;
            background-color: white;
            margin: 0;
            display: inline-block;
        }

        a:hover {
            background-color: #ec407a;
            color: white;
        }

        table {
            width: 80%;
            border-collapse: collapse;
            margin: 20px auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        th {
            background-color: #ec407a;
            color: white;
            padding: 12px;
            text-align: center;
        }

        td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        td[colspan='7'] {
            text-align: center;
            font-style: italic;
            color: #ec407a;
        }
    </style>
</head>
<body>
    <h2>Data Peminjaman Barang</h2>
    <table>
        <tr>
            <th>No</th>
            <th>Nama Peminjam</th>
            <th>Nim</th>
            <th>Nama Barang</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Keterangan</th>
        </tr>
        <?php
        $no = 1;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $no++ . "</td>";
                echo "<td>" . htmlspecialchars($row["nama"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["nim"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["barang"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["tanggal_pinjam"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["tanggal_kembali"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["keterangan"]) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>Tidak ada data peminjaman yang tersedia.</td></tr>";
        }
        $conn->close();
        ?>
    </table>
    <a href="admin_dashboard.php" class="button">Kembali ke Dashboard Admin</a>
</body>
</html>

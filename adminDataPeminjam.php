<?php
session_start();

if (!isset($_SESSION['email']) || $_SESSION['role'] != 'admin') {
    header("Location: adminDataPeminjam.php");
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

$sql = "SELECT p.*, u.nim FROM proses_pinjam p JOIN table_ss u ON p.email = u.email";
$result = $conn->query($sql);

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete_sql = "DELETE FROM proses_pinjam WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: adminDataPeminjam.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - Data Peminjaman Barang</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid black; padding: 10px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
    </style>
</head>
<body>
    <h2>Admin - Data Peminjaman Barang</h2>
    <a href="formPinjam.php">Tambah Barang</a> | <a href="logout.php">Logout</a> | <a href="profil.html">Home</a>
    <table>
        <tr>
            <th>No</th>
            <th>Nama Peminjam</th>
            <th>Nim</th>
            <th>Nama Barang</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Keterangan</th>
            <th>Aksi</th>
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
                echo "<td><a href='edit_item.php?id=" . $row['id'] . "'>Edit</a> | <a href='?delete=" . $row['id'] . "'>Hapus</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>Tidak ada data peminjaman.</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>

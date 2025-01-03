<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
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

$email = $_SESSION['email'];

$sql = "SELECT p.*, u.nim FROM proses_pinjam p JOIN table_ss u ON p.email = u.email WHERE p.email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Data Peminjaman Barang</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid black; padding: 10px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
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
            echo "<tr><td colspan='7'>Tidak ada data peminjaman.</td></tr>";
        }
        $stmt->close();
        $conn->close();
        ?>
    </table>
    <style>
body {
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
    margin-top: 20px; 
}

a {
    color: #ec407a;
    text-decoration: none;
    font-size: 16px;
    padding: 8px;
    border: 1px solid #ec407a;
    border-radius: 4px;
    background-color: white;
    margin: 10px 0; 
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

td[colspan='6'] {
    text-align: center;
    font-style: italic;
    color: #ec407a;
}
}


    </style>
    <th><a href="formPinjam.php">Tambah Barang</a><br></th>
    <th><a href="logout.php">Logout</a></th>
    <th><a href="profil.html">home</a></th>
</body>
</html>
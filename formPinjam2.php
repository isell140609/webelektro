<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ayuseli";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    $email = $_SESSION['email'];
    $nama = $_POST['nama'];
    $barang = $_POST['barang'];
    $tanggal_pinjam = $_POST['tanggal_pinjam'];
    $tanggal_kembali = $_POST['tanggal_kembali'];
    $keterangan = $_POST['keterangan'];

    if ($tanggal_kembali < $tanggal_pinjam) {
        echo "Tanggal kembali tidak boleh lebih awal dari tanggal pinjam!";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO proses_pinjam (email, nama, barang, tanggal_pinjam, tanggal_kembali, keterangan) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $email, $nama, $barang, $tanggal_pinjam, $tanggal_kembali, $keterangan);

    if ($stmt->execute()) {
        header("Location: tambahBarang.php");
        exit;
    } else {
        echo "Gagal menyimpan data: " . $stmt->error;
    }
                                                                                                              
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Form Peminjaman Barang</title>
</head>
<body>
    <h2>Form Peminjaman Barang</h2>
    <form method="POST" action="">
        <label for="nama">Nama Peminjam:</label>
        <input type="text" name="nama" id="nama" required><br><br>
        
        <label for="barang">Nama Barang:</label>
        <input type="text" name="barang" id="barang" required><br><br>

        <label for="tanggal_pinjam">Tanggal Pinjam:</label>
        <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" required><br><br>

        <label for="tanggal_kembali">Tanggal Kembali:</label>
        <input type="date" name="tanggal_kembali" id="tanggal_kembali" required><br><br>

        <label for="keterangan">Keterangan:</label>
        <textarea name="keterangan" id="keterangan"></textarea><br><br>

        <button type="submit">Submit</button>
        <a href="tambahBarang.php">Klik Untuk Lihat Data Tabel Barang Anda?</a>


    </form>
    <style>
body {
    font-family: Arial, sans-serif;
    background-color: #fce4ec;
    color: #333;
    margin: 0;
    padding: 0;
}

h2 {
    color: #ec407a;
    text-align: center;
    margin-top: 30px;
}

form {
    width: 50%;
    margin: 20px auto;
    background-color: #ffffff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

label {
    font-size: 16px;
    color: #ec407a;
    display: block;
    margin-bottom: 10px;
}

input[type="text"],
input[type="date"],
textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ec407a;
    border-radius: 5px;
    box-sizing: border-box;
}

textarea {
    resize: vertical;
    min-height: 100px;
}

button {
    background-color: #ec407a;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
}

button:hover {
    background-color: #d81b60;
}

a {
    display: block;
    text-align: center;
    margin-top: 10px;
    color: #ec407a;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

    </style>
</body>
</html>
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$email = $_SESSION['email'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ayuseli";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$query_user = "SELECT fullname, nim FROM `table_ss` WHERE id_user = ?";
$stmt_user = $conn->prepare($query_user);
if ($stmt_user === false) {
    die('Prepare statement gagal: ' . $conn->error); 
}

$stmt_user->bind_param("i", $user_id);  
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user = $result_user->fetch_assoc();

if ($user) {
    $fullname = $user['fullname'];
    $nim = $user['nim'];
} else {
    echo "Pengguna tidak ditemukan!";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $barang = $_POST['barang'];
    $tanggal_pinjam = $_POST['tanggal_pinjam'];
    $tanggal_kembali = $_POST['tanggal_kembali'];
    $keterangan = $_POST['keterangan'];

    if ($tanggal_kembali < $tanggal_pinjam) {
        echo "Tanggal kembali tidak boleh lebih awal dari tanggal pinjam!";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO proses_pinjam (email, nama, barang, tanggal_pinjam, tanggal_kembali, keterangan) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die('Prepare statement gagal: ' . $conn->error);  
    }

    $stmt->bind_param("ssssss", $email, $fullname, $barang, $tanggal_pinjam, $tanggal_kembali, $keterangan);

    if ($stmt->execute()) {
        header("Location: tambahBarang.php");
        exit;
    } else {
        echo "Gagal menyimpan data: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Peminjaman</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        h1 {
            color: #1976D2;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 0 auto;
        }

        label {
            font-weight: bold;
            margin-bottom: 10px;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #1976D2;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #1565C0;
        }

        a {
            color: #fff;
            text-decoration: none;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            background-color: #1976D2;
            margin: 10px 0; 
            display: inline-block;
        }

        a:hover {
            background-color: #1565C0;
        }

    </style>
</head>
<body>
    <h1>Form Peminjaman Barang</h1>
    <form method="post" action="">
        <label for="nama">Nama:</label>
        <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($fullname); ?>" readonly><br><br>

        <label for="nama">Nim:</label>
        <input type="text" id="nim" name="nim" value="<?php echo htmlspecialchars($nim); ?>" readonly><br><br>
        
        <label for="barang">Barang yang dipinjam:</label>
        <input type="text" id="barang" name="barang" required><br><br>
        
        <label for="tanggal_pinjam">Tanggal Pinjam:</label>
        <input type="date" id="tanggal_pinjam" name="tanggal_pinjam" required><br><br>
        
        <label for="tanggal_kembali">Tanggal Kembali:</label>
        <input type="date" id="tanggal_kembali" name="tanggal_kembali" required><br><br>
        
        <label for="keterangan">Keterangan:</label>
        <textarea id="keterangan" name="keterangan" required></textarea><br><br>
        
        <button type="submit">Pinjam Barang</button>
        <th><a href="tambahBarang.php">Klik untuk Melihat Data Pinjam Anda</a><br></th>
    </form>
</body>
</html>
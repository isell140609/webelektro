<?php
session_start();

if (!isset($_SESSION['admin_username'])) {
    header('Location: login_admin.php'); 
    exit();
}

$conn = new mysqli('localhost', 'username', 'password', 'database_name');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['verify'])) {
    $username = $_POST['username'];
    $verification_code = $_POST['verification_code'];

    $sql = "SELECT * FROM users WHERE username = '$username' AND verification_code = '$verification_code'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $update_sql = "UPDATE users SET verified = 1 WHERE username = '$username'";
        if ($conn->query($update_sql) === TRUE) {
            echo "Akun pengguna $username berhasil diverifikasi!";
        } else {
            echo "Terjadi kesalahan saat memverifikasi akun.";
        }
    } else {
        echo "Kode verifikasi atau username tidak valid.";
    }
}

$conn->close();
?>

<?php
require 'koneksi.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = mysqli_real_escape_string($conn, $_POST["fullname"]);
    $nim = mysqli_real_escape_string($conn, $_POST["nim"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);

    $query_sql = "INSERT INTO `table_ss`(`fullname`, `nim`, `password`, `email`, `is_verified`) 
                  VALUES ('$fullname', '$nim', '$password', '$email', 0)";

    if (mysqli_query($conn, $query_sql)) {
        echo "<h2>Pendaftaran berhasil!</h2>";
        echo "<p>Akun Anda sedang menunggu verifikasi dari admin. Harap tunggu.</p>";
        echo "<p>Setelah akun Anda diverifikasi, Anda akan dapat login dan mengakses sistem.</p>";
        echo "<p>Anda akan diarahkan ke halaman login dalam 5 detik...</p>";
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'login.php';
                }, 5000);
              </script>";
    } else {
        echo "PENDAFTARAN GAGAL: " . mysqli_error($conn);
    }
}
?>


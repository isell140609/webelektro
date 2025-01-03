<?php
session_start(); 
require 'koneksi.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['Password']);

    $query = "SELECT * FROM `table_ss` WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if ($user['is_verified'] == 1) {
            $_SESSION['email'] = $email;
            $_SESSION['user_id'] = $user['id_user']; 
            $_SESSION['nama'] = $user['nama'];

            header("Location: formPinjam.php");
            exit; 
        } else {
            echo "<h2>Akun Anda belum diverifikasi!</h2>";
            echo "<p>Akun Anda masih menunggu verifikasi oleh admin. Harap tunggu beberapa saat.</p>";
        }
    } else {
        echo "<h2>Login Gagal!</h2>";
        echo "<p>Email atau password salah. Silakan coba lagi.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    
    <form method="POST" action="" autocomplete="off">
        <h2>LOGIN</h2>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required autocomplete="off">
        
        <label for="Password">Password:</label>
        <input type="password" name="Password" id="Password" required autocomplete="off">
        
        <button type="submit">Login</button>
        <p>Belum punya akun?<a href="registrasi.html">Registrasi</a></p>
    </form>

    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #e3f2fd;
        color: #333;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        flex-direction: column; 
    }

    notification {
        background-color: #ffcc00; 
        color: #d32f2f; 
        padding: 10px 20px;
        border-radius: 5px;
        width: 80%;
        max-width: 500px;
        text-align: center;
        margin-bottom: 20px;
        font-size: 16px;
    }

    form {
        background-color: #ffffff; 
        width: 100%;
        max-width: 400px;
        padding: 30px 40px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
        text-align: center;
    }

    h2 {
        color: #1976d2; 
        font-size: 28px;
        margin-bottom: 20px;
    }

    label {
        font-size: 16px;
        color: #1976d2; 
        display: block;
        margin-bottom: 10px;
    }

    input[type="email"], input[type="password"] {
        width: 100%;
        padding: 12px 20px;
        margin-bottom: 20px;
        border: 1px solid #1976d2; 
        border-radius: 8px;
        font-size: 16px;
        background-color: #f0f4f8; 
    }

    input[type="email"]:focus, input[type="password"]:focus {
        border-color: #1565c0;
        outline: none;
    }

    button {
        background-color: #1976d2; 
        color: white;
        padding: 12px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        width: 100%;
        font-size: 16px;
        transition: background-color 0.3s ease;
        margin-bottom: 15px;
    }

    button:hover {
        background-color: #1565c0; 
    }

    button a {
        color: white;
        text-decoration: none;
    }

    button a:hover {
        text-decoration: underline;
    }

    button:last-child {
        background-color: transparent;
        border: 2px solid #1976d2;
        color: #1976d2;
    }

    button:last-child:hover {
        background-color: #1976d2;
        color: white;
    }

    footer {
        color: #333;
        text-align: center;
        font-size: 12px;
        margin-top: 20px;
    }

    footer a {
        color: #1976d2;
        text-decoration: underline;
    }

    footer a:hover {
        color: #1565c0;
    }

    @media (max-width: 500px) {
        form {
            padding: 20px;
            width: 90%;
        }
        h2 {
            font-size: 24px;
        }
    }
    </style>

</body>
</html>

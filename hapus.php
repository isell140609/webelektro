<?php
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Periksa apakah parameter 'nama' ada dalam request
    if (isset($_POST['nama']) && !empty($_POST['nama'])) {
        $nama = $_POST['nama'];

        // Query untuk menghapus data berdasarkan nama
        $sql = "DELETE FROM `proses_pinjam` WHERE nama = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind parameter sebagai string
            mysqli_stmt_bind_param($stmt, "s", $nama);

            if (mysqli_stmt_execute($stmt)) {
                echo "Data berhasil dihapus."; // Kirim respons sukses
            } else {
                echo "Gagal menghapus data. Kesalahan: " . mysqli_error($conn);
            }

            mysqli_stmt_close($stmt);
        } else {
            echo "Gagal menyiapkan statement. Kesalahan: " . mysqli_error($conn);
        }
    } else {
        echo "Nama tidak valid atau kosong.";
    }
} else {
    // Jika metode bukan POST, tampilkan formulir
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Hapus Data</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f7fc;
                text-align: center;
                margin: 0;
                padding: 0;
            }
            form {
                margin-top: 50px;
            }
            input, button {
                padding: 10px;
                margin: 5px;
                font-size: 16px;
            }
            button {
                background-color: #e74c3c;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }
            button:hover {
                background-color: #c0392b;
            }
            .message {
                margin-top: 20px;
                font-size: 16px;
                color: green;
            }
        </style>
    </head>
    <body>
        <h1>Hapus Data Pengguna</h1>
        <form id="deleteForm">
            <label for="nama">Nama Pengguna:</label>
            <input type="text" id="nama" name="nama" required>
            <button type="submit">Hapus</button>
        </form>

        <div id="message" class="message"></div>

        <script>
            document.getElementById('deleteForm').addEventListener('submit', function(event) {
                event.preventDefault(); // Menghentikan form dari submit biasa

                var nama = document.getElementById('nama').value;

                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'hapus.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        // Menampilkan pesan sukses atau error
                        document.getElementById('message').innerHTML = xhr.responseText;

                        // Jika penghapusan berhasil, arahkan ke halaman daftar
                        if (xhr.responseText === 'Data berhasil dihapus.') {
                            setTimeout(function() {
                                window.location.href = 'index.php'; // Arahkan ke halaman daftar
                            }, 1000); // Tunggu 1 detik untuk menampilkan pesan
                        }

                        // Kosongkan form setelah penghapusan
                        document.getElementById('nama').value = '';
                    }
                };

                // Kirim data nama ke server
                xhr.send('nama=' + encodeURIComponent(nama));
            });
        </script>
    </body>
    </html>
    <?php
}

mysqli_close($conn);
?>

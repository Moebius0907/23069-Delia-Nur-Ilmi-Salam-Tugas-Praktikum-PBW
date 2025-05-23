<?php
$servername = "localhost";  // Nama server database
$username = "root";         // Username database (default untuk XAMPP adalah 'root')
$password = "";             // Password database (default untuk XAMPP adalah kosong)
$dbname = "book_store";    // Nama database

// Membuat koneksi
$koneksi = mysqli_connect($servername, $username, $password, $dbname);

// Mengecek koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
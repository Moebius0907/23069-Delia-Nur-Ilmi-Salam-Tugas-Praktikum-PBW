<!-- Halaman pendaftaran rute penerbangan -->
<?php
session_start(); //Kode untuk memulai sesi/saat dimana user menjalankan halaman 
date_default_timezone_set('Asia/Jakarta'); //Set waktu WIB 

//Jika user melakukan pendaftaran dengan method post 
if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    $pendaftaran = [ //Input disimpan ke dalam array pendaftaran
        'nama_maskapai' => $_POST['nama_maskapai'],
        'tanggal_penerbangan' => $_POST['tanggal_penerbangan'],
        'bandara_asal' => $_POST['bandara_asal'],
        'bandara_tujuan' => $_POST['bandara_tujuan'],
        'kelas_penerbangan' => $_POST['kelas_penerbangan'],
        'harga_tiket' => $_POST['harga_tiket'],
        'nomor_penerbangan' => strtoupper(substr($_POST['nama_maskapai'], 0, 2)) . rand(100, 999), //Nomor penerbangan dibuat dari 2 kata pertama Nama maskapai dan angka acak 100-999
        'tanggal_pendaftaran' => date('Y-m-d H:i:s')
    ];
    
    //Pengecekan apakah di sesi ini sudah ada pendaftaran
    if (!isset($_SESSION['pendaftaran'])) {
        $_SESSION['pendaftaran'] = []; //Jika belum, dibuat array kosong
    }

    $_SESSION['pendaftaran'][] = $pendaftaran; //Menambahkan pendaftaran ke array indeks berikutnya

    header('Location: list.php'); //Direct ke halaman list.php jika sudah
    exit(); //Berhenti eksekusi program
}
?>

<!-- Frontend html css -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>TravelMania Registration</title>
  <!-- Styling menggunakan tailwind cdn -->
  <script src="https://cdn.tailwindcss.com"></script> 
</head>
<!-- Logo web -->
<body class="bg-[#9cc7f0] min-h-screen flex items-center justify-center p-6">
  <div class="w-full max-w-6xl bg-[#9cc7f0] relative px-4 md:px-12 py-8 rounded-md shadow-lg">
    <div class="flex items-center space-x-3 mb-6">
      <img src="assets/globe.png" alt="Logo" class="w-10 h-10 hover:animate-bounce"/>
      <h1 class="text-yellow-200 text-3xl font-extrabold tracking-widest" style="letter-spacing: 0.2em;">TravelMania</h1>
    </div>

    <!-- Judul halaman pendaftaran -->
    <h2 class="text-[#004a99] font-bold text-2xl mb-4">Pendaftaran Rute Penerbangan</h2>

    <!-- Form pendaftaran dengan metode post -->
    <form action="" method="post" class="space-y-5 text-[#004a99] font-semibold text-sm md:text-base">
      <!-- Input nama maskapai -->
      <div>
        <label for="nama-maskapai" class="block mb-1">Nama Maskapai</label>
        <input name="nama_maskapai" id="nama-maskapai" required placeholder="Masukkan nama maskapai"
               type="text" class="rounded-md px-4 py-2 w-60 md:w-72 text-xs md:text-sm font-normal placeholder:text-gray-400"/>
      </div>
      <!-- Input tanggal penerbangan -->
      <div class="flex items-center space-x-4">
        <label for="tanggal-penerbangan" class="block font-semibold w-40">Tanggal Penerbangan</label>
        <input name="tanggal_penerbangan" id="tanggal-penerbangan" required
               type="date" class="rounded-md px-4 py-2 w-60 md:w-72 text-xs md:text-sm font-normal"/>
      </div>

      <!-- Input bandara asal -->
      <div>
        <label for="bandara-asal" class="block mb-1 font-semibold">Bandara Asal</label>
        <select name="bandara_asal" id="bandara-asal" required class="rounded-md px-4 py-2 w-60 md:w-72 text-xs md:text-sm text-black-400">
          <option value="" disabled selected>Bandara asal</option>
          <?php
          $bandara_asal = ['Soekarno Hatta', 'Husein Sastranegara', 'Abdul Rachman Saleh', 'Juanda']; //Bandara asal dimasukan ke dalam sebuah array 
          sort($bandara_asal);//Diurutkan menggunakan fungsi sort
          foreach ($bandara_asal as $b) echo "<option value=\"$b\">$b</option>"; //Diakses menggunakan perulangan foreach
          ?>
        </select>
      </div>

      <!-- Input bandara tujuan -->
      <div>
        <label for="bandara-tujuan" class="block mb-1 font-semibold">Bandara Tujuan</label>
        <select name="bandara_tujuan" id="bandara-tujuan" required class="rounded-md px-4 py-2 w-60 md:w-72 text-xs md:text-sm text-black-400">
          <option value="" disabled selected>Bandara Tujuan</option>
          <?php
          $bandara_tujuan = ['Ngurah Rai', 'Hasanuddin', 'Inanwatan', 'Sultan Iskandar Muda'];//Bandara tujuan dimasukan ke dalam sebuah array 
          sort($bandara_tujuan);//Diurutkan menggunakan fungsi sort
          foreach ($bandara_tujuan as $b) echo "<option value=\"$b\">$b</option>";//Diakses menggunakan perulangan foreach
          ?>
        </select>
      </div>
      
      <!-- Input kelas penerbangan -->
      <div>
        <label for="kelas-penerbangan" class="block mb-1 font-semibold">Kelas Penerbangan</label>
        <select name="kelas_penerbangan" id="kelas-penerbangan" required onchange="updateHarga()"
                class="rounded-md px-4 py-2 w-60 md:w-72 text-xs md:text-sm text-black-400">
          <option value="" disabled selected>Pilih kelas</option>
          <option value="Economy">Economy</option>
          <option value="Business">Business</option>
          <option value="First Class">First Class</option>
        </select>
      </div>
    
      <!-- Input akan terisi otomatis berdasarkan inputan kelas penerbangan -->
      <div>
        <label for="harga-tiket" class="block mb-1 font-semibold">Harga Tiket (Rp)</label>
        <input name="harga_tiket" id="harga-tiket" required placeholder="Harga akan terisi otomatis"
               type="number" readonly
               class="rounded-md px-4 py-2 w-60 md:w-72 text-xs md:text-sm font-normal placeholder:text-black-00"/>
      </div>

      <!-- Tombol daftar dan lihat daftar -->
      <div class="flex space-x-4">
        <button type="submit" class="bg-green-400 hover:bg-green-600 text-white hover:text-white hover:font-bold font-semibold text-lg md:text-xl rounded px-6 py-2 mt-2 w-40 md:w-48">
          Daftar
        </button>
        <!-- Lihat daftar direct ke halaman list.php -->
        <a href="list.php" class="bg-[#99b3e6] hover:bg-[#004a99] text-[#004a99] hover:text-white hover:font-bold  font-semibold text-lg md:text-xl rounded px-6 py-2 mt-2 w-40 md:w-48 text-center">
          Lihat Daftar
        </a>
      </div>
    </form>
  </div>

<!-- Fungsi js untuk menentukan harga tiket berdasarkan input kelas penerbangan -->
<script>
function updateHarga() {
    // Mengambil nilai input kelas yang disimpan ke dalam var kelas
    const kelas = document.getElementById('kelas-penerbangan').value;
    // Mengambil input untuk harga tiket yang disimpan dalam var hargaInput
    const hargaInput = document.getElementById('harga-tiket');

    // Menentukan harga berdasarkan kelas menggunakan switch
    let harga = 0;
    switch(kelas) {
        case 'Economy':
            harga = 500000; // Harga tiket untuk kelas Economy
            break;
        case 'Business':
            harga = 5000000; // Harga tiket untuk kelas Business
            break;
        case 'First Class':
            harga = 120000; // Harga tiket untuk kelas First Class
            break;
        default:
            harga = 0; // Jika tidak ada kelas yang dipilih
    }

    // Menampilkan harga yang telah ditentukan pada input harga tiket
    hargaInput.value = harga;
}
</script>

</body>
</html>

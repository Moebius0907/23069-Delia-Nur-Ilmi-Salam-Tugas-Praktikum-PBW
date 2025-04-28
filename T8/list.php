<!-- Halaman yang menampilkan daftar pendaftaran -->
<?php
session_start(); 

// Mengambil data pendaftaran dari sesi yang kemudian disimpan dalam array pendaftaran list
$pendaftaranList = $_SESSION['pendaftaran'] ?? [];

//jika tombol hapus ditekan 
if (isset($_POST['hapus'])) {
    $hapus_id = $_POST['hapus_id']; //Mengambil id pendaftaran yang mau dihapus
    unset($pendaftaranList[$hapus_id]); //Hapus menggunakan fungsi unset
    $_SESSION['pendaftaran'] = array_values($pendaftaranList); // Re-index array listPendaftaran
}

//Jika tombol hapus semua ditekan
if (isset($_POST['hapus_semua'])) {
    session_unset();
    session_destroy();
}

?>

<!-- Frontend -->
<!-- Semua icon diambil dari icon.boostrap-->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Daftar Pendaftaran | TravelMania</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#9cc7f0] min-h-screen flex items-center justify-center p-6">
  <div class="w-full max-w-6xl bg-white relative px-4 md:px-12 py-8 rounded-md shadow-lg">
    <!-- Logo web -->
    <div class="flex items-center space-x-3 mb-6">
      <img src="assets/globe.png" alt="Logo" class="w-10 h-10 hover:animate-bounce"/>
      <h1 class="text-yellow-200 text-3xl font-extrabold tracking-widest" style="letter-spacing: 0.2em;">TravelMania</h1>
    </div>

    <!-- Judul halaman List pendaftaran rute penerbangan -->
    <h2 class="text-[#004a99] font-bold text-2xl mb-4">Pendaftaran Rute Penerbangan</h2>

    <!-- Tabel yang menampilkan daftar pendaftaran (indeks dan nomor penerbangan) -->
    <table class="w-full table-auto text-sm text-left text-[#004a99] mb-6">
      <thead class="text-md font-bold border-b">
        <tr>
          <th class="py-2">No</th>
          <th class="py-2">Nomor Penerbangan</th>
          <th class="py-2 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <!-- Perulangan untuk menampilkan setiap isi array pendaftaranList -->
        <?php if (count($pendaftaranList) > 0): ?>
          <?php foreach ($pendaftaranList as $index => $p): ?>
            <tr class="border-b">
              <!-- Mencetak indeks + 1  dan elemen nomor-penerbangan-->
              <td class="py-2"><?php echo $index + 1; ?></td> 
              <td class="py-2"><?php echo $p['nomor_penerbangan']; ?></td>
              <td class="py-2 flex justify-end gap-2">

                <!-- Tombol Lihat Detail -->
                <a href="detail.php?id=<?php echo $index; ?>" class="bg-blue-500 text-white px-4 py-1 rounded hover:bg-blue-700">Lihat Detail</a>

                <!-- Tombol Hapus per data-->
                <form method="POST" action="" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                  <input type="hidden" name="hapus_id" value="<?php echo $index; ?>">
                  <button type="submit" name="hapus" class="text-red-500 hover:text-red-700 px-4 py-1 rounded flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                      <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                    </svg>
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="3" class="py-4 text-center">Belum ada pendaftaran</td></tr>
        <?php endif; ?>
      </tbody>
    </table>

    <!-- Jika tidak ada pendaftaran, tampilkan tombol untuk tambah pendaftaran yang direct ke halaman form.php-->
    <?php if (count($pendaftaranList) === 0): ?>
      <div class="text-center mt-4">
        <a href="form.php" class="bg-green-500 text-white font-bold px-4 py-2 rounded hover:bg-green-700 inline-flex items-center justify-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
          </svg> Tambah Pendaftaran
        </a>
      </div>
    <?php endif; ?>

    <!-- Form tombol kembali dan hapus menggunakan method post -->
    <form method="POST" class="flex justify-between items-center mt-6">
      <!-- Jika pengguna mengeklik tombol kembali ke pendaftaran, akan direct ke halaman form.php -->
      <a href="form.php" class="bg-[#99b3e6] text-[#004a99] font-bold px-6 py-2 rounded hover:bg-[#004a99] hover:text-white">
        Kembali ke Pendaftaran
      </a>
      <!-- Tombol hapus semua -->
      <button name="hapus_semua" type="submit" class="bg-red-400 text-white font-bold px-6 py-2 rounded hover:bg-red-700 inline-flex items-center gap-2" onclick="return confirm('Yakin ingin menghapus semua data?')">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
          <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
        </svg>
        Hapus Semua
      </button>
    </form>
  </div>
</body>
</html>

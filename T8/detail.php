<!-- Halaman yang menampilkan detail data dari setiap pendaftaran penerbangan -->
<?php
session_start();

//Jika data tidak ditemukan
if (!isset($_GET['id']) || !isset($_SESSION['pendaftaran'][$_GET['id']])) {
    echo "<script>alert('Data tidak ditemukan.'); window.location.href='list.php';</script>";
    exit();
}

// Mengambil data dari sesi dan juga id pendaftaran
$data = $_SESSION['pendaftaran'][$_GET['id']];

// Pajak Asal
$pajak_asal = [
    'Soekarno Hatta' => 65000,
    'Husein Sastranegara' => 50000,
    'Abdul Rachman Saleh' => 40000,
    'Juanda' => 30000
][$data['bandara_asal']] ?? 0;

// Pajak Tujuan
$pajak_tujuan = [
    'Ngurah Rai' => 85000,
    'Hasanuddin' => 70000,
    'Inanwatan' => 90000,
    'Sultan Iskandar Muda' => 60000
][$data['bandara_tujuan']] ?? 0;

// Total pajak dan harga tiket
$total_pajak = $pajak_asal + $pajak_tujuan;
$total_harga_tiket = $data['harga_tiket'] + $total_pajak;

// Menentukan tampilan kelas penerbangan dengan menggunakan pengkondisian switch
$kelas = $data['kelas_penerbangan'] ?? '';
$styling_class = '';

switch (strtolower($kelas)) {
    case 'first class':
        $styling_class = 'bg-yellow-400 text-yellow-900 font-bold';
        break;
    case 'business class':
        $styling_class = 'bg-blue-200 text-blue-800 font-semibold';
        break;
    case 'economy':
        $styling_class = 'bg-gray-300 text-gray-700';
        break;
    default:
        $styling_class = 'bg-blue-400 text-white';
        break;
}
?>

<!-- Frontend -->
<!-- Icon yang dipakai diambil dari icon boostrap  -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Detail Pendaftaran | TravelMania</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @media print {
      .no-print {
        display: none;
      }
    }
  </style>
</head>
<body class="bg-[#9cc7f0] min-h-screen flex items-center justify-center p-6">
  <div class="w-full max-w-6xl p-8 space-y-6">
    <!-- Tombol Kembali di Kiri Atas -->
    <div class="absolute top-6 left-6 no-print">
      <a href="list.php" class="bg-[#99b3e6] text-[#004a99] px-4 py-2 rounded hover:bg-[#004a99] hover:text-white flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
          <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
        </svg>
        Kembali
      </a>
    </div>

    <h1 class="text-3xl font-bold text-[#004a99] text-center">Detail Pendaftaran</h1>

    <!-- Tabel Detail Pendaftaran -->
    <div class="bg-white rounded-lg shadow p-6">
      <div class="flex justify-between items-center mb-4">
        <div class="flex items-center gap-4">
          <!-- Judul yang memberikan keterangan ini merupakan detail dari penerbangan dengan nomor penerbangan berapa-->
          <h2 class="text-lg font-bold text-[#004a99]">
            Detail Pendaftaran <?php echo $data['nomor_penerbangan']; ?>
          </h2>
          <!-- Kelas Penerbangan -->
          <span class="px-3 py-1 rounded-full text-sm <?php echo $styling_class; ?>">
            <?php echo htmlspecialchars($kelas) ?: '-'; ?>
          </span>
        </div>

        <!-- Tanggal dan waktu pendaftaran -->
        <div class="flex items-center text-right">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock-fill mr-2" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z"/>
          </svg>
          <span class="text-sm text-[#004a99]">
            <?php 
              echo isset($data['tanggal_pendaftaran']) ? date('d-m-Y H:i:s', strtotime($data['tanggal_pendaftaran'])) : '-';
            ?>
          </span>
        </div>
      </div>

      <!-- Tabel Detail pendaftaran yang berisi nama maskapai, nomor dan tanggal penerbangan, asal bandara, tujuan bandara, serta harga tiket(belum pajak) -->
      <table class="w-full text-sm text-[#004a99]">
        <tr><td class="py-2 font-semibold">Nama Maskapai</td><td><?php echo $data['nama_maskapai']; ?></td></tr>
        <tr><td class="py-2 font-semibold">Nomor Penerbangan</td><td><?php echo $data['nomor_penerbangan']; ?></td></tr>
        <tr><td class="py-2 font-semibold">Tanggal Penerbangan</td><td><?php echo $data['tanggal_penerbangan']; ?></td></tr>
        <tr><td class="py-2 font-semibold">Asal</td><td><?php echo $data['bandara_asal']; ?></td></tr>
        <tr><td class="py-2 font-semibold">Tujuan</td><td><?php echo $data['bandara_tujuan']; ?></td></tr>
        <tr><td class="py-2 font-semibold">Harga Tiket</td><td>Rp <?php echo number_format($data['harga_tiket'], 0, ',', '.'); ?></td></tr>
      </table>
    </div>

    <!-- Tabel Detail Pajak -->
    <div class="bg-white bg-opacity-50 rounded-lg shadow p-6">
      <h2 class="text-lg font-bold text-[#004a99] mb-4">Detail Pajak</h2>
      <table class="w-full text-sm text-[#004a99]">
        <tr><td class="py-2">Pajak Asal</td><td>Rp <?php echo number_format($pajak_asal, 0, ',', '.'); ?></td></tr>
        <tr><td class="py-2">Pajak Tujuan</td><td>Rp <?php echo number_format($pajak_tujuan, 0, ',', '.'); ?></td></tr>
        <tr class="font-semibold"><td class="py-2">Total Pajak</td><td>Rp <?php echo number_format($total_pajak, 0, ',', '.'); ?></td></tr>
        <tr class="font-semibold"><td class="py-2">Total Harga Tiket</td><td>Rp <?php echo number_format($total_harga_tiket, 0, ',', '.'); ?></td></tr>
      </table>
    </div>

    <!-- Tombol Print dengan fungsi js window.print-->
    <div class="no-print mt-6 flex justify-end">
      <button onclick="window.print();" 
          class="bg-green-500 text-white font-bold px-6 py-2 rounded hover:bg-green-600 inline-flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer-fill" viewBox="0 0 16 16">
            <path d="M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2zm6 8H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1"/><path d="M0 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2H2a2 2 0 0 1-2-2zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1"/>
          </svg>
          Cetak tiket
        </button>
        </div>

  </div>
</body>
</html>

<!-- File dashboard berisi statistik jumlah mahasiswa dan mata kuliah -->
<?php
include('koneksi.php'); 

// Query untuk mengambil total mahasiswa dan mata kuliah
$total_mhs = $koneksi->query("SELECT COUNT(*) as total FROM mahasiswa")->fetch_assoc()['total'];
$total_mk  = $koneksi->query("SELECT COUNT(*) as total FROM matakuliah")->fetch_assoc()['total'];
$total_krs  = $koneksi->query("SELECT COUNT(*) as total FROM krs")->fetch_assoc()['total'];

// Query untuk mengambil jumlah mahasiswa per jurusan
$data_jurusan = $koneksi->query("SELECT jurusan, COUNT(*) as jumlah FROM mahasiswa GROUP BY jurusan");
$labels = [];
$values = [];
while ($row = $data_jurusan->fetch_assoc()) {
  $labels[] = $row['jurusan'];
  $values[] = $row['jumlah'];
}
?>

<!-- Kode html -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Sistem Akademik Ahay</title>
  <!-- Menggunakan tailwind cdn untuk styling -->
  <script src="https://cdn.tailwindcss.com"></script> 
  <!-- Untuk membuat pie chart -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!-- Font Awesome untuk icon -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Animasi tambahan ketika halaman pertama kali dimuat  -->
  <style>
    .fade-in {
      opacity: 0;
      transform: translateY(20px);
      animation: fadeInUp 0.8s ease-out forwards;
    }

    @keyframes fadeInUp {
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .stat-card:hover {
      transform: translateY(-5px);
      transition: transform 0.3s ease;
    }
  </style>
</head>
<body class="bg-gray-100 min-h-screen flex">

<!-- Sidebar -->
<aside class="w-64 bg-[#27548A] text-white flex flex-col">
    <div class="p-6 text-xl font-bold flex items-center">
        <i class="fas fa-graduation-cap mr-2"></i>
        <a href="index.php" class="text-white hover:underline">SiAhay</a>
    </div>
    <nav class="flex-1 px-4 space-y-2">
        <a href="mhs.php" class="block bg-gray-100 text-black font-semibold hover:bg-[#B2C6D5] rounded-md px-8 py-4 text-center flex items-center">
            <i class="fas fa-users mr-2"></i>
            <span>Mahasiswa</span>
        </a>
        <a href="matkul.php" class="block bg-gray-200 text-black font-semibold hover:bg-[#B2C6D5] rounded-md px-4 py-4 text-center flex items-center">
            <i class="fas fa-book mr-2"></i>
            <span>Mata Kuliah</span>
        </a>
        <a href="krs.php" class="block bg-gray-200 text-black font-semibold hover:bg-[#B2C6D5] rounded-md px-4 py-4 text-center flex items-center">
            <i class="fas fa-clipboard-list mr-2"></i>
            <span>KRS</span>
        </a>
    </nav>
</aside>

<!-- Main Content : Statistika mahasiswa dan mata kuliah serta distribusi mahasiswa per jurusan-->
<main class="flex-1 p-10 fade-in">
    <h1 class="text-2xl font-semibold mb-6 flex items-center">
      <i class="fas fa-tachometer-alt mr-2 text-blue-700"></i>
      <span>Selamat datang di Sistem Akademik Ahay!</span>
    </h1>
    <div class="grid grid-cols-2 gap-6 mb-10">
      <a href="mhs.php" class="stat-card bg-white p-6 rounded-lg shadow text-center hover:shadow-lg hover:bg-blue-100 cursor-pointer">
        <div class="text-4xl font-bold text-blue-700 flex justify-center items-center">
          <i class="fas fa-users mr-3"></i>
          <?= $total_mhs ?>
        </div>
        <p class="mt-2 text-lg">Total Mahasiswa</p>
      </a>
      <a href="matkul.php" class="stat-card bg-white p-6 rounded-lg shadow text-center hover:shadow-lg hover:bg-blue-100 cursor-pointer">
        <div class="text-4xl font-bold text-blue-700 flex justify-center items-center">
          <i class="fas fa-book mr-3"></i>
          <?= $total_mk ?>
        </div>
        <p class="mt-2 text-lg">Total Mata Kuliah</p>
      </a>
      <a href="krs.php" class="stat-card bg-white p-6 rounded-lg shadow text-center hover:shadow-lg hover:bg-blue-100  cursor-pointer">
        <div class="text-4xl font-bold text-blue-700 flex justify-center items-center">
          <i class="fas fa-clipboard-list mr-3"></i>
          <?= $total_krs ?>
        </div>
        <p class="mt-2 text-lg">Total KRS</p>
      </a>
    </div>

    <!-- Pie Chart untuk memperlihatkan distribusi per jurusan -->
    <div class="bg-white p-6 rounded-lg shadow fade-in">
      <h2 class="text-xl font-semibold mb-4 flex items-center">
        <i class="fas fa-chart-pie mr-2 text-blue-700"></i>
        <span>Distribusi Mahasiswa per Jurusan</span>
      </h2>
      <div class="max-w-md mx-auto">
        <canvas id="pieChart"></canvas>
      </div>
    </div>
  </main>


  <!-- javascript animasi pie chart -->
  <script>
    const ctx = document.getElementById('pieChart').getContext('2d');
    new Chart(ctx, {
      type: 'pie',
      data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
          data: <?= json_encode($values) ?>,
          backgroundColor: [
            '#27548A', '#4F8FC1', '#7FB3D5', '#A9CCE3', '#D6EAF8', '#AED6F1', '#2980B9'
          ],
          hoverOffset: 20
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'bottom'
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                const label = context.label || '';
                const value = context.parsed || 0;
                return `${label}: ${value} mahasiswa`;
              }
            }
          }
        }
      }
    });
  </script>

</body>
</html>
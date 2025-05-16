<!-- Halaman mata kuliah -->
<?php
include('koneksi.php');

// Inisialisasi awal edit data
$editData = null;
$showSuccessNotification = false;
$notificationMessage = '';
$notificationType = '';

// Query untuk tambah/update data mahasiswa 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kodemk     = $_POST['kodemk'];
    $nama       = $_POST['nama'];
    $jumlah_sks = $_POST['jumlah_sks'];

    if (isset($_POST['edit'])) {
        $stmt = $koneksi->prepare("UPDATE matakuliah SET nama=?, jumlah_sks=? WHERE kodemk=?");
        $stmt->bind_param("sis", $nama, $jumlah_sks, $kodemk);
        $notificationMessage = 'Data mata kuliah berhasil diperbarui!';
    } else {
        $stmt = $koneksi->prepare("INSERT INTO matakuliah (kodemk, nama, jumlah_sks) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $kodemk, $nama, $jumlah_sks);
        $notificationMessage = 'Data mata kuliah berhasil ditambahkan!';
    }
    $stmt->execute();
    $showSuccessNotification = true;
    $notificationType = 'success';
}

// Query untuk menghapus data mata kuliah
if (isset($_GET['hapus'])) {
    $kodemk = $_GET['hapus'];
    $koneksi->query("DELETE FROM matakuliah WHERE kodemk='$kodemk'");
    $showSuccessNotification = true;
    $notificationMessage = 'Data mata kuliah berhasil dihapus!';
    $notificationType = 'success';
}

// Query untuk mengambil data yang mau di edit 
if (isset($_GET['edit'])) {
    $kodemk = $_GET['edit'];
    $result = $koneksi->query("SELECT * FROM matakuliah WHERE kodemk='$kodemk'");
    $editData = $result->fetch_assoc();
}

// Query untuk handle pencarian mata kliah
$cari = isset($_GET['cari']) ? $_GET['cari'] : '';
$sql = "SELECT * FROM matakuliah WHERE nama LIKE '%$cari%' ORDER BY nama";
$data = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Data Mata Kuliah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome untuk icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .fade-in {
            animation: fadeIn 0.6s ease-in;
        }
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            background-color: #48BB78;
            color: white;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            animation: slideIn 0.5s, fadeOut 0.5s 2.5s forwards;
        }
        .notification.error {
            background-color: #F56565;
        }
        @keyframes slideIn {
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
        }
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
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
        <a href="matkul.php" class="block bg-white text-[#27548A] font-semibold hover:bg-[#B2C6D5] rounded-md px-4 py-4 text-center flex items-center">
            <i class="fas fa-book mr-2"></i>
            <span>Mata Kuliah</span>
        </a>
        <a href="krs.php" class="block bg-gray-200 text-black font-semibold hover:bg-[#B2C6D5] rounded-md px-4 py-4 text-center flex items-center">
            <i class="fas fa-clipboard-list mr-2"></i>
            <span>KRS</span>
        </a>
    </nav>
</aside>

<!-- Konten Utama -->
<main class="flex-1 p-10 fade-in">
        <h1 class="text-2xl font-semibold mb-6 flex items-center">
            <i class="fas fa-book mr-2 text-blue-700"></i>
            <span>Manajemen Mata Kuliah</span>
        </h1>

        <?php if ($showSuccessNotification): ?>
            <div class="notification <?= $notificationType === 'error' ? 'error' : '' ?>" id="successNotification">
                <?= $notificationMessage ?>
            </div>
            <script>
                setTimeout(function() {
                    document.getElementById('successNotification').remove();
                }, 3000);
            </script>
        <?php endif; ?>

        <!-- Form Pencarian mata kuliah-->
        <form method="GET" class="mb-6 flex items-center justify-center gap-2">
            <input type="text" name="cari" placeholder="Cari nama mata kuliah..." value="<?= htmlspecialchars($cari) ?>" class="px-4 py-2 border rounded w-64 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="bg-white px-4 py-2 hover:bg-[#27548A]/30 rounded-md flex items-center">
                <i class="fas fa-search mr-2 text-gray-700"></i>
                <span class="text-gray-700">Cari</span>
            </button>
            <a href="matkul.php" class="text-blue-600 underline ml-2">Reset</a>
        </form>

        <!-- Tabel Data mata kuliah-->
        <div class="overflow-x-auto bg-white rounded-lg shadow text-sm">
            <table class="w-full">
                <thead class="bg-[#27548A] text-white">
                    <tr>
                        <th class="p-4 text-left">Kode MK</th>
                        <th class="p-4 text-left">Nama</th>
                        <th class="p-4 text-left">SKS</th>
                        <th class="p-4 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($data->num_rows > 0): ?>
                        <?php while ($mk = $data->fetch_assoc()): ?>
                            <tr class="border-t hover:bg-gray-50">
                                <td class="p-4"><?= $mk['kodemk'] ?></td>
                                <td class="p-4"><?= $mk['nama'] ?></td>
                                <td class="p-4"><?= $mk['jumlah_sks'] ?></td>
                                <td class="p-4 space-x-2">
                                    <a href="matkul.php?edit=<?= $mk['kodemk'] ?>" class="text-blue-600 hover:text-blue-800 inline-flex items-center">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </a>
                                    <a href="matkul.php?hapus=<?= $mk['kodemk'] ?>" onclick="return confirm('Yakin hapus data?')" class="text-red-600 hover:text-red-800 inline-flex items-center">
                                        <i class="fas fa-trash-alt mr-1"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="p-4 text-center text-gray-500">
                                <?= $cari ? 'Mata kuliah tidak ditemukan.' : 'Belum ada data mata kuliah tersimpan.' ?>
                            </td>
                        </tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>

        <!-- Form Tambah/Edit data mata kuliah -->
        <div class="bg-white p-6 rounded-lg shadow mt-8">
            <h2 class="text-xl font-semibold mb-4 text-[#27548A] flex items-center">
                <i class="fas <?= $editData ? 'fa-edit' : 'fa-plus-circle' ?> mr-2"></i>
                <?= $editData ? 'Edit Mata Kuliah' : 'Tambah Mata Kuliah' ?>
            </h2>
            <form method="POST">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 mb-2">Kode MK</label>
                        <input type="text" name="kodemk" placeholder="Kode MK" 
                               value="<?= $editData['kodemk'] ?? '' ?>" 
                               <?= $editData ? 'readonly' : '' ?> 
                               required 
                               class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">Nama Mata Kuliah</label>
                        <input type="text" name="nama" placeholder="Nama Mata Kuliah" 
                               value="<?= $editData['nama'] ?? '' ?>" 
                               required 
                               class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">Jumlah SKS</label>
                        <input type="number" name="jumlah_sks" placeholder="Jumlah SKS" 
                               value="<?= $editData['jumlah_sks'] ?? '' ?>" 
                               required 
                               class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               min="1" max="10">
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" name="<?= $editData ? 'edit' : 'tambah' ?>" 
                            class="bg-[#27548A] text-white px-6 py-2 rounded hover:bg-[#1e436e] flex items-center">
                        <i class="fas <?= $editData ? 'fa-save' : 'fa-plus' ?> mr-2"></i>
                        <?= $editData ? 'Update' : 'Simpan' ?>
                    </button>
                </div>
            </form>
        </div>
    </main>

</div>
</body>
</html>
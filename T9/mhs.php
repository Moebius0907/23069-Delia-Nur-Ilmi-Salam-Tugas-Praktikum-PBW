<!-- Halaman data mahasiswa -->
<?php
include('koneksi.php');

// Inisialisasi awal
$editData = null;
$showSuccessNotification = false;
$notificationMessage = '';
$notificationType = '';

// Handle tambah/update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $npm     = $_POST['npm'];
    $nama    = $_POST['nama'];
    $jurusan = $_POST['jurusan'];
    $alamat  = $_POST['alamat'];

    // Query untuk edit data 
    if (isset($_POST['edit'])) {
        $stmt = $koneksi->prepare("UPDATE mahasiswa SET nama=?, jurusan=?, alamat=? WHERE npm=?");
        $stmt->bind_param("ssss", $nama, $jurusan, $alamat, $npm);
        $notificationMessage = 'Data mahasiswa berhasil diperbarui!';
    } else { // Query untuk tambah data mahasiswa 
        $stmt = $koneksi->prepare("INSERT INTO mahasiswa (npm, nama, jurusan, alamat) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $npm, $nama, $jurusan, $alamat);
        $notificationMessage = 'Data mahasiswa berhasil ditambahkan!';
    }
    $stmt->execute();
    $showSuccessNotification = true;
    $notificationType = 'success';
}

// Handle hapus data mahasiswa
if (isset($_GET['hapus'])) {
    $npm = $_GET['hapus'];
    $koneksi->query("DELETE FROM mahasiswa WHERE npm='$npm'");
    $showSuccessNotification = true;
    $notificationMessage = 'Data mahasiswa berhasil dihapus!';
    $notificationType = 'success';
}

// Ambil data mahasiswa untuk edit
if (isset($_GET['edit'])) {
    $npm = $_GET['edit'];
    $result = $koneksi->query("SELECT * FROM mahasiswa WHERE npm='$npm'");
    $editData = $result->fetch_assoc();
}

// Handle pencarian
$cari = isset($_GET['cari']) ? $_GET['cari'] : '';
$sql = "SELECT * FROM mahasiswa WHERE nama LIKE '%$cari%' ORDER BY nama";
$data = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Data Mahasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome untuk icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fadeIn {
            animation: fadeIn 0.7s ease-out;
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
    </style>
</head>
<body class="bg-gray-100 animate-fadeIn">
<div class="flex min-h-screen">

 <!-- Sidebar -->
<aside class="w-64 bg-[#27548A] text-white flex flex-col">
    <div class="p-6 text-xl font-bold flex items-center">
        <i class="fas fa-graduation-cap mr-2"></i>
        <a href="index.php" class="text-white hover:underline">SiAhay</a>
    </div>
    <nav class="flex-1 px-4 space-y-2">
        <a href="mhs.php" class="block bg-white text-[#27548A] font-semibold hover:bg-[#B2C6D5] rounded-md px-8 py-4 text-center flex items-center">
            <i class="fas fa-users mr-2"></i>
            <span>Mahasiswa</span>
        </a>
        <a href="matkul.php" class="block bg-gray-100 text-black font-semibold hover:bg-[#B2C6D5] rounded-md px-4 py-4 text-center flex items-center">
            <i class="fas fa-book mr-2"></i>
            <span>Mata Kuliah</span>
        </a>
        <a href="krs.php" class="block bg-gray-100 text-black font-semibold hover:bg-[#B2C6D5] rounded-md px-4 py-4 text-center flex items-center">
            <i class="fas fa-clipboard-list mr-2"></i>
            <span>KRS</span>
        </a>
    </nav>
</aside>

<!-- Konten Utama -->
<main class="flex-1 p-8">
        <h1 class="text-2xl font-bold mb-6 text-[#27548A]">Manajemen Data Mahasiswa</h1>

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

        <!-- Form Pencarian Mahasiswa  -->
        <form method="GET" class="mb-4 flex justify-center rounded-full items-center gap-4">
            <input type="text" name="cari" placeholder="Cari nama..." value="<?= htmlspecialchars($cari) ?>" class="px-3 py-2 border rounded w-64">
            <button type="submit" class="bg-white px-4 py-2 hover:bg-[#27548A]/30 rounded-md">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="black" class="bi bi-search" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                </svg>
            </button>
            <a href="mhs.php" class="text-blue-600 underline">Reset</a>
        </form>

        <!-- Tabel Mahasiswa -->
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="w-full bg-white shadow rounded">
                <thead class="bg-[#27548A] text-left text-white">
                    <tr>
                        <th class="p-3">NPM</th>
                        <th class="p-3">Nama</th>
                        <th class="p-3">Jurusan</th>
                        <th class="p-3">Alamat</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($data->num_rows > 0): ?>
                        <?php while ($mhs = $data->fetch_assoc()): ?>
                            <tr class="border-t hover:bg-gray-50 text-sm">
                                <td class="p-3"><?= $mhs['npm'] ?></td>
                                <td class="p-3"><?= $mhs['nama'] ?></td>
                                <td class="p-3"><?= $mhs['jurusan'] ?></td>
                                <td class="p-3"><?= $mhs['alamat'] ?></td>
                                <td class="p-3 space-x-2">
                                    <a href="mhs.php?edit=<?= $mhs['npm'] ?>" class="text-blue-600 hover:underline">
                                    <i class="fas fa-edit mr-1"></i>Edit</a>
                                    <a href="mhs.php?hapus=<?= $mhs['npm'] ?>" onclick="return confirm('Yakin hapus data?')" class="text-red-600 hover:underline inline-flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                            <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                                        </svg> Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="p-3 text-center text-base text-gray-400">
                                <?= $cari ? 'Mahasiswa tidak ditemukan.' : 'Belum ada data mahasiswa tersimpan.' ?>
                            </td>
                        </tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>

        <!-- Form Tambah/Edit Data Mahasiswa -->
        <form method="POST" class="bg-white p-6 rounded shadow mb-6 mt-10">
            <h2 class="text-xl font-semibold mb-4 text-[#27548A]"><?= $editData ? 'Edit Mahasiswa' : 'Tambah Mahasiswa' ?></h2>
            <input type="text" name="npm" placeholder="NPM" value="<?= $editData['npm'] ?? '' ?>" <?= $editData ? 'readonly' : '' ?> required class="w-full mb-2 px-3 py-2 border rounded">
            <input type="text" name="nama" placeholder="Nama" value="<?= $editData['nama'] ?? '' ?>" required class="w-full mb-2 px-3 py-2 border rounded">
            <select name="jurusan" required class="w-full mb-2 px-3 py-2 border rounded">
                <option value="">Pilih Jurusan</option>
                <option value="Teknik Informatika" <?= isset($editData) && $editData['jurusan'] == 'Teknik Informatika' ? 'selected' : '' ?>>Teknik Informatika</option>
                <option value="Sistem Operasi" <?= isset($editData) && $editData['jurusan'] == 'Sistem Operasi' ? 'selected' : '' ?>>Sistem Operasi</option>
            </select>
            <textarea name="alamat" placeholder="Alamat" required class="w-full mb-2 px-3 py-2 border rounded"><?= $editData['alamat'] ?? '' ?></textarea>
            <div class="flex justify-end">
                <button type="submit" name="<?= $editData ? 'edit' : 'tambah' ?>" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700"><?= $editData ? 'Update' : 'Simpan' ?>
                </button>
            </div>
        </form>
    </main>
    
</div>
</body>
</html>
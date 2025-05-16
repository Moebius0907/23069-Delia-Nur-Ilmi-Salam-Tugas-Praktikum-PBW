<!-- Halaman krs -->
<?php
include('koneksi.php');

$showSuccessNotification = false;
$errorMessage = '';
$editMode = false;
$editData = null;

// Handle edit mode
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $koneksi->query("SELECT * FROM krs WHERE id=$id");
    if ($result->num_rows > 0) {
        $editMode = true;
        $editData = $result->fetch_assoc();
    }
}

// Query untuk menambah/mengubah data ke database krs
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $npm = $_POST['mahasiswa_npm'];
    $kodemk = $_POST['matakuliah_kodemk'];
    
    // Query untuk mengecek mahasiswa 
    $checkMahasiswa = $koneksi->prepare("SELECT * FROM mahasiswa WHERE npm = ?");
    $checkMahasiswa->bind_param("s", $npm);
    $checkMahasiswa->execute();
    $resultMahasiswa = $checkMahasiswa->get_result();

    // Query untuk mengecek mata kuliah
    $checkMatakuliah = $koneksi->prepare("SELECT * FROM matakuliah WHERE kodemk = ?");
    $checkMatakuliah->bind_param("s", $kodemk);
    $checkMatakuliah->execute();
    $resultMatakuliah = $checkMatakuliah->get_result();

    if ($resultMahasiswa->num_rows === 0) {
        $errorMessage = "NPM Mahasiswa tidak ditemukan!";
    } elseif ($resultMatakuliah->num_rows === 0) {
        $errorMessage = "Kode Mata Kuliah tidak ditemukan!";
    } else {
        if (isset($_POST['edit_id'])) {
            // Update krs
            $id = $_POST['edit_id'];
            $stmt = $koneksi->prepare("UPDATE krs SET mahasiswa_npm=?, matakuliah_kodemk=? WHERE id=?");
            $stmt->bind_param("ssi", $npm, $kodemk, $id);
            $notificationMessage = "Data KRS berhasil diperbarui!";
        } else {
            // tambahkan data krs
            $stmt = $koneksi->prepare("INSERT INTO krs (mahasiswa_npm, matakuliah_kodemk) VALUES (?, ?)");
            $stmt->bind_param("ss", $npm, $kodemk);
            $notificationMessage = "Data KRS berhasil ditambahkan!";
        }
        
        if ($stmt->execute()) {
            $showSuccessNotification = true;
        } else { //pesan jika gagal
            $errorMessage = "Gagal menyimpan data: " . $koneksi->error;
        }
    }
}

// Query untuk Hapus data
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $koneksi->query("DELETE FROM krs WHERE id=$id");
    header("Location: krs.php?deleted=1");
    exit;
}

// Query untuk pencarian
$cari = isset($_GET['cari']) ? $_GET['cari'] : '';
$sql = "
    SELECT krs.id, mahasiswa.nama AS nama_mhs, mahasiswa.npm, matakuliah.nama AS nama_mk, matakuliah.kodemk, matakuliah.jumlah_sks
    FROM krs 
    JOIN mahasiswa ON krs.mahasiswa_npm = mahasiswa.npm 
    JOIN matakuliah ON krs.matakuliah_kodemk = matakuliah.kodemk 
    WHERE mahasiswa.nama LIKE '%$cari%' OR matakuliah.nama LIKE '%$cari%'
    ORDER BY mahasiswa.nama
";
$data = $koneksi->query($sql);

$mahasiswa = $koneksi->query("SELECT * FROM mahasiswa ORDER BY nama");
$matkul = $koneksi->query("SELECT * FROM matakuliah ORDER BY nama");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Data KRS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome untuk icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.6s ease-out;
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
        .hover-effect:hover {
            transform: translateY(-2px);
            transition: transform 0.2s ease;
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
            <a href="matkul.php" class="block bg-gray-100 text-black font-semibold hover:bg-[#B2C6D5] rounded-md px-4 py-4 text-center flex items-center">
                <i class="fas fa-book mr-2"></i>
                <span>Mata Kuliah</span>
            </a>
            <a href="krs.php" class="block bg-white text-[#27548A]  font-semibold hover:bg-[#B2C6D5] rounded-md px-4 py-4 text-center flex items-center">
                <i class="fas fa-clipboard-list mr-2"></i>
                <span>KRS</span>
            </a>
        </nav>
    </aside>

    <!-- Konten Utama -->
    <main class="flex-1 p-10 fade-in">
        <h1 class="text-2xl font-bold mb-6 text-[#27548A] flex items-center">
            <i class="fas fa-clipboard-list mr-2"></i>
            <span>Manajemen KRS</span>
        </h1>

        <?php if (isset($_GET['deleted'])): ?>
            <div class="notification" id="successNotification">
                Data KRS berhasil dihapus!
            </div>
            <script>
                setTimeout(function() {
                    document.getElementById('successNotification').remove();
                }, 3000);
            </script>
        <?php endif; ?>

        <?php if ($showSuccessNotification): ?>
            <div class="notification" id="addSuccessNotification">
                <?= $notificationMessage ?>
            </div>
            <script>
                setTimeout(function() {
                    document.getElementById('addSuccessNotification').remove();
                }, 3000);
            </script>
        <?php endif; ?>

        <?php if ($errorMessage): ?>
            <div class="notification error" id="errorNotification">
                <?= $errorMessage ?>
            </div>
            <script>
                setTimeout(function() {
                    document.getElementById('errorNotification').remove();
                }, 3000);
            </script>
        <?php endif; ?>

        <!-- Form pencarian krs -->
        <form method="GET" class="mb-6 flex items-center gap-2 justify-center">
            <input type="text" name="cari" placeholder="Cari mahasiswa/matkul..." 
                   value="<?= htmlspecialchars($cari) ?>" 
                   class="px-4 py-2 border rounded w-64 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="bg-white px-4 py-2 hover:bg-[#27548A]/30 rounded-md flex items-center">
                <i class="fas fa-search mr-2 text-gray-700"></i>
                <span class="text-gray-700">Cari</span>
            </button>
            <a href="krs.php" class="text-blue-600 underline ml-2">Reset</a>
        </form>

        <!-- Tabel Data krs-->
        <div class="overflow-x-auto bg-white rounded-lg shadow hover-effect">
            <table class="w-full text-sm">
                <thead class="bg-[#27548A] text-white">
                    <tr>
                        <th class="p-4 text-left">Mahasiswa</th>
                        <th class="p-4 text-left">Mata Kuliah</th>
                        <th class="p-4 text-left">Keterangan</th>
                        <th class="p-4 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($data->num_rows > 0): ?>
                        <?php while ($krs = $data->fetch_assoc()): ?>
                            <tr class="border-t hover:bg-gray-50">
                                <td class="p-4"><?= $krs['nama_mhs'] ?> (<?= $krs['npm'] ?>)</td>
                                <td class="p-4"><?= $krs['nama_mk'] ?> (<?= $krs['kodemk'] ?>)</td>
                                <td class="p-4">
                                    <?= $krs['nama_mhs'] ?> mengambil mata kuliah <?= $krs['nama_mk'] ?> (<?= $krs['jumlah_sks'] ?> SKS)
                                </td>
                                <td class="p-4">
                                    <a href="krs.php?edit=<?= $krs['id'] ?>" 
                                       class="text-blue-600 hover:text-blue-800 inline-flex items-center">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </a>
                                    <a href="krs.php?hapus=<?= $krs['id'] ?>" 
                                       onclick="return confirm('Yakin hapus data?')" 
                                       class="text-red-600 hover:text-red-800 inline-flex items-center">
                                        <i class="fas fa-trash-alt mr-1"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="p-4 text-center text-gray-500">
                                <?= $cari ? 'Data tidak ditemukan.' : 'Belum ada data KRS.' ?>
                            </td>
                        </tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>

        <!-- Form Tambah/Edit data krs -->
        <div class="bg-white p-6 rounded-lg shadow mt-8 hover-effect">
            <h2 class="text-xl font-semibold mb-4 text-[#27548A] flex items-center">
                <i class="fas <?= $editMode ? 'fa-edit' : 'fa-plus-circle' ?> mr-2"></i>
                <?= $editMode ? 'Edit Data KRS' : 'Tambah KRS Baru' ?>
            </h2>
            <form method="POST">
                <?php if ($editMode): ?>
                    <input type="hidden" name="edit_id" value="<?= $editData['id'] ?>">
                <?php endif; ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="mahasiswa_npm" class="block text-gray-700 mb-2">NPM Mahasiswa</label>
                        <input type="text" name="mahasiswa_npm" id="mahasiswa_npm" 
                               value="<?= $editMode ? $editData['mahasiswa_npm'] : '' ?>" 
                               required 
                               class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               placeholder="Masukkan NPM Mahasiswa">
                    </div>
                    <div>
                        <label for="matakuliah_kodemk" class="block text-gray-700 mb-2">Mata Kuliah</label>
                        <select name="matakuliah_kodemk" id="matakuliah_kodemk" 
                                required 
                                class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Pilih Mata Kuliah --</option>
                            <?php 
                            $matkul->data_seek(0);
                            while ($mk = $matkul->fetch_assoc()): 
                                $selected = ($editMode && $editData['matakuliah_kodemk'] == $mk['kodemk']) ? 'selected' : '';
                            ?>
                                <option value="<?= $mk['kodemk'] ?>" <?= $selected ?>>
                                    <?= $mk['nama'] ?> (<?= $mk['kodemk'] ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end">
                    <?php if ($editMode): ?>
                        <a href="krs.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-700 mr-2 flex items-center">
                            <i class="fas fa-times mr-2"></i> Batal
                        </a>
                    <?php endif; ?>
                    <button type="submit" class="bg-[#27548A] text-white px-6 py-2 rounded hover:bg-[#1e436e] flex items-center">
                        <i class="fas <?= $editMode ? 'fa-save' : 'fa-plus' ?> mr-2"></i>
                        <?= $editMode ? 'Update Data' : 'Tambah KRS' ?>
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>
</body>
</html>
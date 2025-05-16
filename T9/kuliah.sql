-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2025 at 06:00 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kuliah`
--

-- --------------------------------------------------------

--
-- Table structure for table `krs`
--

CREATE TABLE `krs` (
  `id` int(11) NOT NULL,
  `mahasiswa_npm` char(13) DEFAULT NULL,
  `matakuliah_kodemk` char(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `krs`
--

INSERT INTO `krs` (`id`, `mahasiswa_npm`, `matakuliah_kodemk`) VALUES
(7, '2310631170003', 'FIK620'),
(9, '2310631170069', 'FIK614'),
(11, '2310631170159', 'FIK613'),
(12, '2310631170069', 'FIK613'),
(13, '2310631170069', 'FIK614'),
(14, '2310631170051', 'FIK621'),
(15, '2310631250041', 'SIK619');

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `npm` char(13) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `jurusan` enum('Teknik Informatika','Sistem Operasi') DEFAULT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`npm`, `nama`, `jurusan`, `alamat`) VALUES
('2310631170003', 'Afifah Fadilah', 'Teknik Informatika', 'Subang'),
('2310631170051', 'Surya Kamal', 'Teknik Informatika', 'Tambun'),
('2310631170069', 'Delia Nur Ilmi Salam', 'Teknik Informatika', 'Garut'),
('2310631170105', 'Nabiilah Nur Fauziyyah ', 'Teknik Informatika', 'Bekasi\r\n'),
('2310631170159', 'Zahra Fadhilatusyifa', 'Teknik Informatika', 'Cikarang'),
('2310631250041', 'Azzelya Rosya Denovya', 'Sistem Operasi', 'Bekasi'),
('2310631250095', 'Muhammad Aziz Sutawijaya', 'Sistem Operasi', 'Bogor');

-- --------------------------------------------------------

--
-- Table structure for table `matakuliah`
--

CREATE TABLE `matakuliah` (
  `kodemk` char(6) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `jumlah_sks` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `matakuliah`
--

INSERT INTO `matakuliah` (`kodemk`, `nama`, `jumlah_sks`) VALUES
('FIK613', 'KECERDASAN BUATAN', 3),
('FIK614', 'SISTEM OPERASI ', 3),
('FIK615', 'REKAYASA PERANGKAT LUNAK', 3),
('FIK617', 'ANALISIS DESAIN ALGORITMA', 3),
('FIK618', 'PEMROGRAMAN BERBASIS WEB', 3),
('FIK619', 'STATISTIKA DAN PROBABILITAS', 3),
('FIK620', 'EMBEDDED INTELLIGENT SYSTEMS', 3),
('FIK621', 'METODE NUMERIK', 3),
('SIK619', 'DATA WAREHOUSE ', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `krs`
--
ALTER TABLE `krs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mahasiswa_npm` (`mahasiswa_npm`),
  ADD KEY `matakuliah_kodemk` (`matakuliah_kodemk`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`npm`);

--
-- Indexes for table `matakuliah`
--
ALTER TABLE `matakuliah`
  ADD PRIMARY KEY (`kodemk`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `krs`
--
ALTER TABLE `krs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `krs`
--
ALTER TABLE `krs`
  ADD CONSTRAINT `krs_ibfk_1` FOREIGN KEY (`mahasiswa_npm`) REFERENCES `mahasiswa` (`npm`),
  ADD CONSTRAINT `krs_ibfk_2` FOREIGN KEY (`matakuliah_kodemk`) REFERENCES `matakuliah` (`kodemk`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

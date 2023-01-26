-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 26, 2023 at 11:20 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `apptoko`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `password`, `nama`) VALUES
(43, 'haechan@gmail.com', '6320c1115d5bc2b6ca615b96be050884', 'haechan'),
(56, 'hc@gmail.com', '6320c1115d5bc2b6ca615b96be050884', 'hc'),
(57, 'sun@gmail.com', 'ebd556e6dfc99dbed29675ce1c6c68e5', 'fullsun'),
(58, 'fullsun@gmail.com', 'ebd556e6dfc99dbed29675ce1c6c68e5', 'haechan'),
(59, 'haechan@gmail.com', '6320c1115d5bc2b6ca615b96be050884', 'haechan'),
(60, 'sunflower@gmail.com', 'ebd556e6dfc99dbed29675ce1c6c68e5', 'sunflower'),
(61, 'fullsun@gmail.com', 'ebd556e6dfc99dbed29675ce1c6c68e5', 'haechan'),
(62, 'donghyuck@gmail.com', 'ebd556e6dfc99dbed29675ce1c6c68e5', 'donghyuck lee'),
(63, 'hyuck@gmail.com', 'ebd556e6dfc99dbed29675ce1c6c68e5', 'donghyuck'),
(64, 'hyuck@gmail.com', 'ebd556e6dfc99dbed29675ce1c6c68e5', 'donghyuck'),
(65, 'hyuckdong@gmail.com', 'ebd556e6dfc99dbed29675ce1c6c68e5', 'hyuck'),
(66, 'hyuckdong@gmail.com', 'ebd556e6dfc99dbed29675ce1c6c68e5', 'hyuck'),
(67, 'hyuckdong@gmail.com', 'ebd556e6dfc99dbed29675ce1c6c68e5', 'hyuck'),
(68, 'hyuck66@gmail.com', 'ebd556e6dfc99dbed29675ce1c6c68e5', 'hyuck');

-- --------------------------------------------------------

--
-- Table structure for table `item_transaksi`
--

CREATE TABLE `item_transaksi` (
  `id` int(11) NOT NULL,
  `transaksi_id` int(11) NOT NULL,
  `produk_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `harga_saat_transaksi` int(11) NOT NULL,
  `sub_total` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item_transaksi`
--

INSERT INTO `item_transaksi` (`id`, `transaksi_id`, `produk_id`, `qty`, `harga_saat_transaksi`, `sub_total`) VALUES
(20, 11, 12, 3, 10000, 30000),
(21, 12, 14, 1, 2000, 2000),
(22, 12, 12, 2, 10000, 20000),
(23, 12, 13, 2, 8500, 17000),
(24, 13, 12, 2, 10000, 20000),
(25, 13, 13, 2, 8500, 17000),
(26, 13, 14, 4, 2000, 8000),
(27, 12, 12, 2, 120000, 240000),
(33, 32, 12, 1, 305000, 305000),
(34, 39, 25, 1, 30500, 30500);

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `harga` int(11) NOT NULL,
  `stok` int(11) DEFAULT NULL,
  `stok_jual` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `admin_id`, `nama`, `harga`, `stok`, `stok_jual`) VALUES
(12, 57, 'hello future', 305000, 12, 0),
(13, 57, 'we go up', 305000, 13, 0),
(14, 57, 'we young', 305000, 11, 0),
(18, 57, 'candy', 300000, 7, 0),
(19, 57, 'glitch mode', 395000, 10, 0),
(20, 57, 'boom', 295000, 6, 0),
(21, 57, 'beatbox', 395000, 7, 0),
(22, 58, '2022 season greetings', 900000, 6, 0),
(25, 58, 'beatbox', 30500, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `produk_id` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id`, `admin_id`, `nama`, `produk_id`, `harga`, `jumlah`) VALUES
(3, 57, 'sri', 13, 123000, 88);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `tanggal` datetime NOT NULL,
  `total` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `admin_id`, `tanggal`, `total`) VALUES
(9, 43, '2021-12-28 07:45:36', 52000),
(10, 43, '2021-12-28 08:17:06', 40000),
(11, 43, '2021-12-28 08:22:22', 54000),
(12, 43, '2022-01-04 01:30:58', 39000),
(13, 43, '2022-01-04 01:33:24', 45000),
(14, 58, '2022-11-22 13:41:14', 285000),
(16, 57, '2022-11-18 08:19:08', 3400000),
(17, 57, '2022-11-22 13:28:39', 725000),
(25, 57, '2023-01-26 17:41:09', 305000),
(26, 57, '2023-01-26 17:42:24', 305000),
(27, 57, '2023-01-26 17:47:28', 395000),
(28, 57, '2023-01-26 17:49:30', 900000),
(29, 57, '2023-01-26 18:02:05', 30500),
(30, 57, '2023-01-26 18:05:30', 305000),
(31, 57, '2023-01-26 18:10:14', 30500),
(32, 57, '2023-01-26 18:12:12', 305000),
(33, 58, '2023-01-26 18:26:43', 305000),
(34, 58, '2023-01-26 18:55:51', 305000),
(35, 58, '2023-01-26 18:59:12', 30500),
(37, 57, '2023-01-26 21:05:03', 61000),
(38, 58, '2023-01-26 21:08:43', 30500),
(39, 57, '2023-01-27 05:04:52', 30500);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_transaksi`
--
ALTER TABLE `item_transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_item_transaksi_produk_id` (`produk_id`),
  ADD KEY `fk_transaksi_id` (`transaksi_id`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_admin_id` (`admin_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_produkk_id` (`produk_id`),
  ADD KEY `fk_adminn_id` (`admin_id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_transaksi_admin_id` (`admin_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `item_transaksi`
--
ALTER TABLE `item_transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `item_transaksi`
--
ALTER TABLE `item_transaksi`
  ADD CONSTRAINT `fk_item_transaksi_produk_id` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`),
  ADD CONSTRAINT `fk_transaksi_id` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`);

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `fk_admin_id` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`);

--
-- Constraints for table `supplier`
--
ALTER TABLE `supplier`
  ADD CONSTRAINT `fk_adminn_id` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`),
  ADD CONSTRAINT `fk_produkk_id` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `fk_transaksi_admin_id` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

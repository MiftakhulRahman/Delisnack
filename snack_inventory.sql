-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 30 Des 2024 pada 03.28
-- Versi server: 8.0.30
-- Versi PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `snack_inventory`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Snack', 'Makanan ringan kemasan', '2024-12-29 03:34:38'),
(2, 'Minuman', 'Minuman kemasan atau botol', '2024-12-29 03:34:38'),
(3, 'Makanan Instan', 'Makanan instan siap masak', '2024-12-29 03:34:38'),
(4, 'Coklat dan Permen', 'Coklat batangan dan permen kemasan', '2024-12-29 03:34:38'),
(5, 'Susu dan Produk Olahan', 'Susu cair, bubuk, dan turunannya', '2024-12-29 03:34:38');

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category_id` int DEFAULT NULL,
  `supplier_id` int DEFAULT NULL,
  `stock` int DEFAULT '0',
  `min_stock` int DEFAULT '0',
  `buy_price` decimal(10,0) DEFAULT NULL,
  `sell_price` decimal(10,0) DEFAULT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `code`, `name`, `category_id`, `supplier_id`, `stock`, `min_stock`, `buy_price`, `sell_price`, `description`, `created_at`) VALUES
(1, 'SNK001', 'Choki-Choki', 1, 1, 50, 10, 500, 1000, 'Coklat pasta kemasan', '2024-12-29 03:45:15'),
(2, 'SNK002', 'Roma Biskuit Kelapa', 1, 1, 90, 20, 2000, 3500, 'Biskuit kelapa enak', '2024-12-29 03:45:15'),
(3, 'SNK003', 'Taro Snack Keju', 1, 2, 75, 15, 1500, 2500, 'Snack keju gurih', '2024-12-29 03:45:15'),
(4, 'MIN001', 'Teh Pucuk Harum', 2, 2, 196, 30, 3000, 5000, 'Teh kemasan botol', '2024-12-29 03:45:15'),
(5, 'MIN002', 'Aqua Gelas', 2, 2, 496, 50, 500, 1000, 'Air mineral gelas', '2024-12-29 03:45:15'),
(6, 'MIN003', 'Ultra Milk Coklat', 5, 4, 145, 20, 5000, 8000, 'Susu cair rasa coklat', '2024-12-29 03:45:15'),
(7, 'INS001', 'Indomie Goreng', 3, 2, 300, 20, 2500, 4000, 'Mie instan goreng', '2024-12-29 03:45:15'),
(8, 'INS002', 'Pop Mie Ayam', 3, 3, 150, 15, 4000, 6000, 'Mie instan cup rasa ayam', '2024-12-29 03:45:15'),
(9, 'COK001', 'SilverQueen Almond', 4, 4, 97, 10, 15000, 20000, 'Coklat almond premium', '2024-12-29 03:45:15'),
(10, 'PER001', 'Sugus Permen', 4, 6, 300, 30, 1000, 2000, 'Permen rasa buah', '2024-12-29 03:45:15'),
(11, 'SNK004', 'Lays Original', 1, 5, 35, 10, 10000, 14000, 'Keripik kentang kemasan', '2024-12-29 03:45:15'),
(12, 'MIN004', 'Good Day Cappuccino', 2, 6, 115, 20, 2000, 3000, 'Kopi instan rasa cappuccino', '2024-12-29 03:45:15'),
(13, 'MIN005', 'Milo Kotak', 5, 4, 96, 20, 5000, 7000, 'Susu rasa coklat', '2024-12-29 03:45:15'),
(14, 'INS003', 'Sarimi Ayam Bawang', 3, 2, 200, 25, 2000, 3500, 'Mie instan rasa ayam bawang', '2024-12-29 03:45:15'),
(15, 'COK002', 'KitKat 4 Fingers', 4, 4, 75, 10, 10000, 15000, 'Coklat renyah berlapis', '2024-12-29 03:45:15'),
(16, 'SKN004', 'Beng-Beng', 1, 1, 200, 20, 2000, 3500, 'Wafer coklat dengan karamel', '2024-12-29 04:03:15'),
(17, 'SNK005', 'Good Time Choco Chips', 1, 1, 150, 10, 1500, 2500, 'Biskuit dengan choco chips', '2024-12-29 04:04:13'),
(18, 'SNK006', 'Lays Classic', 1, 2, 119, 15, 5000, 8000, 'Keripik kentang rasa original', '2024-12-29 04:05:00'),
(19, 'SNK007', 'Cheetos Jagung Bakar', 1, 2, 100, 10, 4000, 6000, 'Snack rasa jagung bakar', '2024-12-29 04:06:57'),
(20, 'SNK008', 'Piatos Keju', 1, 3, 80, 5, 4500, 7500, 'Keripik kentang rasa keju', '2024-12-29 04:07:46'),
(21, 'INS004', 'Sarimi Rasa Ayam Bawang', 3, 3, 300, 15, 2000, 3500, 'Mie instan rasa ayam bawang', '2024-12-29 04:12:07'),
(22, 'INS005', 'Super Bubur Ayam', 3, 2, 146, 10, 5000, 8000, 'Bubur instan rasa ayam', '2024-12-29 04:15:15'),
(23, 'INS006', 'Pop Mie Kari Ayam', 3, 3, 9, 10, 4000, 6000, 'Mie instan cup rasa kari ayam', '2024-12-29 04:16:37'),
(24, 'COK003', 'Toblerone Milk Chocolate', 4, 5, 100, 5, 1500, 20000, 'Coklat Swiss berbentuk segitiga', '2024-12-29 04:17:32'),
(25, 'COK004', 'Hershey&#039;s Kisses', 4, 5, 5, 10, 20000, 30000, '', '2024-12-29 04:18:17'),
(26, 'SUS004', 'Bear Breand', 5, 2, 40, 10, 10000, 12000, 'Susu steril', '2024-12-29 06:41:53'),
(103, 'SUS009', 'Susu Murni', 5, 1, 50, 10, 10000, 12000, 'Susu Kental Manis', '2024-12-29 15:18:05');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales`
--

CREATE TABLE `sales` (
  `id` int NOT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales_details`
--

CREATE TABLE `sales_details` (
  `id` int NOT NULL,
  `sale_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` int NOT NULL,
  `product_id` int DEFAULT NULL,
  `type` enum('in','out') NOT NULL,
  `quantity` int NOT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `notes` text,
  `user_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` text,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `address`, `phone`, `email`, `created_at`) VALUES
(1, 'Mayora', 'Jl. Daan Mogot No.9', '021-1234567', 'contact@mayora.com', '2024-12-29 03:34:38'),
(2, 'Indofood', 'Jl. Sudirman No.25', '021-7654321', 'info@indofood.com', '2024-12-29 03:34:38'),
(3, 'GarudaFood', 'Jl. Kuningan No.45', '021-5556789', 'sales@garudafood.com', '2024-12-29 03:34:38'),
(4, 'Nestle', 'Jl. Gatot Subroto No.11', '021-8765432', 'support@nestle.co.id', '2024-12-29 03:34:38'),
(5, 'Wings Food', 'Jl. Pancoran No.8', '021-2345678', 'info@wingsfood.com', '2024-12-29 03:34:38'),
(6, 'Orang Tua Group', 'Jl. Kebon Jeruk No.33', '021-5678910', 'cs@orangtua.co.id', '2024-12-29 03:34:38');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `role` enum('admin','staff') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `name`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin', '2024-12-26 17:24:07');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indeks untuk tabel `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `sales_details`
--
ALTER TABLE `sales_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_id` (`sale_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeks untuk tabel `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_stock_movements_date` (`date`);

--
-- Indeks untuk tabel `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT untuk tabel `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT untuk tabel `sales_details`
--
ALTER TABLE `sales_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT untuk tabel `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT untuk tabel `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`);

--
-- Ketidakleluasaan untuk tabel `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `sales_details`
--
ALTER TABLE `sales_details`
  ADD CONSTRAINT `sales_details_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`),
  ADD CONSTRAINT `sales_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Ketidakleluasaan untuk tabel `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `stock_movements_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

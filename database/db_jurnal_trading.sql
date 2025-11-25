-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 25 Nov 2025 pada 16.10
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_jurnal_trading`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `trades`
--

CREATE TABLE `trades` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `currency_pair` varchar(10) NOT NULL,
  `position_type` enum('Buy','Sell') NOT NULL,
  `entry_price` decimal(10,5) NOT NULL,
  `stop_loss` decimal(10,5) NOT NULL,
  `take_profit` decimal(10,5) NOT NULL,
  `hasil` enum('Pending','TP','SL') NOT NULL DEFAULT 'Pending',
  `profit_loss` decimal(10,2) DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `trades`
--

INSERT INTO `trades` (`id`, `user_id`, `currency_pair`, `position_type`, `entry_price`, `stop_loss`, `take_profit`, `hasil`, `profit_loss`, `notes`) VALUES
(11, 1, 'XAU/USD', 'Buy', 4040.20000, 4020.20000, 4060.20000, 'TP', 21.30, 'BREAKOUT'),
(12, 1, 'XAU/USD', 'Buy', 4060.00000, 4040.00000, 4080.00000, 'TP', 100.40, 'BREAKOUT'),
(13, 1, 'XAU/USD', 'Buy', 4080.00000, 4060.00000, 4100.00000, 'TP', 100.10, 'BREAKOUT'),
(14, 1, 'XAU/USD', 'Buy', 4120.00000, 4100.00000, 4140.00000, 'TP', 161.20, 'BREAKOUT'),
(15, 1, 'XAU/USD', 'Buy', 4160.00000, 4140.00000, 4180.00000, 'TP', 161.28, 'BREAKOUT'),
(16, 1, 'XAU/USD', 'Buy', 4160.05000, 4140.00000, 4180.00000, 'SL', -163.60, 'BREAKOUT'),
(17, 1, 'XAU/USD', 'Sell', 4099.92000, 4120.00000, 4080.00000, 'SL', -160.96, 'BREAKOUT'),
(18, 1, 'XAU/USD', 'Buy', 4140.03000, 4120.00000, 4160.00000, 'SL', -162.16, 'BREAKOUT'),
(19, 1, 'XAU/USD', 'Sell', 4119.76000, 4140.00000, 4100.00000, 'TP', 165.20, 'BREAKOUT'),
(20, 1, 'XAU/USD', 'Sell', 4099.87000, 4120.00000, 4080.00000, 'SL', -161.04, 'BREAKOUT'),
(21, 1, 'XAU/USD', 'Buy', 4120.26000, 4100.00000, 4185.87000, 'TP', 524.88, 'BREAKOUT'),
(22, 1, 'XAU/USD', 'Buy', 4140.04000, 4120.00000, 4185.87000, 'TP', 367.44, 'BREAKOUT'),
(23, 1, 'XAU/USD', 'Buy', 4160.93000, 4140.00000, 4200.00000, 'TP', 200.08, 'BREAKOUT'),
(24, 1, 'XAU/USD', 'Buy', 4180.00000, 4160.00000, 4200.00000, 'TP', 159.28, 'BREAKOUT'),
(25, 1, 'XAU/USD', 'Buy', 4200.00000, 4180.00000, 4220.00000, 'Pending', NULL, 'BREAKOUT'),
(26, 1, 'GBP/USD', 'Buy', 200.00000, 180.00000, 220.00000, 'Pending', NULL, 'BREAKOUT'),
(27, 1, 'GBP/JPY', 'Buy', 1.57850, 1.56850, 1.58150, 'Pending', NULL, 'BREAKOUT');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$AtHGsxT6jdFOxaMJ1yD7p./igzn6HJ3I.YKtiV7Immr0N62Vde9s.', '2025-10-21 14:41:50'),
(2, 'karyawan', 'karyawan@gmail.com', '$2y$10$hQvx1CTDDiA.B0PrN52jRuoOyQHb2SaFWDqbobJgqxWhal13TIU/W', '2025-10-26 14:40:55');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `trades`
--
ALTER TABLE `trades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `trades`
--
ALTER TABLE `trades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `trades`
--
ALTER TABLE `trades`
  ADD CONSTRAINT `trades_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

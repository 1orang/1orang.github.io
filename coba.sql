-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 05 Mar 2025 pada 06.12
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `coba`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` enum('admin','staff') NOT NULL DEFAULT 'staff',
  `STATUS` enum('online','offline') DEFAULT 'offline'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `username`, `photo`, `email`, `password`, `level`, `STATUS`) VALUES
(1, 'Choirul umam', '40.png', 'umamcs14@gmail.com', '$2y$10$cRSs8UDvcYHXjMTgKmEF1ea78Wlpgud7Oqzy0r4.z7F9P4Hd0t4OC', 'admin', 'online'),
(2, 'M.setiyadi', '45.png', 'M.setiyadi@gmail.com', '$2y$10$WSsPr467IdCa/vNu7tygsOBTt/Px0rf4NxlDZlu5LN4pOX68Fl2Eu', 'staff', 'offline'),
(3, 'M.Ivan Fauzan', 'logo.png', 'ivan@gmail.com', '$2y$10$1Dp9Jg8NnEMWHszJMUx95ekblnVVE/Av36HXUrjykto3ZQnZgQMOG', 'staff', 'offline'),
(4, 'Arnold Joshua Nahatan', '46.png', 'arnold@gmail.com', '$2y$10$0g.JVBY0XJt/DNAw.9qMTeIZRCgLXpWpBfVfKL3f.WHmtCHYMF2l2', 'staff', 'offline'),
(5, 'Alya Putri', '35.png', 'Alya@gmail.com', '$2y$10$SPLWqEXMfXkbBLaQFBuzX./p/NFZpnk9z0nG0o/GFe9V4ArMfRK7q', 'staff', 'offline'),
(6, 'Mufaroha', 'logo.png', 'Mufaroha@gmail.com', '$2y$10$zaCVhG/VmzfYRGLePWNF8eY4PM6Xm9/09behU0djP9gWWoktvBhPK', 'staff', 'offline'),
(7, 'soliyah S.Ag', '36.png', 'soliyah@gmail.com', '$2y$10$IU8U6jsHzbSLPVKfp1cRBe0Co6kPnrwJawagIxhcGSZVf2IOIvVyi', 'staff', 'offline'),
(8, 'Siti Nurholis', 'polda.png', 'holis@gmail.com', '$2y$10$/pnUVX6ak4kZm.WC1GwPJ.gjPKQmxklY9bND7/dZeSPxpapvZ0Doy', 'staff', 'online'),
(9, 'Enti Ameliyani', '34.png', 'amel@gmail.com', '$2y$10$MkOujfxsQQxqpBrMVYILDe5RmebcsBe9XNj3wrb1g3yhCsZij2GEa', 'staff', 'offline'),
(11, 'Fajar Gilang Ramadhan', '48.png', 'fajar@gmail.com', '$2y$10$mfaduSBNXec4ILVxJgauzevWCk0AnhCL/vETt8PGtx.XQBei790y.', 'staff', 'offline'),
(10, 'Nuranah', '37.png', 'anah@gmail.com', '$2y$10$6gykdOhdIlu.LZTh71rHYeBrWaDxCRWq/6K1m/ALsOWnHBvBUUmUS', 'staff', 'offline');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

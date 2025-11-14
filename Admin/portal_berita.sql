-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Nov 2025 pada 01.55
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
-- Database: `portal_berita`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `konten` text NOT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `id_penulis` int(11) DEFAULT NULL,
  `id_editor` int(11) DEFAULT NULL,
  `gambar_sampul` varchar(255) DEFAULT NULL,
  `tags` text DEFAULT NULL,
  `status` enum('draft','pending','review','published','rejected') DEFAULT 'draft',
  `tanggal_dibuat` timestamp NOT NULL DEFAULT current_timestamp(),
  `tanggal_diupdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `tanggal_dipublikasi` timestamp NULL DEFAULT NULL,
  `views` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `berita`
--

CREATE TABLE `berita` (
  `id_berita` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `isi` longtext NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `status` enum('draft','pending','review','published') DEFAULT 'draft',
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `berita`
--

INSERT INTO `berita` (`id_berita`, `judul`, `kategori`, `tags`, `isi`, `gambar`, `status`, `tanggal`) VALUES
(2, 'Budaya Indonesia Pukau Dunia, Tari Gending Sriwijaya Sedot Perhatian di Osaka', 'Culture', '', 'OSAKA, theSurabayaNews.id – Warisan budaya Indonesia kembali mencuri perhatian dunia. Melalui komunitas tari AntarSukha, kekayaan tradisi Nusantara dipentaskan penuh warna di ajang Osaka Expo 2025 Jepang. Pertunjukan ini menjadi bukti nyata bagaimana seni tari Indonesia mampu menjadi bahasa universal yang menghubungkan bangsa-bangsa.\r\n\r\nAntarSukha menampilkan deretan tarian tradisional dari berbagai daerah, mulai dari Tari Gending Sriwijaya yang anggun dari Sumatera Selatan, Golek Langen Puspitasari khas Yogyakarta, gerakan energik Tari Tor Tor Tandok asal Sumatera Utara, hingga Goyang Kanan Kiri dari Nusa Tenggara Timur. Semua tarian tersebut diperindah dengan kostum berwarna-warni, aksesori etnik, dan koreografi yang sarat makna filosofis, membuat penonton terpukau.\r\n\r\nPendiri AntarSukha, Emira Oepangat, menyebut partisipasi dalam pameran dunia ini bukan sekadar penampilan seni, melainkan juga bentuk kontribusi nyata dalam menjaga identitas bangsa.\r\n\r\n“Kegiatan ini bukan hanya pertunjukan, tapi juga cara kami memperkenalkan budaya Indonesia ke panggung global. Kami bangga mendapat sambutan hangat dari panitia dan penonton yang antusias ingin mengenal lebih dekat budaya Nusantara,” ujar Emira.\r\n\r\nKomunitas ini tidak hanya berkarya di Jepang. Sebelumnya, AntarSukha telah membawa nama Indonesia ke berbagai negara: tampil di festival budaya di Prancis dan Serbia, mendukung acara diplomatik di Belanda dan Portugal, ikut serta dalam pertukaran budaya di Penang – Malaysia, hingga meramaikan festival Muara di Esplanade Singapura.', 'uploads/6912095c2ad7f_Tari-Gending.jpg', 'pending', '2025-11-10 15:48:44');

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`id`, `nama_kategori`, `deskripsi`, `status`) VALUES
(1, 'News', 'Berisi berita terkini seputar peristiwa nasional dan regional yang penting, disajikan secara faktual dan aktual untuk memberikan informasi terbaru kepada pembaca', 'active'),
(2, 'Economy', 'Membahas perkembangan ekonomi, bisnis, keuangan, dan kebijakan pemerintah yang memengaruhi dunia usaha serta masyarakat', 'active'),
(3, 'Lifestyle', 'Menyajikan artikel seputar gaya hidup, kesehatan, kuliner, hiburan, hingga tips keseharian yang relevan dengan tren masyarakat modern', 'active'),
(4, 'Culture', 'Mengangkat kekayaan budaya lokal dan internasional, tradisi, seni, serta nilai-nilai yang menjadi identitas suatu bangsa', 'active'),
(5, 'Sports', 'Menampilkan berita dan analisis seputar dunia olahraga, mulai dari sepak bola, badminton, hingga ajang olahraga internasional lainnya', 'active'),
(6, 'World', 'Memberikan informasi global tentang isu-isu internasional, politik dunia, konflik, dan peristiwa penting dari berbagai negara', 'active'),
(7, 'Fashion', 'Berfokus pada tren busana, kecantikan, dan gaya berpakaian terbaru, serta inspirasi dari dunia mode lokal dan global', 'active');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('jurnalis','editor','admin') NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `tanggal_bergabung` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama_lengkap`, `email`, `role`, `status`, `tanggal_bergabung`) VALUES
(1, 'amira_jurnalis', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Amira Izzati', 'amira@portalberita.com', 'jurnalis', 'active', '2025-11-10 13:53:28'),
(2, 'aisyah_editor', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Aisyah Widia Nugroho', 'aisyah@portalberita.com', 'editor', 'active', '2025-11-10 13:53:28'),
(3, 'nalia_admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Naila Zazkia Rizgina', 'nalia@portalberita.com', 'admin', 'active', '2025-11-10 13:53:28');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_kategori` (`id_kategori`),
  ADD KEY `id_penulis` (`id_penulis`),
  ADD KEY `id_editor` (`id_editor`);

--
-- Indeks untuk tabel `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id_berita`);

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
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
-- AUTO_INCREMENT untuk tabel `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `berita`
--
ALTER TABLE `berita`
  MODIFY `id_berita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `articles_ibfk_2` FOREIGN KEY (`id_penulis`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `articles_ibfk_3` FOREIGN KEY (`id_editor`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 05, 2026 at 02:16 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_tripmate`
--

-- --------------------------------------------------------

--
-- Table structure for table `appeals`
--

CREATE TABLE `appeals` (
  `id_appeals` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookmarks`
--

CREATE TABLE `bookmarks` (
  `id_bookmark` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `destination_id` bigint(20) UNSIGNED NOT NULL,
  `source` enum('wisata','kuliner','penginapan') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookmarks`
--

INSERT INTO `bookmarks` (`id_bookmark`, `id_user`, `destination_id`, `source`, `created_at`, `updated_at`) VALUES
(1, 3, 41, 'wisata', '2026-08-04 23:53:57', '2026-08-04 23:53:57'),
(2, 3, 1, 'kuliner', '2026-08-05 01:07:29', '2026-08-05 01:07:29');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `destinasi`
--

CREATE TABLE `destinasi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_destinasi` varchar(255) NOT NULL,
  `tipe` enum('wisata','kuliner','penginapan') NOT NULL,
  `kota` varchar(255) NOT NULL,
  `kategori` varchar(255) NOT NULL,
  `harga` decimal(12,2) NOT NULL DEFAULT 0.00,
  `hidden_gem` tinyint(1) NOT NULL DEFAULT 0,
  `deskripsi` text DEFAULT NULL,
  `fasilitas` text DEFAULT NULL,
  `fitur_cbf` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `destinasi_images`
--

CREATE TABLE `destinasi_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `destinasi_id` bigint(20) UNSIGNED NOT NULL,
  `url_image` varchar(255) NOT NULL,
  `is_thumbnail` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `destinasi_kategori`
--

CREATE TABLE `destinasi_kategori` (
  `destinasi_id` bigint(20) UNSIGNED NOT NULL,
  `kategori_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail`
--

CREATE TABLE `detail` (
  `id_detail` bigint(20) UNSIGNED NOT NULL,
  `id_perencanaan` bigint(20) UNSIGNED NOT NULL,
  `id_destinasi` bigint(20) UNSIGNED NOT NULL,
  `jenis` varchar(255) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id_jadwal` bigint(20) UNSIGNED NOT NULL,
  `id_perencanaan` bigint(20) UNSIGNED NOT NULL,
  `id_destinasi` bigint(20) UNSIGNED DEFAULT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jarak`
--

CREATE TABLE `jarak` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_wisata` bigint(20) UNSIGNED DEFAULT NULL,
  `id_kuliner` bigint(20) UNSIGNED DEFAULT NULL,
  `id_penginapan` bigint(20) UNSIGNED DEFAULT NULL,
  `jarak` decimal(10,2) DEFAULT NULL,
  `durasi` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_kategori` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kuliner`
--

CREATE TABLE `kuliner` (
  `id_kuliner` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` text DEFAULT NULL,
  `deskripsi` longtext DEFAULT NULL,
  `kota` varchar(100) DEFAULT NULL,
  `provinsi` varchar(100) DEFAULT NULL,
  `transportasi` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `harga` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tipe_kuliner` varchar(255) DEFAULT NULL,
  `jam_buka` time DEFAULT NULL,
  `jam_tutup` time DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `kategori_kuliner` varchar(255) DEFAULT NULL,
  `trend` varchar(20) NOT NULL DEFAULT 'Populer',
  `budget` varchar(20) DEFAULT NULL,
  `fasilitas` text DEFAULT NULL,
  `fitur_cbf` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `hari_operasional` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kuliner`
--

INSERT INTO `kuliner` (`id_kuliner`, `nama`, `alamat`, `deskripsi`, `kota`, `provinsi`, `transportasi`, `gambar`, `harga`, `tipe_kuliner`, `jam_buka`, `jam_tutup`, `latitude`, `longitude`, `kategori_kuliner`, `trend`, `budget`, `fasilitas`, `fitur_cbf`, `created_at`, `updated_at`, `hari_operasional`) VALUES
(1, 'Cloud Cakes', 'Jl. Sampit I No.59, Kramat Pela, Kebayoran Baru', 'Ekspansi gerai dessert Bali yang memicu antrean viral panjang berkat tekstur donat mochi berlapis kenyal ganda.', 'Jakarta Selatan', 'DKI Jakarta', 'MRT Blok M, TransJakarta Blok M, Kendaraan Pribadi', 'https://tse1.mm.bing.net/th/id/OIP.XCMJmA1C-FrmqmfAlOOYdQHaEK?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 45000.00, 'Kafe & Dessert', '14:00:00', '18:00:00', -6.24433200, 106.79657900, 'Dessert (Donat Mochi)', 'Hidden Gem', 'murah', 'parkir, toilet, Wi-Fi, pembayaran non tunai, Takeaway, pembayaran tunai', 'kafe dessert donat mochi hidden gem murah jakarta selatan dki jakarta mrt blok m transjakarta blok m kendaraan pribadi parkir toilet wi fi pembayaran non tunai takeaway pembayaran tunai ekspansi gerai dessert bali yang memicu antrean viral panjang berkat tekstur donat mochi berlapis kenyal ganda', NULL, NULL, 'Setiap Hari'),
(2, 'Donatopia', 'Blok M Hub, Jl. Sultan Hasanuddin Dalam, Melawai', 'Gerai donat bergaya industrial dengan variasi donat kotak ramah kantong dan inovasi gaya Thailand.', 'Jakarta Selatan', 'DKI Jakarta', 'MRT Blok M, TransJakarta Blok M', 'https://image.idntimes.com/post/20251103/thumb-3-1_d5a6a09c-62ba-4faf-a56f-3e93869fefe0.jpg', 22500.00, 'Kafe & Dessert', '13:00:00', '20:00:00', -6.24361000, 106.80058200, 'Dessert (Roti & Donat)', 'Terkenal', 'murah', 'parkir, toilet, area merokok, pembayaran non tunai, Takeaway, pembayaran tunai', 'kafe dessert roti donat terkenal murah jakarta selatan dki jakarta mrt blok m transjakarta blok m parkir toilet area merokok pembayaran non tunai takeaway pembayaran tunai gerai donat bergaya industrial dengan variasi donat kotak ramah kantong dan inovasi gaya thailand', NULL, NULL, 'Setiap Hari'),
(3, 'Ayam Renald', 'Blok M Hub, Jl. Melawai Raya', 'Sensasi hidangan jalanan dengan olahan protein ayam bumbu pekat nusantara lintas daerah, termasuk bumbu Palekko.', 'Jakarta Selatan', 'DKI Jakarta', 'MRT Blok M, TransJakarta Blok M', 'https://tse2.mm.bing.net/th/id/OIP.uEU6EOW9snOlOmTGeYVLgAHaEK?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 32500.00, 'Nusantara', '12:00:00', '22:00:00', -6.23988200, 106.81220500, 'Makanan Berat Nusantara', 'Terkenal', 'murah', 'parkir, toilet, Wi-Fi, pembayaran non tunai, Takeaway, pembayaran tunai', 'nusantara makanan berat terkenal murah jakarta selatan dki jakarta mrt blok m transjakarta blok m parkir toilet wi fi pembayaran non tunai takeaway pembayaran tunai sensasi hidangan jalanan dengan olahan protein ayam bumbu pekat nusantara lintas daerah termasuk bumbu palekko', NULL, NULL, 'Setiap Hari'),
(4, 'Obihiro Nikudon', 'Wisma Nasional, Jl. Melawai 5 No.14', 'Suaka gastronomi Little Tokyo yang mengabdikan menu utamanya pada potongan lemak daging karubi premium.', 'Jakarta Selatan', 'DKI Jakarta', 'MRT Blok M, TransJakarta Blok M', 'https://indopop.id/storage/gambar/900x500/obihiro-nikudon-blok-m-instagram-051120253.webp', 67500.00, 'Asia', '09:00:00', '23:00:00', -6.24989100, 106.62900100, 'Nasi Daging Jepang', 'Hidden Gem', 'murah', 'parkir, toilet, area merokok, pembayaran non tunai, Takeaway, pembayaran tunai', 'asia nasi daging jepang hidden gem murah jakarta selatan dki jakarta mrt blok m transjakarta blok m parkir toilet area merokok pembayaran non tunai takeaway pembayaran tunai suaka gastronomi little tokyo yang mengabdikan menu utamanya pada potongan lemak daging karubi premium', NULL, NULL, 'Setiap Hari'),
(5, 'Sarang Semut', 'Jl. Bulungan No.12, Kramat Pela', 'Ruang komunal dengan rancangan lorong gelap eksperimental, menuntut atensi penuh pada tekstur makanan.', 'Jakarta Selatan', 'DKI Jakarta', 'MRT Blok M, TransJakarta Blok M', 'https://assets-a1.kompasiana.com/items/album/2025/11/08/sarang-semut-690f2979ed64154fe84c5fa3.jpg?t=o&v=1200', 90000.00, 'Kafe & Dessert', '07:00:00', '22:00:00', -6.24489300, 106.79730300, 'Kafe / Pastry / Gelato', 'Hidden Gem', 'murah', 'parkir, toilet, Wi-Fi, area merokok, ruang keluarga, pembayaran non tunai, Takeaway, mushola, pembayaran tunai', 'kafe dessert pastry gelato hidden gem murah jakarta selatan dki jakarta mrt blok m transjakarta blok m parkir toilet wi fi area merokok ruang keluarga pembayaran non tunai takeaway mushola pembayaran tunai ruang komunal dengan rancangan lorong gelap eksperimental menuntut atensi penuh pada tekstur makanan', NULL, NULL, 'Setiap Hari'),
(6, 'Jullien', 'Pacific Place Mall, Level GF, South Lobby', 'Simulai gerbong kereta kuno elegan Paris yang menjanjikan akurasi masakan pedesaan tradisional khas benua Eropa.', 'Jakarta Selatan', 'DKI Jakarta', 'MRT Istora Mandiri, TransJakarta', 'https://tse2.mm.bing.net/th/id/OIP.RLyt21v3jzAovtud1aBgAQHaEz?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 40000.00, 'Western', '10:00:00', '22:00:00', -6.22507400, 106.81020900, 'Bistro Prancis Kelas Atas', 'Hidden Gem', 'murah', 'parkir, toilet, Wi-Fi, pembayaran non tunai, Takeaway, mushola, pembayaran tunai', 'western bistro prancis kelas atas hidden gem murah jakarta selatan dki jakarta mrt istora mandiri transjakarta parkir toilet wi fi pembayaran non tunai takeaway mushola pembayaran tunai simulai gerbong kereta kuno elegan paris yang menjanjikan akurasi masakan pedesaan tradisional khas benua eropa', NULL, NULL, 'Setiap Hari'),
(7, 'The Cutler Prime Rib', 'Pacific Place Mall', 'Teater untuk karnivora ibu capital dengan metode pemanggangan lambat menggunakan saus reduksi tulang ekstensif.', 'Jakarta Selatan', 'DKI Jakarta', 'MRT Istora Mandiri, Kendaraan Pribadi', 'https://tse2.mm.bing.net/th/id/OIP.r_Ur773_1psXtjYhCKrJJwHaEK?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 375000.00, 'Western', '11:00:00', '22:00:00', -6.22419300, 106.80973500, 'Steakhouse Premium', 'Hidden Gem', 'mahal', 'parkir, toilet, Wi-Fi, area merokok, pembayaran non tunai, Takeaway, mushola, pembayaran tunai', 'western steakhouse premium hidden gem mahal jakarta selatan dki jakarta mrt istora mandiri kendaraan pribadi parkir toilet wi fi area merokok pembayaran non tunai takeaway mushola pembayaran tunai teater untuk karnivora ibu capital dengan metode pemanggangan lambat menggunakan saus reduksi tulang ekstensif', NULL, NULL, 'Setiap Hari'),
(8, 'ABOUT US Brasserie', 'Kebayoran Baru', 'Pengukuhan arsitektur rustic Nordik yang menyajikan palet berani fusion seperti telur berbumbu kecombrang.', 'Jakarta Selatan', 'DKI Jakarta', 'MRT Asean, Kendaraan Pribadi', 'https://tse2.mm.bing.net/th/id/OIP.KBJplqAc-xrwyxM0b0yY9QHaFb?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 120000.00, 'Fusion', '08:00:00', '22:00:00', -6.23568000, 106.80775200, 'Fusion Skandi-Asia', 'Hidden Gem', 'sedang', 'parkir, toilet, Wi-Fi, area merokok, pembayaran non tunai, Takeaway, mushola, pembayaran tunai', 'fusion skandi asia hidden gem sedang jakarta selatan dki jakarta mrt asean kendaraan pribadi parkir toilet wi fi area merokok pembayaran non tunai takeaway mushola pembayaran tunai pengukuhan arsitektur rustic nordik yang menyajikan palet berani fusion seperti telur berbumbu kecombrang', NULL, NULL, 'Setiap Hari'),
(9, 'Numi Bistrolounge', 'Jl. Wolter Monginsidi No.40A, Cipete', 'Lounge kedap cahaya silau bergaya luks, tempat pelarian privat para penikmat gastronomi tingkat lanjutan.', 'Jakarta Selatan', 'DKI Jakarta', 'MRT Cipete Raya, Kendaraan Pribadi', 'https://whatsnewindonesia.com/sites/default/files/Stallone/August%202025/numi%20bistro.webp', 125000.00, 'Fusion', '11:00:00', '23:00:00', -6.27906500, 106.79836100, 'Fusi Asia-Eropa', 'Hidden Gem', 'sedang', 'parkir, toilet, Wi-Fi, area merokok, pembayaran non tunai, Takeaway, mushola, pembayaran tunai', 'fusion fusi asia eropa hidden gem sedang jakarta selatan dki jakarta mrt cipete raya kendaraan pribadi parkir toilet wi fi area merokok pembayaran non tunai takeaway mushola pembayaran tunai lounge kedap cahaya silau bergaya luks tempat pelarian privat para penikmat gastronomi tingkat lanjutan', NULL, NULL, 'Setiap Hari'),
(10, 'Namaaz Dining', 'Jl. Gunawarman No.42', 'Ruang pertunjukan sulap ilmiah masakan nusantara, mendobrak persepsi panca indera mengenai wujud asli makanan.', 'Jakarta Selatan', 'DKI Jakarta', 'MRT Senayan, Kendaraan Pribadi', 'https://tse3.mm.bing.net/th/id/OIP.91VE3zLQfMQ9hcf1cvElZwHaEK?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 1600000.00, 'Fusion', '19:00:00', '22:00:00', -6.24895700, 106.80711000, 'Gastronomi Molekuler', 'Terkenal', 'mahal', 'parkir, toilet, Wi-Fi, area merokok, pembayaran non tunai, Takeaway, mushola, pembayaran tunai', 'fusion gastronomi molekuler terkenal mahal jakarta selatan dki jakarta mrt senayan kendaraan pribadi parkir toilet wi fi area merokok pembayaran non tunai takeaway mushola pembayaran tunai ruang pertunjukan sulap ilmiah masakan nusantara mendobrak persepsi panca indera mengenai wujud asli makanan', NULL, NULL, 'Sel-Sab'),
(11, 'Butter Baby', 'PIM 2 & Central Park (Cabang)', 'Dominasi ruang krom perak berhiaskan dessert hibrida yang menekan batas kenormalan roti lapis dan ayam goreng.', 'Jakarta Selatan', 'DKI Jakarta', 'KRL, TransJakarta, Kendaraan Pribadi', 'https://tse4.mm.bing.net/th/id/OIP.acwsr9ZzxLFkRORc6QNZQAHaJ5?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 35000.00, 'Nusantara', '10:00:00', '22:00:00', -6.24453700, 106.80306200, 'Kue Pencuci Mulut Estetik', 'Hidden Gem', 'murah', 'parkir, toilet, Wi-Fi, area merokok, ruang keluarga, pembayaran non tunai, Takeaway, mushola, pembayaran tunai', 'nusantara kue pencuci mulut estetik hidden gem murah jakarta selatan dki jakarta krl transjakarta kendaraan pribadi parkir toilet wi fi area merokok ruang keluarga pembayaran non tunai takeaway mushola pembayaran tunai dominasi ruang krom perak berhiaskan dessert hibrida yang menekan batas kenormalan roti lapis dan ayam goreng', NULL, NULL, 'Setiap Hari'),
(12, 'Arborea Café', 'Gedung KLHK, Jl. Gatot Subroto', 'Pelarian harfiah dari asap knalpot ibu kota menuju panggung kayu ekologis di jantung pepohonan kompleks kementerian.', 'Jakarta Selatan', 'DKI Jakarta', 'KRL Palmerah, TransJakarta', 'https://tse3.mm.bing.net/th/id/OIP.uYHMzAIVp7vRxpMi4FpvDwHaFj?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 42500.00, 'Kafe & Dessert', '08:30:00', '22:00:00', -6.20607500, 106.80153800, 'Kafe Kopi Taman', 'Hidden Gem', 'murah', 'parkir, toilet, Wi-Fi, area merokok, pembayaran non tunai, Takeaway, mushola, pembayaran tunai', 'kafe dessert kopi taman hidden gem murah jakarta selatan dki jakarta krl palmerah transjakarta parkir toilet wi fi area merokok pembayaran non tunai takeaway mushola pembayaran tunai pelarian harfiah dari asap knalpot ibu kota menuju panggung kayu ekologis di jantung pepohonan kompleks kementerian', NULL, NULL, 'Setiap Hari'),
(13, 'Giyanti Coffee Roastery', 'Jl. Surabaya No. 20, Menteng', 'Kedai seni penyeduh ortodoks di gang antik, merawat murninya ekstraksi biji lokal melalui presisi mesin kopi tingkat elit.', 'Jakarta Pusat', 'DKI Jakarta', 'KRL Cikini, TransJakarta', 'https://manual.co.id/wp-content/uploads/2013/12/Giyanti_4.jpg', 62500.00, 'Kafe & Dessert', '09:30:00', '18:30:00', -6.19872800, 106.84013700, 'Kopi Biji Sangrai Spesial', 'Terkenal', 'murah', 'parkir, toilet, Wi-Fi, area merokok, pembayaran non tunai, Takeaway, mushola, pembayaran tunai', 'kafe dessert kopi biji sangrai spesial terkenal murah jakarta pusat dki jakarta krl cikini transjakarta parkir toilet wi fi area merokok pembayaran non tunai takeaway mushola pembayaran tunai kedai seni penyeduh ortodoks di gang antik merawat murninya ekstraksi biji lokal melalui presisi mesin kopi tingkat elit', NULL, NULL, 'Sel-Min'),
(14, 'Sudut Timur Coffee and Eatery', 'Jl. Eeretan II No. 101, Kramat Jati', 'Jawaban wilayah timur akan estetika kanopi matahari terbenam dengan sajian kudapan kelas menengah yang akomodatif.', 'Jakarta Timur', 'DKI Jakarta', 'Kendaraan Pribadi, TransJakarta', 'https://tse1.mm.bing.net/th/id/OIP.TrI76c4ybS4e7pm4k-ZHjQHaFj?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 42500.00, 'Kafe & Dessert', '12:00:00', '21:00:00', -6.27050600, 106.85689400, 'Kafe Santai Semi-Terbuka', 'Terkenal', 'murah', 'parkir, toilet, Wi-Fi, area merokok, pembayaran non tunai, Takeaway, mushola, pembayaran tunai', 'kafe dessert santai semi terbuka terkenal murah jakarta timur dki jakarta kendaraan pribadi transjakarta parkir toilet wi fi area merokok pembayaran non tunai takeaway mushola pembayaran tunai jawaban wilayah timur akan estetika kanopi matahari terbenam dengan sajian kudapan kelas menengah yang akomodatif', NULL, NULL, 'Setiap Hari'),
(15, 'Li Lian', 'Park Hyatt Jakarta, Level 19', 'Panggung mahakarya bebek Peking beraroma asap rambutan dalam kemewahan cakrawala puncak pencakar langit.', 'Jakarta Pusat', 'DKI Jakarta', 'KRL Gondangdia, Kendaraan Pribadi', 'https://tse4.mm.bing.net/th/id/OIP.mVhyONYSqocDVFb13mOUUgHaE8?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 75000.00, 'Asia', '11:30:00', '22:00:00', -6.18488700, 106.83163900, 'Tiongkok Kontemporer', 'Hidden Gem', 'murah', 'parkir, toilet, Wi-Fi, area merokok, pembayaran non tunai, Takeaway, mushola, pembayaran tunai', 'asia tiongkok kontemporer hidden gem murah jakarta pusat dki jakarta krl gondangdia kendaraan pribadi parkir toilet wi fi area merokok pembayaran non tunai takeaway mushola pembayaran tunai panggung mahakarya bebek peking beraroma asap rambutan dalam kemewahan cakrawala puncak pencakar langit', NULL, NULL, 'Setiap Hari'),
(16, 'OSMO', 'Melawai, Kebayoran Baru', 'Bunker mixologi eksperimental yang melebur tradisi distilasi alkohol luar dengan tanaman obat keanekaragaman hayati Indonesia.', 'Jakarta Selatan', 'DKI Jakarta', 'MRT Blok M', 'https://whatsnewindonesia.com/sites/default/files/2025-10/OSMO%20Bar.webp', 160000.00, 'Fusion', '16:00:00', '01:00:00', -6.24190200, 106.80320200, 'Bar Koktail / Wiski', 'Hidden Gem', 'sedang', 'parkir, toilet, Wi-Fi, area merokok, pembayaran non tunai, Takeaway, mushola, pembayaran tunai', 'fusion bar koktail wiski hidden gem sedang jakarta selatan dki jakarta mrt blok m parkir toilet wi fi area merokok pembayaran non tunai takeaway mushola pembayaran tunai bunker mixologi eksperimental yang melebur tradisi distilasi alkohol luar dengan tanaman obat keanekaragaman hayati indonesia', NULL, NULL, 'Setiap Hari'),
(17, 'DoubleTree', 'DoubleTree by Hilton, Kemayoran', 'Ekosistem dapur komunal skala raksasa yang mengakomodasi dari antrean uap dim sum segar hingga kuah kental kaldu iga.', 'Jakarta Pusat', 'DKI Jakarta', 'Kendaraan Pribadi, TransJakarta', 'https://tse2.mm.bing.net/th/id/OIP.Oo9KSvQ2u-HKFtKBdIC94gHaFj?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 250000.00, 'Western', '06:00:00', '22:00:00', -6.14103700, 106.85383100, 'Prasmanan Internasional', 'Terkenal', 'sedang', 'parkir, toilet, Wi-Fi, area merokok, pembayaran non tunai, Takeaway, mushola, pembayaran tunai', 'western prasmanan internasional terkenal sedang jakarta pusat dki jakarta kendaraan pribadi transjakarta parkir toilet wi fi area merokok pembayaran non tunai takeaway mushola pembayaran tunai ekosistem dapur komunal skala raksasa yang mengakomodasi dari antrean uap dim sum segar hingga kuah kental kaldu iga', NULL, NULL, 'Setiap Hari'),
(18, 'SICILE', 'Senopati', 'Benteng pelayuan sapi pesisir Italia, mengekstraksi rasa umami daging dari pembakaran kayu pohon apel dan herba darat.', 'Jakarta Selatan', 'DKI Jakarta', 'MRT Senayan, Kendaraan Pribadi', 'https://tse3.explicit.bing.net/th/id/OIP.iNk7HTmTtSUWAXfE4Ygx1wHaFb?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 147500.00, 'Western', '11:00:00', '23:00:00', -6.23271500, 106.81206300, 'Steakhouse / Mediterania', 'Hidden Gem', 'sedang', 'parkir, toilet, Wi-Fi, area merokok, pembayaran non tunai, Takeaway, mushola, pembayaran tunai', 'western steakhouse mediterania hidden gem sedang jakarta selatan dki jakarta mrt senayan kendaraan pribadi parkir toilet wi fi area merokok pembayaran non tunai takeaway mushola pembayaran tunai benteng pelayuan sapi pesisir italia mengekstraksi rasa umami daging dari pembakaran kayu pohon apel dan herba darat', NULL, NULL, 'Setiap Hari'),
(19, 'TAMU', 'Melawai, Kebayoran Baru', 'Penghormatan pada bumbu purbakala ibu pertiwi yang direkonstruksi ulang menggunakan perlengkapan laboratorium dapur masa depan.', 'Jakarta Selatan', 'DKI Jakarta', 'MRT Blok M', 'https://i0.wp.com/madhang.com/wp-content/uploads/TAMU-Cafe-BSD.jpg?w=1440&ssl=1', 75000.00, 'Fusion', '11:00:00', '22:00:00', -6.24170800, 106.80350200, 'Kuliner Warisan Fusi', 'Hidden Gem', 'murah', 'parkir, toilet, Wi-Fi, area merokok, pembayaran non tunai, Takeaway, mushola, pembayaran tunai', 'fusion kuliner warisan fusi hidden gem murah jakarta selatan dki jakarta mrt blok m parkir toilet wi fi area merokok pembayaran non tunai takeaway mushola pembayaran tunai penghormatan pada bumbu purbakala ibu pertiwi yang direkonstruksi ulang menggunakan perlengkapan laboratorium dapur masa depan', NULL, NULL, 'Setiap Hari'),
(20, 'Kindling', 'Jakarta Pusat', 'Relik sejarah batu bata tahun 1900-an yang menjadi pelindung bagi piring-piring eksotik pengawinan rempah timur dan barat.', 'Jakarta Pusat', 'DKI Jakarta', 'Kendaraan Pribadi', 'https://tse1.mm.bing.net/th/id/OIP.iIhj4wqOFeK2IcQoslj9ZwHaHa?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 142500.00, 'Fusion', '11:00:00', '22:00:00', -6.18918800, 106.83791500, 'Fusi Asia-Prancis', 'Hidden Gem', 'sedang', 'parkir, toilet, Wi-Fi, area merokok, pembayaran non tunai, Takeaway, mushola, pembayaran tunai', 'fusion fusi asia prancis hidden gem sedang jakarta pusat dki jakarta kendaraan pribadi parkir toilet wi fi area merokok pembayaran non tunai takeaway mushola pembayaran tunai relik sejarah batu bata tahun 1900 an yang menjadi pelindung bagi piring piring eksotik pengawinan rempah timur dan barat', NULL, NULL, 'Setiap Hari'),
(21, 'NIKUZEN Jakarta', 'Mori Tower', 'Suaka esoterik penganut kultus kesempurnaan daging sapi Jepang, dilayani langsung oleh koreografi sentuhan ahli koki sushi.', 'Jakarta Selatan', 'DKI Jakarta', 'Kendaraan Pribadi', 'https://tse3.mm.bing.net/th/id/OIP.dv2JmEP9ZHnW1HkfhObaTQHaFj?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 215000.00, 'Asia', '12:00:00', '22:00:00', -6.21635100, 106.81562400, 'Jepang / Omakase', 'Hidden Gem', 'sedang', 'parkir, toilet, Wi-Fi, area merokok, pembayaran non tunai, Takeaway, mushola, pembayaran tuna', 'asia jepang omakase hidden gem sedang jakarta selatan dki jakarta kendaraan pribadi parkir toilet wi fi area merokok pembayaran non tunai takeaway mushola pembayaran tuna suaka esoterik penganut kultus kesempurnaan daging sapi jepang dilayani langsung oleh koreografi sentuhan ahli koki sushi', NULL, NULL, 'Setiap Hari'),
(22, 'Emilia Bar Italiano', 'SCBD', 'Bilik pelepasan stres psikologis elit pekerja SCBD yang mendewa-dewakan ragi piza segar dan kelenturan pasta olahan tangan.', 'Jakarta Selatan', 'DKI Jakarta', 'MRT Senayan, TransJakarta', 'https://manual.co.id/wp-content/uploads/2024/09/18_Emilia-321_Website-980x719.jpg', 122500.00, 'Western', '11:00:00', '23:30:00', -6.23031500, 106.80992300, 'Bar Piza & Pasta Artisan', 'Terkenal', 'sedang', 'parkir, toilet, Wi-Fi, area merokok, pembayaran non tunai, Takeaway, mushola, pembayaran tunai', 'western bar piza pasta artisan terkenal sedang jakarta selatan dki jakarta mrt senayan transjakarta parkir toilet wi fi area merokok pembayaran non tunai takeaway mushola pembayaran tunai bilik pelepasan stres psikologis elit pekerja scbd yang mendewa dewakan ragi piza segar dan kelenturan pasta olahan tangan', NULL, NULL, 'Setiap Hari'),
(23, 'Petak Enam di Tjandra', 'Jl. Pancoran No.43, Glodok', 'Konsolidasi ruang arsitektural Tiongkok modern raksasa yang merawat ratusan pedagang gerobakan masa lalu dalam sistem modern.', 'Jakarta Barat', 'DKI Jakarta', 'TransJakarta Glodok', 'https://manual.co.id/wp-content/uploads/2021/02/Manual-Petak-Enam-10-980x719.jpg', 37500.00, 'Nusantara', '09:30:00', '21:00:00', -6.14151400, 106.81281200, 'Jajanan Nusantara & Tionghoa', 'Terkenal', 'murah', 'parkir, toilet, Wi-Fi, area merokok, pembayaran non tunai, Takeaway, mushola, pembayaran tunai', 'nusantara jajanan tionghoa terkenal murah jakarta barat dki jakarta transjakarta glodok parkir toilet wi fi area merokok pembayaran non tunai takeaway mushola pembayaran tunai konsolidasi ruang arsitektural tiongkok modern raksasa yang merawat ratusan pedagang gerobakan masa lalu dalam sistem modern', NULL, NULL, 'Setiap Hari'),
(24, 'Gultik (Gulai Tikungan)', 'Kawasan Bulungan, Blok M', 'Garis perbatasan sosial ibu kota sirna di atas piring gulai sapi kaki lima yang telah membentengi trotoar Blok M selama puluhan tahun.', 'Jakarta Selatan', 'DKI Jakarta', 'MRT Blok M, TransJakarta Blok M', 'https://tse2.mm.bing.net/th/id/OIP.YTvVoRwAitQ91Cc2uDoGJQHaEi?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 17500.00, 'Nusantara', '17:00:00', '03:00:00', -6.24281100, 106.79635700, 'Gulai Jalanan', 'Ikonik', 'murah', 'parkir, toilet, Wi-Fi, area merokok, pembayaran non tunai, Takeaway, mushola, pembayaran tunai', 'nusantara gulai jalanan ikonik murah jakarta selatan dki jakarta mrt blok m transjakarta blok m parkir toilet wi fi area merokok pembayaran non tunai takeaway mushola pembayaran tunai garis perbatasan sosial ibu kota sirna di atas piring gulai sapi kaki lima yang telah membentengi trotoar blok m selama puluhan tahun', NULL, NULL, 'Setiap Hari'),
(25, 'Roti Bakar Eddy', 'Jl. Raden Patah No.11, Selong', 'Posko utama bagi burung hantu ibu kota, meleburkan batasan umur di atas meja baja berhiaskan tumpukan roti terpanggang mentega dan piring nasi gila.', 'Jakarta Selatan', 'DKI Jakarta', 'Kendaraan Pribadi, MRT Asean', 'https://salsawisata.com/wp-content/uploads/2021/09/Roti-Bakar-Eddy.jpg', 25000.00, 'Nusantara', '17:00:00', '02:00:00', -6.29290200, 106.92917700, 'Kudapan Nokturnal', 'Ikonik', 'murah', 'parkir, toilet, Wi-Fi, area merokok, pembayaran non tunai, Takeaway, mushola, pembayaran tunai', 'nusantara kudapan nokturnal ikonik murah jakarta selatan dki jakarta kendaraan pribadi mrt asean parkir toilet wi fi area merokok pembayaran non tunai takeaway mushola pembayaran tunai posko utama bagi burung hantu ibu kota meleburkan batasan umur di atas meja baja berhiaskan tumpukan roti terpanggang mentega dan piring nasi gila', NULL, NULL, 'Setiap Hari'),
(26, 'Sate H. Romli & Taichan', 'Seputar Blok M Square', 'Dua dinasti tusuk sate bertarung: hegemoni bumbu kacang halus turun-temurun berhadapan dengan agresi radikal asam-pedas daging telanjang ala Taichan.', 'Jakarta Selatan', 'DKI Jakarta', 'MRT Blok M', 'https://tse1.mm.bing.net/th/id/OIP.kuxPrHZYnUQAawDf4Z-XuQHaEK?r=0&o=7rm=3&rs=1&pid=ImgDetMain&o=7&rm=3', 30000.00, 'Nusantara', '18:00:00', '01:00:00', -6.22653200, 106.78762400, 'Sate Daging Asap', 'Terkenal', 'murah', 'parkir, toilet, Wi-Fi, area merokok, pembayaran non tunai, Takeaway, mushola, pembayaran tunai', 'nusantara sate daging asap terkenal murah jakarta selatan dki jakarta mrt blok m parkir toilet wi fi area merokok pembayaran non tunai takeaway mushola pembayaran tunai dua dinasti tusuk sate bertarung hegemoni bumbu kacang halus turun temurun berhadapan dengan agresi radikal asam pedas daging telanjang ala taichan', NULL, NULL, 'Setiap Hari'),
(27, 'Scarlett House', 'Jakarta Selatan', 'Studio tata ruang bergaya rumah liburan Eropa yang sukses mengubah etalase kue-kue artisanal menjadi komoditas wajib tangkapan lensa media sosial.', 'Jakarta Selatan', 'DKI Jakarta', 'Kendaraan Pribadi, MRT', 'https://tse4.mm.bing.net/th/id/OIP.lN1EekoAItff8G4hi5JZZAHaJQ?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 67500.00, 'Kafe & Dessert', '10:00:00', '20:00:00', -6.24285900, 106.80129300, 'Kafe Estetik Klasik', 'Terkenal', 'murah', 'parkir, toilet, Wi-Fi, area merokok, pembayaran non tunai, Takeaway, mushola, pembayaran tunai', 'kafe dessert estetik klasik terkenal murah jakarta selatan dki jakarta kendaraan pribadi mrt parkir toilet wi fi area merokok pembayaran non tunai takeaway mushola pembayaran tunai studio tata ruang bergaya rumah liburan eropa yang sukses mengubah etalase kue kue artisanal menjadi komoditas wajib tangkapan lensa media sosial', NULL, NULL, 'Setiap Hari'),
(28, 'MulaMula Café', 'Jakarta Selatan', 'Suaka kemudi mikro yang menyisipkan dosis kafein dosis tinggi di antara keruwetan sirkulasi metropolitan bagi pekerja lepas nomad.', 'Jakarta Selatan', 'DKI Jakarta', 'KRL, Kendaraan Pribadi', 'https://tse2.mm.bing.net/th/id/OIP.d6sFzy2ibKBd6ym5CwHyGwHaEG?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 40000.00, 'Kafe & Dessert', '09:00:00', '21:00:00', -6.20016700, 106.84380600, 'Kedai Kopi Minimalis', 'Hidden Gem', 'murah', 'parkir, toilet, Wi-Fi, area merokok, pembayaran non tunai, Takeaway, mushola, pembayaran tunai', 'kafe dessert kedai kopi minimalis hidden gem murah jakarta selatan dki jakarta krl kendaraan pribadi parkir toilet wi fi area merokok pembayaran non tunai takeaway mushola pembayaran tunai suaka kemudi mikro yang menyisipkan dosis kafein dosis tinggi di antara keruwetan sirkulasi metropolitan bagi pekerja lepas nomad', NULL, NULL, 'Setiap Hari'),
(29, 'BawBaw', 'Jakarta Barat', 'Konstruksi papan kayu bersih penganut keheningan arsitektur Zen, memanjakan lidah lewat letupan udon lava dan potongan rapi biota laut mentah.', 'Jakarta Barat', 'DKI Jakarta', 'Kendaraan Pribadi', 'https://www.instagram.com/p/CSs9rA8HxiK/', 60000.00, 'Asia', '11:00:00', '22:00:00', -6.17229300, 106.90443200, 'Jepang Minimalis', 'Hidden Gem', 'murah', 'parkir, toilet, Wi-Fi, area merokok, pembayaran non tunai, Takeaway, mushola, pembayaran tunai', 'asia jepang minimalis hidden gem murah jakarta barat dki jakarta kendaraan pribadi parkir toilet wi fi area merokok pembayaran non tunai takeaway mushola pembayaran tunai konstruksi papan kayu bersih penganut keheningan arsitektur zen memanjakan lidah lewat letupan udon lava dan potongan rapi biota laut mentah', NULL, NULL, 'Setiap Hari'),
(30, 'Lara Djonggrang', 'Jl. Teuku Cik Ditiro No.4, Menteng', 'Ekspedisi arkeologis lidah menembus zaman Majapahit, mengekstrak arsip resep luhur yang disajikan dalam altar patung dewa dewi purba nan mistis.', 'Jakarta Pusat', 'DKI Jakarta', 'KRL Cikini, TransJakarta', 'https://tse4.mm.bing.net/th/id/OIP.iQNXSFDLrGsejUJW5uP_7wHaE8?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 190000.00, 'Nusantara', '11:00:00', '23:00:00', -6.19391700, 106.83716500, 'Masakan Kerajaan Nusantara', 'Ikonik', 'sedang', 'parkir, toilet, Wi-Fi, area merokok, pembayaran non tunai, Takeaway, mushola, pembayaran tunai', 'nusantara masakan kerajaan ikonik sedang jakarta pusat dki jakarta krl cikini transjakarta parkir toilet wi fi area merokok pembayaran non tunai takeaway mushola pembayaran tunai ekspedisi arkeologis lidah menembus zaman majapahit mengekstrak arsip resep luhur yang disajikan dalam altar patung dewa dewi purba nan mistis', NULL, NULL, 'Setiap Hari'),
(31, 'Coca Suki Restaurant', 'Jakarta Barat', 'Pusat komando acara akhir pekan keluarga lintas generasi yang sangat rewel terhadap standar kebersihan sayuran hijau dan kualitas kaldu celup.', 'Jakarta Barat', 'DKI Jakarta', 'TransJakarta, Kendaraan Pribadi', 'https://tse4.mm.bing.net/th/id/OIP._WFq7ELfjXdzs55wqGmtFAHaEj?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 150000.00, 'Asia', '11:00:00', '21:30:00', -6.19046600, 106.76147500, 'Rebusan Kaldu Jepang', 'Terkenal', 'sedang', 'parkir, toilet, Wi-Fi, area merokok, pembayaran non tunai, Takeaway, mushola, pembayaran tunai', 'asia rebusan kaldu jepang terkenal sedang jakarta barat dki jakarta transjakarta kendaraan pribadi parkir toilet wi fi area merokok pembayaran non tunai takeaway mushola pembayaran tunai pusat komando acara akhir pekan keluarga lintas generasi yang sangat rewel terhadap standar kebersihan sayuran hijau dan kualitas kaldu celup', NULL, NULL, 'Setiap Hari'),
(32, 'House of Etera', 'Jl. Tebet Raya No. 73, Tebet', 'Mensinergikan ritme kehidupan komersial kafe kekinian dengan infrastruktur ibadah paripurna dan tusukan ayam saus bunga kecombrang.', 'Jakarta Selatan', 'DKI Jakarta', 'KRL Tebet, TransJakarta', 'https://temanhealing.id/wp-content/uploads/2023/02/TemanHealing-Review-Etera-Tebet_Post_05.jpg', 52500.00, 'Fusion', '10:00:00', '22:00:00', -6.22738800, 106.85657400, 'Kafe Fusi Kecombrang', 'Hidden Gem', 'murah', 'parkir, toilet, Wi-Fi, area merokok, pembayaran non tunai, Takeaway, mushola, pembayaran tunai', 'fusion kafe fusi kecombrang hidden gem murah jakarta selatan dki jakarta krl tebet transjakarta parkir toilet wi fi area merokok pembayaran non tunai takeaway mushola pembayaran tunai mensinergikan ritme kehidupan komersial kafe kekinian dengan infrastruktur ibadah paripurna dan tusukan ayam saus bunga kecombrang', NULL, NULL, 'Setiap Hari'),
(33, 'Toodz House', 'Jl. Cipete Raya No. 79, Fatmawati', 'Mendemonstrasikan bahwa manipulasi genetik hidangan luar ke dalam kearifan loka—nasi panas bertabur saus kental carbonara—adalah takdir yang tak terhindarkan.', 'Jakarta Selatan', 'DKI Jakarta', 'MRT Cipete Raya', 'https://tse4.mm.bing.net/th/id/OIP.WWGYF_BCg9ZlRbd4rOKs6QHaEK?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 52500.00, 'Fusion', '09:00:00', '22:00:00', -6.27772500, 106.80195900, 'Eksperimen Makanan Rumahan', 'Terkenal', 'murah', 'parkir, toilet, Wi-Fi, area merokok, pembayaran non tunai, Takeaway, mushola, pembayaran tunai', 'fusion eksperimen makanan rumahan terkenal murah jakarta selatan dki jakarta mrt cipete raya parkir toilet wi fi area merokok pembayaran non tunai takeaway mushola pembayaran tunai mendemonstrasikan bahwa manipulasi genetik hidangan luar ke dalam kearifan loka nasi panas bertabur saus kental carbonara adalah takdir yang tak terhindarkan', NULL, NULL, 'Setiap Hari'),
(34, 'Kopi Es Tak Kie', 'Jl. Pintu Besar Selatan 3 No. 4-6, Glodok', 'Relik sakti penjaga murninya perpaduan robusta arabika yang memboikot keras masuknya mesin pencampur modern sejak pendiriannya tahun 1927.', 'Jakarta Barat', 'DKI Jakarta', 'TransJakarta Glodok, KRL Jakarta Kota', 'https://tse4.mm.bing.net/th/id/OIP.GfbpkMDdvemjit7WGlB2mAHaE8?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 35000.00, 'Kafe & Dessert', '07:00:00', '14:00:00', -6.14083800, 106.81374900, 'Kopi Ciptaan Historis', 'Ikonik', 'murah', 'parkir, toilet, Wi-Fi, area merokok, pembayaran non tunai, Takeaway, mushola, pembayaran tunai', 'kafe dessert kopi ciptaan historis ikonik murah jakarta barat dki jakarta transjakarta glodok krl jakarta kota parkir toilet wi fi area merokok pembayaran non tunai takeaway mushola pembayaran tunai relik sakti penjaga murninya perpaduan robusta arabika yang memboikot keras masuknya mesin pencampur modern sejak pendiriannya tahun 1927', NULL, NULL, 'Setiap Hari'),
(35, 'Batavia Cafe (Cafe Batavia)', 'Taman Fatahillah, Kota Tua', 'Katedral waktu yang menjebak pengunjung dalam eforia kolonial, menyajikan gado-gado saus tebal yang diiringi tiupan trompet panggung irama jazz.', 'Jakarta Barat', 'DKI Jakarta', 'KRL Jakarta Kota, TransJakarta', 'https://tse4.mm.bing.net/th/id/OIP.RrF7o3NDEG76Xs6fDX4v8AHaEK?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 100000.00, 'Fusion', '08:00:00', '23:00:00', -6.13421900, 106.81266800, 'Restoran Museum Hidup', 'Ikonik', 'murah', 'parkir, toilet, Wi-Fi, area merokok, pembayaran non tunai, Takeaway, mushola, pembayaran tunai', 'fusion restoran museum hidup ikonik murah jakarta barat dki jakarta krl jakarta kota transjakarta parkir toilet wi fi area merokok pembayaran non tunai takeaway mushola pembayaran tunai katedral waktu yang menjebak pengunjung dalam eforia kolonial menyajikan gado gado saus tebal yang diiringi tiupan trompet panggung irama jazz', NULL, NULL, 'Setiap Hari'),
(36, 'Asinan Kamboja H. Mansyur', 'Jl. Taman Kamboja No.10, Rawamangun', 'Sindikat pertahanan budaya Betawi radikal yang menolak ekspansi waralaba, meracik cairan bumbu kacang pemicu hiper-liur penikmat kol dan kerupuk mi merah.', 'Jakarta Timur', 'DKI Jakarta', 'TransJakarta Rawamangun, LRT Velodrome', 'https://asset-2.tstatic.net/wartakota/foto/bank/images/asinan-legendaris-kamboja-alm-h-mansyur-rawamangun-pulogadung-jakarta-timur.jpg', 27500.00, 'Nusantara', '10:00:00', '21:00:00', -6.20078600, 106.88562000, 'Mahakarya Asinan Betawi', 'Ikonik', 'murah', 'parkir, pembayaran non tunai, Takeaway, pembayaran tunai', 'nusantara mahakarya asinan betawi ikonik murah jakarta timur dki jakarta transjakarta rawamangun lrt velodrome parkir pembayaran non tunai takeaway pembayaran tunai sindikat pertahanan budaya betawi radikal yang menolak ekspansi waralaba meracik cairan bumbu kacang pemicu hiper liur penikmat kol dan kerupuk mi merah', NULL, NULL, 'Setiap Hari'),
(37, 'Hause Rooftop', 'MD Place Tower 2 Lt. 6, Setiabudi', 'Proyek terasering kanopi urban yang mencampurkan sayur petik dari kebun sendiri ke dalam tumpukan piza pinggiran renyah di bawah siraman polusi cahaya ibukota.', 'Jakarta Selatan', 'DKI Jakarta', 'LRT Rasuna Said, TransJakarta', 'https://salsawisata.com/wp-content/uploads/2023/08/Hause-Rooftop-jakarta.jpg', 140000.00, 'Fusion', '09:00:00', '23:00:00', -6.20745000, 106.82812400, 'Perkebunan Atap Gedung', 'Terkenal', 'sedang', 'parkir, toilet, Wi-Fi, area merokok, ruang keluarga, pembayaran non tunai, live music, Takeaway, reservasi, mushola, pembayaran tunai', 'fusion perkebunan atap gedung terkenal sedang jakarta selatan dki jakarta lrt rasuna said transjakarta parkir toilet wi fi area merokok ruang keluarga pembayaran non tunai live music takeaway reservasi mushola pembayaran tunai proyek terasering kanopi urban yang mencampurkan sayur petik dari kebun sendiri ke dalam tumpukan piza pinggiran renyah di bawah siraman polusi cahaya ibukota', NULL, NULL, 'Setiap Hari'),
(38, 'Jetski Café', 'Jl. Raya Pantai Mutiara No. 57, Pluit', 'Suaka evakuasi warga sipil pesisir yang menjahit batas laut dengan deru mesin pacu air konglomerat, dibalut saus ikan gurih tiram dan uap laut pekat.', 'Jakarta Utara', 'DKI Jakarta', 'TransJakarta, Kendaraan Pribadi', 'https://tse2.mm.bing.net/th/id/OIP.sLmGEKOoeJ1-2CYsxaVJvwHaE4?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 145000.00, 'Fusion', '16:00:00', '23:30:00', -6.10320400, 106.79035900, 'Resort Pesisir Utara', 'Terkenal', 'sedang', 'parkir, toilet, Wi-Fi, area merokok, ruang keluarga, pembayaran non tunai, live music, Takeaway, reservasi, mushola, pembayaran tunai', 'fusion resort pesisir utara terkenal sedang jakarta utara dki jakarta transjakarta kendaraan pribadi parkir toilet wi fi area merokok ruang keluarga pembayaran non tunai live music takeaway reservasi mushola pembayaran tunai suaka evakuasi warga sipil pesisir yang menjahit batas laut dengan deru mesin pacu air konglomerat dibalut saus ikan gurih tiram dan uap laut pekat', NULL, NULL, 'Setiap Hari'),
(39, 'Sate Padang H. Ajo Manih', 'Kawasan Rawamangun Muka', 'Panglima besar peracikan lidah sapi berkuah rempah Sumatera, mendikte standarisasi usus dan ketupat bagi barisan puluhan kendaraan penikmat pedas di timur kota.', 'Jakarta Timur', 'DKI Jakarta', 'LRT Velodrome, TransJakarta', 'https://tse4.mm.bing.net/th/id/OIP.7TSQC6FzYeaCKDF-A1AJgwHaGb?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 40000.00, 'Nusantara', '15:30:00', '23:00:00', -6.21810300, 106.87610700, 'Sate Padang Pariaman', 'Ikonik', 'murah', 'parkir, area merokok, pembayaran non tunai, Takeaway, pembayaran tunai', 'nusantara sate padang pariaman ikonik murah jakarta timur dki jakarta lrt velodrome transjakarta parkir area merokok pembayaran non tunai takeaway pembayaran tunai panglima besar peracikan lidah sapi berkuah rempah sumatera mendikte standarisasi usus dan ketupat bagi barisan puluhan kendaraan penikmat pedas di timur kota', NULL, NULL, 'Setiap Hari'),
(40, 'Claypot Popo Sabang', 'Jl. Sabang', 'Eksekutor maut penawar dinginnya musim hujan, melempar telur ayam setengah matang dan sapi cincang ke dasar tungku tanah liat pembakar tenggorokan yang presisi.', 'Jakarta Pusat', 'DKI Jakarta', 'KRL Gondangdia, TransJakarta', 'https://tse4.mm.bing.net/th/id/OIP.04Nh3lPm8u56Q7j0vYUPRAHaE8?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 37500.00, 'Asia', '11:30:00', '21:00:00', -6.18368600, 106.82497100, 'Rebusan Periuk Panas', 'Terkenal', 'murah', 'parkir, toilet, area merokok, pembayaran non tunai, Takeaway, pembayaran tunai', 'asia rebusan periuk panas terkenal murah jakarta pusat dki jakarta krl gondangdia transjakarta parkir toilet area merokok pembayaran non tunai takeaway pembayaran tunai eksekutor maut penawar dinginnya musim hujan melempar telur ayam setengah matang dan sapi cincang ke dasar tungku tanah liat pembakar tenggorokan yang presisi', NULL, NULL, 'Setiap Hari'),
(41, 'Kopi Canant', 'Jl. Braga No. 10', 'Suasana industrial modern yang luas dan nyaman di tengah kawasan bersejarah. Menu andalan berupa racikan kopi artisan dan camilan ringan. Keunikannya terletak pada ketersediaan ruang terbuka yang lega, menjadikannya oase relaksasi yang kontras dengan kepadatan di sepanjang jalan utama Braga.', 'Bandung', 'Jawa Barat', 'Mobil, Motor, Jalan Kaki', 'https://tse3.mm.bing.net/th/id/OIP.2E4WBiE3vL6oy_nOCa6guAAAAA?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 32500.00, 'Kafe & Dessert', '11:00:00', '22:00:00', -6.91685400, 107.60907100, 'Kopi, Pastry, Western Ringan', 'Hidden Gem', 'murah', 'Indoor, Outdoor, Semi Outdoor, WiFi, Toilet', 'kafe dessert kopi pastry western ringan hidden gem murah bandung jawa barat mobil motor jalan kaki indoor outdoor semi outdoor wifi toilet suasana industrial modern yang luas dan nyaman di tengah kawasan bersejarah menu andalan berupa racikan kopi artisan dan camilan ringan keunikannya terletak pada ketersediaan ruang terbuka yang lega menjadikannya oase relaksasi yang kontras dengan kepadatan di sepanjang jalan utama braga', NULL, NULL, 'Setiap Hari'),
(42, 'Dumuk Bareto', 'Jl. Sukawangi 2', 'Mengusung konsep otentik pedesaan Sunda dengan material dominan kayu dan bambu. Suasananya tenang, mengingatkan pada kampung halaman. Menu andalan adalah sistem prasmanan dengan aneka lauk tradisional dan lalapan segar. Keunikannya adalah atmosfer tradisional yang sangat kuat meski berlokasi tidak jauh dari pusat kota.', 'Bandung', 'Jawa Barat', 'Mobil, Motor', 'https://tse1.mm.bing.net/th/id/OIP.ykrygVVUL-lb_ht_GaYIbQHaEI?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 40000.00, 'Nusantara', '11:00:00', '22:00:00', -6.87761700, 107.59780400, 'Masakan Khas Sunda', 'Terkenal', 'murah', 'Prasmanan, Area Lesehan, Parkir, Toilet, Mushola', 'nusantara masakan khas sunda terkenal murah bandung jawa barat mobil motor prasmanan area lesehan parkir toilet mushola mengusung konsep otentik pedesaan sunda dengan material dominan kayu dan bambu suasananya tenang mengingatkan pada kampung halaman menu andalan adalah sistem prasmanan dengan aneka lauk tradisional dan lalapan segar keunikannya adalah atmosfer tradisional yang sangat kuat meski berlokasi tidak jauh dari pusat kota', NULL, NULL, 'Setiap Hari'),
(43, 'San Gimignano', 'Jl. LLRE Martadinata 89', 'Restoran ini memancarkan aura klasik arsitektur Tuscany, Italia, yang sangat elegan dan romantis. Menu andalan mencakup pasta buatan tangan (handmade), hidangan penutup Italia, dan gelato premium. Keunikannya adalah desain interior yang sangat imersif, membawa pengunjung seakan-akan sedang bersantap di benua Eropa.', 'Bandung', 'Jawa Barat', 'Mobil, Motor', 'https://www.instagram.com/p/DA5YwfbSfqS/?img_index=1', 52500.00, 'Western', '11:00:00', '22:00:00', -6.90532500, 107.61958300, 'Italia, Pasta, Gelato, Western', 'Terkenal', 'murah', 'AC, WiFi Cepat, Parkir Valet, Toilet Mewah', 'western italia pasta gelato terkenal murah bandung jawa barat mobil motor ac wifi cepat parkir valet toilet mewah restoran ini memancarkan aura klasik arsitektur tuscany italia yang sangat elegan dan romantis menu andalan mencakup pasta buatan tangan handmade hidangan penutup italia dan gelato premium keunikannya adalah desain interior yang sangat imersif membawa pengunjung seakan akan sedang bersantap di benua eropa', NULL, NULL, 'Setiap Hari'),
(44, 'Tempayan Bistro', 'Jl. Citarum No. 10', 'Suasana bistro bergaya kontemporer yang diwarnai dengan pencahayaan hangat, sangat ideal untuk pertemuan keluarga lintas generasi. Menu andalan berupa variasi nasi nusantara dan lauk pauk lokal yang dikemas secara modern. Keunikannya terletak pada kemampuannya menyajikan masakan daerah dengan standar pelayanan bistro kelas atas.', 'Bandung', 'Jawa Barat', 'Mobil, Motor', 'https://tse3.mm.bing.net/th/id/OIP.CnELUPXATA_UpkzNEWkDGgHaE7?r=0&o=7rm=3&rs=1&pid=ImgDetMain&o=7&rm=3', 65000.00, 'Fusion', '11:00:00', '22:00:00', -6.90513400, 107.62222400, 'Fusi Indonesia, Asia', 'Terkenal', 'murah', 'Area Keluarga, Parkir Luas, WiFi, Toilet Bersih', 'fusion fusi indonesia asia terkenal murah bandung jawa barat mobil motor area keluarga parkir luas wifi toilet bersih suasana bistro bergaya kontemporer yang diwarnai dengan pencahayaan hangat sangat ideal untuk pertemuan keluarga lintas generasi menu andalan berupa variasi nasi nusantara dan lauk pauk lokal yang dikemas secara modern keunikannya terletak pada kemampuannya menyajikan masakan daerah dengan standar pelayanan bistro kelas atas', NULL, NULL, 'Setiap Hari'),
(45, 'Cold n Brew', 'Jl. LLRE Martadinata 107', 'Kafe komunal bernuansa monokromatik yang efisien, menjadi titik kumpul favorit bagi pekerja lepas. Menu andalan terdiri dari racikan kopi kreatif kekinian dan sajian waffle renyah. Keunikan utamanya adalah jam operasional yang sangat memanjang hingga larut malam, memfasilitasi kebutuhan demografi yang bekerja tanpa batas waktu.', 'Bandung', 'Jawa Barat', 'Mobil, Motor', 'https://i0.wp.com/madhang.com/wp-content/uploads/Cold-n-Brew-Coffee-Solo.jpg?resize=1440%2C1800&ssl=1', 40000.00, 'Kafe & Dessert', '11:00:00', '22:00:00', -6.90583100, 107.62200800, 'Kopi Kreatif, Waffle, Dessert', 'Terkenal', 'murah', 'Colokan Listrik, WiFi Cepat, Area Merokok', 'kafe dessert kopi kreatif waffle terkenal murah bandung jawa barat mobil motor colokan listrik wifi cepat area merokok kafe komunal bernuansa monokromatik yang efisien menjadi titik kumpul favorit bagi pekerja lepas menu andalan terdiri dari racikan kopi kreatif kekinian dan sajian waffle renyah keunikan utamanya adalah jam operasional yang sangat memanjang hingga larut malam memfasilitasi kebutuhan demografi yang bekerja tanpa batas waktu', NULL, NULL, 'Setiap Hari'),
(46, 'Karnivor Restaurant', 'Jl. LLRE Martadinata 127', 'Atmosfer ruang makan didesain dengan konsep maskulin seperti kabin petualangan di alam liar, dipenuhi furnitur kayu kokoh. Menu andalan sangat berfokus pada berbagai variasi daging bakar (steak dan ribs) dalam porsi raksasa. Keunikannya adalah konsep karnivora absolut dengan ukuran sajian yang melampaui standar restoran pada umumnya.', 'Bandung', 'Jawa Barat', 'Mobil, Motor', 'https://tse1.mm.bing.net/th/id/OIP.C8ogIUaqtJ01R1axf-OvpQHaD4?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 150000.00, 'Western', '11:00:00', '22:00:00', -6.90767000, 107.62410000, 'Steak, Iga Bakar, Western', 'Terkenal', 'sedang', 'Parkir Luas, Area Semi-Outdoor, Toilet, Mushola', 'western steak iga bakar terkenal sedang bandung jawa barat mobil motor parkir luas area semi outdoor toilet mushola atmosfer ruang makan didesain dengan konsep maskulin seperti kabin petualangan di alam liar dipenuhi furnitur kayu kokoh menu andalan sangat berfokus pada berbagai variasi daging bakar steak dan ribs dalam porsi raksasa keunikannya adalah konsep karnivora absolut dengan ukuran sajian yang melampaui standar restoran pada umumnya', NULL, NULL, 'Setiap Hari'),
(47, 'Justus Steak House Dago', 'Jl. Ir. H. Juanda 59', 'Restoran berarsitektur megah dengan standar pelayanan eksklusif yang menyasar kalangan korporat dan keluarga kelas menengah atas. Menu andalan berpusat pada potongan daging steak premium yang telah bersertifikasi halal secara resmi. Keunikannya adalah integrasi fasilitas hotel bintang lima (seperti valet gratis dan ruang rapat) ke dalam format restoran.', 'Bandung', 'Jawa Barat', 'Mobil, Motor', 'https://tse2.mm.bing.net/th/id/OIP.jcDC37MjMt8R-k_U2WotIQHaE7?r=0&o=7rm=3&rs=1&pid=ImgDetMain&o=7&rm=3', 215000.00, 'Western', '11:00:00', '22:00:00', -6.90112100, 107.61238100, 'Steak Halal Premium', 'Terkenal', 'sedang', 'Free Valet, Meeting Room, Mushola, Speed Wi-Fi', 'western steak halal premium terkenal sedang bandung jawa barat mobil motor free valet meeting room mushola speed wi fi restoran berarsitektur megah dengan standar pelayanan eksklusif yang menyasar kalangan korporat dan keluarga kelas menengah atas menu andalan berpusat pada potongan daging steak premium yang telah bersertifikasi halal secara resmi keunikannya adalah integrasi fasilitas hotel bintang lima seperti valet gratis dan ruang rapat ke dalam format restoran', NULL, NULL, 'Setiap Hari'),
(48, 'Warung Nasi Ibu Imas', 'Jl. Balonggede 67', 'Kondisi warung yang selalu sibuk, riuh, dan dipadati pelanggan dari segala lapisan sosial ekonomi masyarakat. Menu andalan yang paling banyak dicari adalah ayam bakar, karedok leunca, dan racikan sambal super pedas. Keunikannya adalah resiliensi bisnis yang luar biasa dan status operasional penuh sepanjang hari (24 jam) tanpa henti.', 'Bandung', 'Jawa Barat', 'Mobil, Motor, Jalan Kaki', 'https://awsimages.detik.net.id/community/media/visual/2024/09/11/warung-nasi-ibu-imas_169.jpeg?w=620', 27500.00, 'Nusantara', '11:00:00', '22:00:00', -6.92555300, 107.60877100, 'Makanan Khas Sunda', 'Ikonik', 'murah', 'Meja Komunal, Lesehan Terbatas, Toilet', 'nusantara makanan khas sunda ikonik murah bandung jawa barat mobil motor jalan kaki meja komunal lesehan terbatas toilet kondisi warung yang selalu sibuk riuh dan dipadati pelanggan dari segala lapisan sosial ekonomi masyarakat menu andalan yang paling banyak dicari adalah ayam bakar karedok leunca dan racikan sambal super pedas keunikannya adalah resiliensi bisnis yang luar biasa dan status operasional penuh sepanjang hari 24 jam tanpa henti', NULL, NULL, 'Setiap Hari'),
(49, 'Batagor Kingsley', 'Jl. Veteran No. 25', 'Ruang makan bergaya restoran keluarga klasik Tionghoa-Indonesia yang luas dan selalu padat oleh wisatawan pemburu oleh-oleh. Menu andalan adalah batagor bumbu kacang yang gurih, bakso kuah, dan mi yamien manis. Keunikannya adalah reputasi legendarisnya yang secara tak tertulis mewajibkan setiap turis luar kota untuk mampir.', 'Bandung', 'Jawa Barat', 'Mobil, Motor', 'https://tse4.mm.bing.net/th/id/OIP.NeeVNhXP4sNgP8t8Nlsg4QHaE8?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 35000.00, 'Nusantara', '11:00:00', '22:00:00', -6.91882200, 107.61549800, 'Batagor, Siomay, Yamien', 'Ikonik', 'murah', 'Etalase Luas, Area Makan Lebar, Toilet, Parkir', 'nusantara batagor siomay yamien ikonik murah bandung jawa barat mobil motor etalase luas area makan lebar toilet parkir ruang makan bergaya restoran keluarga klasik tionghoa indonesia yang luas dan selalu padat oleh wisatawan pemburu oleh oleh menu andalan adalah batagor bumbu kacang yang gurih bakso kuah dan mi yamien manis keunikannya adalah reputasi legendarisnya yang secara tak tertulis mewajibkan setiap turis luar kota untuk mampir', NULL, NULL, 'Kamis - Selasa'),
(50, 'Bubur Ichsan', 'Jl. Banceuy, Braga', 'Berupa lapak sederhana di tepi jalan raya yang menyatu dengan denyut kehidupan pagi hari di kawasan bersejarah, menawarkan kehangatan interaksi khas warga lokal. Menu andalan berupa bubur ayam putih tanpa bumbu kuning dengan topping suwiran ayam, cakwe, dan kedelai melimpah. Keunikannya adalah profil rasa kaldu bening yang murni dan otentik.', 'Bandung', 'Jawa Barat', 'Motor, Jalan Kaki', 'https://asset.kompas.com/crops/q9u7khSzSL2r6lnVT_jVeCgwdHc=/0x44:1000x711/1200x800/data/photo/2024/01/24/65b07ee40b43d.jpg', 22500.00, 'Nusantara', '11:00:00', '22:00:00', -6.91743400, 107.60736200, 'Bubur Ayam Khas Bandung', 'Terkenal', 'murah', 'Makan Di Tempat Terbatas, Bawa Pulang', 'nusantara bubur ayam khas bandung terkenal murah bandung jawa barat motor jalan kaki makan di tempat terbatas bawa pulang berupa lapak sederhana di tepi jalan raya yang menyatu dengan denyut kehidupan pagi hari di kawasan bersejarah menawarkan kehangatan interaksi khas warga lokal menu andalan berupa bubur ayam putih tanpa bumbu kuning dengan topping suwiran ayam cakwe dan kedelai melimpah keunikannya adalah profil rasa kaldu bening yang murni dan otentik', NULL, NULL, 'Setiap Hari'),
(51, 'Pancawarna 5', 'Kota Baru Parahyangan', 'Tata ruang didesain dengan sentuhan artistik yang sangat tinggi, menyatu dengan keteraturan kawasan perumahan elit di pinggiran barat kota. Suasananya tenang dan terang benderang. Menu andalan adalah fusi masakan Nusantara dan hidangan Barat yang menggugah selera, serta kue segar. Keunikannya adalah estetika visual restoran yang dirancang secara detail.', 'Bandung Barat', 'Jawa Barat', 'Mobil, Motor', 'https://www.instagram.com/p/DZFxk7TByUP/', 40000.00, 'Western', '11:00:00', '22:00:00', -6.86797000, 107.46851100, 'Nusantara, Western, Bakery', 'Hidden Gem', 'murah', 'Parkir Besar, AC, Spot Foto Estetik, Toilet Bersih', 'western nusantara bakery hidden gem murah bandung barat jawa barat mobil motor parkir besar ac spot foto estetik toilet bersih tata ruang didesain dengan sentuhan artistik yang sangat tinggi menyatu dengan keteraturan kawasan perumahan elit di pinggiran barat kota suasananya tenang dan terang benderang menu andalan adalah fusi masakan nusantara dan hidangan barat yang menggugah selera serta kue segar keunikannya adalah estetika visual restoran yang dirancang secara detail', NULL, NULL, 'Setiap Hari');
INSERT INTO `kuliner` (`id_kuliner`, `nama`, `alamat`, `deskripsi`, `kota`, `provinsi`, `transportasi`, `gambar`, `harga`, `tipe_kuliner`, `jam_buka`, `jam_tutup`, `latitude`, `longitude`, `kategori_kuliner`, `trend`, `budget`, `fasilitas`, `fitur_cbf`, `created_at`, `updated_at`, `hari_operasional`) VALUES
(52, 'Nuesara', 'Jl. Ciungwanara 10A', 'Menampilkan wajah bangunan yang sangat segar, modern, dan minimalis, dirancang secara spesifik untuk menarik minat demografi usia muda dan mahasiswa. Menu andalan sangat bervariasi dari hidangan pembuka hingga sajian penutup dengan harga bersahabat. Keunikannya terletak pada atmosfer santai yang mendorong interaksi sosial dalam waktu lama.', 'Bandung', 'Jawa Barat', 'Mobil, Motor', 'https://tse4.mm.bing.net/th/id/OIP.E-mUGMaoov422l4YNNWUygHaNJ?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 87500.00, 'Fusion', '11:00:00', '22:00:00', -6.89509900, 107.61170800, 'Fusion Asia, Kopi', 'Hidden Gem', 'murah', 'WiFi, Area Komunal, Parkir, Toilet Estetik', 'fusion asia kopi hidden gem murah bandung jawa barat mobil motor wifi area komunal parkir toilet estetik menampilkan wajah bangunan yang sangat segar modern dan minimalis dirancang secara spesifik untuk menarik minat demografi usia muda dan mahasiswa menu andalan sangat bervariasi dari hidangan pembuka hingga sajian penutup dengan harga bersahabat keunikannya terletak pada atmosfer santai yang mendorong interaksi sosial dalam waktu lama', NULL, NULL, 'Setiap Hari'),
(53, 'Homeground Restaurant', 'Jl. Gn. Batu 3, Ciumbuleuit', 'Restoran ini memeluk konsep integrasi dengan alam, dikelilingi oleh pepohonan rimbun yang menciptakan sirkulasi udara mikro yang sangat menyegarkan pikiran. Menu andalan mencakup pilihan masakan barat modern yang berfokus pada kualitas bahan organik. Keunikannya adalah kemampuannya menyediakan suaka hijau yang tenang di daerah resapan air.', 'Bandung', 'Jawa Barat', 'Mobil, Motor', 'https://tse1.mm.bing.net/th/id/OIP.3v2DlEQWyaLRHoJVHtrzAAHaE7?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 92500.00, 'Western', '11:00:00', '22:00:00', -6.86960500, 107.60742400, 'Barat Modern, Pilihan Sehat', 'Hidden Gem', 'murah', 'Area Hijau Terbuka, Parkir Luas, Mushola, Toilet', 'western barat modern pilihan sehat hidden gem murah bandung jawa barat mobil motor area hijau terbuka parkir luas mushola toilet restoran ini memeluk konsep integrasi dengan alam dikelilingi oleh pepohonan rimbun yang menciptakan sirkulasi udara mikro yang sangat menyegarkan pikiran menu andalan mencakup pilihan masakan barat modern yang berfokus pada kualitas bahan organik keunikannya adalah kemampuannya menyediakan suaka hijau yang tenang di daerah resapan air', NULL, NULL, 'Setiap Hari'),
(54, 'Sola', 'Jl. Raya Golf Dago 78', 'Mengambil posisi di kawasan perbukitan lapangan golf yang bergengsi, restoran berkonsep dining bar ini memancarkan aura kemewahan gaya hidup kaum urban elit. Menu andalan disajikan melalui teknik penataan hidangan (plating) yang artistik. Keunikannya adalah kombinasi antara kemewahan ruang dalam dengan pemandangan lapangan rumput yang tak berujung.', 'Bandung', 'Jawa Barat', 'Mobil', 'https://tse1.mm.bing.net/th/id/OIP.-ZhKEnEleURx3gRMfbBfxwHaJQ?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 47500.00, 'Fusion', '11:00:00', '22:00:00', -6.86719600, 107.62466500, 'Asia dan Barat Eksklusif', 'Hidden Gem', 'murah', 'Bar Premium, Area Terbuka, Toilet Mewah, Parkir', 'fusion asia dan barat eksklusif hidden gem murah bandung jawa barat mobil bar premium area terbuka toilet mewah parkir mengambil posisi di kawasan perbukitan lapangan golf yang bergengsi restoran berkonsep dining bar ini memancarkan aura kemewahan gaya hidup kaum urban elit menu andalan disajikan melalui teknik penataan hidangan plating yang artistik keunikannya adalah kombinasi antara kemewahan ruang dalam dengan pemandangan lapangan rumput yang tak berujung', NULL, NULL, 'Setiap Hari'),
(55, 'Maison Wilhelmina', 'Jl. Lembong No. 1', 'Interior rumah lawas disulap menjadi ruang makan serba putih yang hangat dengan elemen kayu kulit. Terdapat balkon kecil yang berfungsi sebagai perpustakaan mini berisi literatur asli. Menu andalan adalah Spaghetti Bolognese, set hidangan Teriyaki, dan minuman Marcantile Balua. Keunikannya adalah perpaduan nuansa intelektual perpustakaan dengan estetika pop Harry Potter.', 'Bandung', 'Jawa Barat', 'Motor, Jalan Kaki', 'https://tse2.mm.bing.net/th/id/OIP.gELUi2HKbz00ocuHETewCwHaEr?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 47500.00, 'Western', '11:00:00', '22:00:00', -6.91587400, 107.60939500, 'Pastry, Pasta, Cocktail', 'Hidden Gem', 'murah', 'Area Balkon Buku, Toilet Bersih, WiFi', 'western pastry pasta cocktail hidden gem murah bandung jawa barat motor jalan kaki area balkon buku toilet bersih wifi interior rumah lawas disulap menjadi ruang makan serba putih yang hangat dengan elemen kayu kulit terdapat balkon kecil yang berfungsi sebagai perpustakaan mini berisi literatur asli menu andalan adalah spaghetti bolognese set hidangan teriyaki dan minuman marcantile balua keunikannya adalah perpaduan nuansa intelektual perpustakaan dengan estetika pop harry potter', NULL, NULL, 'Setiap Hari'),
(56, 'BY JO Resto', 'Ruko Paskal 23 Blok N', 'Berada di dalam lanskap kawasan perbelanjaan paling padat, restoran ini mengadopsi desain futuristik yang mengoptimalkan efisiensi pergerakan pelanggan. Menu andalan berfokus pada variasi gorengan gurih gaya Asia dan lauk siap saji. Keunikannya adalah konsep pelayanan cepat di mana pelanggan secara mandiri memilih makanan langsung dari rak pamer.', 'Bandung', 'Jawa Barat', 'Mobil, Motor', 'https://www.instagram.com/p/DYECcH2yd3F/', 105000.00, 'Fusion', '11:00:00', '22:00:00', -6.91512400, 107.59304800, 'Asia Ringan, Gorengan', 'Hidden Gem', 'sedang', 'Akses Mal, AC, Ruang Duduk Bersih, Toilet Mal', 'fusion asia ringan gorengan hidden gem sedang bandung jawa barat mobil motor akses mal ac ruang duduk bersih toilet mal berada di dalam lanskap kawasan perbelanjaan paling padat restoran ini mengadopsi desain futuristik yang mengoptimalkan efisiensi pergerakan pelanggan menu andalan berfokus pada variasi gorengan gurih gaya asia dan lauk siap saji keunikannya adalah konsep pelayanan cepat di mana pelanggan secara mandiri memilih makanan langsung dari rak pamer', NULL, NULL, 'Setiap Hari'),
(57, 'Meze Steakhouse', 'Jl. Naripan No. 36', 'Menempati area atap (rooftop) bangunan yang diselimuti alunan musik jazz, memberikan privasi absolut untuk acara makan malam romantis yang jauh dari tatapan publik. Menu andalan adalah daging sapi pilihan dengan kematangan medium rare yang sempurna dan hidangan pembuka khas timur. Keunikannya adalah lokasinya yang tak terduga dengan bumbu steak yang sangat berani.', 'Bandung', 'Jawa Barat', 'Mobil, Motor', 'https://tse1.mm.bing.net/th/id/OIP.Dgs8MQRIHnyj3n6RLX5ZBQHaFj?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 285000.00, 'Western', '11:00:00', '22:00:00', -6.92026700, 107.61354000, 'Steak Premium, Mezze', 'Hidden Gem', 'mahal', 'Rooftop Romantis, Musik Jazz, Toilet, Parkir', 'western steak premium mezze hidden gem mahal bandung jawa barat mobil motor rooftop romantis musik jazz toilet parkir menempati area atap rooftop bangunan yang diselimuti alunan musik jazz memberikan privasi absolut untuk acara makan malam romantis yang jauh dari tatapan publik menu andalan adalah daging sapi pilihan dengan kematangan medium rare yang sempurna dan hidangan pembuka khas timur keunikannya adalah lokasinya yang tak terduga dengan bumbu steak yang sangat berani', NULL, NULL, 'Selasa - Minggu'),
(58, 'Artisan Tea by Pharrell', 'Gg. Dalem Kaum, Pasar', 'Tersembunyi di dalam labirin gang sempit pasar pakaian tradisional, menyajikan ruang mikroskopis yang hanya mampu menampung enam orang pada satu waktu. Menu andalan adalah pengalaman omakase, sebuah perjalanan degustasi enam jenis seduhan teh premium. Keunikannya adalah kerahasiaan ekstrem dan penolakan mutlak terhadap operasional pasar massal.', 'Bandung', 'Jawa Barat', 'Jalan Kaki', 'https://tse4.mm.bing.net/th/id/OIP.KUYXUNjCnmtWzbMX_d8HawHaJQ?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 40000.00, 'Asia', '11:00:00', '22:00:00', -6.92114100, 107.60594500, 'Omakase Teh Artisan', 'Hidden Gem', 'murah', 'Hanya 6 Kursi, Ruang Intim, AC Terpusat', 'asia omakase teh artisan hidden gem murah bandung jawa barat jalan kaki hanya 6 kursi ruang intim ac terpusat tersembunyi di dalam labirin gang sempit pasar pakaian tradisional menyajikan ruang mikroskopis yang hanya mampu menampung enam orang pada satu waktu menu andalan adalah pengalaman omakase sebuah perjalanan degustasi enam jenis seduhan teh premium keunikannya adalah kerahasiaan ekstrem dan penolakan mutlak terhadap operasional pasar massal', NULL, NULL, 'Rabu - Minggu'),
(59, 'Madam Hoek', 'Jl. Kelenteng No. 54', 'Transformasi kreatif dari sebuah rumah kolonial tua menjadi sebuah \"warteg modern\" yang memancarkan estetika retro yang fotogenik dan sangat bersahabat. Menu andalan adalah berbagai pilihan sarapan pagi komplit dengan lauk-pauk tradisional rumahan. Keunikannya adalah kontradiksi antara harga makanan yang murah dengan penataan ruang yang sangat artistik.', 'Bandung', 'Jawa Barat', 'Motor, Jalan Kaki', 'https://tse3.mm.bing.net/th/id/OIP.fnDrozcrQCJ8w4HYaTsCfwHaJQ?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 42500.00, 'Nusantara', '11:00:00', '22:00:00', -6.91692700, 107.59374000, 'Lauk Nusantara, Sarapan', 'Hidden Gem', 'murah', 'Estetika Rumah Tua, Area Terbuka, Toilet', 'nusantara lauk sarapan hidden gem murah bandung jawa barat motor jalan kaki estetika rumah tua area terbuka toilet transformasi kreatif dari sebuah rumah kolonial tua menjadi sebuah warteg modern yang memancarkan estetika retro yang fotogenik dan sangat bersahabat menu andalan adalah berbagai pilihan sarapan pagi komplit dengan lauk pauk tradisional rumahan keunikannya adalah kontradiksi antara harga makanan yang murah dengan penataan ruang yang sangat artistik', NULL, NULL, 'Setiap Hari'),
(60, 'Nanny’s Pavillon', 'Jl. Gn. Kareumbi No. 10', 'Kompleks restoran yang dirancang secara spesifik untuk rekreasi keluarga, mengusung arsitektur paviliun pedesaan Prancis yang teduh dan menenangkan. Menu andalan didominasi oleh sajian manis seperti aneka pancake, wafel, serta hidangan keju panggang. Keunikannya adalah konsistensi tema visual yang menjadikannya semacam taman hiburan mini berbahan dasar makanan.', 'Bandung', 'Jawa Barat', 'Mobil, Motor', 'https://tse2.mm.bing.net/th/id/OIP.IKDOqp6q6MbpG15ZZeNEGwHaDs?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 77500.00, 'Western', '11:00:00', '22:00:00', -6.86931100, 107.60908900, 'Pancake, Pasta, Western', 'Terkenal', 'murah', 'Area Taman, Ruang Bermain Anak, Parkir, Toilet', 'western pancake pasta terkenal murah bandung jawa barat mobil motor area taman ruang bermain anak parkir toilet kompleks restoran yang dirancang secara spesifik untuk rekreasi keluarga mengusung arsitektur paviliun pedesaan prancis yang teduh dan menenangkan menu andalan didominasi oleh sajian manis seperti aneka pancake wafel serta hidangan keju panggang keunikannya adalah konsistensi tema visual yang menjadikannya semacam taman hiburan mini berbahan dasar makanan', NULL, NULL, 'Setiap Hari'),
(61, 'Mang Loong', 'Jl. Dr. Cipto No. 22', 'Atmosfer ruang makan sarat dengan simbolisme visual kebudayaan Chinatown klasik, menggunakan lampion merah elegan dan perabot kayu yang solid. Menu andalan yang paling populer adalah bubur oriental kuah kental dan set nasi hainan yang kaya kaldu. Keunikan utamanya adalah menghadirkan totalitas cita rasa tradisional Tiongkok dengan jaminan sertifikasi halal.', 'Bandung', 'Jawa Barat', 'Mobil, Motor', 'https://tse2.mm.bing.net/th/id/OIP.PATjNGIbHLe_2mzl52rhSgHaJQ?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 77500.00, 'Asia', '11:00:00', '22:00:00', -6.90504000, 107.60128200, 'Masakan Tiongkok Halal', 'Hidden Gem', 'murah', 'AC, Desain Klasik Tiongkok, Toilet, Parkir Terbatas', 'asia masakan tiongkok halal hidden gem murah bandung jawa barat mobil motor ac desain klasik tiongkok toilet parkir terbatas atmosfer ruang makan sarat dengan simbolisme visual kebudayaan chinatown klasik menggunakan lampion merah elegan dan perabot kayu yang solid menu andalan yang paling populer adalah bubur oriental kuah kental dan set nasi hainan yang kaya kaldu keunikan utamanya adalah menghadirkan totalitas cita rasa tradisional tiongkok dengan jaminan sertifikasi halal', NULL, NULL, 'Setiap Hari'),
(62, 'Keenari Eatery & Space', 'Jl. Gatot Subroto 140', 'Sebuah pusat interaksi komunitas yang sangat dinamis, menggabungkan kafe berdesain interior kekinian dengan fungsi ruang kerja fleksibel yang menghadap pusat perbelanjaan. Menu andalan berupa aneka sajian untuk bersantap dari pagi hingga malam hari, serta kopi presisi. Keunikannya adalah kapabilitas transformasi ruang yang mendukung penyelenggaraan berbagai acara (events).', 'Bandung', 'Jawa Barat', 'Mobil, Motor', 'https://tse1.mm.bing.net/th/id/OIP.Lqjeid6xq0RatGOTKCoU1wHaEU?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 40000.00, 'Kafe & Dessert', '11:00:00', '22:00:00', -6.92718400, 107.63540500, 'Kopi, Sarapan, Makan Malam', 'Terkenal', 'murah', 'Ruang Serbaguna, WiFi, Toilet, Mushola Luas', 'kafe dessert kopi sarapan makan malam terkenal murah bandung jawa barat mobil motor ruang serbaguna wifi toilet mushola luas sebuah pusat interaksi komunitas yang sangat dinamis menggabungkan kafe berdesain interior kekinian dengan fungsi ruang kerja fleksibel yang menghadap pusat perbelanjaan menu andalan berupa aneka sajian untuk bersantap dari pagi hingga malam hari serta kopi presisi keunikannya adalah kapabilitas transformasi ruang yang mendukung penyelenggaraan berbagai acara events', NULL, NULL, 'Setiap Hari'),
(63, 'Take Five', 'Trans Luxury Hotel', 'Bar speakeasy yang dirahasiakan di balik struktur beton hotel termewah di Bandung, menawarkan pelarian eskapis ke dalam dunia gemerlap kelas atas dengan privasi tinggi. Menu andalan bertumpu pada koleksi koktail eksklusif racikan ahli (mixologist) dan kudapan tapas premium. Keunikannya adalah akses masuk rahasia dan perlindungan status anonimitas tamu yang ketat.', 'Bandung', 'Jawa Barat', 'Mobil', 'default.jpg', 42500.00, 'Fusion', '11:00:00', '22:00:00', -6.92547300, 107.63638500, 'Cocktail Eksklusif, Tapas', 'Hidden Gem', 'murah', 'Bar Mewah, Lounge Privat, AC, Parkir Valet', 'fusion cocktail eksklusif tapas hidden gem murah bandung jawa barat mobil bar mewah lounge privat ac parkir valet bar speakeasy yang dirahasiakan di balik struktur beton hotel termewah di bandung menawarkan pelarian eskapis ke dalam dunia gemerlap kelas atas dengan privasi tinggi menu andalan bertumpu pada koleksi koktail eksklusif racikan ahli mixologist dan kudapan tapas premium keunikannya adalah akses masuk rahasia dan perlindungan status anonimitas tamu yang ketat', NULL, NULL, 'Setiap Hari'),
(64, 'Theo\'s Wife Lois', 'Jl. Progo No. 23', 'Arsitektur kontemporer dengan bidang kaca transparan yang luas, menghasilkan pencahayaan alami optimal untuk menciptakan suasana yang energik dan kasual sepanjang hari. Menu andalan yang menonjol adalah TWL Spicy Fried Chicken dan Classic Cheeseburger gaya Amerika. Keunikannya adalah kemampuannya memberikan pengalaman kuliner tingkat tinggi dalam format yang sama sekali tidak kaku.', 'Bandung', 'Jawa Barat', 'Mobil, Motor', 'https://tse3.mm.bing.net/th/id/OIP.hjd77zsf_t4wFrIL5UR7rAHaE8?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 50000.00, 'Western', '11:00:00', '22:00:00', -6.90419500, 107.61959300, 'All Day Dining, Burger', 'Hidden Gem', 'murah', 'Area Semi Outdoor, WiFi, AC Sebagian, Toilet', 'western all day dining burger hidden gem murah bandung jawa barat mobil motor area semi outdoor wifi ac sebagian toilet arsitektur kontemporer dengan bidang kaca transparan yang luas menghasilkan pencahayaan alami optimal untuk menciptakan suasana yang energik dan kasual sepanjang hari menu andalan yang menonjol adalah twl spicy fried chicken dan classic cheeseburger gaya amerika keunikannya adalah kemampuannya memberikan pengalaman kuliner tingkat tinggi dalam format yang sama sekali tidak kaku', NULL, NULL, 'Setiap Hari'),
(65, 'Braci Bar & Bistro', 'Jl. Sulanjana No. 5', 'Fasad bangunan mengadopsi struktur estetika Art Deco yang telah direvitalisasi, memancarkan pesona retro yang secara mengejutkan sangat sinkron dengan kehidupan malam kota. Menu andalan yang paradoksal namun disukai adalah Bakmi Asin Ayam klasik dan aneka mocktail segar. Keunikannya adalah keberanian menggabungkan ritme bar modern dengan substansi menu kedai kaki lima.', 'Bandung', 'Jawa Barat', 'Mobil, Motor', 'https://tse4.explicit.bing.net/th/id/OIP.wdBVt-ZUbUdHMzsFUs5-fwHaJQ?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 160000.00, 'Western', '11:00:00', '22:00:00', -6.90004100, 107.61243300, 'Bakmi Asin, Minuman Segar', 'Hidden Gem', 'sedang', 'Area Bar, Musik, AC, Toilet Bersih, Parkir', 'western bakmi asin minuman segar hidden gem sedang bandung jawa barat mobil motor area bar musik ac toilet bersih parkir fasad bangunan mengadopsi struktur estetika art deco yang telah direvitalisasi memancarkan pesona retro yang secara mengejutkan sangat sinkron dengan kehidupan malam kota menu andalan yang paradoksal namun disukai adalah bakmi asin ayam klasik dan aneka mocktail segar keunikannya adalah keberanian menggabungkan ritme bar modern dengan substansi menu kedai kaki lima', NULL, NULL, 'Setiap Hari'),
(66, 'Braga Permai', 'Jl. Braga No. 58', 'Merupakan monumen hidup sejarah kuliner yang mempertahankan warisan interior Hindia Belanda dengan detail kanopi memanjang yang menghadap jalan berbatu historis. Menu andalan meliputi ragam bistik daging sapi gaya lama, camilan bitterballen asli, dan sajian es krim tradisional. Keunikannya adalah sensasi melintasi batas waktu yang dipertahankan secara konsisten sejak awal pembangunannya.', 'Bandung', 'Jawa Barat', 'Mobil, Motor, Jalan Kaki', 'https://tse1.mm.bing.net/th/id/OIP.b5Hacb5IjRLA7_234U73FAHaEK?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 87500.00, 'Nusantara', '11:00:00', '22:00:00', -6.91716200, 107.60951600, 'Masakan Kolonial, Western', 'Ikonik', 'murah', 'Area Trotoar, Interior Klasik, Toilet Bersih', 'nusantara masakan kolonial western ikonik murah bandung jawa barat mobil motor jalan kaki area trotoar interior klasik toilet bersih merupakan monumen hidup sejarah kuliner yang mempertahankan warisan interior hindia belanda dengan detail kanopi memanjang yang menghadap jalan berbatu historis menu andalan meliputi ragam bistik daging sapi gaya lama camilan bitterballen asli dan sajian es krim tradisional keunikannya adalah sensasi melintasi batas waktu yang dipertahankan secara konsisten sejak awal pembangunannya', NULL, NULL, 'Setiap Hari'),
(67, 'Raja Rasa', 'Jl. Setraria No. 1', 'Restoran keluarga berskala raksasa yang diperindah dengan struktur kayu tradisional dan efek suara menenangkan dari kolam koi yang melingkari paviliun utamanya. Menu andalan adalah hibrida masakan Sunda dan Bali, serta aneka olahan hidangan laut dalam porsi kolektif. Keunikannya adalah kapasitas infrastruktur yang mampu menampung puluhan keluarga sekaligus tanpa menurunkan kualitas.', 'Bandung', 'Jawa Barat', 'Mobil, Motor', 'https://tse2.mm.bing.net/th/id/OIP.ZCpUyGpO7Gg77a3bzQMAHwHaEK?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 110000.00, 'Nusantara', '11:00:00', '22:00:00', -6.87966900, 107.58418900, 'Seafood, Sunda, Bali', 'Terkenal', 'sedang', 'Area Lesehan Besar, Kolam Ikan, Parkir Luas', 'nusantara seafood sunda bali terkenal sedang bandung jawa barat mobil motor area lesehan besar kolam ikan parkir luas restoran keluarga berskala raksasa yang diperindah dengan struktur kayu tradisional dan efek suara menenangkan dari kolam koi yang melingkari paviliun utamanya menu andalan adalah hibrida masakan sunda dan bali serta aneka olahan hidangan laut dalam porsi kolektif keunikannya adalah kapasitas infrastruktur yang mampu menampung puluhan keluarga sekaligus tanpa menurunkan kualitas', NULL, NULL, 'Setiap Hari'),
(68, 'Dago Bakery Punclut', 'Kawasan Punclut', 'Sebuah lanskap surealis yang didominasi oleh menara kastil bergaya arsitektur Gotik di bibir tebing yang menjulang, memberikan sudut pandang visual tanpa batas ke arah cekungan kota. Menu andalan bervariasi dari hidangan kenyamanan barat seperti Beef Lasagna hingga makanan tradisional seperti Batagor. Keunikan utamanya adalah fungsi utamanya sebagai studio foto raksasa bermodus operandi restoran.', 'Bandung Barat', 'Jawa Barat', 'Mobil, Motor', 'https://www.harapanrakyat.com/wp-content/uploads/2023/10/Dago-Bakery-Punclut-Cafe-Bandung-Bernuansa-Eropa-Klasik.jpg', 55000.00, 'Western', '11:00:00', '22:00:00', -6.84198300, 107.62294100, 'Western, Sunda, Pastry', 'Terkenal', 'murah', 'Arsitektur Kastil Gotik, Spot Foto Masif, Toilet', 'western sunda pastry terkenal murah bandung barat jawa barat mobil motor arsitektur kastil gotik spot foto masif toilet sebuah lanskap surealis yang didominasi oleh menara kastil bergaya arsitektur gotik di bibir tebing yang menjulang memberikan sudut pandang visual tanpa batas ke arah cekungan kota menu andalan bervariasi dari hidangan kenyamanan barat seperti beef lasagna hingga makanan tradisional seperti batagor keunikan utamanya adalah fungsi utamanya sebagai studio foto raksasa bermodus operandi restoran', NULL, NULL, 'Setiap Hari'),
(69, 'Tilu Kitchen', 'Jl. LLRE Martadinata 81', 'Menghuni struktur bangunan kolonial berbalut warna krem dengan teras luas, memancarkan atmosfer yang sangat elegan, aristokratis, dan diramaikan oleh kurasi pertunjukan musik langsung. Menu andalan adalah Sup Buntut Bakar premium dan Tilu Padang Platter dengan rendang beraroma kelapa. Keunikannya adalah orkestrasi sempurna antara presentasi visual tingkat tinggi dan kualitas hiburan malam.', 'Bandung', 'Jawa Barat', 'Mobil, Motor', 'https://tse1.mm.bing.net/th/id/OIP.FlLd3bL4NTmcpab9KotNCwHaFP?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 80000.00, 'Fusion', '11:00:00', '22:00:00', -6.90560000, 107.61918100, 'Fusi Kelas Atas, Nusantara', 'Terkenal', 'murah', 'Live Music, Ruang VIP, Valet, AC, Toilet', 'fusion fusi kelas atas nusantara terkenal murah bandung jawa barat mobil motor live music ruang vip valet ac toilet menghuni struktur bangunan kolonial berbalut warna krem dengan teras luas memancarkan atmosfer yang sangat elegan aristokratis dan diramaikan oleh kurasi pertunjukan musik langsung menu andalan adalah sup buntut bakar premium dan tilu padang platter dengan rendang beraroma kelapa keunikannya adalah orkestrasi sempurna antara presentasi visual tingkat tinggi dan kualitas hiburan malam', NULL, NULL, 'Setiap Hari'),
(70, 'Roemah Kentang 1908', 'Jl. Banda No.18, Citarum, Kec. Bandung Wetan, Kota Bandung, Jawa Barat 40115.', 'Roemah Kentang 1908Klik untuk membuka panel samping guna melihat informasi selengkapnya merupakan salah satu destinasi kuliner paling ikonik di Bandung yang berhasil mengubah sebuah rumah tua peninggalan zaman kolonial Belanda—yang dulunya sarat akan kisah misteri—menjadi sebuah restoran premium bergaya eropa-modern yang sangat elegan. Interior di dalamnya didominasi sekat-sekat kaca besar, lampu gantung klasik, dan tanaman hijau yang memberikan kesan mewah namun tetap asri. Menu yang disajikan sangat variatif, mulai dari hidangan barat seperti steak premium dan pasta, kuliner nusantara yang dikemas modern, hingga aneka kreasi berbasis kentang yang menjadi ciri khasnya. Tempat ini sangat ideal untuk makan bersama keluarga, mengadakan acara formal, hingga tempat dating romantis di malam hari.', 'Bandung.', 'Jawa Barat', 'Mobil, Motor', 'https://tse3.mm.bing.net/th/id/OIP.YWmJ7bo8C-8rUBoV_DQEtAHaEA?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 80000.00, 'Restoran & Cafe (Fine Dining / Casual Dining)', '09:00:00', '22:00:00', -6.90790000, 107.61860000, 'Fine Dining', 'Terkenal', 'murah', 'Parkir, Toilet, Wi-Fi, Area Merokok, Pembayaran Non Tunai, Pembayaran Tunai, Takeaway', 'restoran cafe fine dining casual dining terkenal murah bandung jawa barat mobil motor parkir toilet wi fi area merokok pembayaran non tunai pembayaran tunai takeaway roemah kentang 1908klik untuk membuka panel samping guna melihat informasi selengkapnya merupakan salah satu destinasi kuliner paling ikonik di bandung yang berhasil mengubah sebuah rumah tua peninggalan zaman kolonial belanda yang dulunya sarat akan kisah misteri menjadi sebuah restoran premium bergaya eropa modern yang sangat elegan interior di dalamnya didominasi sekat sekat kaca besar lampu gantung klasik dan tanaman hijau yang memberikan kesan mewah namun tetap asri menu yang disajikan sangat variatif mulai dari hidangan barat seperti steak premium dan pasta kuliner nusantara yang dikemas modern hingga aneka kreasi berbasis kentang yang menjadi ciri khasnya tempat ini sangat ideal untuk makan bersama keluarga mengadakan acara formal hingga tempat dating romantis di malam hari', NULL, NULL, 'Selasa - Minggu'),
(71, 'Kampung Daun', 'Kawasan Ledeng', 'Resor kuliner seluas ratusan meter persegi yang mengekstraksi potensi ekosistem lembah berhutan', 'Bandung Barat', 'Jawa Barat', 'Mobil', 'https://tse1.mm.bing.net/th/id/OIP.SQiilI42XVDMZ_895Z6cMgHaJQ?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 67500.00, 'Nusantara', '10:00:00', '22:00:00', -6.81692600, 107.58985400, 'Restoran Tradisional', 'Ikonik', 'murah', 'Parkir, Toilet, Wi-Fi, Area Merokok, Pembayaran Non Tunai, Pembayaran Tunai, Takeaway', 'nusantara restoran tradisional ikonik murah bandung barat jawa barat mobil parkir toilet wi fi area merokok pembayaran non tunai pembayaran tunai takeaway resor kuliner seluas ratusan meter persegi yang mengekstraksi potensi ekosistem lembah berhutan', NULL, NULL, 'Setiap Hari'),
(72, 'Roti Macan', 'Kawasan Buah Batu', 'Fasilitas pengolahan adonan dan pemanggangan skala mikro yang operasionalnya bertumpu pada kualitas absolut, memicu antrean panjang harian yang menghabiskan seluruh stok sebelum siang hari tiba. Menu andalan mutlak adalah Roti Cranberry Cheese artisan dengan tekstur kenyal dan aroma ragi fermentasi alami yang khas. Keunikannya adalah manifestasi fenomena \'Fear Of Missing Out\' dalam komoditas kue.', 'Bandung', 'Jawa Barat', 'Motor, Mobil', 'https://image.idntimes.com/post/20251123/snapinsta_cb5c0110-f0de-4749-898d-87b87802b846.jpg?tr=w-750', 32500.00, 'Kafe & Dessert', '11:00:00', '22:00:00', -6.93620000, 107.62291100, 'Artisan Sourdough', 'Hidden Gem', 'murah', 'Ruang Tunggu Terbatas, Sistem Bawa Pulang', 'kafe dessert artisan sourdough hidden gem murah bandung jawa barat motor mobil ruang tunggu terbatas sistem bawa pulang fasilitas pengolahan adonan dan pemanggangan skala mikro yang operasionalnya bertumpu pada kualitas absolut memicu antrean panjang harian yang menghabiskan seluruh stok sebelum siang hari tiba menu andalan mutlak adalah roti cranberry cheese artisan dengan tekstur kenyal dan aroma ragi fermentasi alami yang khas keunikannya adalah manifestasi fenomena fear of missing out dalam komoditas kue', NULL, NULL, 'Setiap Hari'),
(73, 'Yamato Gyukatsu', 'Mal Paris Van Java', 'Diintegrasikan secara mulus ke dalam lorong pusat perbelanjaan paling mewah di Bandung, tempat ini membawa kedisiplinan desain Jepang yang minimalis dengan pencahayaan fungsional yang tajam. Menu andalan berupa Australian Tenderloin Truffle Set yang wajib disajikan dengan alat panggang batu miniatur untuk interaksi pelanggan. Keunikannya adalah tingkat interaktivitas pengunjung dalam menentukan kematangan protein.', 'Bandung', 'Jawa Barat', 'Mobil, Motor', 'https://tse4.mm.bing.net/th/id/OIP.6i1USR8EAGLmt6ZawUK4iwHaNJ?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 105000.00, 'Asia', '11:00:00', '22:00:00', -6.88908200, 107.59641000, 'Gastronomi Jepang', 'Terkenal', 'sedang', 'AC Terpusat, Kursi Eksklusif, Akses Mal Lengkap', 'asia gastronomi jepang terkenal sedang bandung jawa barat mobil motor ac terpusat kursi eksklusif akses mal lengkap diintegrasikan secara mulus ke dalam lorong pusat perbelanjaan paling mewah di bandung tempat ini membawa kedisiplinan desain jepang yang minimalis dengan pencahayaan fungsional yang tajam menu andalan berupa australian tenderloin truffle set yang wajib disajikan dengan alat panggang batu miniatur untuk interaksi pelanggan keunikannya adalah tingkat interaktivitas pengunjung dalam menentukan kematangan protein', NULL, NULL, 'Setiap Hari'),
(74, 'Seblak Oces', 'Jl. Dipatiukur', 'Lapak non-permanen yang tergelar di tepi jalan utama kawasan kampus padat penduduk, menghasilkan suasana komunal jalanan yang riuh, berasap, namun penuh dengan vitalitas sosial kaum muda. Menu andalan berupa kerupuk basah yang direbus dalam kaldu rempah kencur pekat, dicampur puluhan modifikasi topping. Keunikannya adalah posisinya sebagai pionir seblak kuah basah modern yang melahirkan industri ikutan.', 'Bandung', 'Jawa Barat', 'Motor, Jalan Kaki', 'https://tse2.mm.bing.net/th/id/OIP.C_s3Q5AMM_flqg09D2HtYQAAAA?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 22500.00, 'Nusantara', '11:00:00', '22:00:00', -6.90566400, 107.60899800, 'Jajanan Kaki Lima Ekstrem', 'Terkenal', 'murah', 'Duduk Di Trotoar, Tenda Terpal Sederhana', 'nusantara jajanan kaki lima ekstrem terkenal murah bandung jawa barat motor jalan kaki duduk di trotoar tenda terpal sederhana lapak non permanen yang tergelar di tepi jalan utama kawasan kampus padat penduduk menghasilkan suasana komunal jalanan yang riuh berasap namun penuh dengan vitalitas sosial kaum muda menu andalan berupa kerupuk basah yang direbus dalam kaldu rempah kencur pekat dicampur puluhan modifikasi topping keunikannya adalah posisinya sebagai pionir seblak kuah basah modern yang melahirkan industri ikutan', NULL, NULL, 'Setiap Hari'),
(75, 'Seblak Sultan', 'Jl. Pahlawan No. 70', 'Berupa unit usaha mikro yang selalu dikepung oleh ratusan pesanan harian, beroperasi dengan ritme peracikan mekanis yang sangat cepat di bawah tenda panas untuk mengurangi waktu tunggu. Menu andalan berpusat pada kustomisasi seblak radikal dengan penambahan protein hewani berat seperti balungan, tulang rawan sapi, dan hidangan laut. Keunikannya adalah racikan bumbu rahasia yang menghasilkan tekstur kuah berminyak dan kaya kaldu.', 'Bandung', 'Jawa Barat', 'Motor, Jalan Kaki', 'https://tse3.mm.bing.net/th/id/OIP.rb2uoMcrgTDdEoIl6fF4OwHaHa?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 25000.00, 'Nusantara', '11:00:00', '22:00:00', -6.90405600, 107.61279700, 'Jajanan Kaki Lima Pedas', 'Terkenal', 'murah', 'Area Antrean Memanjang, Tempat Duduk Terbatas', 'nusantara jajanan kaki lima pedas terkenal murah bandung jawa barat motor jalan kaki area antrean memanjang tempat duduk terbatas berupa unit usaha mikro yang selalu dikepung oleh ratusan pesanan harian beroperasi dengan ritme peracikan mekanis yang sangat cepat di bawah tenda panas untuk mengurangi waktu tunggu menu andalan berpusat pada kustomisasi seblak radikal dengan penambahan protein hewani berat seperti balungan tulang rawan sapi dan hidangan laut keunikannya adalah racikan bumbu rahasia yang menghasilkan tekstur kuah berminyak dan kaya kaldu', NULL, NULL, 'Setiap Hari'),
(76, 'Wheels Coffee', 'Jl. Eyckman / Jl. Riau', 'Berperan ganda sebagai kafe komersial sekaligus fasilitas manufaktur kopi', 'Bandung', 'Jawa Barat', 'Mobil, Motor', 'https://tse4.mm.bing.net/th/id/OIP.BxTjEh8SnzifrO9caV4A2wAAAA?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 57500.00, 'Kafe & Dessert', '08:00:00', '22:00:00', -6.89361700, 107.60232600, 'Coffee Shop', 'Terkenal', 'murah', 'Parkir, Toilet, Wi-Fi, Area Merokok, Pembayaran Non Tunai, Pembayaran Tunai, Takeaway', 'kafe dessert coffee shop terkenal murah bandung jawa barat mobil motor parkir toilet wi fi area merokok pembayaran non tunai pembayaran tunai takeaway berperan ganda sebagai kafe komersial sekaligus fasilitas manufaktur kopi', NULL, NULL, 'Selasa - Minggu'),
(77, 'Tizi Cake Shop', 'Jl. Kidang Pananjung', 'Mengisolasi diri dari agresivitas perkembangan kota modern, institusi ini menjaga ekosistem ruang yang sepenuhnya beku dalam waktu, mempertahankan perabot dan ritme lambat era 1980-an yang sangat menenteramkan. Menu andalan adalah potongan kue tart tradisional berukuran presisi, sosis bratwurst bakar berkaliber besar, dan bistik sapi. Keunikannya adalah fungsi primernya sebagai kapsul waktu yang menyajikan kemurnian memorabilia kuliner.', 'Bandung', 'Jawa Barat', 'Mobil, Motor', 'https://tse3.mm.bing.net/th/id/OIP.P6S7KEhhQ6N-gmjQ3WtujgHaF2?r=0&w=720&h=569&rs=1&pid=ImgDetMain&o=7&rm=3', 70000.00, 'Western', '11:00:00', '22:00:00', -6.88280500, 107.61385300, 'Kue Klasik Eropa, Steak Sapi', 'Ikonik', 'murah', 'Taman Nostalgia, Ornamen Kayu Lawas, Parkir', 'western kue klasik eropa steak sapi ikonik murah bandung jawa barat mobil motor taman nostalgia ornamen kayu lawas parkir mengisolasi diri dari agresivitas perkembangan kota modern institusi ini menjaga ekosistem ruang yang sepenuhnya beku dalam waktu mempertahankan perabot dan ritme lambat era 1980 an yang sangat menenteramkan menu andalan adalah potongan kue tart tradisional berukuran presisi sosis bratwurst bakar berkaliber besar dan bistik sapi keunikannya adalah fungsi primernya sebagai kapsul waktu yang menyajikan kemurnian memorabilia kuliner', NULL, NULL, 'Setiap Hari'),
(78, 'Miss Bee Providore', 'Jl. Rancabentang', 'Memecah batas spasial antara ruang dalam dan alam luar melalui struktur rumah kaca (glasshouse) berdimensi besar yang menangkap setiap spektrum cahaya matahari dengan sangat fotogenik. Menu andalan berkisar pada masakan Italia rekayasa ulang seperti pizza berlapis tipis renyah dan hidangan pasta minyak truffle mewah. Keunikannya adalah penyeimbangan yang sangat akurat antara elegansi desain dan keramahan terhadap keluarga.', 'Bandung', 'Jawa Barat', 'Mobil, Motor', 'https://tse4.mm.bing.net/th/id/OIP.UeyDw7kLtUr4PN6Usdsf8gHaJ3?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 82500.00, 'Western', '11:00:00', '22:00:00', -6.86812900, 107.60927200, 'Fusi Barat Kontemporer', 'Terkenal', 'murah', 'Bangunan Kaca Estetik, Area Bermain Anak', 'western fusi barat kontemporer terkenal murah bandung jawa barat mobil motor bangunan kaca estetik area bermain anak memecah batas spasial antara ruang dalam dan alam luar melalui struktur rumah kaca glasshouse berdimensi besar yang menangkap setiap spektrum cahaya matahari dengan sangat fotogenik menu andalan berkisar pada masakan italia rekayasa ulang seperti pizza berlapis tipis renyah dan hidangan pasta minyak truffle mewah keunikannya adalah penyeimbangan yang sangat akurat antara elegansi desain dan keramahan terhadap keluarga', NULL, NULL, 'Setiap Hari'),
(79, 'Keuken van Elsje', 'Jl. Buton No. 11', 'Menyusup di antara jajaran perumahan tua yang sepi, restoran ini mensimulasikan ruang makan privat seorang aristokrat keturunan Belanda, dipenuhi oleh memorabilia keluarga dan porselen antik. Menu andalan adalah Macaroni Schotel panggang berkerak keju padat, bistik gaya rumahan kuno, dan sup krim pekat yang dibuat sesuai resep asal usulnya. Keunikannya adalah pelunturan batas antara institusi komersial dan kenyamanan bertamu di rumah kerabat.', 'Bandung', 'Jawa Barat', 'Mobil, Motor', 'https://media-cdn.tripadvisor.com/media/photo-s/0d/f8/2a/ce/suasana-malam-hari.jpg', 55000.00, 'Nusantara', '11:00:00', '22:00:00', -6.91828900, 107.61603300, 'Resep Kolonial Belanda Asli', 'Hidden Gem', 'murah', 'Rumah Tinggal Pribadi, Koleksi Piring Antik', 'nusantara resep kolonial belanda asli hidden gem murah bandung jawa barat mobil motor rumah tinggal pribadi koleksi piring antik menyusup di antara jajaran perumahan tua yang sepi restoran ini mensimulasikan ruang makan privat seorang aristokrat keturunan belanda dipenuhi oleh memorabilia keluarga dan porselen antik menu andalan adalah macaroni schotel panggang berkerak keju padat bistik gaya rumahan kuno dan sup krim pekat yang dibuat sesuai resep asal usulnya keunikannya adalah pelunturan batas antara institusi komersial dan kenyamanan bertamu di rumah kerabat', NULL, NULL, 'Setiap Hari'),
(80, 'Justus Cimanuk', 'Jl. Cimanuk No. 8', 'Sebagai salah satu dari 16 cabang sukses jenama ini, lokasi Cimanuk merepresentasikan tingkat penyempurnaan tertinggi dari tata kelola restoran kelas menengah ke atas dengan protokol pelayanan yang sangat rigid dan profesional. Menu andalan tidak bergeser dari spesialisasi protein daging panggang bermutu tinggi, disertai pilihan aneka saus pendamping intens. Keunikannya adalah standarisasi kualitas mutlak yang tidak terpengaruh oleh perluasan cabang bisnis.', 'Bandung', 'Jawa Barat', 'Mobil, Motor', 'https://tse1.mm.bing.net/th/id/OIP.2Q7kwrHT_nJgOXv5XU1rhgHaEO?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 215000.00, 'Western', '11:00:00', '22:00:00', -6.90475500, 107.62090200, 'Steak Premium Halal', 'Terkenal', 'sedang', 'Area Merokok, Mushola, Ruang Rapat Eksklusif', 'western steak premium halal terkenal sedang bandung jawa barat mobil motor area merokok mushola ruang rapat eksklusif sebagai salah satu dari 16 cabang sukses jenama ini lokasi cimanuk merepresentasikan tingkat penyempurnaan tertinggi dari tata kelola restoran kelas menengah ke atas dengan protokol pelayanan yang sangat rigid dan profesional menu andalan tidak bergeser dari spesialisasi protein daging panggang bermutu tinggi disertai pilihan aneka saus pendamping intens keunikannya adalah standarisasi kualitas mutlak yang tidak terpengaruh oleh perluasan cabang bisnis', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id_menu` bigint(20) UNSIGNED NOT NULL,
  `id_kuliner` bigint(20) UNSIGNED NOT NULL,
  `nama_menu` varchar(255) NOT NULL,
  `harga_menu` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id_menu`, `id_kuliner`, `nama_menu`, `harga_menu`, `created_at`, `updated_at`) VALUES
(1, 1, 'Salted Caramel Cupcake', 45000.00, NULL, NULL),
(2, 1, 'Red Velvet Cake', 40000.00, NULL, NULL),
(3, 1, 'Nutella Donut', 35000.00, NULL, NULL),
(4, 1, 'Cheese Cloud Cake', 50000.00, NULL, NULL),
(5, 2, 'Donut Classic Sugar', 15000.00, NULL, NULL),
(6, 2, 'Donut Cokelat Mesis', 18000.00, NULL, NULL),
(7, 2, 'Donut Dancow', 18000.00, NULL, NULL),
(8, 2, 'Donut Red Velvet', 22000.00, NULL, NULL),
(9, 3, 'Ayam Goreng Lengkuas', 28000.00, NULL, NULL),
(10, 3, 'Ayam Bakar Madu', 32000.00, NULL, NULL),
(11, 3, 'Sambal Ijo', 8000.00, NULL, NULL),
(12, 3, 'Tahu Tempe Goreng', 10000.00, NULL, NULL),
(13, 4, 'Chicken Steak', 45000.00, NULL, NULL),
(14, 4, 'Beef Steak', 65000.00, NULL, NULL),
(15, 4, 'Spaghetti Carbonara', 42000.00, NULL, NULL),
(16, 4, 'French Fries', 22000.00, NULL, NULL),
(17, 5, 'Batagor Original', 30000.00, NULL, NULL),
(18, 5, 'Batagor Kuah', 32000.00, NULL, NULL),
(19, 5, 'Siomay', 28000.00, NULL, NULL),
(20, 5, 'Es Teh Manis', 8000.00, NULL, NULL),
(21, 6, 'Ayam Bakar Original', 35000.00, NULL, NULL),
(22, 6, 'Ayam Bakar Pedas', 38000.00, NULL, NULL),
(23, 6, 'Nasi Putih', 7000.00, NULL, NULL),
(24, 6, 'Es Jeruk', 9000.00, NULL, NULL),
(25, 7, 'Indomie Upnormal', 28000.00, NULL, NULL),
(26, 7, 'Roti Bakar Coklat', 25000.00, NULL, NULL),
(27, 7, 'Susu Regal', 18000.00, NULL, NULL),
(28, 7, 'Es Kopi Susu', 22000.00, NULL, NULL),
(29, 8, 'Bakso Urat', 28000.00, NULL, NULL),
(30, 8, 'Bakso Keju', 30000.00, NULL, NULL),
(31, 8, 'Mie Yamin', 22000.00, NULL, NULL),
(32, 8, 'Es Campur', 18000.00, NULL, NULL),
(33, 9, 'Bistik Lidah', 85000.00, NULL, NULL),
(34, 9, 'Nasi Goreng Special', 55000.00, NULL, NULL),
(35, 9, 'Pancake', 45000.00, NULL, NULL),
(36, 9, 'Coffee Latte', 32000.00, NULL, NULL),
(37, 10, 'Grilled Salmon', 85000.00, NULL, NULL),
(38, 10, 'Chicken Cordon Bleu', 65000.00, NULL, NULL),
(39, 10, 'Pizza Margherita', 70000.00, NULL, NULL),
(40, 10, 'Mojito', 28000.00, NULL, NULL),
(41, 11, 'Chicken Wings', 42000.00, NULL, NULL),
(42, 11, 'Pasta Carbonara', 48000.00, NULL, NULL),
(43, 11, 'Burger Beef', 50000.00, NULL, NULL),
(44, 11, 'Ice Chocolate', 30000.00, NULL, NULL),
(45, 12, 'Fish and Chips', 58000.00, NULL, NULL),
(46, 12, 'Beef Burger', 62000.00, NULL, NULL),
(47, 12, 'Pancake', 38000.00, NULL, NULL),
(48, 12, 'Milkshake', 35000.00, NULL, NULL),
(49, 13, 'Kopi Susu', 25000.00, NULL, NULL),
(50, 13, 'Nasi Liwet', 38000.00, NULL, NULL),
(51, 13, 'Mie Goreng', 30000.00, NULL, NULL),
(52, 13, 'Pisang Bakar', 22000.00, NULL, NULL),
(53, 14, 'Grilled Chicken', 65000.00, NULL, NULL),
(54, 14, 'Cream Soup', 30000.00, NULL, NULL),
(55, 14, 'Pasta Aglio Olio', 48000.00, NULL, NULL),
(56, 14, 'Coffee', 25000.00, NULL, NULL),
(57, 15, 'Es Kopi Susu', 22000.00, NULL, NULL),
(58, 15, 'Roti Bakar', 25000.00, NULL, NULL),
(59, 15, 'French Fries', 20000.00, NULL, NULL),
(60, 15, 'Mie Rebus', 18000.00, NULL, NULL),
(61, 16, 'Chicken Steak', 60000.00, NULL, NULL),
(62, 16, 'Pasta Bolognese', 48000.00, NULL, NULL),
(63, 16, 'Latte', 32000.00, NULL, NULL),
(64, 16, 'Cheesecake', 35000.00, NULL, NULL),
(65, 17, 'Kopi Aren', 28000.00, NULL, NULL),
(66, 17, 'Croissant', 32000.00, NULL, NULL),
(67, 17, 'Chicken Rice', 45000.00, NULL, NULL),
(68, 17, 'Brownies', 25000.00, NULL, NULL),
(69, 18, 'Steak Tenderloin', 90000.00, NULL, NULL),
(70, 18, 'Spaghetti', 50000.00, NULL, NULL),
(71, 18, 'Soup', 30000.00, NULL, NULL),
(72, 18, 'Lemon Tea', 18000.00, NULL, NULL),
(73, 19, 'BBQ Platter', 120000.00, NULL, NULL),
(74, 19, 'Hot Chocolate', 25000.00, NULL, NULL),
(75, 19, 'Nasi Goreng', 40000.00, NULL, NULL),
(76, 19, 'Sosis Bakar', 30000.00, NULL, NULL),
(77, 20, 'Chicken Katsu', 50000.00, NULL, NULL),
(78, 20, 'Beef Rice Bowl', 58000.00, NULL, NULL),
(79, 20, 'Matcha Latte', 32000.00, NULL, NULL),
(80, 20, 'French Fries', 22000.00, NULL, NULL),
(81, 21, 'Kopi Susu Toko Djawa', 28000.00, NULL, NULL),
(82, 21, 'Americano', 25000.00, NULL, NULL),
(83, 21, 'Croissant', 30000.00, NULL, NULL),
(84, 21, 'Pain Au Chocolat', 32000.00, NULL, NULL),
(85, 22, 'Es Kopi Susu', 26000.00, NULL, NULL),
(86, 22, 'Matcha Latte', 32000.00, NULL, NULL),
(87, 22, 'Chicken Sandwich', 35000.00, NULL, NULL),
(88, 22, 'French Fries', 22000.00, NULL, NULL),
(89, 23, 'Cappuccino', 32000.00, NULL, NULL),
(90, 23, 'Chicken Steak', 55000.00, NULL, NULL),
(91, 23, 'Pasta Carbonara', 48000.00, NULL, NULL),
(92, 23, 'Cheesecake', 38000.00, NULL, NULL),
(93, 24, 'Smoked Beef Sandwich', 42000.00, NULL, NULL),
(94, 24, 'Chicken Sandwich', 40000.00, NULL, NULL),
(95, 24, 'French Fries', 22000.00, NULL, NULL),
(96, 24, 'Ice Coffee', 28000.00, NULL, NULL),
(97, 25, 'Kopi Tubruk', 22000.00, NULL, NULL),
(98, 25, 'Kopi Susu', 25000.00, NULL, NULL),
(99, 25, 'Pisang Bakar', 18000.00, NULL, NULL),
(100, 25, 'Roti Bakar', 22000.00, NULL, NULL),
(101, 26, 'Cafe Latte', 35000.00, NULL, NULL),
(102, 26, 'Flat White', 33000.00, NULL, NULL),
(103, 26, 'Croissant', 30000.00, NULL, NULL),
(104, 26, 'Cheesecake', 38000.00, NULL, NULL),
(105, 27, 'Kaya Toast', 28000.00, NULL, NULL),
(106, 27, 'Kopi O', 22000.00, NULL, NULL),
(107, 27, 'Soft Boiled Egg', 18000.00, NULL, NULL),
(108, 27, 'Teh Tarik', 25000.00, NULL, NULL),
(109, 28, 'Americano', 25000.00, NULL, NULL),
(110, 28, 'Cafe Latte', 32000.00, NULL, NULL),
(111, 28, 'Brownies', 28000.00, NULL, NULL),
(112, 28, 'French Fries', 22000.00, NULL, NULL),
(113, 29, 'Sate Ayam', 38000.00, NULL, NULL),
(114, 29, 'Sate Kambing', 50000.00, NULL, NULL),
(115, 29, 'Lontong', 10000.00, NULL, NULL),
(116, 29, 'Es Teh Manis', 8000.00, NULL, NULL),
(117, 30, 'Sate Ayam', 35000.00, NULL, NULL),
(118, 30, 'Sate Sapi', 48000.00, NULL, NULL),
(119, 30, 'Nasi Putih', 7000.00, NULL, NULL),
(120, 30, 'Es Jeruk', 10000.00, NULL, NULL),
(121, 31, 'Cuanki Original', 22000.00, NULL, NULL),
(122, 31, 'Bakso Cuanki', 25000.00, NULL, NULL),
(123, 31, 'Batagor', 22000.00, NULL, NULL),
(124, 31, 'Es Teh', 7000.00, NULL, NULL),
(125, 32, 'Mie Rica Ayam', 32000.00, NULL, NULL),
(126, 32, 'Mie Rica Baso', 35000.00, NULL, NULL),
(127, 32, 'Pangsit Goreng', 18000.00, NULL, NULL),
(128, 32, 'Es Jeruk', 10000.00, NULL, NULL),
(129, 33, 'Mie Yamin Manis', 30000.00, NULL, NULL),
(130, 33, 'Mie Yamin Asin', 30000.00, NULL, NULL),
(131, 33, 'Bakso', 25000.00, NULL, NULL),
(132, 33, 'Es Teh', 7000.00, NULL, NULL),
(133, 34, 'Bakmi Ayam', 32000.00, NULL, NULL),
(134, 34, 'Bakmi Bakso', 35000.00, NULL, NULL),
(135, 34, 'Pangsit Rebus', 20000.00, NULL, NULL),
(136, 34, 'Es Campur', 18000.00, NULL, NULL),
(137, 35, 'Nasi Bancakan', 30000.00, NULL, NULL),
(138, 35, 'Ayam Goreng', 22000.00, NULL, NULL),
(139, 35, 'Pepes Tahu', 15000.00, NULL, NULL),
(140, 35, 'Es Kelapa', 18000.00, NULL, NULL),
(141, 36, 'Gurame Bakar', 85000.00, NULL, NULL),
(142, 36, 'Nasi Liwet', 35000.00, NULL, NULL),
(143, 36, 'Karedok', 18000.00, NULL, NULL),
(144, 36, 'Es Cendol', 18000.00, NULL, NULL),
(145, 37, 'Ayam Bakar', 45000.00, NULL, NULL),
(146, 37, 'Ikan Gurame', 90000.00, NULL, NULL),
(147, 37, 'Nasi Timbel', 32000.00, NULL, NULL),
(148, 37, 'Es Kelapa Muda', 20000.00, NULL, NULL),
(149, 38, 'Nasi Timbel', 35000.00, NULL, NULL),
(150, 38, 'Ayam Goreng', 28000.00, NULL, NULL),
(151, 38, 'Sop Buntut', 65000.00, NULL, NULL),
(152, 38, 'Es Jeruk', 10000.00, NULL, NULL),
(153, 39, 'Nasi Tutug Oncom', 28000.00, NULL, NULL),
(154, 39, 'Ayam Bakar', 35000.00, NULL, NULL),
(155, 39, 'Pepes Ikan', 30000.00, NULL, NULL),
(156, 39, 'Es Teh', 7000.00, NULL, NULL),
(157, 40, 'Nasi Liwet', 30000.00, NULL, NULL),
(158, 40, 'Ayam Goreng', 25000.00, NULL, NULL),
(159, 40, 'Tahu Tempe', 15000.00, NULL, NULL),
(160, 40, 'Es Jeruk', 10000.00, NULL, NULL),
(161, 41, 'Nasi Liwet', 35000.00, NULL, NULL),
(162, 41, 'Ayam Goreng Kampung', 38000.00, NULL, NULL),
(163, 41, 'Gurame Bakar', 85000.00, NULL, NULL),
(164, 41, 'Es Kelapa Muda', 18000.00, NULL, NULL),
(165, 42, 'Nasi Goreng Seafood', 45000.00, NULL, NULL),
(166, 42, 'Chicken Katsu', 42000.00, NULL, NULL),
(167, 42, 'Beef Rice Bowl', 48000.00, NULL, NULL),
(168, 42, 'Thai Tea', 22000.00, NULL, NULL),
(169, 43, 'Grilled Chicken', 65000.00, NULL, NULL),
(170, 43, 'Spaghetti Carbonara', 55000.00, NULL, NULL),
(171, 43, 'Pizza Margherita', 70000.00, NULL, NULL),
(172, 43, 'Cafe Latte', 35000.00, NULL, NULL),
(173, 44, 'Chicken Steak', 58000.00, NULL, NULL),
(174, 44, 'Fish and Chips', 62000.00, NULL, NULL),
(175, 44, 'Matcha Latte', 35000.00, NULL, NULL),
(176, 44, 'Cheesecake', 38000.00, NULL, NULL),
(177, 45, 'Tenderloin Steak', 98000.00, NULL, NULL),
(178, 45, 'Chicken Cordon Bleu', 72000.00, NULL, NULL),
(179, 45, 'Pasta Aglio Olio', 50000.00, NULL, NULL),
(180, 45, 'Ice Lemon Tea', 18000.00, NULL, NULL),
(181, 46, 'Nasi Goreng Kampung', 38000.00, NULL, NULL),
(182, 46, 'Chicken Wings', 42000.00, NULL, NULL),
(183, 46, 'French Fries', 25000.00, NULL, NULL),
(184, 46, 'Milkshake', 30000.00, NULL, NULL),
(185, 47, 'Sirloin Steak', 95000.00, NULL, NULL),
(186, 47, 'Chicken Steak', 65000.00, NULL, NULL),
(187, 47, 'Spaghetti Bolognese', 52000.00, NULL, NULL),
(188, 47, 'Mojito', 28000.00, NULL, NULL),
(189, 48, 'Beef Burger', 58000.00, NULL, NULL),
(190, 48, 'Fish and Chips', 62000.00, NULL, NULL),
(191, 48, 'Latte', 32000.00, NULL, NULL),
(192, 48, 'Brownies', 30000.00, NULL, NULL),
(193, 49, 'Chicken Rice Bowl', 45000.00, NULL, NULL),
(194, 49, 'Pasta Carbonara', 52000.00, NULL, NULL),
(195, 49, 'Red Velvet Cake', 38000.00, NULL, NULL),
(196, 49, 'Ice Chocolate', 30000.00, NULL, NULL),
(197, 50, 'Chicken Steak', 68000.00, NULL, NULL),
(198, 50, 'Cream Soup', 28000.00, NULL, NULL),
(199, 50, 'Cafe Latte', 35000.00, NULL, NULL),
(200, 50, 'Cheese Cake', 38000.00, NULL, NULL),
(201, 51, 'Croissant', 32000.00, NULL, NULL),
(202, 51, 'Chocolate Bread', 25000.00, NULL, NULL),
(203, 51, 'Cafe Latte', 32000.00, NULL, NULL),
(204, 51, 'Cheesecake', 38000.00, NULL, NULL),
(205, 52, 'Fish and Chips', 65000.00, NULL, NULL),
(206, 52, 'Chicken Steak', 62000.00, NULL, NULL),
(207, 52, 'Matcha Latte', 35000.00, NULL, NULL),
(208, 52, 'Chocolate Cake', 40000.00, NULL, NULL),
(209, 53, 'Beef Burger', 58000.00, NULL, NULL),
(210, 53, 'French Fries', 22000.00, NULL, NULL),
(211, 53, 'Cafe Latte', 32000.00, NULL, NULL),
(212, 53, 'Brown Sugar Coffee', 30000.00, NULL, NULL),
(213, 54, 'Kopi Susu', 25000.00, NULL, NULL),
(214, 54, 'Pisang Bakar', 22000.00, NULL, NULL),
(215, 54, 'Mie Goreng', 30000.00, NULL, NULL),
(216, 54, 'Nasi Liwet', 38000.00, NULL, NULL),
(217, 55, 'Tenderloin Steak', 120000.00, NULL, NULL),
(218, 55, 'Grilled Salmon', 98000.00, NULL, NULL),
(219, 55, 'Pasta Carbonara', 55000.00, NULL, NULL),
(220, 55, 'Cafe Latte', 35000.00, NULL, NULL),
(221, 56, 'Chicken Steak', 62000.00, NULL, NULL),
(222, 56, 'Pizza', 72000.00, NULL, NULL),
(223, 56, 'Milkshake', 32000.00, NULL, NULL),
(224, 56, 'French Fries', 25000.00, NULL, NULL),
(225, 57, 'Nasi Goreng', 38000.00, NULL, NULL),
(226, 57, 'Chicken Wings', 42000.00, NULL, NULL),
(227, 57, 'Ice Coffee', 28000.00, NULL, NULL),
(228, 57, 'Cheesecake', 35000.00, NULL, NULL),
(229, 58, 'Es Kopi Nako', 28000.00, NULL, NULL),
(230, 58, 'Americano', 25000.00, NULL, NULL),
(231, 58, 'Croissant', 30000.00, NULL, NULL),
(232, 58, 'Toast Bread', 25000.00, NULL, NULL),
(233, 59, 'Kopi Kenangan Mantan', 24000.00, NULL, NULL),
(234, 59, 'Americano', 22000.00, NULL, NULL),
(235, 59, 'Hazelnut Latte', 28000.00, NULL, NULL),
(236, 59, 'Roti Bakar', 22000.00, NULL, NULL),
(237, 60, 'Es Kopi Susu Jiwa', 22000.00, NULL, NULL),
(238, 60, 'Americano', 20000.00, NULL, NULL),
(239, 60, 'Toast Bread', 25000.00, NULL, NULL),
(240, 60, 'Brown Sugar Latte', 26000.00, NULL, NULL),
(241, 61, 'Butterscotch Sea Salt Latte', 33000.00, NULL, NULL),
(242, 61, 'Cafe Latte', 30000.00, NULL, NULL),
(243, 61, 'Americano', 25000.00, NULL, NULL),
(244, 61, 'Butter Croissant', 28000.00, NULL, NULL),
(245, 62, 'Caramel Macchiato', 55000.00, NULL, NULL),
(246, 62, 'Java Chip Frappuccino', 62000.00, NULL, NULL),
(247, 62, 'Caffe Latte', 50000.00, NULL, NULL),
(248, 62, 'Blueberry Cheesecake', 45000.00, NULL, NULL),
(249, 63, 'Kopi Luwak', 65000.00, NULL, NULL),
(250, 63, 'Cappuccino', 42000.00, NULL, NULL),
(251, 63, 'Cafe Latte', 40000.00, NULL, NULL),
(252, 63, 'Chocolate Cake', 38000.00, NULL, NULL),
(253, 64, 'Alcapone Donut', 12000.00, NULL, NULL),
(254, 64, 'Avocado Dicaprio', 12000.00, NULL, NULL),
(255, 64, 'J.Cool Yogurt', 28000.00, NULL, NULL),
(256, 64, 'JCoffee Latte', 30000.00, NULL, NULL),
(257, 65, 'Roti Bakar Coklat Keju', 28000.00, NULL, NULL),
(258, 65, 'Roti Bakar Nutella', 32000.00, NULL, NULL),
(259, 65, 'Indomie Rebus', 22000.00, NULL, NULL),
(260, 65, 'Es Teh Manis', 8000.00, NULL, NULL),
(261, 66, 'Sate Ayam', 38000.00, NULL, NULL),
(262, 66, 'Sate Kambing', 50000.00, NULL, NULL),
(263, 66, 'Lontong', 10000.00, NULL, NULL),
(264, 66, 'Es Jeruk', 10000.00, NULL, NULL),
(265, 67, 'Soto Ayam', 28000.00, NULL, NULL),
(266, 67, 'Soto Daging', 35000.00, NULL, NULL),
(267, 67, 'Nasi Putih', 7000.00, NULL, NULL),
(268, 67, 'Es Teh', 7000.00, NULL, NULL),
(269, 68, 'Mie Yamin', 30000.00, NULL, NULL),
(270, 68, 'Bakso Urat', 32000.00, NULL, NULL),
(271, 68, 'Pangsit Rebus', 18000.00, NULL, NULL),
(272, 68, 'Es Campur', 18000.00, NULL, NULL),
(273, 69, 'Cuanki Original', 25000.00, NULL, NULL),
(274, 69, 'Bakso Cuanki', 28000.00, NULL, NULL),
(275, 69, 'Siomay', 22000.00, NULL, NULL),
(276, 69, 'Es Teh', 7000.00, NULL, NULL),
(277, 70, 'Pempek Kapal Selam', 35000.00, NULL, NULL),
(278, 70, 'Pempek Lenjer', 28000.00, NULL, NULL),
(279, 70, 'Tekwan', 30000.00, NULL, NULL),
(280, 70, 'Es Kacang Merah', 18000.00, NULL, NULL),
(281, 71, 'Nasi Timbel', 32000.00, NULL, NULL),
(282, 71, 'Ayam Goreng', 28000.00, NULL, NULL),
(283, 71, 'Pepes Ikan', 35000.00, NULL, NULL),
(284, 71, 'Es Kelapa', 18000.00, NULL, NULL),
(285, 72, 'Nasi Liwet', 35000.00, NULL, NULL),
(286, 72, 'Ayam Bakar', 42000.00, NULL, NULL),
(287, 72, 'Karedok', 18000.00, NULL, NULL),
(288, 72, 'Es Cendol', 18000.00, NULL, NULL),
(289, 73, 'Iga Bakar', 75000.00, NULL, NULL),
(290, 73, 'Iga Penyet', 72000.00, NULL, NULL),
(291, 73, 'Nasi Putih', 7000.00, NULL, NULL),
(292, 73, 'Es Jeruk', 10000.00, NULL, NULL),
(293, 74, 'Ayam Bakar', 38000.00, NULL, NULL),
(294, 74, 'Gurame Goreng', 85000.00, NULL, NULL),
(295, 74, 'Nasi Liwet', 32000.00, NULL, NULL),
(296, 74, 'Es Kelapa Muda', 18000.00, NULL, NULL),
(297, 75, 'Ayam Goreng Sambal Hejo', 35000.00, NULL, NULL),
(298, 75, 'Nasi Timbel', 32000.00, NULL, NULL),
(299, 75, 'Tahu Tempe', 15000.00, NULL, NULL),
(300, 75, 'Es Teh', 7000.00, NULL, NULL),
(301, 76, 'Ayam Goreng', 30000.00, NULL, NULL),
(302, 76, 'Ikan Goreng', 38000.00, NULL, NULL),
(303, 76, 'Sayur Asem', 12000.00, NULL, NULL),
(304, 76, 'Es Jeruk', 10000.00, NULL, NULL),
(305, 77, 'Kopi Susu Aren', 28000.00, NULL, NULL),
(306, 77, 'Americano', 25000.00, NULL, NULL),
(307, 77, 'Brownies', 28000.00, NULL, NULL),
(308, 77, 'French Fries', 22000.00, NULL, NULL),
(309, 78, 'Chicken Steak', 58000.00, NULL, NULL),
(310, 78, 'Pasta Carbonara', 52000.00, NULL, NULL),
(311, 78, 'Cafe Latte', 32000.00, NULL, NULL),
(312, 78, 'Red Velvet Cake', 38000.00, NULL, NULL),
(313, 79, 'Nasi Goreng Kampung', 38000.00, NULL, NULL),
(314, 79, 'Chicken Wings', 42000.00, NULL, NULL),
(315, 79, 'Es Kopi Susu', 28000.00, NULL, NULL),
(316, 79, 'French Fries', 22000.00, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_07_31_000002_create_user_preferences_table', 1),
(5, '2026_07_31_000003_create_user_notifications_table', 1),
(6, '2026_07_31_000004_create_pesan_table', 1),
(7, '2026_07_31_000005_create_wisata_table', 1),
(8, '2026_07_31_000006_create_penginapan_table', 1),
(9, '2026_07_31_000007_create_kuliner_table', 1),
(10, '2026_07_31_000008_create_menu_table', 1),
(11, '2026_07_31_000010_create_ratings_table', 1),
(12, '2026_07_31_000011_create_travel_plans_table', 1),
(13, '2026_07_31_000012_create_detail_table', 1),
(14, '2026_07_31_000013_create_appeals_table', 1),
(15, '2026_07_31_000014_create_jadwal_table', 1),
(16, '2026_07_31_000015_create_jarak_table', 1),
(17, '2026_05_31_054355_create_kategori_table', 2),
(18, '2026_05_31_054531_create_destinasi_table', 2),
(19, '2026_05_31_054540_create_destinasi_kategori_table', 2),
(20, '2026_05_31_054547_create_destinasi_images_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penginapan`
--

CREATE TABLE `penginapan` (
  `id_penginapan` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` text DEFAULT NULL,
  `deskripsi` longtext DEFAULT NULL,
  `kota` varchar(100) DEFAULT NULL,
  `provinsi` varchar(100) DEFAULT NULL,
  `transportasi` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `harga` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tipe_penginapan` varchar(255) DEFAULT NULL,
  `jam_buka` time DEFAULT NULL,
  `jam_tutup` time DEFAULT NULL,
  `hari_operasional` varchar(100) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `kategori_penginapan` varchar(255) DEFAULT NULL,
  `trend` varchar(20) NOT NULL DEFAULT 'Populer',
  `budget` varchar(20) DEFAULT NULL,
  `fasilitas` text DEFAULT NULL,
  `fitur_cbf` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `penginapan`
--

INSERT INTO `penginapan` (`id_penginapan`, `nama`, `alamat`, `deskripsi`, `kota`, `provinsi`, `transportasi`, `gambar`, `harga`, `tipe_penginapan`, `jam_buka`, `jam_tutup`, `hari_operasional`, `latitude`, `longitude`, `kategori_penginapan`, `trend`, `budget`, `fasilitas`, `fitur_cbf`, `created_at`, `updated_at`) VALUES
(1, 'La Boheme, Rooms and Coffee', 'Jl. Setiabudi Barat No. 18', 'Hotel bergaya tropis bohemian nan hangat, memiliki lobi kafe estetik dengan sajian croffle artisan, dan area rooftop memukau.', 'Jakarta Selatan', 'DKI Jakarta', 'Mobil, Motor, MRT, TransJakarta', 'https://blog.bookingtogo.com/wp-content/uploads/2022/12/La-Boheme-Rooms-and-Coffee-Setiabudi.jpg', 600000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.20667200, 106.82479300, 'Boutique Hotel', 'Hidden Gem', 'sedang', 'Wifi, Restoran, Kamar Mandi Dalam, AC, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel boutique hidden gem sedang jakarta selatan dki jakarta mobil motor mrt transjakarta wifi restoran kamar mandi dalam ac resepsionis 24 jam parkir air panas sarapan tv hotel bergaya tropis bohemian nan hangat memiliki lobi kafe estetik dengan sajian croffle artisan dan area rooftop memukau', NULL, NULL),
(2, 'Posto Dormire Hotel', 'Jl. Dr. Susilo Raya No. 3, Grogol', 'Akomodasi industrial-chic dengan dinding bata mentah', 'Jakarta Barat', 'DKI Jakarta', 'Mobil, Motor, KRL', 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/512343029.jpg?k=e96b5c394be55658f56793814dc35744badf2684bc24ba35ef655b2c7c87b239&o=&hp=1', 550000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.16557600, 106.79730600, 'Business Hotel', 'Hidden Gem', 'sedang', 'Parkir, Wi-Fi, AC, TV, Kamar Mandi, Resepsionis 24 Jam, Pembayaran Non Tunai, Pembayaran Tunai', 'hotel business hidden gem sedang jakarta barat dki jakarta mobil motor krl parkir wi fi ac tv kamar mandi resepsionis 24 jam pembayaran non tunai pembayaran tunai akomodasi industrial chic dengan dinding bata mentah', NULL, NULL),
(3, 'House of Tugu Hotel', 'Kawasan Kota Tua (Batavia)', 'Museum hidup yang merestorasi era kejayaan kolonial, sangat romantis dengan menu otentik Rijsttafel ala kerajaan masa lalu.', 'Jakarta Barat', 'DKI Jakarta', 'Mobil, Motor, KRL, TransJakarta', 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/604035241.jpg?k=5adad541f4a14222b4960de10a5c1f88e747ebd4f5b1fa3f8e44d6299639c674&o=&hp=1', 2500000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.13480800, 106.81179700, 'Luxury Heritage', 'Hidden Gem', 'mahal', 'Wifi, Restoran, Kamar Mandi Dalam, AC, Kolam Renang, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel luxury heritage hidden gem mahal jakarta barat dki jakarta mobil motor krl transjakarta wifi restoran kamar mandi dalam ac kolam renang resepsionis 24 jam parkir air panas sarapan tv museum hidup yang merestorasi era kejayaan kolonial sangat romantis dengan menu otentik rijsttafel ala kerajaan masa lalu', NULL, NULL),
(4, 'Kosenda Hotel', 'Jl. K.H. Wahid Hasyim No. 127', 'Fusi arsitektur mid-century dan budaya Betawi yang nyentrik, menawarkan Waha Kitchen (masakan Peranakan) dan Awan Lounge.', 'Jakarta Pusat', 'DKI Jakarta', 'Mobil, Motor, MRT', 'https://tse2.mm.bing.net/th/id/OIP.poymMV9wU1yukX5yf1Li9gHaJy?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 850000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.18691300, 106.82200400, 'Art Boutique', 'Hidden Gem', 'mahal', 'Wifi, Restoran, Kamar Mandi Dalam, AC, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel art boutique hidden gem mahal jakarta pusat dki jakarta mobil motor mrt wifi restoran kamar mandi dalam ac resepsionis 24 jam parkir air panas sarapan tv fusi arsitektur mid century dan budaya betawi yang nyentrik menawarkan waha kitchen masakan peranakan dan awan lounge', NULL, NULL),
(5, 'Rumanami Residence', 'Jl. Benda No. 39, Kemang', 'Bangunan bata merah silindris bergaya Victoria yang artistik, memiliki rooftop sejuk dengan menu andalan wood-fire pizza.', 'Jakarta Selatan', 'DKI Jakarta', 'Mobil, Motor', 'https://tse3.mm.bing.net/th/id/OIP.P0vrwDZXmwkSeblLnZosJgHaFj?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 650000.00, 'Apartemen/Residence', '00:00:00', '23:59:00', 'Setiap Hari', -6.27441200, 106.81722500, 'Residence', 'Hidden Gem', 'mahal', 'Wifi, Restoran, Kamar Mandi Dalam, AC, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'apartemen residence hidden gem mahal jakarta selatan dki jakarta mobil motor wifi restoran kamar mandi dalam ac resepsionis 24 jam parkir air panas sarapan tv bangunan bata merah silindris bergaya victoria yang artistik memiliki rooftop sejuk dengan menu andalan wood fire pizza', NULL, NULL),
(6, 'White Tree Residence', 'Jl. Asem II No. 19/18A, Cipete', 'Mengusung konsep loft minimalis dengan struktur mezzanine dan dapur mini', 'Jakarta Selatan', 'DKI Jakarta', 'Mobil, Motor, MRT', 'https://linebank.co.id/blog/wp-content/uploads/2023/06/White-Tree-Residence-LB-Blog-jpg.webp', 700000.00, 'Apartemen/Residence', '00:00:00', '23:59:00', 'Setiap Hari', -6.27212500, 106.80379200, 'Serviced Residence', 'Hidden Gem', 'mahal', 'Parkir, Wi-Fi, AC, TV, Kamar Mandi, Resepsionis 24 Jam, Pembayaran Non Tunai, Pembayaran Tunai', 'apartemen residence serviced hidden gem mahal jakarta selatan dki jakarta mobil motor mrt parkir wi fi ac tv kamar mandi resepsionis 24 jam pembayaran non tunai pembayaran tunai mengusung konsep loft minimalis dengan struktur mezzanine dan dapur mini', NULL, NULL),
(7, 'Nostoi', 'Area Kuningan', 'Properti sangat tersembunyi yang mengaplikasikan zen minimalisme Jepang', 'Jakarta Selatan', 'DKI Jakarta', 'Mobil, Motor', 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/220491620.jpg?k=e50417921d5c4d2f7f8e3845587956276956fe9c2b2e0774319212f5484d9f00&o=&hp=1', 650000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.21500100, 106.82569600, 'Boutique Hotel', 'Hidden Gem', 'mahal', 'Parkir, Wi-Fi, AC, TV, Kamar Mandi, Resepsionis 24 Jam, Pembayaran Non Tunai, Pembayaran Tunai', 'hotel boutique hidden gem mahal jakarta selatan dki jakarta mobil motor parkir wi fi ac tv kamar mandi resepsionis 24 jam pembayaran non tunai pembayaran tunai properti sangat tersembunyi yang mengaplikasikan zen minimalisme jepang', NULL, NULL),
(8, 'Sawana Suites', 'Jl. Danau Limboto No. B1 45, Benhil', 'Akomodasi terjangkau di kawasan residensial padat, memiliki rooftop kecil untuk bersantai menikmati gemerlap kota malam hari.', 'Jakarta Pusat', 'DKI Jakarta', 'Mobil, Motor, MRT', 'https://tse4.mm.bing.net/th/id/OIP.Mg6Q5N5EWSuoGNWENekHwwHaHs?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 460000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.20843400, 106.80628800, 'Suites / Budget', 'Hidden Gem', 'sedang', 'Wifi, Restoran, Kamar Mandi Dalam, AC, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel suites budget hidden gem sedang jakarta pusat dki jakarta mobil motor mrt wifi restoran kamar mandi dalam ac resepsionis 24 jam parkir air panas sarapan tv akomodasi terjangkau di kawasan residensial padat memiliki rooftop kecil untuk bersantai menikmati gemerlap kota malam hari', NULL, NULL),
(9, 'Liberta Hub Blok M Penthouse', 'Kawasan Blok M', 'Menyediakan unit kamar penthouse lapang bergaya kontemporer dengan fasilitas bathtub elegan yang sangat instagrammable.', 'Jakarta Selatan', 'DKI Jakarta', 'Mobil, Motor, MRT', 'https://tse1.mm.bing.net/th/id/OIP.apmoDI9gWR-eEPCgjR393gHaEr?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 500000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.24271100, 106.80252500, 'Hub / Penthouse', 'Terkenal', 'sedang', 'Wifi, Restoran, Kamar Mandi Dalam, AC, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel hub penthouse terkenal sedang jakarta selatan dki jakarta mobil motor mrt wifi restoran kamar mandi dalam ac resepsionis 24 jam parkir air panas sarapan tv menyediakan unit kamar penthouse lapang bergaya kontemporer dengan fasilitas bathtub elegan yang sangat instagrammable', NULL, NULL),
(10, 'The Orient Jakarta', 'Jl. Jend. Sudirman No. 36', 'Mahakarya desainer Bill Bensley', 'Jakarta Pusat', 'DKI Jakarta', 'Mobil, Motor, MRT, TransJakarta', 'https://tse1.mm.bing.net/th/id/OIP.dRCQaitNotVnYiTHMnwv1QHaFj?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 3000000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.21537500, 106.81753000, 'Luxury Hotel', 'Terkenal', 'mahal', 'Parkir, Wi-Fi, AC, TV, Kamar Mandi, Resepsionis 24 Jam, Pembayaran Non Tunai, Pembayaran Tunai', 'hotel luxury terkenal mahal jakarta pusat dki jakarta mobil motor mrt transjakarta parkir wi fi ac tv kamar mandi resepsionis 24 jam pembayaran non tunai pembayaran tunai mahakarya desainer bill bensley', NULL, NULL),
(11, 'Artotel Casa Kuningan', 'Kawasan Kuningan', 'Menciptakan anomali visual di tengah kota diplomatik melalui fasilitas area kolam renang yang dikelilingi pasir pantai putih.', 'Jakarta Selatan', 'DKI Jakarta', 'Mobil, Motor', 'https://tse3.mm.bing.net/th/id/OIP.w6pAaX10_EFcg-IJU9p0oAHaE8?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 850000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.23470300, 106.82703900, 'Boutique Hotel', 'Terkenal', 'mahal', 'Wifi, Restoran, Kamar Mandi Dalam, AC, Kolam Renang, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel boutique terkenal mahal jakarta selatan dki jakarta mobil motor wifi restoran kamar mandi dalam ac kolam renang resepsionis 24 jam parkir air panas sarapan tv menciptakan anomali visual di tengah kota diplomatik melalui fasilitas area kolam renang yang dikelilingi pasir pantai putih', NULL, NULL),
(12, 'Park 5 Simatupang', 'Jl. Intan, Cilandak', 'Properti bernuansa alam kayu dan rimbun pepohonan, sangat homey, dengan Wyl\'s Kitchen yang menyajikan masakan fusi lokal.', 'Jakarta Selatan', 'DKI Jakarta', 'Mobil, Motor', 'https://tse1.mm.bing.net/th/id/OIP.nIIewrJ9QVRxn9FQDsXewgHaEt?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 900000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.29607400, 106.79899400, 'Boutique Hotel', 'Hidden Gem', 'mahal', 'Wifi, Restoran, Kamar Mandi Dalam, AC, Kolam Renang, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel boutique hidden gem mahal jakarta selatan dki jakarta mobil motor wifi restoran kamar mandi dalam ac kolam renang resepsionis 24 jam parkir air panas sarapan tv properti bernuansa alam kayu dan rimbun pepohonan sangat homey dengan wyl s kitchen yang menyajikan masakan fusi lokal', NULL, NULL),
(13, 'The Gunawarman', 'Jl. Gunawarman, Senopati', 'Arsitektur rumah bangsawan Eropa klasik berlapis panel kayu maskulin', 'Jakarta Selatan', 'DKI Jakarta', 'Mobil, Motor', 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/77274124.jpg?k=12bc0a68f3cdbf06f38115c644972990c4f228ec56e3c546d032ec1b8a37091c&o=&hp=1', 2800000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.23144800, 106.80791300, 'Boutique Hotel', 'Hidden Gem', 'mahal', 'Parkir, Wi-Fi, AC, TV, Kamar Mandi, Resepsionis 24 Jam, Pembayaran Non Tunai, Pembayaran Tunai', 'hotel boutique hidden gem mahal jakarta selatan dki jakarta mobil motor parkir wi fi ac tv kamar mandi resepsionis 24 jam pembayaran non tunai pembayaran tunai arsitektur rumah bangsawan eropa klasik berlapis panel kayu maskulin', NULL, NULL),
(14, 'The Hermitage', 'Jl. Cilacap No. 1, Menteng', 'Restorasi bangunan Art Deco 1920-an yang elegan', 'Jakarta Pusat', 'DKI Jakarta', 'Mobil, Motor, KRL', 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/26/72/3b/5c/the-hermitage-jakarta.jpg?w=700&h=-1&s=1', 2200000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.19809300, 106.83858000, 'Luxury Hotel', 'Ikonik', 'mahal', 'Parkir, Wi-Fi, AC, TV, Kamar Mandi, Resepsionis 24 Jam, Pembayaran Non Tunai, Pembayaran Tunai', 'hotel luxury ikonik mahal jakarta pusat dki jakarta mobil motor krl parkir wi fi ac tv kamar mandi resepsionis 24 jam pembayaran non tunai pembayaran tunai restorasi bangunan art deco 1920 an yang elegan', NULL, NULL),
(15, 'The Dharmawangsa', 'Jl. Brawijaya Raya No. 26', 'Episentrum kemewahan tradisi Jawa dengan taman tropis masif dan privasi absolut', 'Jakarta Selatan', 'DKI Jakarta', 'Mobil, Motor', 'https://tse4.mm.bing.net/th/id/OIP.K7oGLydPna-kHMzwtywm3gHaEL?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 4500000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.25292600, 106.80584600, 'Luxury Hotel', 'Ikonik', 'mahal', 'Parkir, Wi-Fi, AC, TV, Kamar Mandi, Resepsionis 24 Jam, Pembayaran Non Tunai, Pembayaran Tunai', 'hotel luxury ikonik mahal jakarta selatan dki jakarta mobil motor parkir wi fi ac tv kamar mandi resepsionis 24 jam pembayaran non tunai pembayaran tunai episentrum kemewahan tradisi jawa dengan taman tropis masif dan privasi absolut', NULL, NULL),
(16, 'Menteng Park Jakarta', 'Kawasan Menteng', 'Apartemen butik bersuasana praktis namun berkelas, dilengkapi dapur lengkap, fasilitas taman gantung, dan kolam renang outdoor.', 'Jakarta Pusat', 'DKI Jakarta', 'Mobil, Motor', 'https://tse4.mm.bing.net/th/id/OIP.XSNS1GBHXzmqzaeuEKvOEAHaFj?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 750000.00, 'Apartemen/Residence', '00:00:00', '23:59:00', 'Setiap Hari', -6.19168800, 106.83932100, 'Serviced Apt', 'Terkenal', 'mahal', 'Wifi, Kamar Mandi Dalam, AC, Kolam Renang, Resepsionis 24 Jam, Parkir, Air Panas, TV', 'apartemen residence serviced apt terkenal mahal jakarta pusat dki jakarta mobil motor wifi kamar mandi dalam ac kolam renang resepsionis 24 jam parkir air panas tv apartemen butik bersuasana praktis namun berkelas dilengkapi dapur lengkap fasilitas taman gantung dan kolam renang outdoor', NULL, NULL),
(17, 'Sunlake Waterfront', 'Jl. Danau Permai Raya, Sunter', 'Akomodasi yang mengeksploitasi pemandangan Danau Sunter, menghadirkan nuansa resor liburan dengan sajian otentik India dan Korea.', 'Jakarta Utara', 'DKI Jakarta', 'Mobil, Motor', 'https://tse4.mm.bing.net/th/id/OIP.4ZgM_sVbyqP2uiN_eLvPUgHaE7?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 850000.00, 'Resort', '00:00:00', '23:59:00', 'Setiap Hari', -6.14685000, 106.87980800, 'Resort', 'Terkenal', 'mahal', 'Wifi, Restoran, Kamar Mandi Dalam, AC, Kolam Renang, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'resort terkenal mahal jakarta utara dki jakarta mobil motor wifi restoran kamar mandi dalam ac kolam renang resepsionis 24 jam parkir air panas sarapan tv akomodasi yang mengeksploitasi pemandangan danau sunter menghadirkan nuansa resor liburan dengan sajian otentik india dan korea', NULL, NULL),
(18, 'Yello Hotel Harmoni', 'Jl. Hayam Wuruk No. 6', 'Hotel kasual dengan warna neon cerah yang dipenuhi estetika street art', 'Jakarta Pusat', 'DKI Jakarta', 'Mobil, Motor, TransJakarta', 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/116406034.jpg?k=4907a8b47589142f47b0d412306a078f12509ff8a5d4bcf122f085903ccc60cd&o=&hp=1', 600000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.16405500, 106.82140700, 'Budget Hotel', 'Terkenal', 'sedang', 'Parkir, Wi-Fi, AC, TV, Kamar Mandi, Resepsionis 24 Jam, Pembayaran Non Tunai, Pembayaran Tunai', 'hotel budget terkenal sedang jakarta pusat dki jakarta mobil motor transjakarta parkir wi fi ac tv kamar mandi resepsionis 24 jam pembayaran non tunai pembayaran tunai hotel kasual dengan warna neon cerah yang dipenuhi estetika street art', NULL, NULL),
(19, 'Sakura Terrace', 'Jl. Taman Setia Budi II No. 45', 'Guesthouse manajemen Jepang yang mendedikasikan diri pada efisiensi tata ruang mikro, kebersihan, dan kesunyian absolut.', 'Jakarta Selatan', 'DKI Jakarta', 'Mobil, Motor, MRT', 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/240320979.jpg?k=55efa3e46e9e29814cc634f1785489f9972473f91be947ca73c9e2c1833a4df8&o=', 500000.00, 'Guesthouse', '00:00:00', '23:59:00', 'Setiap Hari', -6.20956800, 106.82704500, 'Premium Guesthouse', 'Hidden Gem', 'sedang', 'Wifi, Kamar Mandi Dalam, AC, Resepsionis 24 Jam, Parkir, Air Panas, TV', 'guesthouse premium hidden gem sedang jakarta selatan dki jakarta mobil motor mrt wifi kamar mandi dalam ac resepsionis 24 jam parkir air panas tv guesthouse manajemen jepang yang mendedikasikan diri pada efisiensi tata ruang mikro kebersihan dan kesunyian absolut', NULL, NULL),
(20, 'Hotel Monopoli', 'Jl. Taman Kemang No. 12', 'Destinasi staycation favorit di Kemang yang menggabungkan gaya retro-industrial, rooftop pool interaktif, dan kelab bawah tanah.', 'Jakarta Selatan', 'DKI Jakarta', 'Mobil, Motor', 'https://tse1.mm.bing.net/th/id/OIP.gXMabyiCdgjardKL9jNt5QHaE8?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 800000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.25596300, 106.81014300, 'Lifestyle Boutique', 'Terkenal', 'mahal', 'Wifi, Restoran, Kamar Mandi Dalam, AC, Kolam Renang, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel lifestyle boutique terkenal mahal jakarta selatan dki jakarta mobil motor wifi restoran kamar mandi dalam ac kolam renang resepsionis 24 jam parkir air panas sarapan tv destinasi staycation favorit di kemang yang menggabungkan gaya retro industrial rooftop pool interaktif dan kelab bawah tanah', NULL, NULL),
(21, 'Liberta Hotel Kemang', 'Jl. Kemang 1 No. 6', 'Opsi hemat bergaya kekinian di sentra Kemang, memprioritaskan desain interior modern, kelengkapan fasilitas, dan kids club.', 'Jakarta Selatan', 'DKI Jakarta', 'Mobil, Motor', 'https://ak-d.tripcdn.com/images/220i0s000000hihyw1305_R_960_660_R5_D.jpg', 450000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.25611900, 106.81230300, '3-Star Hotel', 'Terkenal', 'sedang', 'Wifi, Restoran, Kamar Mandi Dalam, AC, Kolam Renang, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel 3 star terkenal sedang jakarta selatan dki jakarta mobil motor wifi restoran kamar mandi dalam ac kolam renang resepsionis 24 jam parkir air panas sarapan tv opsi hemat bergaya kekinian di sentra kemang memprioritaskan desain interior modern kelengkapan fasilitas dan kids club', NULL, NULL),
(22, 'Morrissey Hotel', 'Jl. K.H. Wahid Hasyim', 'Interpretasi desain loft ala New York dengan beton ekspos', 'Jakarta Pusat', 'DKI Jakarta', 'Mobil, Motor, MRT', 'https://media-cdn.tripadvisor.com/media/photo-s/31/cd/e7/14/caption.jpg', 1100000.00, 'Apartemen/Residence', '00:00:00', '23:59:00', 'Setiap Hari', -6.18704800, 106.82936600, 'Serviced Residence', 'Terkenal', 'mahal', 'Parkir, Wi-Fi, AC, TV, Kamar Mandi, Resepsionis 24 Jam, Pembayaran Non Tunai, Pembayaran Tunai', 'apartemen residence serviced terkenal mahal jakarta pusat dki jakarta mobil motor mrt parkir wi fi ac tv kamar mandi resepsionis 24 jam pembayaran non tunai pembayaran tunai interpretasi desain loft ala new york dengan beton ekspos', NULL, NULL),
(23, 'Hotel Gran Mahakam', 'Jl. Mahakam I No. 6, Blok M', 'Berdiri simetris sebagai lambang keanggunan Eropa di Blok M', 'Jakarta Selatan', 'DKI Jakarta', 'Mobil, Motor, MRT', 'https://tse2.mm.bing.net/th/id/OIP.Ur0xmYkml92fSNZbFhWFugHaE7?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 1500000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.24428500, 106.79588900, 'Business Hotel', 'Ikonik', 'mahal', 'Parkir, Wi-Fi, AC, TV, Kamar Mandi, Resepsionis 24 Jam, Pembayaran Non Tunai, Pembayaran Tunai', 'hotel business ikonik mahal jakarta selatan dki jakarta mobil motor mrt parkir wi fi ac tv kamar mandi resepsionis 24 jam pembayaran non tunai pembayaran tunai berdiri simetris sebagai lambang keanggunan eropa di blok m', NULL, NULL),
(24, '25hours The Oddbird', 'District 8 SCBD', 'Merayakan eksentrisitas dan warna-warni kontemporer, mendaur ulang material vintage, dan berfokus pada kuliner Amerika Selatan.', 'Jakarta Selatan', 'DKI Jakarta', 'Mobil, Motor, MRT', 'https://travellingindonesia.com/wp-content/uploads/2024/11/25Hours-Hotel-Jakarta-The-Oddbird-.jpeg', 2500000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.22950200, 106.80711800, 'Lifestyle Luxury', 'Hidden Gem', 'mahal', 'Wifi, Restoran, Kamar Mandi Dalam, AC, Kolam Renang, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel lifestyle luxury hidden gem mahal jakarta selatan dki jakarta mobil motor mrt wifi restoran kamar mandi dalam ac kolam renang resepsionis 24 jam parkir air panas sarapan tv merayakan eksentrisitas dan warna warni kontemporer mendaur ulang material vintage dan berfokus pada kuliner amerika selatan', NULL, NULL),
(25, 'Oakwood Suites', 'Setiabudi, Kuningan', 'Apartemen servis megah yang menyembunyikan fasilitas pantai pasir putih di kolam renangnya', 'Jakarta Selatan', 'DKI Jakarta', 'Mobil, Motor, MRT', 'https://media-cdn.tripadvisor.com/media/photo-s/12/d7/93/8d/oakwood-suites-la-maison.jpg', 1400000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.20722100, 106.82751600, 'Serviced Apartment', 'Terkenal', 'mahal', 'Parkir, Wi-Fi, AC, TV, Kamar Mandi, Resepsionis 24 Jam, Pembayaran Non Tunai, Pembayaran Tunai', 'hotel serviced apartment terkenal mahal jakarta selatan dki jakarta mobil motor mrt parkir wi fi ac tv kamar mandi resepsionis 24 jam pembayaran non tunai pembayaran tunai apartemen servis megah yang menyembunyikan fasilitas pantai pasir putih di kolam renangnya', NULL, NULL),
(26, 'Grand Tropic Suites', 'Jl. Letjen S. Parman, Grogol', 'Lanskap eksterior properti ini dirancang layaknya hutan tropis yang lebat, melingkupi fasilitas laguna buatan ramah keluarga.', 'Jakarta Barat', 'DKI Jakarta', 'Mobil, Motor, TransJakarta', 'https://pix10.agoda.net/hotelImages/111/111546/111546_16090611330046130158.jpg?s=1024x768', 650000.00, 'Resort', '00:00:00', '23:59:00', 'Setiap Hari', -6.17140500, 106.78800000, 'Resort Suites', 'Terkenal', 'mahal', 'Wifi, Restoran, Kamar Mandi Dalam, AC, Kolam Renang, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'resort suites terkenal mahal jakarta barat dki jakarta mobil motor transjakarta wifi restoran kamar mandi dalam ac kolam renang resepsionis 24 jam parkir air panas sarapan tv lanskap eksterior properti ini dirancang layaknya hutan tropis yang lebat melingkupi fasilitas laguna buatan ramah keluarga', NULL, NULL),
(27, 'Wonderloft Hostel', 'Kawasan Kota Tua', 'Akomodasi backpacker ikonik berfasad warna ceria', 'Jakarta Barat', 'DKI Jakarta', 'KRL, TransJakarta', 'https://tse2.mm.bing.net/th/id/OIP.BuLnfgVMrzGs79LOJ2jIRQHaFj?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 150000.00, 'Hostel', '00:00:00', '23:59:00', 'Setiap Hari', -6.13649200, 106.81254800, 'Backpacker Hostel', 'Hidden Gem', 'murah', 'Parkir, Wi-Fi, AC, TV, Kamar Mandi, Resepsionis 24 Jam, Pembayaran Non Tunai, Pembayaran Tunai', 'hostel backpacker hidden gem murah jakarta barat dki jakarta krl transjakarta parkir wi fi ac tv kamar mandi resepsionis 24 jam pembayaran non tunai pembayaran tunai akomodasi backpacker ikonik berfasad warna ceria', NULL, NULL),
(28, 'Konko Hostel Jakarta', 'Jl. Kebon Sirih', 'Merombak definisi hostel dengan kapsul privasi (pod) bergaya industrial rapi dan kafe yang meracik kopi berkualitas tinggi.', 'Jakarta Pusat', 'DKI Jakarta', 'Mobil, Motor, KRL', 'https://media-cdn.tripadvisor.com/media/photo-p/13/ee/69/01/p-20180730-140827-vhdr.jpg', 180000.00, 'Hostel', '00:00:00', '23:59:00', 'Setiap Hari', -6.18289300, 106.83292500, 'Premium Hostel', 'Hidden Gem', 'murah', 'Wifi, Restoran, AC, Resepsionis 24 Jam, Parkir, Sarapan', 'hostel premium hidden gem murah jakarta pusat dki jakarta mobil motor krl wifi restoran ac resepsionis 24 jam parkir sarapan merombak definisi hostel dengan kapsul privasi pod bergaya industrial rapi dan kafe yang meracik kopi berkualitas tinggi', NULL, NULL),
(29, 'Tuju Arteri Pods', 'Dekat Pondok Indah', 'Bilik tidur kapsul yang futuristik menyerupai pesawat antariksa', 'Jakarta Selatan', 'DKI Jakarta', 'Mobil, Motor', 'https://tse3.mm.bing.net/th/id/OIP.bzyBh8adsAEpvVgNwEwGewHaE8?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 200000.00, 'Hostel', '00:00:00', '23:59:00', 'Setiap Hari', -6.25387000, 106.78186200, 'Capsule Hostel', 'Hidden Gem', 'murah', 'Parkir, Wi-Fi, AC, TV, Kamar Mandi, Resepsionis 24 Jam, Pembayaran Non Tunai, Pembayaran Tunai', 'hostel capsule hidden gem murah jakarta selatan dki jakarta mobil motor parkir wi fi ac tv kamar mandi resepsionis 24 jam pembayaran non tunai pembayaran tunai bilik tidur kapsul yang futuristik menyerupai pesawat antariksa', NULL, NULL),
(30, 'LeGreen Suite Kuningan', 'Belakang Area Mega Kuningan', 'Properti budget dengan fasad tanaman rambat lebat yang berlokasi strategis membelakangi kompleks perkantoran super sibuk.', 'Jakarta Selatan', 'DKI Jakarta', 'Mobil, Motor', 'https://pix10.agoda.net/hotelImages/1061245/-1/0059a97a4f4db1d4005737c34b963a0f.jpg?ca=9&ce=1&s=1024x768', 300000.00, 'Guesthouse', '00:00:00', '23:59:00', 'Setiap Hari', -6.20703600, 106.82488400, 'Guesthouse', 'Terkenal', 'murah', 'Wifi, Kamar Mandi Dalam, AC, Resepsionis 24 Jam, Parkir, Air Panas, TV', 'guesthouse terkenal murah jakarta selatan dki jakarta mobil motor wifi kamar mandi dalam ac resepsionis 24 jam parkir air panas tv properti budget dengan fasad tanaman rambat lebat yang berlokasi strategis membelakangi kompleks perkantoran super sibuk', NULL, NULL),
(31, 'Harlys Residence', 'Jl. Tomang Tinggi', 'Menyediakan suasana lokal rumahan yang sangat bersih dengan fasilitas mini-gym atap', 'Jakarta Barat', 'DKI Jakarta', 'Mobil, Motor', 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/48821127.jpg?k=d52e707f287142059ca3eb0af15ca1b35c1f8b175374e410e6a97c96857b6a1f&o=&hp=1', 350000.00, 'Apartemen/Residence', '00:00:00', '23:59:00', 'Setiap Hari', -6.17391500, 106.79523600, 'Serviced Residence', 'Terkenal', 'sedang', 'Parkir, Wi-Fi, AC, TV, Kamar Mandi, Resepsionis 24 Jam, Pembayaran Non Tunai, Pembayaran Tunai', 'apartemen residence serviced terkenal sedang jakarta barat dki jakarta mobil motor parkir wi fi ac tv kamar mandi resepsionis 24 jam pembayaran non tunai pembayaran tunai menyediakan suasana lokal rumahan yang sangat bersih dengan fasilitas mini gym atap', NULL, NULL),
(32, 'DENZ Premiere', 'Jl. Genteng Ijo, Kuningan', 'Opsi penginapan korporat hemat berdesain rasional dan fungsional yang memungkinkan akses pejalan kaki ke mal mewah terdekat.', 'Jakarta Selatan', 'DKI Jakarta', 'Mobil, Motor', 'https://ik.imagekit.io/tvlk/apr-asset/Ixf4aptF5N2Qdfmh4fGGYhTN274kJXuNMkUAzpL5HuD9jzSxIGG5kZNhhHY-p7nw/hotel/asset/20046112-221215ed48376b310bf4ce016b85a93e.jpeg?tr=q-80,c-at_max,w-1280,h-720&_src=imagekit', 350000.00, 'Guesthouse', '00:00:00', '23:59:00', 'Setiap Hari', -6.22007400, 106.82730500, 'Guesthouse', 'Hidden Gem', 'sedang', 'Wifi, Kamar Mandi Dalam, AC, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'guesthouse hidden gem sedang jakarta selatan dki jakarta mobil motor wifi kamar mandi dalam ac resepsionis 24 jam parkir air panas sarapan tv opsi penginapan korporat hemat berdesain rasional dan fungsional yang memungkinkan akses pejalan kaki ke mal mewah terdekat', NULL, NULL),
(33, 'Classic Hotel Jakarta', 'Kawasan Pasar Baru', 'Properti lawas bernuansa marmer 1980-an yang mendedikasikan fungsinya sebagai kompleks hiburan komprehensif nan masif.', 'Jakarta Pusat', 'DKI Jakarta', 'Mobil, Motor, KRL', 'https://tse3.mm.bing.net/th/id/OIP.LY_-YAM8Q7heR4GfCXyVtwHaE7?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 500000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.16124000, 106.82961100, 'Entertainment Hotel', 'Terkenal', 'sedang', 'Wifi, Restoran, Kamar Mandi Dalam, AC, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel entertainment terkenal sedang jakarta pusat dki jakarta mobil motor krl wifi restoran kamar mandi dalam ac resepsionis 24 jam parkir air panas sarapan tv properti lawas bernuansa marmer 1980 an yang mendedikasikan fungsinya sebagai kompleks hiburan komprehensif nan masif', NULL, NULL),
(34, 'Hotel Kuretakeso', 'Kemang', 'Implementasi otentik Ryokan Jepang di Kemang, menawarkan kamar tikar Tatami, pemandian Onsen, dan sarapan set tradisional.', 'Jakarta Selatan', 'DKI Jakarta', 'Mobil, Motor', 'https://tse1.mm.bing.net/th/id/OIP.HZfY64CBv5Ao8yyaxVl1twAAAA?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 650000.00, 'Penginapan Tradisional', '00:00:00', '23:59:00', 'Setiap Hari', -6.25395200, 106.81466800, 'Japanese Ryokan', 'Hidden Gem', 'mahal', 'Wifi, Restoran, Kamar Mandi Dalam, AC, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'penginapan tradisional japanese ryokan hidden gem mahal jakarta selatan dki jakarta mobil motor wifi restoran kamar mandi dalam ac resepsionis 24 jam parkir air panas sarapan tv implementasi otentik ryokan jepang di kemang menawarkan kamar tikar tatami pemandian onsen dan sarapan set tradisional', NULL, NULL),
(35, 'Ibis Styles Tanah Abang', 'Jl. Fachrudin, Tanah Abang', 'Merespons lanskap sentra grosir dengan mengaplikasikan warna warni tekstil pada dinding kamar', 'Jakarta Pusat', 'DKI Jakarta', 'Mobil, Motor, KRL', 'https://tse1.mm.bing.net/th/id/OIP.yZwaN4BdW0UQzrYkCuoZLgHaE8?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 650000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.18353700, 106.81521800, 'Budget Hotel', 'Terkenal', 'mahal', 'Parkir, Wi-Fi, AC, TV, Kamar Mandi, Resepsionis 24 Jam, Pembayaran Non Tunai, Pembayaran Tunai', 'hotel budget terkenal mahal jakarta pusat dki jakarta mobil motor krl parkir wi fi ac tv kamar mandi resepsionis 24 jam pembayaran non tunai pembayaran tunai merespons lanskap sentra grosir dengan mengaplikasikan warna warni tekstil pada dinding kamar', NULL, NULL),
(36, 'Wisma Delima', 'Jl. Jaksa', 'Simbol pariwisata independen lintas benua era 1970-an', 'Jakarta Pusat', 'DKI Jakarta', 'KRL, TransJakarta', 'https://dynamic-media-cdn.tripadvisor.com/media/partner/bookingcom/photo-o/19/b3/28/fa/4074508.jpg?w=900&h=-1&s=1', 150000.00, 'Guesthouse', '00:00:00', '23:59:00', 'Setiap Hari', -6.18334600, 106.83905600, 'Guest House', 'Hidden Gem', 'murah', 'Parkir, Wi-Fi, AC, TV, Kamar Mandi, Resepsionis 24 Jam, Pembayaran Non Tunai, Pembayaran Tunai', 'guesthouse guest house hidden gem murah jakarta pusat dki jakarta krl transjakarta parkir wi fi ac tv kamar mandi resepsionis 24 jam pembayaran non tunai pembayaran tunai simbol pariwisata independen lintas benua era 1970 an', NULL, NULL),
(37, 'Artotel Thamrin', 'Jl. Sunda No. 3', 'Menciptakan poros seni dengan fasad luar gedung berlukiskan mural raksasa', 'Jakarta Pusat', 'DKI Jakarta', 'Mobil, Motor, MRT', 'https://tse4.mm.bing.net/th/id/OIP.Cx32tHmRKiZjtqQ8BTvMFwHaLI?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 750000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.18845000, 106.82524700, 'Art Hotel', 'Terkenal', 'mahal', 'Parkir, Wi-Fi, AC, TV, Kamar Mandi, Resepsionis 24 Jam, Pembayaran Non Tunai, Pembayaran Tunai', 'hotel art terkenal mahal jakarta pusat dki jakarta mobil motor mrt parkir wi fi ac tv kamar mandi resepsionis 24 jam pembayaran non tunai pembayaran tunai menciptakan poros seni dengan fasad luar gedung berlukiskan mural raksasa', NULL, NULL),
(38, 'The Langham', 'District 8 SCBD', 'Mewakili puncak kemewahan klasik modern dengan pualam berlimpah, kolam indoor dramatis, dan kuliner Eropa by Tom Aikens.', 'Jakarta Selatan', 'DKI Jakarta', 'Mobil, Motor, MRT', 'https://tse4.mm.bing.net/th/id/OIP.gT3g0oL_NBmsr82Eom7vtQHaE7?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 4500000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.22725300, 106.80741000, 'Ultra Luxury', 'Ikonik', 'mahal', 'Wifi, Restoran, Kamar Mandi Dalam, AC, Kolam Renang, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel ultra luxury ikonik mahal jakarta selatan dki jakarta mobil motor mrt wifi restoran kamar mandi dalam ac kolam renang resepsionis 24 jam parkir air panas sarapan tv mewakili puncak kemewahan klasik modern dengan pualam berlimpah kolam indoor dramatis dan kuliner eropa by tom aikens', NULL, NULL),
(39, 'Novotel Mangga Dua', 'Mangga Dua Square', 'Menciptakan resor keluarga yang tak terduga dengan kolam renang laguna masif di podium beton tepat di atas bangunan mal.', 'Jakarta Utara', 'DKI Jakarta', 'Mobil, Motor, TransJakarta', 'https://tse1.mm.bing.net/th/id/OIP.IaKC9kr5PWnY9ewDm0X68wHaEX?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 800000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.13789800, 106.83199500, '4-Star Hotel', 'Terkenal', 'mahal', 'Wifi, Restoran, Kamar Mandi Dalam, AC, Kolam Renang, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel 4 star terkenal mahal jakarta utara dki jakarta mobil motor transjakarta wifi restoran kamar mandi dalam ac kolam renang resepsionis 24 jam parkir air panas sarapan tv menciptakan resor keluarga yang tak terduga dengan kolam renang laguna masif di podium beton tepat di atas bangunan mal', NULL, NULL),
(40, 'FM7 Resort Hotel', 'Perbatasan Bandara Soekarno Hatta', 'Hotel transit yang bertransformasi menjadi area relaksasi masif, memiliki kolam renang besar dan menu Tom Yum otentik terbaik.', 'Jakarta Barat', 'DKI Jakarta', 'Mobil, Motor', 'https://lirp-cdn.multiscreensite.com/61d4ea87/dms3rep/multi/opt/Outside+Terra+Lobby-min-1920w.jpg', 850000.00, 'Resort', '00:00:00', '23:59:00', 'Setiap Hari', -6.10638200, 106.68358200, 'Airport Resort', 'Terkenal', 'mahal', 'Wifi, Restoran, Kamar Mandi Dalam, AC, Kolam Renang, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'resort airport terkenal mahal jakarta barat dki jakarta mobil motor wifi restoran kamar mandi dalam ac kolam renang resepsionis 24 jam parkir air panas sarapan tv hotel transit yang bertransformasi menjadi area relaksasi masif memiliki kolam renang besar dan menu tom yum otentik terbaik', NULL, NULL),
(41, 'Sans Vibes Diemdi Kiaracondong', 'Jl. Ibrahim Adjie, Komplek Kiaracondong', 'Tersembunyi di gang dengan desain industrial minimalis. Suasana tenang cocok untuk kerja remote atau digital detox.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online, Motor', 'https://pix10.agoda.net/hotelImages/10773837/0/64616185d120ad15155b1af6df8b4ef6.jpg?ce=0&s=1024x768', 170000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.91888500, 107.64506800, 'Budget Hotel', 'Terkenal', 'murah', 'WiFi, Kamar Mandi Dalam, AC, Resepsionis 24 Jam, Parkir, Air Panas, TV', 'hotel budget terkenal murah bandung jawa barat kendaraan pribadi taksi online motor wifi kamar mandi dalam ac resepsionis 24 jam parkir air panas tv tersembunyi di gang dengan desain industrial minimalis suasana tenang cocok untuk kerja remote atau digital detox', NULL, NULL),
(42, 'Sans Family Hotel', 'Area Setiabudi Bawah', 'Kamar luas bernuansa \"homey\" yang sangat ramah keluarga, terletak dekat dengan pusat kuliner komersial.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online, Bus', 'https://res.klook.com/klook-hotel/image/upload/s2pix8/hotelImages/32613733/-1/70689d7147763adb8b2561c3bf4a8ff0.jpg', 240000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.93019200, 107.62170100, 'Family Budget', 'Terkenal', 'murah', 'WiFi, Kamar Mandi Dalam, AC, Resepsionis 24 Jam, Parkir, Air Panas, TV', 'hotel family budget terkenal murah bandung jawa barat kendaraan pribadi taksi online bus wifi kamar mandi dalam ac resepsionis 24 jam parkir air panas tv kamar luas bernuansa homey yang sangat ramah keluarga terletak dekat dengan pusat kuliner komersial', NULL, NULL),
(43, 'Sans Vibes Surapati Konforta', 'Area Surapati / Dekat Gedung Sate', 'Properti estetik minimalis yang memiliki area rooftop tersembunyi dengan pemandangan lanskap kota sore hari.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online', 'https://pix10.agoda.net/hotelImages/5903016/768766148/c1a165ca37137540894b2842829d78bf.jpg?ce=2&s=1024x768', 260000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.89942100, 107.62989600, 'Modern Budget', 'Terkenal', 'murah', 'WiFi, Kamar Mandi Dalam, AC, Resepsionis 24 Jam, Parkir, Air Panas, TV', 'hotel modern budget terkenal murah bandung jawa barat kendaraan pribadi taksi online wifi kamar mandi dalam ac resepsionis 24 jam parkir air panas tv properti estetik minimalis yang memiliki area rooftop tersembunyi dengan pemandangan lanskap kota sore hari', NULL, NULL),
(44, 'Sans Hotel Alexander', 'Area Setiabudi Atas', 'Penginapan bersih dan nyaman yang menyajikan fasilitas kolam renang komunal dan parkir luas di jalur wisata utama.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online', 'https://tse2.mm.bing.net/th/id/OIP.txbKOfJEjna6K8loDZjs4gHaE_?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 350000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.90464300, 107.59853500, 'Modern Budget', 'Terkenal', 'sedang', 'WiFi, Restoran, Kamar Mandi Dalam, AC, Kolam Renang, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel modern budget terkenal sedang bandung jawa barat kendaraan pribadi taksi online wifi restoran kamar mandi dalam ac kolam renang resepsionis 24 jam parkir air panas sarapan tv penginapan bersih dan nyaman yang menyajikan fasilitas kolam renang komunal dan parkir luas di jalur wisata utama', NULL, NULL),
(45, 'The Gaia Hotel Bandung', 'Jl. Dr. Setiabudi No.430', 'Kemewahan monolitik di tepi tebing. Suasana sangat eksklusif dengan deretan menu fine dining bertaraf internasional.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online', 'https://tse1.mm.bing.net/th/id/OIP.Vh56yzxFSXKnnsK2ayVYGwHaE1?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 1980000.00, 'Resort', '00:00:00', '23:59:00', 'Setiap Hari', -6.84382600, 107.60083100, 'Luxury Resort', 'Terkenal', 'mahal', 'WiFi, Restoran, Kamar Mandi Dalam, AC, Kolam Renang, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'resort luxury terkenal mahal bandung jawa barat kendaraan pribadi taksi online wifi restoran kamar mandi dalam ac kolam renang resepsionis 24 jam parkir air panas sarapan tv kemewahan monolitik di tepi tebing suasana sangat eksklusif dengan deretan menu fine dining bertaraf internasional', NULL, NULL),
(46, 'The Trans Luxury Hotel', 'Jl. Gatot Subroto No.289', 'Menawarkan pengalaman menginap bintang 6 yang glamor, menonjolkan kolam renang dengan pasir pantai putih buatan.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online', 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/109777652.jpg?k=7c890f76693d1744a65b83c6c71f3e1075d90088d385bcca00f7c3e2dad35776&o=&hp=1', 2300000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.92687000, 107.63660000, 'Luxury 6-Star', 'Ikonik', 'mahal', 'WiFi, Restoran, Kamar Mandi Dalam, AC, Kolam Renang, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel luxury 6 star ikonik mahal bandung jawa barat kendaraan pribadi taksi online wifi restoran kamar mandi dalam ac kolam renang resepsionis 24 jam parkir air panas sarapan tv menawarkan pengalaman menginap bintang 6 yang glamor menonjolkan kolam renang dengan pasir pantai putih buatan', NULL, NULL),
(47, 'Bobocabin Cikole', 'Hutan Pinus Cikole, Lembang', 'Kabin modular futuristik di tengah hutan pinus bersuhu dingin. Menu andalan berupa fasilitas barbeque privat.', 'Bandung Barat', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online', 'https://tse3.mm.bing.net/th/id/OIP.z83WoY2TXNb941tYkKALZwHaE7?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 1100000.00, 'Glamping/Camping', '00:00:00', '23:59:00', 'Setiap Hari', -6.78383300, 107.64830900, 'Glamping / Cabin', 'Terkenal', 'mahal', 'WiFi, Restoran, Kamar Mandi Dalam, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan', 'glamping camping cabin terkenal mahal bandung barat jawa barat kendaraan pribadi taksi online wifi restoran kamar mandi dalam resepsionis 24 jam parkir air panas sarapan kabin modular futuristik di tengah hutan pinus bersuhu dingin menu andalan berupa fasilitas barbeque privat', NULL, NULL),
(48, 'Chanaya Resort', 'Area Bukit Bandung', 'Menggabungkan kemewahan modern dengan keheningan alam perbukitan, menghadirkan vila berdesain tradisional kayu.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online', 'https://tse2.mm.bing.net/th/id/OIP.bicdlQ5tL8DSnbRHIlpTyAHaEK?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 1400000.00, 'Resort', '00:00:00', '23:59:00', 'Setiap Hari', -6.84550100, 107.62975900, 'Nature Resort', 'Hidden Gem', 'mahal', 'WiFi, Restoran, Kamar Mandi Dalam, Kolam Renang, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'resort nature hidden gem mahal bandung jawa barat kendaraan pribadi taksi online wifi restoran kamar mandi dalam kolam renang resepsionis 24 jam parkir air panas sarapan tv menggabungkan kemewahan modern dengan keheningan alam perbukitan menghadirkan vila berdesain tradisional kayu', NULL, NULL),
(49, 'Oasis Inn', 'Area Pusat Kota', 'Properti fungsional yang strategis di kawasan perkotaan, mengutamakan kemudahan akses mobilitas logistik tamu.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online', 'https://tse3.mm.bing.net/th/id/OIP.nS1Gbg5Qm0VaCBJT3KqucAHaFj?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 400000.00, 'Guesthouse', '00:00:00', '23:59:00', 'Setiap Hari', -6.80386100, 107.54914400, 'Guesthouse', 'Hidden Gem', 'sedang', 'WiFi, Kamar Mandi Dalam, AC, Resepsionis 24 Jam, Parkir, Air Panas, TV', 'guesthouse hidden gem sedang bandung jawa barat kendaraan pribadi taksi online wifi kamar mandi dalam ac resepsionis 24 jam parkir air panas tv properti fungsional yang strategis di kawasan perkotaan mengutamakan kemudahan akses mobilitas logistik tamu', NULL, NULL),
(50, 'Sekararum Butik Syariah', 'Area Perkotaan Bandung', 'Menyediakan ketenangan religius dengan operasional syariah murni, cocok untuk privasi keluarga dengan menu rumahan.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online', 'https://tse2.mm.bing.net/th/id/OIP.eVXlmyIOFFkjNPzlmWcyrgHaE8?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 350000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.88697600, 107.58025900, 'Syariah Boutique', 'Hidden Gem', 'sedang', 'WiFi, Restoran, Kamar Mandi Dalam, AC, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel syariah boutique hidden gem sedang bandung jawa barat kendaraan pribadi taksi online wifi restoran kamar mandi dalam ac resepsionis 24 jam parkir air panas sarapan tv menyediakan ketenangan religius dengan operasional syariah murni cocok untuk privasi keluarga dengan menu rumahan', NULL, NULL),
(51, 'Uma Gati', 'Area Utara Pinggiran', 'Properti hijau dengan dominasi ornamen kayu dan bambu, memberikan nuansa tropis pedesaan di pinggiran kota.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online', 'https://tse1.mm.bing.net/th/id/OIP.Nr_O045DEwWUpjxBaN7gsQHaE8?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 450000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.86813200, 107.62809400, 'Eco-Boutique', 'Hidden Gem', 'sedang', 'WiFi, Restoran, Kamar Mandi Dalam, AC, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel eco boutique hidden gem sedang bandung jawa barat kendaraan pribadi taksi online wifi restoran kamar mandi dalam ac resepsionis 24 jam parkir air panas sarapan tv properti hijau dengan dominasi ornamen kayu dan bambu memberikan nuansa tropis pedesaan di pinggiran kota', NULL, NULL),
(52, 'Rumah Lereng Bandung', 'Area Ciumbuleuit Atas', 'Terisolasi di lereng curam dengan privasi absolut, menawarkan pemandangan magis ke arah lembah Bandung di pagi hari.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi', 'https://tse2.mm.bing.net/th/id/OIP.mK9NUsVWdRR0ZJGeb9ypGgHaE7?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 1500000.00, 'Villa', '00:00:00', '23:59:00', 'Setiap Hari', -6.89758400, 107.73230300, 'Private Villa', 'Hidden Gem', 'mahal', 'WiFi, Restoran, Kamar Mandi Dalam, AC, Kolam Renang, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'villa private hidden gem mahal bandung jawa barat kendaraan pribadi wifi restoran kamar mandi dalam ac kolam renang resepsionis 24 jam parkir air panas sarapan tv terisolasi di lereng curam dengan privasi absolut menawarkan pemandangan magis ke arah lembah bandung di pagi hari', NULL, NULL),
(53, 'Mogens Guesthouse', 'Jl. Kebon Sirih No.26', 'Tersembunyi secara apik di gang jalan pusat kota. Sangat damai meski dekat Braga, sajian menu sarapan simpel dan lezat.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online, Jalan Kaki', 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/120693003.jpg?k=0f5a2d1b956c027c0a7387188d4156a43bd6c145d9b6251018f12fbe18bc3a6f&o=&hp=1', 350000.00, 'Guesthouse', '00:00:00', '23:59:00', 'Setiap Hari', -6.91193100, 107.60736300, 'Guesthouse', 'Hidden Gem', 'sedang', 'WiFi, Restoran, Kamar Mandi Dalam, AC, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'guesthouse hidden gem sedang bandung jawa barat kendaraan pribadi taksi online jalan kaki wifi restoran kamar mandi dalam ac resepsionis 24 jam parkir air panas sarapan tv tersembunyi secara apik di gang jalan pusat kota sangat damai meski dekat braga sajian menu sarapan simpel dan lezat', NULL, NULL),
(54, 'Tama Boutique Hotel', 'Jl. Dr. Rajiman No.5', 'Mengusung dekorasi ala Korea yang trendi. Uniknya, sarapan difokuskan pada hidangan otentik dari restoran Bornga.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online', 'https://tse4.mm.bing.net/th/id/OIP.xMHBfPs5FF2bqaOwO69UgAHaFj?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 550000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.90352800, 107.59843400, 'Boutique Hotel', 'Hidden Gem', 'sedang', 'WiFi, Restoran, Kamar Mandi Dalam, AC, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel boutique hidden gem sedang bandung jawa barat kendaraan pribadi taksi online wifi restoran kamar mandi dalam ac resepsionis 24 jam parkir air panas sarapan tv mengusung dekorasi ala korea yang trendi uniknya sarapan difokuskan pada hidangan otentik dari restoran bornga', NULL, NULL),
(55, 'Chara Hotel', 'Jl. Gatot Subroto No.31', 'Arsitektur adaptif menggunakan susunan kontainer dengan interior kayu jati dan karpet berbulu tebal yang estetik.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online', 'https://tse4.mm.bing.net/th/id/OIP.TXLECMLmBGHu3j-38yfF1AHaE7?r=0&o=7rm=3&rs=1&pid=ImgDetMain&o=7&rm=3', 300000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.92226500, 107.61972000, 'Industrial Hotel', 'Hidden Gem', 'murah', 'WiFi, Restoran, Kamar Mandi Dalam, AC, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel industrial hidden gem murah bandung jawa barat kendaraan pribadi taksi online wifi restoran kamar mandi dalam ac resepsionis 24 jam parkir air panas sarapan tv arsitektur adaptif menggunakan susunan kontainer dengan interior kayu jati dan karpet berbulu tebal yang estetik', NULL, NULL),
(56, 'Moxy Bandung', 'Jl. Ir. H. Djuanda No.69', 'Dirancang khusus untuk milenial. Sky bar atap gedung, lobi 24 jam, piringan hitam, dan menu hidangan lokal kekinian.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online', 'https://tse2.mm.bing.net/th/id/OIP.8CG31IWZz2c1lg93iRS03gHaE8?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 650000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.90027900, 107.61267100, 'Lifestyle Hotel', 'Terkenal', 'mahal', 'WiFi, Restoran, Kamar Mandi Dalam, AC, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel lifestyle terkenal mahal bandung jawa barat kendaraan pribadi taksi online wifi restoran kamar mandi dalam ac resepsionis 24 jam parkir air panas sarapan tv dirancang khusus untuk milenial sky bar atap gedung lobi 24 jam piringan hitam dan menu hidangan lokal kekinian', NULL, NULL),
(57, 'Padma Hotel Bandung', 'Jl. Ranca Bentang No.56-58', 'Resor legendaris di dinding lembah Ciumbuleuit. Kolam air hangat tanpa batas dan tradisi prasmanan teh sore yang ikonis.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online', 'https://tse1.mm.bing.net/th/id/OIP.PFeyHDRe0b_hrRcTMAaCNQHaD-?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 1500000.00, 'Resort', '00:00:00', '23:59:00', 'Setiap Hari', -6.86437600, 107.60947200, 'Luxury Resort', 'Ikonik', 'mahal', 'WiFi, Restoran, Kamar Mandi Dalam, AC, Kolam Renang, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'resort luxury ikonik mahal bandung jawa barat kendaraan pribadi taksi online wifi restoran kamar mandi dalam ac kolam renang resepsionis 24 jam parkir air panas sarapan tv resor legendaris di dinding lembah ciumbuleuit kolam air hangat tanpa batas dan tradisi prasmanan teh sore yang ikonis', NULL, NULL),
(58, 'The House Tour Downtown', 'Jl. Cimanuk No.11a', 'Berada di lingkungan asri Riau, interiornya seperti rumah kaca Inggris yang klasik dengan restoran beranda yang sejuk.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online', 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/29/e4/c9/0e/caption.jpg?w=1100&h=600&s=1', 500000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.90341800, 107.62100400, 'Boutique Hotel', 'Hidden Gem', 'sedang', 'WiFi, Restoran, Kamar Mandi Dalam, AC, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel boutique hidden gem sedang bandung jawa barat kendaraan pribadi taksi online wifi restoran kamar mandi dalam ac resepsionis 24 jam parkir air panas sarapan tv berada di lingkungan asri riau interiornya seperti rumah kaca inggris yang klasik dengan restoran beranda yang sejuk', NULL, NULL),
(59, 'The House Tour Middletown', 'Jl. Panumbang Jaya No.5', 'Butik rahasia di area perumahan Ciumbuleuit dengan desain mid-century modern. Kafe internal menyajikan pastry artisanal.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online', 'https://tse3.mm.bing.net/th/id/OIP.H70NR9CjrlSTzfFSfiAQtAHaEK?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 500000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.86672200, 107.60388900, 'Boutique Hotel', 'Hidden Gem', 'sedang', 'WiFi, Restoran, Kamar Mandi Dalam, AC, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel boutique hidden gem sedang bandung jawa barat kendaraan pribadi taksi online wifi restoran kamar mandi dalam ac resepsionis 24 jam parkir air panas sarapan tv butik rahasia di area perumahan ciumbuleuit dengan desain mid century modern kafe internal menyajikan pastry artisanal', NULL, NULL);
INSERT INTO `penginapan` (`id_penginapan`, `nama`, `alamat`, `deskripsi`, `kota`, `provinsi`, `transportasi`, `gambar`, `harga`, `tipe_penginapan`, `jam_buka`, `jam_tutup`, `hari_operasional`, `latitude`, `longitude`, `kategori_penginapan`, `trend`, `budget`, `fasilitas`, `fitur_cbf`, `created_at`, `updated_at`) VALUES
(60, 'The House Tour Uphill', 'Jl. Sersan Bajuri No.72', 'Berada di dataran tinggi yang menawarkan udara sejuk pegunungan dengan arsitektur resor tropis berskala intim.', 'Bandung Barat', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online', 'https://i.ytimg.com/vi/ktFNY5HDioc/maxresdefault.jpg', 850000.00, 'Resort', '00:00:00', '23:59:00', 'Setiap Hari', -6.83168900, 107.59522700, 'Boutique Resort', 'Hidden Gem', 'mahal', 'WiFi, Restoran, Kamar Mandi Dalam, AC, Kolam Renang, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'resort boutique hidden gem mahal bandung barat jawa barat kendaraan pribadi taksi online wifi restoran kamar mandi dalam ac kolam renang resepsionis 24 jam parkir air panas sarapan tv berada di dataran tinggi yang menawarkan udara sejuk pegunungan dengan arsitektur resor tropis berskala intim', NULL, NULL),
(61, 'Villa Tibra', 'Jl. Kolonel Masturi No.508c', 'Konsep kabin pedesaan modern yang viral di TikTok. Sangat estetis, pet-friendly, dan memiliki kafe hijau menenangkan.', 'Bandung Barat', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online', 'https://tse2.mm.bing.net/th/id/OIP.3hoNsc8KJI0PLff_0kqIYwHaFj?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 1200000.00, 'Villa', '00:00:00', '23:59:00', 'Setiap Hari', -6.82505200, 107.55496500, 'Villa / Pet Friendly', 'Hidden Gem', 'mahal', 'WiFi, Restoran, Kamar Mandi Dalam, AC, Kolam Renang, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'villa pet friendly hidden gem mahal bandung barat jawa barat kendaraan pribadi taksi online wifi restoran kamar mandi dalam ac kolam renang resepsionis 24 jam parkir air panas sarapan tv konsep kabin pedesaan modern yang viral di tiktok sangat estetis pet friendly dan memiliki kafe hijau menenangkan', NULL, NULL),
(62, 'Art Deco Luxury Hotel', 'Jl. Ranca Bentang No.2', 'Atmosfer era 1920-an yang gemerlap. Daya tarik utamanya adalah jacuzzi air hangat dan kolam renang atap dengan panorama memukau.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online', 'https://tse4.mm.bing.net/th/id/OIP.S5CQUx2g9AE0DBnm3aVHbwHaFI?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 800000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.86975600, 107.60809700, 'Luxury Boutique', 'Terkenal', 'mahal', 'WiFi, Restoran, Kamar Mandi Dalam, AC, Kolam Renang, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel luxury boutique terkenal mahal bandung jawa barat kendaraan pribadi taksi online wifi restoran kamar mandi dalam ac kolam renang resepsionis 24 jam parkir air panas sarapan tv atmosfer era 1920 an yang gemerlap daya tarik utamanya adalah jacuzzi air hangat dan kolam renang atap dengan panorama memukau', NULL, NULL),
(63, 'Stevie G Hotel', 'Jl. Sersan Bajuri No.72', 'Desain luar biasa eksentrik berakar pada budaya pop, terkenal dengan kamar \"The Anfield\" untuk penggemar sepak bola.', 'Bandung Barat', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online', 'https://tse4.mm.bing.net/th/id/OIP.APdvM0UCR956NSxAc-GN2QHaEa?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 600000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.83168100, 107.59532100, 'Thematic Hotel', 'Hidden Gem', 'sedang', 'WiFi, Restoran, Kamar Mandi Dalam, AC, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel thematic hidden gem sedang bandung barat jawa barat kendaraan pribadi taksi online wifi restoran kamar mandi dalam ac resepsionis 24 jam parkir air panas sarapan tv desain luar biasa eksentrik berakar pada budaya pop terkenal dengan kamar the anfield untuk penggemar sepak bola', NULL, NULL),
(64, 'Kollektiv Hotel', 'Jl. Prof. Dr. Sutami No.62', 'Terbuat dari peti kemas bertingkat yang dibalut tanaman hijau. Hara Cafe di lantai dasar menyajikan fusi makanan Barat.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online', 'https://pix10.agoda.net/hotelImages/3365192/-1/b899531d75dd6175ef6a78ecab47da30.jpg?ca=7&ce=1&ar=1x1&s=800x', 550000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.87792200, 107.58681800, 'Eco-Boutique', 'Hidden Gem', 'sedang', 'WiFi, Restoran, Kamar Mandi Dalam, AC, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel eco boutique hidden gem sedang bandung jawa barat kendaraan pribadi taksi online wifi restoran kamar mandi dalam ac resepsionis 24 jam parkir air panas sarapan tv terbuat dari peti kemas bertingkat yang dibalut tanaman hijau hara cafe di lantai dasar menyajikan fusi makanan barat', NULL, NULL),
(65, 'Noor Hotel', 'Jl. Madura No.6', 'Konsep Muslim butik bergaya shabby chic. Pelayanan unik berupa sajian air Zamzam, buah kurma, dan menu khas Peranakan.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online', 'https://tse2.mm.bing.net/th/id/OIP.LsSP34EZMJuKz8Bg4vH_SQAAAA?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 600000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.90689300, 107.61954000, 'Syariah Boutique', 'Hidden Gem', 'sedang', 'WiFi, Restoran, Kamar Mandi Dalam, AC, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel syariah boutique hidden gem sedang bandung jawa barat kendaraan pribadi taksi online wifi restoran kamar mandi dalam ac resepsionis 24 jam parkir air panas sarapan tv konsep muslim butik bergaya shabby chic pelayanan unik berupa sajian air zamzam buah kurma dan menu khas peranakan', NULL, NULL),
(66, 'de Braga by ARTOTEL', 'Jl. Braga No.10', 'Terletak strategis di Jalan Braga. Interior dipenuhi seni lokal kontemporer dengan menu sarapan andalan ayam rendang.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online, Jalan Kaki', 'https://tse3.mm.bing.net/th/id/OIP.7TeYnC0QI-4vqnTNfclfugHaEo?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 650000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.92013800, 107.61044100, 'Art Hotel', 'Terkenal', 'mahal', 'WiFi, Restoran, Kamar Mandi Dalam, AC, Kolam Renang, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel art terkenal mahal bandung jawa barat kendaraan pribadi taksi online jalan kaki wifi restoran kamar mandi dalam ac kolam renang resepsionis 24 jam parkir air panas sarapan tv terletak strategis di jalan braga interior dipenuhi seni lokal kontemporer dengan menu sarapan andalan ayam rendang', NULL, NULL),
(67, 'The Papandayan', 'Jl. Gatot Subroto No.83', 'Kemegahan paripurna kelas lima bintang. Menyediakan kolam renang bernuansa laguna tropis dan ragam sajian buffet masif.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online', 'https://media-cdn.tripadvisor.com/media/photo-s/21/5e/26/ff/new-modern-design-yet.jpg', 1100000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.92284700, 107.62373600, 'Luxury 5-Star', 'Ikonik', 'mahal', 'WiFi, Restoran, Kamar Mandi Dalam, AC, Kolam Renang, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel luxury 5 star ikonik mahal bandung jawa barat kendaraan pribadi taksi online wifi restoran kamar mandi dalam ac kolam renang resepsionis 24 jam parkir air panas sarapan tv kemegahan paripurna kelas lima bintang menyediakan kolam renang bernuansa laguna tropis dan ragam sajian buffet masif', NULL, NULL),
(68, 'The Batik Bed And Coffee', 'Jl. Sriwijaya, Buahbatu', 'Menggabungkan kehangatan budaya lokal motif batik dalam arsitektur butik modern, terletak di sub-urban Buahbatu yang tenang.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online', 'https://tse2.mm.bing.net/th/id/OIP.lgjF843RHAcIHTmMypQT6QHaFj?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 250000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.94615300, 107.61278800, 'Budget Boutique', 'Hidden Gem', 'murah', 'WiFi, Restoran, Kamar Mandi Dalam, AC, Resepsionis 24 Jam, Parkir, Air Panas, TV', 'hotel budget boutique hidden gem murah bandung jawa barat kendaraan pribadi taksi online wifi restoran kamar mandi dalam ac resepsionis 24 jam parkir air panas tv menggabungkan kehangatan budaya lokal motif batik dalam arsitektur butik modern terletak di sub urban buahbatu yang tenang', NULL, NULL),
(69, 'Kytos Hotel', 'Jl. Dr. Setiabudi No.153', 'Tampil biasa dari fasad jalan raya, namun menyembunyikan rooftop luar biasa dengan kolam renang air hangat tak terhingga.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online', 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/12/50/4d/77/kytos-hotel.jpg?w=900&h=500&s=1', 400000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.86921100, 107.59353600, 'Smart Hotel', 'Terkenal', 'sedang', 'WiFi, Restoran, Kamar Mandi Dalam, AC, Kolam Renang, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel smart terkenal sedang bandung jawa barat kendaraan pribadi taksi online wifi restoran kamar mandi dalam ac kolam renang resepsionis 24 jam parkir air panas sarapan tv tampil biasa dari fasad jalan raya namun menyembunyikan rooftop luar biasa dengan kolam renang air hangat tak terhingga', NULL, NULL),
(70, 'Kalya Hotel', 'Jl. Sumur Bandung No.7', 'Penginapan fungsional berkecepatan internet tinggi, tepat di sentra Dago, cocok untuk produktivitas pelancong bisnis.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online', 'https://ik.imagekit.io/tvlk/apr-asset/Ixf4aptF5N2Qdfmh4fGGYhTN274kJXuNMkUAzpL5HuD9jzSxIGG5kZNhhHY-p7nw/hotel/asset/10009981-b764d5537d19ae1877d1112e3f58cd14.jpeg?tr=q-40,c-at_max,w-1280,h-720&_src=imagekit', 450000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.88557700, 107.61250800, 'Smart / Business', 'Terkenal', 'sedang', 'WiFi, Restoran, Kamar Mandi Dalam, AC, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel smart business terkenal sedang bandung jawa barat kendaraan pribadi taksi online wifi restoran kamar mandi dalam ac resepsionis 24 jam parkir air panas sarapan tv penginapan fungsional berkecepatan internet tinggi tepat di sentra dago cocok untuk produktivitas pelancong bisnis', NULL, NULL),
(71, 'Ivory by Ayola', 'Jl. Bahureksa No.3', 'Memberikan kenyamanan kamar yang sangat luas dengan suasana jalanan yang rindang, terkoneksi dengan kafe spesialisasi kopi.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online', 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/181940334.jpg?k=2b7c397fccb5fd5476e3ce70cc0d6bf6f9f6c604a1fae4769b5d4f6bdf00cab5&o=&hp=1', 550000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.90478400, 107.61417800, 'Boutique Hotel', 'Terkenal', 'sedang', 'WiFi, Restoran, Kamar Mandi Dalam, AC, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel boutique terkenal sedang bandung jawa barat kendaraan pribadi taksi online wifi restoran kamar mandi dalam ac resepsionis 24 jam parkir air panas sarapan tv memberikan kenyamanan kamar yang sangat luas dengan suasana jalanan yang rindang terkoneksi dengan kafe spesialisasi kopi', NULL, NULL),
(72, 'De Paviljoen', 'Jl. R.E. Martadinata No.68', 'Gaya arsitektur kolonial kontemporer dengan fasilitas luas menyerupai apartemen mini, ramah untuk grup rombongan dan keluarga.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online', 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/768532671.jpg?k=0bf6b0cde49ac28cf4a7f941057d85e837b9971159e3eb080b6f4147ac91c2e9&o=', 700000.00, 'Apartemen/Residence', '00:00:00', '23:59:00', 'Setiap Hari', -6.90574400, 107.62097200, 'Condotel / Lifestyle', 'Terkenal', 'mahal', 'WiFi, Restoran, Kamar Mandi Dalam, AC, Kolam Renang, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'apartemen residence condotel lifestyle terkenal mahal bandung jawa barat kendaraan pribadi taksi online wifi restoran kamar mandi dalam ac kolam renang resepsionis 24 jam parkir air panas sarapan tv gaya arsitektur kolonial kontemporer dengan fasilitas luas menyerupai apartemen mini ramah untuk grup rombongan dan keluarga', NULL, NULL),
(73, 'Kimaya Braga Bandung', 'Jl. Braga No.8', 'Penginapan modern dengan letak geografis primer di mulut Jalan Braga, memudahkan penjelajahan wisata sejarah kota dengan berjalan kaki.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online', 'https://tse3.mm.bing.net/th/id/OIP.6OJqHD5Usbs52QaU9yKKCAAAAA?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 500000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.92048000, 107.61034900, 'Midscale Hotel', 'Terkenal', 'sedang', 'WiFi, Restoran, Kamar Mandi Dalam, AC, Kolam Renang, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel midscale terkenal sedang bandung jawa barat kendaraan pribadi taksi online wifi restoran kamar mandi dalam ac kolam renang resepsionis 24 jam parkir air panas sarapan tv penginapan modern dengan letak geografis primer di mulut jalan braga memudahkan penjelajahan wisata sejarah kota dengan berjalan kaki', NULL, NULL),
(74, 'YELLO Hotel Paskal', 'Paskal Hyper Square', 'Dekorasi kuning eksentrik dengan aura street art dan ruang game arkade, menempel sempurna dengan mal Paskal 23 terbesar.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online, Kereta', 'https://tse3.mm.bing.net/th/id/OIP.4o9vS8tmjiQdEKaANOUOTgHaEf?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 600000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.91536100, 107.59521200, 'Lifestyle / Urban', 'Terkenal', 'sedang', 'WiFi, Restoran, Kamar Mandi Dalam, AC, Kolam Renang, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel lifestyle urban terkenal sedang bandung jawa barat kendaraan pribadi taksi online kereta wifi restoran kamar mandi dalam ac kolam renang resepsionis 24 jam parkir air panas sarapan tv dekorasi kuning eksentrik dengan aura street art dan ruang game arkade menempel sempurna dengan mal paskal 23 terbesar', NULL, NULL),
(75, 'Triple Seven Bed & Breakfast', 'Jl. Pesantren Wetan No.20', 'Butik retro berbiaya rendah dengan interior sangat instagrammable, berlokasi dekat bandara di luar pusat turisme utama.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online', 'https://tse3.mm.bing.net/th/id/OIP.vhMyv1sTqpbqYZnj-_UJNgHaEo?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 250000.00, 'Bed & Breakfast', '00:00:00', '23:59:00', 'Setiap Hari', -6.90466700, 107.59278800, 'B&B / Retro', 'Hidden Gem', 'murah', 'WiFi, Restoran, Kamar Mandi Dalam, AC, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'bed breakfast b b retro hidden gem murah bandung jawa barat kendaraan pribadi taksi online wifi restoran kamar mandi dalam ac resepsionis 24 jam parkir air panas sarapan tv butik retro berbiaya rendah dengan interior sangat instagrammable berlokasi dekat bandara di luar pusat turisme utama', NULL, NULL),
(76, 'Cottonwood Bed & Breakfast', 'Jl. Mustang No.1A', 'Sangat intim dengan hanya 9 kamar yang didesain secara unik dan tematik chic, menyajikan menu sarapan khas Inggris dan Irlandia.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online', 'https://salsawisata.com/wp-content/uploads/2022/08/Cottonwood-Bed-Breakfast-House-Bandung.jpg', 350000.00, 'Bed & Breakfast', '00:00:00', '23:59:00', 'Setiap Hari', -6.88606200, 107.57758300, 'B&B / Thematic', 'Hidden Gem', 'sedang', 'WiFi, Restoran, Kamar Mandi Dalam, AC, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'bed breakfast b b thematic hidden gem sedang bandung jawa barat kendaraan pribadi taksi online wifi restoran kamar mandi dalam ac resepsionis 24 jam parkir air panas sarapan tv sangat intim dengan hanya 9 kamar yang didesain secara unik dan tematik chic menyajikan menu sarapan khas inggris dan irlandia', NULL, NULL),
(77, 'Malaka Hotel', 'Jl. Halimun No.36', 'Bersembunyi dalam naungan pohon rindang dengan dominasi arsitektur kaca dan putih abu-abu, bernapas konsep eco-green yang teduh.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online', 'https://ik.imagekit.io/tvlk/apr-asset/dgXfoyh24ryQLRcGq00cIdKHRmotrWLNlvG-TxlcLxGkiDwaUSggleJNPRgIHCX6/hotel/asset/10000300-8e7c32e84f1a561f9da86d65a8a0fc3b.jpeg?tr=q-40,c-at_max,w-1280,h-720&_src=imagekit', 450000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.92718800, 107.62316800, 'Eco-Green Budget', 'Hidden Gem', 'sedang', 'WiFi, Restoran, Kamar Mandi Dalam, AC, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel eco green budget hidden gem sedang bandung jawa barat kendaraan pribadi taksi online wifi restoran kamar mandi dalam ac resepsionis 24 jam parkir air panas sarapan tv bersembunyi dalam naungan pohon rindang dengan dominasi arsitektur kaca dan putih abu abu bernapas konsep eco green yang teduh', NULL, NULL),
(78, 'Tebu Hotel', 'Jl. R.E. Martadinata No.62', 'Properti kompak fungsional tinggi yang menghadap jalan raya sibuk, menyajikan atraksi menu unik berupa sari tebu manis segar.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online', 'https://tse4.mm.bing.net/th/id/OIP.Bb7D6d-mSJ9vHvNi6WU0XQHaE8?r=0&o=7rm=3&rs=1&pid=ImgDetMain&o=7&rm=3', 450000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.90603300, 107.62044800, 'City Hotel', 'Terkenal', 'sedang', 'WiFi, Restoran, Kamar Mandi Dalam, AC, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel city terkenal sedang bandung jawa barat kendaraan pribadi taksi online wifi restoran kamar mandi dalam ac resepsionis 24 jam parkir air panas sarapan tv properti kompak fungsional tinggi yang menghadap jalan raya sibuk menyajikan atraksi menu unik berupa sari tebu manis segar', NULL, NULL),
(79, 'Beehive Boutique Hotel', 'Jl. Dayang Sumbi No.1', 'Arsitektur estetik Skandinavia dengan atap kamar transparan parsial, kafe ruang bawah menyajikan kuliner internasional bermutu tinggi.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online', 'https://tse4.mm.bing.net/th/id/OIP.JAtBZRs2KunGZqHD74W_NQHaFj?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 550000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.88767600, 107.61285600, 'Scandinavian Boutique', 'Hidden Gem', 'sedang', 'WiFi, Restoran, Kamar Mandi Dalam, AC, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel scandinavian boutique hidden gem sedang bandung jawa barat kendaraan pribadi taksi online wifi restoran kamar mandi dalam ac resepsionis 24 jam parkir air panas sarapan tv arsitektur estetik skandinavia dengan atap kamar transparan parsial kafe ruang bawah menyajikan kuliner internasional bermutu tinggi', NULL, NULL),
(80, 'Aryaduta Bandung', 'Jl. Sumatera No.51', 'Mengukuhkan citra klasik kemewahan tengaran kota, memfasilitasi integrasi langsung dengan pusat perbelanjaan Bandung Indah Plaza.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Taksi Online', 'https://res.klook.com/klook-hotel/image/upload/travelapi/14000000/13400000/13393000/13392944/8587d774_z.jpg', 900000.00, 'Hotel', '00:00:00', '23:59:00', 'Setiap Hari', -6.90905700, 107.61197000, 'Luxury Classic', 'Ikonik', 'mahal', 'WiFi, Restoran, Kamar Mandi Dalam, AC, Kolam Renang, Resepsionis 24 Jam, Parkir, Air Panas, Sarapan, TV', 'hotel luxury classic ikonik mahal bandung jawa barat kendaraan pribadi taksi online wifi restoran kamar mandi dalam ac kolam renang resepsionis 24 jam parkir air panas sarapan tv mengukuhkan citra klasik kemewahan tengaran kota memfasilitasi integrasi langsung dengan pusat perbelanjaan bandung indah plaza', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pesan`
--

CREATE TABLE `pesan` (
  `id_pesan` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `pesan` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'unread',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id_ulasan` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `id_wisata` bigint(20) UNSIGNED DEFAULT NULL,
  `id_penginapan` bigint(20) UNSIGNED DEFAULT NULL,
  `id_kuliner` bigint(20) UNSIGNED DEFAULT NULL,
  `komentar` text DEFAULT NULL,
  `rating` decimal(3,2) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id_ulasan`, `id_user`, `id_wisata`, `id_penginapan`, `id_kuliner`, `komentar`, `rating`, `gambar`, `created_at`, `updated_at`) VALUES
(1, 3, 20, NULL, NULL, 'bagus', 5.00, NULL, '2026-08-04 23:33:58', '2026-08-04 23:33:58');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('A6g41po7944SYmFGUZN5ZN5Go0w0cbSAA7NdL660', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.131.0 Chrome/148.0.7778.280 Electron/42.7.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUE0xZGNQb1l6aEJ6M2J3Uldzd1ZwZHZlTXREWFhQNXVTV3l1b01GRiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzt9', 1785861981),
('cUqTkI5ya2udoVZtUy7cErmj20sO3g4X9tqcneud', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiMjkyYklMM2JWeUR1TE0zM1ljMVdvdU8xdDZkQTRwMHRHQWhYN2NzaCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kZXN0aW5hc2kvcGVuZ2luYXBhbi80NSI7czo1OiJyb3V0ZSI7czoxNDoiZGVzdGluYXNpLnNob3ciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjM6InVybCI7YTowOnt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzt9', 1785868119),
('RlxiI6N0u5jFW7TDvs5P4io2Es0UsKA5FMvYehQL', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.131.0 Chrome/148.0.7778.280 Electron/42.7.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibUttWUx1aThmRmNBcFpXOTdVaktTUGZlZXBmNzlJNHIxS0RJVnNuNyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1785910938),
('zUvZWg3zIa8tLQ9aGVLa13qK76MamcmkBseCohpz', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQlFHU2pjZTFGQktzVGJ2dWJlWGlIN1Z6bm1UallYWU5GRDd3Uk42cCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZWNvbW1lbmRhdGlvbi9kZWJ1ZyI7czo1OiJyb3V0ZSI7czoyMDoicmVjb21tZW5kYXRpb24uZGVidWciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=', 1785925691);

-- --------------------------------------------------------

--
-- Table structure for table `travel_plans`
--

CREATE TABLE `travel_plans` (
  `id_perencanaan` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `tujuan` varchar(255) NOT NULL,
  `nama_perjalanan` varchar(255) NOT NULL,
  `tanggal_berangkat` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `foto_sampul` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `travel_plan_items`
--

CREATE TABLE `travel_plan_items` (
  `id_item` bigint(20) UNSIGNED NOT NULL,
  `id_perencanaan` bigint(20) UNSIGNED NOT NULL,
  `destination_id` bigint(20) UNSIGNED NOT NULL,
  `source` enum('wisata','kuliner','penginapan') NOT NULL,
  `hari_ke` int(11) DEFAULT 1,
  `urutan` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `gambar` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `warning_count` int(11) NOT NULL DEFAULT 0,
  `deactivation_reason_code` varchar(255) DEFAULT NULL,
  `deactivation_reason_detail` text DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `email`, `email_verified_at`, `password`, `role`, `gambar`, `is_active`, `warning_count`, `deactivation_reason_code`, `deactivation_reason_detail`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin_tripmate', 'admin@tripmate.id', NULL, '$2y$12$8ZJKZcfHXOfjEztckdMaN.0QGHgSF5hYPBcN/zZlfEBAPSmq6BnEe', 'admin', NULL, 1, 0, NULL, NULL, NULL, '2026-08-04 05:23:55', '2026-08-04 05:23:55'),
(2, 'user_demo', 'user@gmail.com', NULL, '$2y$12$vZi1F3UF7tMi7BsgS6pLRuwO166tL4KI8m9tD6UPeYjV3v.Cr40em', 'user', NULL, 1, 0, NULL, NULL, NULL, '2026-08-04 05:23:55', '2026-08-04 05:23:55'),
(3, 'diaz', 'diaz@gmail.com', NULL, '$2y$12$Uz4W/FUIDuJY0DhxL1PLZuMs9x5jqBrV5WiYRKrRJyHKF6anNaCLG', 'user', 'avatars/n7tnYD6dV0njUSXD6LitLbT80TC6fYhmBCMR2IKq.jpg', 1, 0, NULL, NULL, NULL, '2026-08-04 07:16:19', '2026-08-05 02:27:29');

-- --------------------------------------------------------

--
-- Table structure for table `user_notifications`
--

CREATE TABLE `user_notifications` (
  `id_user_notifications` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `pesan` text NOT NULL,
  `tgl` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_preferences`
--

CREATE TABLE `user_preferences` (
  `id_user_preferences` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `budget` varchar(20) DEFAULT NULL,
  `ukuran_grup` int(11) DEFAULT NULL,
  `tipe_destinasi` varchar(255) DEFAULT NULL,
  `minat_wisata` text DEFAULT NULL,
  `hidden_gem` tinyint(1) NOT NULL DEFAULT 0,
  `user_preferences` text DEFAULT NULL,
  `kota_preferensi` varchar(255) DEFAULT NULL,
  `provinsi_preferensi` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `trend` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_preferences`
--

INSERT INTO `user_preferences` (`id_user_preferences`, `id_user`, `budget`, `ukuran_grup`, `tipe_destinasi`, `minat_wisata`, `hidden_gem`, `user_preferences`, `kota_preferensi`, `provinsi_preferensi`, `created_at`, `updated_at`, `trend`) VALUES
(1, 3, 'Murah', NULL, NULL, '[\"Wisata Alam\"]', 0, NULL, 'Bandung', NULL, '2026-08-04 10:09:17', '2026-08-05 03:28:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wisata`
--

CREATE TABLE `wisata` (
  `id_wisata` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` text DEFAULT NULL,
  `deskripsi` longtext DEFAULT NULL,
  `kota` varchar(100) DEFAULT NULL,
  `provinsi` varchar(100) DEFAULT NULL,
  `transportasi` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `harga` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tipe_wisata` varchar(255) DEFAULT NULL,
  `jam_buka` time DEFAULT NULL,
  `jam_tutup` time DEFAULT NULL,
  `hari_operasional` varchar(100) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `kategori_wisata` varchar(255) DEFAULT NULL,
  `trend` varchar(20) NOT NULL DEFAULT 'Populer',
  `budget` varchar(20) DEFAULT NULL,
  `fasilitas` text DEFAULT NULL,
  `fitur_cbf` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wisata`
--

INSERT INTO `wisata` (`id_wisata`, `nama`, `alamat`, `deskripsi`, `kota`, `provinsi`, `transportasi`, `gambar`, `harga`, `tipe_wisata`, `jam_buka`, `jam_tutup`, `hari_operasional`, `latitude`, `longitude`, `kategori_wisata`, `trend`, `budget`, `fasilitas`, `fitur_cbf`, `created_at`, `updated_at`) VALUES
(1, 'Taman Mini Indonesia Indah (TMII)', 'Jl. Raya Taman Mini, Ceger, Cipayung', 'Suasana edukatif dan sangat rimbun pasca-revitalisasi menjadi kawasan bebas emisi. Menyuguhkan anjungan 33 provinsi. Keunikannya terletak pada wahana kereta gantung lintas danau nusantara, museum komodo, dan pertunjukan air mancur \"Tirta Bercerita\".', 'Jakarta Timur', 'DKI Jakarta', 'LRT (Stasiun TMII), TransJakarta, Kendaraan Pribadi', 'https://tse1.mm.bing.net/th/id/OIP.cI5Jl6lUq-IXMv_JndK_OwHaEa?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 50000.00, 'Wisata Budaya', '06:00:00', '20:00:00', 'Setiap Hari', -6.30439600, 106.88926300, 'Wisata Budaya', 'Ikonik', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata budaya ikonik murah jakarta timur dki jakarta lrt stasiun tmii transjakarta kendaraan pribadi parkir toilet mushola area istirahat area merokok pembayaran tunai suasana edukatif dan sangat rimbun pasca revitalisasi menjadi kawasan bebas emisi menyuguhkan anjungan 33 provinsi keunikannya terletak pada wahana kereta gantung lintas danau nusantara museum komodo dan pertunjukan air mancur tirta bercerita', NULL, NULL),
(2, 'Monumen Nasional (Monas)', 'Jl. Silang Monas, Gambir', 'Taman pelataran monumental yang sangat luas dan berangin. Keunikan utamanya adalah menara tugu setinggi 132 meter berlapis emas, memungkinkan wisatawan melihat tata kota Jakarta dari dek observasi, serta menelusuri diorama kemerdekaan di dasar tugu.', 'Jakarta Pusat', 'DKI Jakarta', 'KRL (Gondangdia/Juanda), TransJakarta (Halte Monas)', 'https://i.pinimg.com/originals/0b/e6/57/0be6578b5a1c08ddf706eb6191e8aa52.jpg', 10000.00, 'Wisata Sejarah', '08:00:00', '22:00:00', 'Selasa - Minggu (Senin tutup)', -6.17515200, 106.82714800, 'Wisata Sejarah', 'Ikonik', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata sejarah ikonik murah jakarta pusat dki jakarta krl gondangdia juanda transjakarta halte monas parkir toilet mushola area istirahat area merokok pembayaran tunai taman pelataran monumental yang sangat luas dan berangin keunikan utamanya adalah menara tugu setinggi 132 meter berlapis emas memungkinkan wisatawan melihat tata kota jakarta dari dek observasi serta menelusuri diorama kemerdekaan di dasar tugu', NULL, NULL),
(3, 'Museum Nasional Indonesia (Museum Gajah)', 'Jl. Medan Merdeka Barat No.12, Gambir', 'Memancarkan kemegahan institusi peninggalan zaman kolonial. Sangat unik dengan instalasi interaktif baru berbasis kecerdasan buatan, koleksi arca Hindu-Buddha terlengkap, serta pertunjukan video mapping di fasad gedungnya saat malam hari.', 'Jakarta Pusat', 'DKI Jakarta', 'TransJakarta (Halte Monas), KRL (Gambir)', 'https://tse3.mm.bing.net/th/id/OIP.AhwevMXdXaYd02mswY7B8AHaFS?r=0&o=7rm=3&rs=1&pid=ImgDetMain&o=7&rm=3', 50000.00, 'Wisata Sejarah', '08:00:00', '16:00:00', 'Selasa - Minggu (Jumat-Sabtu tutup 20:00)', -6.17614600, 106.82154700, 'Wisata Sejarah', 'Ikonik', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata sejarah ikonik murah jakarta pusat dki jakarta transjakarta halte monas krl gambir parkir toilet mushola area istirahat area merokok pembayaran tunai memancarkan kemegahan institusi peninggalan zaman kolonial sangat unik dengan instalasi interaktif baru berbasis kecerdasan buatan koleksi arca hindu buddha terlengkap serta pertunjukan video mapping di fasad gedungnya saat malam hari', NULL, NULL),
(4, 'Kawasan Kota Tua Jakarta & Fatahillah', 'Jl. Taman Fatahillah No.1, Pinangsia', 'Beratmosfer persis seperti kota-kota di Eropa kuno (Little Amsterdam). Alun-alun dihiasi pengamen jalanan dan sepeda ontel warna-warni. Menu andalan di sekitarnya adalah sajian indies dan kopi klasik dari Cafe Batavia peninggalan abad ke-19.', 'Jakarta Barat', 'DKI Jakarta', 'KRL (Stasiun Jakarta Kota), TransJakarta (Halte Kota)', 'https://tse2.mm.bing.net/th/id/OIP.yCwq2yez1FagUdw-NwNn5gHaE8?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 0.00, 'Wisata Sejarah', '00:00:00', '23:59:00', 'Senin - Minggu (Museum Selasa-Minggu)', -6.13485500, 106.81365800, 'Wisata Sejarah', 'Ikonik', 'gratis', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata sejarah ikonik gratis jakarta barat dki jakarta krl stasiun jakarta kota transjakarta halte kota parkir toilet mushola area istirahat area merokok pembayaran tunai beratmosfer persis seperti kota kota di eropa kuno little amsterdam alun alun dihiasi pengamen jalanan dan sepeda ontel warna warni menu andalan di sekitarnya adalah sajian indies dan kopi klasik dari cafe batavia peninggalan abad ke 19', NULL, NULL),
(5, 'Museum Bank Indonesia', 'Jl. Pintu Besar Utara No.3, Pinangsia', 'Mengusung gaya arsitektur palatial yang mewah, dingin, dan tertata sempurna. Sangat unik karena wisatawan dapat memasuki ruangan brankas otentik bekas De Javasche Bank yang berisi tumpukan balok emas tiruan, didukung dengan teknologi visual termutakhir.', 'Jakarta Barat', 'DKI Jakarta', 'KRL (Stasiun Jakarta Kota), TransJakarta', 'https://www.citimhotel.com/wp-content/uploads/2020/12/photo6251136054367202526-1.jpg', 50000.00, 'Wisata Edukasi', '08:00:00', '21:00:00', 'Selasa - Minggu', -6.13700600, 106.81296700, 'Wisata Edukasi', 'Terkenal', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata edukasi terkenal murah jakarta barat dki jakarta krl stasiun jakarta kota transjakarta parkir toilet mushola area istirahat area merokok pembayaran tunai mengusung gaya arsitektur palatial yang mewah dingin dan tertata sempurna sangat unik karena wisatawan dapat memasuki ruangan brankas otentik bekas de javasche bank yang berisi tumpukan balok emas tiruan didukung dengan teknologi visual termutakhir', NULL, NULL),
(6, 'Museum MACAN', 'AKR Tower Level MM, Jl. Panjang No.5, Kebon Jeruk', 'Berada di dalam gedung perkantoran, atmosfernya sunyi, eksklusif, berskala internasional, dan estetis. Keunikan absolutnya adalah kepemilikan instalasi Infinity Mirrored Room karya Yayoi Kusama. Menyediakan kopi artisan dari Common Grounds.', 'Jakarta Barat', 'DKI Jakarta', 'TransJakarta (Halte Kebon Jeruk), Taksi Daring', 'https://tse3.mm.bing.net/th/id/OIP.KL3NFsCaIWYDWt5NvIgysQHaE7?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 110000.00, 'Wisata Budaya', '10:00:00', '18:00:00', 'Selasa - Minggu', -6.19075420, 106.76794000, 'Wisata Budaya', 'Terkenal', 'sedang', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata budaya terkenal sedang jakarta barat dki jakarta transjakarta halte kebon jeruk taksi daring parkir toilet mushola area istirahat area merokok pembayaran tunai berada di dalam gedung perkantoran atmosfernya sunyi eksklusif berskala internasional dan estetis keunikan absolutnya adalah kepemilikan instalasi infinity mirrored room karya yayoi kusama menyediakan kopi artisan dari common grounds', NULL, NULL),
(7, 'Dunia Fantasi (Dufan) Ancol', 'Jl. Lodan Timur No.7, Ancol, Pademangan', 'Taman hiburan keluarga super meriah yang penuh sorak sorai dan lagu tema nostalgia. Menawarkan keseruan pacu adrenalin dari wahana ikonik seperti Halilintar, Bianglala, Kora-Kora, hingga area tematik bergaya benua Amerika dan Asia.', 'Jakarta Utara', 'DKI Jakarta', 'TransJakarta (Halte Ancol), KRL (Ancol)', 'https://3.bp.blogspot.com/-dOH5ynICOJs/XNpnRAB-gKI/AAAAAAAAFnY/1gVcVmt7QA4iWuWq_o7DO8kn9cQDf7A2ACLcBGAs/s1600/Turangga%2BRangga%2BDunia%2BFantasi%2BAncol.jpg', 250000.00, 'Taman Hiburan', '10:00:00', '18:00:00', 'Senin - Minggu', -6.12507600, 106.83357500, 'Taman Hiburan', 'Ikonik', 'mahal', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'taman hiburan ikonik mahal jakarta utara dki jakarta transjakarta halte ancol krl ancol parkir toilet mushola area istirahat area merokok pembayaran tunai taman hiburan keluarga super meriah yang penuh sorak sorai dan lagu tema nostalgia menawarkan keseruan pacu adrenalin dari wahana ikonik seperti halilintar bianglala kora kora hingga area tematik bergaya benua amerika dan asia', NULL, NULL),
(8, 'Sea World Ancol', 'Jl. Lodan Timur No.7, Ancol, Pademangan', 'Memiliki suasana remang kebiruan menyerupai dasar lautan dalam yang sejuk. Keunikannya adalah keberadaan terowongan akuarium raksasa kaca (underwater tunnel), kolam sentuh ikan hiu anakan, dan atraksi penyelam memberi makan kawanan predator laut.', 'Jakarta Utara', 'DKI Jakarta', 'TransJakarta (Halte Ancol), KRL (Ancol)', 'https://jayasukses.com/wp-content/uploads/2023/06/Sea-World-Ancol.png', 110000.00, 'Wisata Bahari', '09:00:00', '17:00:00', 'Setiap Hari', -6.12562900, 106.84278900, 'Wisata Bahari', 'Terkenal', 'sedang', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata bahari terkenal sedang jakarta utara dki jakarta transjakarta halte ancol krl ancol parkir toilet mushola area istirahat area merokok pembayaran tunai memiliki suasana remang kebiruan menyerupai dasar lautan dalam yang sejuk keunikannya adalah keberadaan terowongan akuarium raksasa kaca underwater tunnel kolam sentuh ikan hiu anakan dan atraksi penyelam memberi makan kawanan predator laut', NULL, NULL),
(9, 'Ocean Dream Samudra', 'Jl. Lodan Timur No.7, Ancol, Pademangan', 'Sentra konservasi luar ruangan berangin laut yang cocok untuk anak-anak. Menawarkan atraksi edukatif teater mamalia seperti kepintaran lumba-lumba melompat, pertunjukan aksi singa laut lucu, dan habitat koleksi burung langka.', 'Jakarta Utara', 'DKI Jakarta', 'TransJakarta (Halte Ancol), KRL (Ancol)', 'https://tse4.mm.bing.net/th/id/OIP._9oRRRu84JRyOnh0GfPzhQHaFj?r=0&o=7rm=3&rs=1&pid=ImgDetMain&o=7&rm=3', 110000.00, 'Wisata Edukasi', '09:00:00', '17:00:00', 'Setiap Hari', -6.12474900, 106.84344320, 'Wisata Edukasi', 'Terkenal', 'sedang', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata edukasi terkenal sedang jakarta utara dki jakarta transjakarta halte ancol krl ancol parkir toilet mushola area istirahat area merokok pembayaran tunai sentra konservasi luar ruangan berangin laut yang cocok untuk anak anak menawarkan atraksi edukatif teater mamalia seperti kepintaran lumba lumba melompat pertunjukan aksi singa laut lucu dan habitat koleksi burung langka', NULL, NULL),
(10, 'Taman Ismail Marzuki (TIM)', 'Jl. Cikini Raya No.73, Menteng', 'Pusat kesenian paling bergengsi pasca-pemugaran dengan gaya arsitektur industrial-organik yang hangat. Keunikan utamanya adalah gedung Perpustakaan Jakarta yang amat estetik dan planetarium besar, dikelilingi taman hijau yang cocok untuk berdiskusi.', 'Jakarta Pusat', 'DKI Jakarta', 'KRL (Stasiun Cikini), TransJakarta', 'https://asset.kompas.com/crops/P30BnHETsi-8fPUeTbZTOJJjeuw=/33x43:769x533/1200x800/data/photo/2018/11/05/173354301.jpg', 0.00, 'Wisata Budaya', '08:00:00', '23:59:00', 'Setiap Hari', -6.19085700, 106.83836500, 'Wisata Budaya', 'Ikonik', 'gratis', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata budaya ikonik gratis jakarta pusat dki jakarta krl stasiun cikini transjakarta parkir toilet mushola area istirahat area merokok pembayaran tunai pusat kesenian paling bergengsi pasca pemugaran dengan gaya arsitektur industrial organik yang hangat keunikan utamanya adalah gedung perpustakaan jakarta yang amat estetik dan planetarium besar dikelilingi taman hijau yang cocok untuk berdiskusi', NULL, NULL),
(11, 'Museum di Tengah Kebun', 'Jl. Kemang Timur No.66, Bangka, Mampang Prapatan', 'Kapsul waktu nan sepi, hijau, dan amat sangat eksklusif. Menyimpan 4.000 pusaka seni dunia milik kolektor pribadi di sebuah rumah berarsitektur Joglo dengan taman rimbun. Keunikan utamanya terletak pada koleksi arca raksasa serta larangan berfoto di dalam galeri untuk merawat kesakralan.', 'Jakarta Selatan', 'DKI Jakarta', 'Kendaraan Pribadi, Taksi Daring', 'https://asset.kompas.com/crops/tT4_zwYOgzU_MJT9dyt7amboHpA=/0x130:1600x1197/1200x800/data/photo/2022/05/23/628aea050018b.jpeg', 0.00, 'Wisata Sejarah', '09:00:00', '15:00:00', 'Sabtu - Minggu (sesi 2 kloter)', -6.26727800, 106.82404200, 'Wisata Sejarah', 'Hidden Gem', 'gratis', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata sejarah hidden gem gratis jakarta selatan dki jakarta kendaraan pribadi taksi daring parkir toilet mushola area istirahat area merokok pembayaran tunai kapsul waktu nan sepi hijau dan amat sangat eksklusif menyimpan 4 000 pusaka seni dunia milik kolektor pribadi di sebuah rumah berarsitektur joglo dengan taman rimbun keunikan utamanya terletak pada koleksi arca raksasa serta larangan berfoto di dalam galeri untuk merawat kesakralan', NULL, NULL),
(12, 'Hauwke\'s Auto Gallery', 'Jl. Puri Mutiara VI No.18B, Cipete, Cilandak', 'Bernuansa persis seperti jalanan kota klasik Amerika era 1950-an. Sangat magis dengan pajangan lebih dari 50 mobil antik Eropa dan Amerika. Keunikannya adalah replika pompa bensin kuno, mobil limosin asli milik Presiden Soekarno, dan bar diner retro untuk fotografi pre-wedding.', 'Jakarta Selatan', 'DKI Jakarta', 'Kendaraan Pribadi, Taksi Daring', 'https://i.ytimg.com/vi/PB_3-e9tuQM/maxresdefault.jpg', 0.00, 'Wisata Sejarah', '09:00:00', '17:00:00', 'Setiap Hari', -6.27691900, 106.81335600, 'Wisata Sejarah', 'Hidden Gem', 'gratis', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata sejarah hidden gem gratis jakarta selatan dki jakarta kendaraan pribadi taksi daring parkir toilet mushola area istirahat area merokok pembayaran tunai bernuansa persis seperti jalanan kota klasik amerika era 1950 an sangat magis dengan pajangan lebih dari 50 mobil antik eropa dan amerika keunikannya adalah replika pompa bensin kuno mobil limosin asli milik presiden soekarno dan bar diner retro untuk fotografi pre wedding', NULL, NULL),
(13, 'Museum Prasasti', 'Jl. Tanah Abang I No.1, Petojo Selatan, Gambir', 'Bekas makam elit Belanda dengan nuansa gotik yang teduh, sepi, namun sedikit magis (melancholic beauty). Keunikannya terletak pada pahatan patung marmer \"bidadari menangis\", nisan para pahlawan dan pesohor kolonial, serta kereta jenazah kereta kuda berukir kayu yang menyeramkan.', 'Jakarta Pusat', 'DKI Jakarta', 'TransJakarta (Halte Monas), KRL (Juanda)', 'https://asset-2.tstatic.net/jakarta/foto/bank/images/museum-taman-prasasti.jpg', 5000.00, 'Wisata Sejarah', '09:00:00', '15:00:00', 'Selasa - Minggu', -6.17193500, 106.81874000, 'Wisata Sejarah', 'Hidden Gem', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata sejarah hidden gem murah jakarta pusat dki jakarta transjakarta halte monas krl juanda parkir toilet mushola area istirahat area merokok pembayaran tunai bekas makam elit belanda dengan nuansa gotik yang teduh sepi namun sedikit magis melancholic beauty keunikannya terletak pada pahatan patung marmer bidadari menangis nisan para pahlawan dan pesohor kolonial serta kereta jenazah kereta kuda berukir kayu yang menyeramkan', NULL, NULL),
(14, 'Gedung Candra Naya', 'Jl. Gajah Mada No.188, Glodok, Taman Sari', 'Paradoks arsitektural yang menakjubkan. Sebuah rumah bergaya Tionghoa abad 18 dengan atap melengkung ala dinasti yang dipertahankan di tengah himpitan gedung apartemen pencakar langit. Memiliki ukiran naga kayu dan halaman batu alam yang tenang memecah bisingnya jalanan Gajah Mada.', 'Jakarta Barat', 'DKI Jakarta', 'TransJakarta (Halte Glodok)', 'https://asset-2.tstatic.net/jakarta/foto/bank/images/gedung-candra-naya-yang-diapit-oleh-gedung-pencakar-langit.jpg', 0.00, 'Wisata Sejarah', '08:00:00', '17:00:00', 'Setiap Hari', -6.14718100, 106.81518700, 'Wisata Sejarah', 'Hidden Gem', 'gratis', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata sejarah hidden gem gratis jakarta barat dki jakarta transjakarta halte glodok parkir toilet mushola area istirahat area merokok pembayaran tunai paradoks arsitektural yang menakjubkan sebuah rumah bergaya tionghoa abad 18 dengan atap melengkung ala dinasti yang dipertahankan di tengah himpitan gedung apartemen pencakar langit memiliki ukiran naga kayu dan halaman batu alam yang tenang memecah bisingnya jalanan gajah mada', NULL, NULL),
(15, 'Cemara 6 Art Gallery', 'Jl. HOS. Cokroaminoto No.9-11, Menteng', 'Kombinasi galeri seni rupa modern dan kafe dengan suasana rumahan Menteng klasik yang teduh. Diinisiasi oleh intelektual Toeti Heraty, menyajikan koleksi pelukis maestro seperti Affandi dan Salim. Pengunjung dapat menikmati sajian teh atau kopi di area serambi sembari menelaah karya seni.', 'Jakarta Pusat', 'DKI Jakarta', 'KRL (Stasiun Gondangdia), Kendaraan Pribadi', 'https://tse3.mm.bing.net/th/id/OIP.trLqrVkuIJ4ToIZaAs4W_QHaDa?w=323&h=161&c=7&r=0&o=7&dpr=1.1&pid=1.7&rm=3', 35000.00, 'Wisata Budaya', '10:00:00', '17:00:00', 'Setiap Hari', -6.18812000, 106.82919500, 'Wisata Budaya', 'Hidden Gem', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata budaya hidden gem murah jakarta pusat dki jakarta krl stasiun gondangdia kendaraan pribadi parkir toilet mushola area istirahat area merokok pembayaran tunai kombinasi galeri seni rupa modern dan kafe dengan suasana rumahan menteng klasik yang teduh diinisiasi oleh intelektual toeti heraty menyajikan koleksi pelukis maestro seperti affandi dan salim pengunjung dapat menikmati sajian teh atau kopi di area serambi sembari menelaah karya seni', NULL, NULL),
(16, 'Setu Babakan', 'Srengseng Sawah, Jagakarsa', 'Desa budaya komunal yang sangat hidup di tepian danau resapan. Nuansa asri kampung tradisional Betawi kental terasa dengan deretan rumah kebaya. Menu andalannya adalah kerak telor otentik yang dimasak menggunakan tungku arang, bir pletok hangat, serta soto betawi kuah santan di pesisir danau.', 'Jakarta Selatan', 'DKI Jakarta', 'KRL (Stasiun Lenteng Agung), TransJakarta', 'https://tse3.mm.bing.net/th/id/OIP.B_ZS300hVzzkfhysWfAH3QHaHU?w=184&h=180&c=7&r=0&o=7&dpr=1.1&pid=1.7&rm=3', 0.00, 'Desa Wisata', '07:00:00', '17:00:00', 'Setiap Hari', -6.34094100, 106.82397400, 'Desa Wisata', 'Terkenal', 'gratis', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'desa wisata terkenal gratis jakarta selatan dki jakarta krl stasiun lenteng agung transjakarta parkir toilet mushola area istirahat area merokok pembayaran tunai desa budaya komunal yang sangat hidup di tepian danau resapan nuansa asri kampung tradisional betawi kental terasa dengan deretan rumah kebaya menu andalannya adalah kerak telor otentik yang dimasak menggunakan tungku arang bir pletok hangat serta soto betawi kuah santan di pesisir danau', NULL, NULL),
(17, 'Taman Margasatwa Ragunan', 'Jl. Harsono RM No.1, Ragunan, Pasar Minggu', 'Paru-paru kota berusia 160 tahun lebih yang meredam teriknya matahari Jakarta berkat tajuk pepohonan kanopi raksasa. Menawarkan atmosfer ekologis yang segar untuk lari pagi, piknik beralaskan tikar, edukasi satwa terlengkap, dan wahana pusat primata Schmutzer yang rimbun alami.', 'Jakarta Selatan', 'DKI Jakarta', 'TransJakarta (Halte Ragunan), JakCard', 'https://tse4.mm.bing.net/th/id/OIP.xc8ZP2zqmkEfQ6KJMKHieAHaEK?w=289&h=180&c=7&r=0&o=7&dpr=1.1&pid=1.7&rm=3', 4000.00, 'Wisata Alam', '07:00:00', '16:00:00', 'Selasa - Minggu', -6.30621600, 106.81854400, 'Wisata Alam', 'Ikonik', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata alam ikonik murah jakarta selatan dki jakarta transjakarta halte ragunan jakcard parkir toilet mushola area istirahat area merokok pembayaran tunai paru paru kota berusia 160 tahun lebih yang meredam teriknya matahari jakarta berkat tajuk pepohonan kanopi raksasa menawarkan atmosfer ekologis yang segar untuk lari pagi piknik beralaskan tikar edukasi satwa terlengkap dan wahana pusat primata schmutzer yang rimbun alami', NULL, NULL),
(18, 'Masjid Istiqlal', 'Jl. Taman Wijaya Kusuma, Ps. Baru, Sawah Besar', 'Memancarkan spiritualitas dalam kemegahan struktur marmer dan pilar baja tahan karat yang kolosal. Desain terbuka tanpa dinding membuat aliran angin sejuk menyelimuti jamaah. Keunikannya terletak pada satu kubah utama raksasa dan statusnya sebagai simbol kebanggaan toleransi arsitektural di Asia Tenggara.', 'Jakarta Pusat', 'DKI Jakarta', 'TransJakarta (Halte Juanda), KRL (Juanda)', 'https://tse3.mm.bing.net/th/id/OIP.lHd93m1HWEDtWE0xorcwUwHaEw?w=300&h=193&c=7&r=0&o=7&dpr=1.1&pid=1.7&rm=3', 0.00, 'Wisata Religi', '04:00:00', '21:00:00', 'Setiap Hari', -6.16983500, 106.83095100, 'Wisata Religi', 'Ikonik', 'gratis', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata religi ikonik gratis jakarta pusat dki jakarta transjakarta halte juanda krl juanda parkir toilet mushola area istirahat area merokok pembayaran tunai memancarkan spiritualitas dalam kemegahan struktur marmer dan pilar baja tahan karat yang kolosal desain terbuka tanpa dinding membuat aliran angin sejuk menyelimuti jamaah keunikannya terletak pada satu kubah utama raksasa dan statusnya sebagai simbol kebanggaan toleransi arsitektural di asia tenggara', NULL, NULL),
(19, 'Gereja Katedral Jakarta', 'Jl. Katedral No.7, Ps. Baru, Sawah Besar', 'Sangat dramatis dengan nuansa gereja Eropa Barat. Interior dipenuhi cahaya temaram dari jendela kaca patri bergambar kronologi suci dan langit-langit berukir kayu tinggi. Memiliki tiga menara besi tajam yang ikonis, serta museum relikui sejarah misi Katolik di bagian balkon atasnya.', 'Jakarta Pusat', 'DKI Jakarta', 'TransJakarta (Halte Juanda), KRL (Juanda)', 'https://tse1.mm.bing.net/th/id/OIP.T0jKzQKQndYe_J-7PgN2tgHaEK?w=309&h=180&c=7&r=0&o=7&dpr=1.1&pid=1.7&rm=3', 0.00, 'Wisata Religi', '06:00:00', '20:00:00', 'Setiap Hari', -6.16901400, 106.83330000, 'Wisata Religi', 'Ikonik', 'gratis', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata religi ikonik gratis jakarta pusat dki jakarta transjakarta halte juanda krl juanda parkir toilet mushola area istirahat area merokok pembayaran tunai sangat dramatis dengan nuansa gereja eropa barat interior dipenuhi cahaya temaram dari jendela kaca patri bergambar kronologi suci dan langit langit berukir kayu tinggi memiliki tiga menara besi tajam yang ikonis serta museum relikui sejarah misi katolik di bagian balkon atasnya', NULL, NULL),
(20, 'Taman Situ Lembang', 'Jl. Lembang, Menteng', 'Danau artifisial kuno yang terapit oleh rumah-rumah menteng bernilai miliaran rupiah. Merupakan oase meditasi, sangat sunyi dari deru mesin kendaraan. Wisatawan kerap berkunjung hanya untuk duduk membaca buku, memancing rekreasional, atau menikmati mekarnya bunga teratai di pagi berkabut.', 'Jakarta Pusat', 'DKI Jakarta', 'KRL (Stasiun Cikini/Sudirman), Taksi Daring', 'https://tse3.mm.bing.net/th/id/OIP._mfdnQtdCf_allPEUL1dswHaEI?w=277&h=180&c=7&r=0&o=7&dpr=1.1&pid=1.7&rm=3', 0.00, 'Wisata Alam', '06:00:00', '18:00:00', 'Setiap Hari', -6.19752300, 106.83467300, 'Wisata Alam', 'Hidden Gem', 'gratis', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata alam hidden gem gratis jakarta pusat dki jakarta krl stasiun cikini sudirman taksi daring parkir toilet mushola area istirahat area merokok pembayaran tunai danau artifisial kuno yang terapit oleh rumah rumah menteng bernilai miliaran rupiah merupakan oase meditasi sangat sunyi dari deru mesin kendaraan wisatawan kerap berkunjung hanya untuk duduk membaca buku memancing rekreasional atau menikmati mekarnya bunga teratai di pagi berkabut', NULL, NULL),
(21, 'Tebet Eco Park', 'Jl. Tebet Barat Raya, Tebet', 'Ruang publik progresif berdesain ekologi mutakhir. Suasana asri dan penuh interaksi komunal urban. Ikon absolutnya adalah jembatan estetis Infinity Link Bridge berkelir oranye, koridor lahan basah untuk observasi hidrologi, serta titik-titik bersantai estetik yang digandrungi kaum pekerja remote.', 'Jakarta Selatan', 'DKI Jakarta', 'KRL (Stasiun Cawang), TransJakarta', 'https://tse4.mm.bing.net/th/id/OIP.9ivsOzQNZLWVP5z73WWJeQHaE8?w=269&h=180&c=7&r=0&o=7&dpr=1.1&pid=1.7&rm=3', 0.00, 'Wisata Alam', '06:00:00', '20:00:00', 'Setiap Hari', -6.23692400, 106.85288600, 'Wisata Alam', 'Terkenal', 'gratis', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata alam terkenal gratis jakarta selatan dki jakarta krl stasiun cawang transjakarta parkir toilet mushola area istirahat area merokok pembayaran tunai ruang publik progresif berdesain ekologi mutakhir suasana asri dan penuh interaksi komunal urban ikon absolutnya adalah jembatan estetis infinity link bridge berkelir oranye koridor lahan basah untuk observasi hidrologi serta titik titik bersantai estetik yang digandrungi kaum pekerja remote', NULL, NULL),
(22, 'Hutan Mangrove Pantai Indah Kapuk (PIK)', 'Jl. Katamaran Indah 1, Kapuk Muara, Penjaringan', 'Surga hijau di tepi muara yang didominasi akar napas pohon bakau berlumpur. Suasana menenangkan khas habitat muara dengan embusan angin pesisir. Wisatawan gemar menyewa kano air atau berburu komposisi foto landscape di sepanjang lintasan jembatan titian kayu bambu meliuk.', 'Jakarta Utara', 'DKI Jakarta', 'TransJakarta, Kendaraan Pribadi', 'https://tse2.mm.bing.net/th/id/OIP.-ouWuSDEhNXs7QbYHtXEagHaEU?w=299&h=180&c=7&r=0&o=7&dpr=1.1&pid=1.7&rm=3', 30000.00, 'Wisata Alam', '08:00:00', '17:30:00', 'Setiap Hari', -6.10597000, 106.73705900, 'Wisata Alam', 'Terkenal', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata alam terkenal murah jakarta utara dki jakarta transjakarta kendaraan pribadi parkir toilet mushola area istirahat area merokok pembayaran tunai surga hijau di tepi muara yang didominasi akar napas pohon bakau berlumpur suasana menenangkan khas habitat muara dengan embusan angin pesisir wisatawan gemar menyewa kano air atau berburu komposisi foto landscape di sepanjang lintasan jembatan titian kayu bambu meliuk', NULL, NULL),
(23, 'Satriamandala Museum', 'Jl. Gatot Subroto No.14, Kuningan Barat, Mampang', 'Museum bernuansa heroisme militer yang mengedepankan displai fisik seperti alutsista, meriam, jet tempur, dan persenjataan masa pra-kemerdekaan. Keunikan utamanya adalah letak kafe Kampoeng Djoeang rahasia di halaman belakang yang memutar lagu nostalgia sembari menyajikan camilan lokal.', 'Jakarta Selatan', 'DKI Jakarta', 'TransJakarta (Halte Gatot Subroto)', 'https://tse2.mm.bing.net/th/id/OIP.rT1FfCHk3-ZGXvsqCFzKTgHaDa?r=0&o=7rm=3&rs=1&pid=ImgDetMain&o=7&rm=3', 5000.00, 'Wisata Sejarah', '08:00:00', '21:00:00', 'Selasa - Minggu', -6.23154000, 106.81900800, 'Wisata Sejarah', 'Terkenal', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata sejarah terkenal murah jakarta selatan dki jakarta transjakarta halte gatot subroto parkir toilet mushola area istirahat area merokok pembayaran tunai museum bernuansa heroisme militer yang mengedepankan displai fisik seperti alutsista meriam jet tempur dan persenjataan masa pra kemerdekaan keunikan utamanya adalah letak kafe kampoeng djoeang rahasia di halaman belakang yang memutar lagu nostalgia sembari menyajikan camilan lokal', NULL, NULL),
(24, 'Museum Wayang', 'Jl. Pintu Besar Utara No.27, Kota Tua', 'Membawa pengunjung pada suasana pedesaan Jawa klasik lewat lantunan pelan musik gamelan di dalam gedung kolonial. Menyajikan ratusan koleksi boneka wayang ukir dari penjuru nusantara, wayang rumput, serta pergelaran hidup wayang kulit di akhir pekan oleh dalang tersohor.', 'Jakarta Barat', 'DKI Jakarta', 'KRL (Jakarta Kota), TransJakarta (Halte Kota)', 'https://radarbanyumas.disway.id/upload/cae6ae17e75bd33d99c5d0470d6b63ce.jpeg', 50000.00, 'Wisata Budaya', '09:00:00', '15:30:00', 'Selasa - Minggu', -6.13468300, 106.81228900, 'Wisata Budaya', 'Terkenal', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata budaya terkenal murah jakarta barat dki jakarta krl jakarta kota transjakarta halte kota parkir toilet mushola area istirahat area merokok pembayaran tunai membawa pengunjung pada suasana pedesaan jawa klasik lewat lantunan pelan musik gamelan di dalam gedung kolonial menyajikan ratusan koleksi boneka wayang ukir dari penjuru nusantara wayang rumput serta pergelaran hidup wayang kulit di akhir pekan oleh dalang tersohor', NULL, NULL),
(25, 'Museum Tekstil', 'Jl. K.S. Tubun No.2-4, Kota Bambu Selatan, Palmerah', 'Gedung bercorak vernakular Hindia dengan pekarangan luas. Di dalamnya tersimpan keindahan ribuan gulung tenun tradisional, songket pakan, dan batik corak langka Nusantara. Fasilitas primadonanya adalah bilik pendopo bagi pengunjung yang ingin meracik lilin malam dan belajar teknik membatik.', 'Jakarta Barat', 'DKI Jakarta', 'KRL (Stasiun Tanah Abang), TransJakarta', 'https://tse1.mm.bing.net/th/id/OIP.tGFK0_QcxkgTxRrE1jhG3wHaE8?r=0&o=7rm=3&rs=1&pid=ImgDetMain&o=7&rm=3', 5000.00, 'Wisata Budaya', '09:00:00', '15:00:00', 'Selasa - Minggu', -6.18816100, 106.80962600, 'Wisata Budaya', 'Terkenal', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata budaya terkenal murah jakarta barat dki jakarta krl stasiun tanah abang transjakarta parkir toilet mushola area istirahat area merokok pembayaran tunai gedung bercorak vernakular hindia dengan pekarangan luas di dalamnya tersimpan keindahan ribuan gulung tenun tradisional songket pakan dan batik corak langka nusantara fasilitas primadonanya adalah bilik pendopo bagi pengunjung yang ingin meracik lilin malam dan belajar teknik membatik', NULL, NULL),
(26, 'Atsiri Museum Sarinah', 'Gedung Sarinah Lantai 5, Jl. M.H. Thamrin', 'Tersembunyi rapi di lantai lima pusat perbelanjaan Sarinah, museum ini memberikan stimulasi olfaktori atau penciuman. Atmosfer dipenuhi aroma tanaman atsiri menenangkan. Pengunjung diajak meracik wewangian sendiri, mengeksplorasi tanaman aromaterapi endemik, diiringi pencahayaan ambient yang syahdu.', 'Jakarta Pusat', 'DKI Jakarta', 'MRT (Stasiun Bundaran HI), TransJakarta', 'https://tse4.mm.bing.net/th/id/OIP.rf47UNPZpPZ2p0-DO7z4lAHaF4?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 50000.00, 'Wisata Edukasi', '10:00:00', '21:00:00', 'Setiap Hari', -6.18709100, 106.82342400, 'Wisata Edukasi', 'Terkenal', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata edukasi terkenal murah jakarta pusat dki jakarta mrt stasiun bundaran hi transjakarta parkir toilet mushola area istirahat area merokok pembayaran tunai tersembunyi rapi di lantai lima pusat perbelanjaan sarinah museum ini memberikan stimulasi olfaktori atau penciuman atmosfer dipenuhi aroma tanaman atsiri menenangkan pengunjung diajak meracik wewangian sendiri mengeksplorasi tanaman aromaterapi endemik diiringi pencahayaan ambient yang syahdu', NULL, NULL),
(27, 'MoJa Museum', 'Kompleks Gelora Bung Karno, Senayan', 'Suasana semarak bergaya roller-disco ala dekade 80-an yang cerah dan dipenuhi pendar cahaya neon pastel. Merupakan pusat rekreasi swafoto anak muda. Daya tariknya adalah lintasan aspal arena bermain sepatu roda ganda dan tembok labirin interaktif yang dapat dicorat-coret dengan kuas lukis oleh pengunjung.', 'Jakarta Pusat', 'DKI Jakarta', 'MRT (Istora Mandiri), TransJakarta', 'https://assets.poskota.co.id/crop/original/medias/2025/Sep/12/moja-museum.jpg', 125000.00, 'Wisata Buatan', '11:00:00', '19:30:00', 'Setiap Hari', -6.21750700, 106.80321700, 'Wisata Buatan', 'Terkenal', 'sedang', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata buatan terkenal sedang jakarta pusat dki jakarta mrt istora mandiri transjakarta parkir toilet mushola area istirahat area merokok pembayaran tunai suasana semarak bergaya roller disco ala dekade 80 an yang cerah dan dipenuhi pendar cahaya neon pastel merupakan pusat rekreasi swafoto anak muda daya tariknya adalah lintasan aspal arena bermain sepatu roda ganda dan tembok labirin interaktif yang dapat dicorat coret dengan kuas lukis oleh pengunjung', NULL, NULL),
(28, 'Pos Bloc Jakarta', 'Jl. Pos No.2, Ps. Baru, Sawah Besar', 'Simbol kebangkitan ekonomi sirkular pada cangkang arsitektur eks-Kantor Pos era Belanda. Menghadirkan atmosfer kultural modern, ramai namun berkelas. Sangat digemari karena perpaduan lapak perancang busana lokal, pameran kriya, dan menu pastry atau kopi susu pandan di great hall dengan plafon kaca raksasa tinggi.', 'Jakarta Pusat', 'DKI Jakarta', 'KRL (Stasiun Juanda), TransJakarta (Pasar Baru)', 'https://tse4.mm.bing.net/th/id/OIP.MufTzpVFXE4M1sTY3EUldgHaF7?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 0.00, 'Wisata Buatan', '10:00:00', '21:00:00', 'Senin - Minggu (Sabtu-Minggu buka 07:00)', -6.16686000, 106.83379200, 'Wisata Buatan', 'Terkenal', 'gratis', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata buatan terkenal gratis jakarta pusat dki jakarta krl stasiun juanda transjakarta pasar baru parkir toilet mushola area istirahat area merokok pembayaran tunai simbol kebangkitan ekonomi sirkular pada cangkang arsitektur eks kantor pos era belanda menghadirkan atmosfer kultural modern ramai namun berkelas sangat digemari karena perpaduan lapak perancang busana lokal pameran kriya dan menu pastry atau kopi susu pandan di great hall dengan plafon kaca raksasa tinggi', NULL, NULL),
(29, 'M Bloc Space', 'Jl. Panglima Polim No.37, Melawai, Kebayoran Baru', 'Episentrum berkumpulnya budaya perlawanan artistik (counter-culture) di perumahan kuno gaya tropis dekade 50-an milik Peruri. Udara dipenuhi gaung musik independen dan obrolan pemuda. Gudang belakang diubah menjadi ruang konser tanpa kursi duduk. Menjual kopi literan dan beragam kuliner kasual Jakarta Selatan.', 'Jakarta Selatan', 'DKI Jakarta', 'MRT (Stasiun Blok M), TransJakarta', 'https://tse3.mm.bing.net/th/id/OIP.hb4dPGFDp1TTEkxvItesBAHaE4?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 0.00, 'Wisata Buatan', '10:00:00', '22:00:00', 'Setiap Hari', -6.24130400, 106.79873400, 'Wisata Buatan', 'Terkenal', 'gratis', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata buatan terkenal gratis jakarta selatan dki jakarta mrt stasiun blok m transjakarta parkir toilet mushola area istirahat area merokok pembayaran tunai episentrum berkumpulnya budaya perlawanan artistik counter culture di perumahan kuno gaya tropis dekade 50 an milik peruri udara dipenuhi gaung musik independen dan obrolan pemuda gudang belakang diubah menjadi ruang konser tanpa kursi duduk menjual kopi literan dan beragam kuliner kasual jakarta selatan', NULL, NULL),
(30, 'ALOHA Pasir Putih', 'Kawasan Pantai Indah Kapuk 2 (PIK 2)', 'Sebuah anomali pesisir tropis di Jakarta. Menciptakan ilusi atmosfer riang gembira layaknya sedang berlibur di resor tepi laut kepulauan Hawaii. Menyuguhkan pemandangan ombak lepas berbatasan pasir putih buatan yang membentang, diselubungi tenan bar dan kafe yang menyajikan minuman buah kelapa dan boga bahari ringan.', 'Jakarta Utara', 'DKI Jakarta', 'Kendaraan Pribadi, Bus Shuttle PIK', 'https://asset-2.tstatic.net/wartakota/foto/bank/images/Aloha-Pasir-Putih-PIK-2.jpg', 0.00, 'Wisata Buatan', '10:00:00', '23:00:00', 'Setiap Hari', -6.07162800, 106.71504600, 'Wisata Buatan', 'Terkenal', 'gratis', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata buatan terkenal gratis jakarta utara dki jakarta kendaraan pribadi bus shuttle pik parkir toilet mushola area istirahat area merokok pembayaran tunai sebuah anomali pesisir tropis di jakarta menciptakan ilusi atmosfer riang gembira layaknya sedang berlibur di resor tepi laut kepulauan hawaii menyuguhkan pemandangan ombak lepas berbatasan pasir putih buatan yang membentang diselubungi tenan bar dan kafe yang menyajikan minuman buah kelapa dan boga bahari ringan', NULL, NULL),
(31, 'Pantjoran PIK', 'Kawasan Golf Island PIK, Pantai Maju', 'Ruang gastronomi masif berskala makro dengan rekayasa tematik pecinan dinasti oriental raksasa. Menawarkan kelimpahan kedai legendaris seperti nasi campur, dim sum, hingga seafood tumpah ruah. Ornamen warna merah emas berpadu dengan lanskap pagoda bertingkat menghiasi kawasan gemerlap ini tanpa henti.', 'Jakarta Utara', 'DKI Jakarta', 'Kendaraan Pribadi, Bus Shuttle PIK', 'https://www.exploresunda.com/images/Pantjoran-PIK-2.jpg', 0.00, 'Wisata Kuliner', '07:00:00', '23:00:00', 'Setiap Hari', -6.09027300, 106.74446900, 'Wisata Kuliner', 'Terkenal', 'gratis', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata kuliner terkenal gratis jakarta utara dki jakarta kendaraan pribadi bus shuttle pik parkir toilet mushola area istirahat area merokok pembayaran tunai ruang gastronomi masif berskala makro dengan rekayasa tematik pecinan dinasti oriental raksasa menawarkan kelimpahan kedai legendaris seperti nasi campur dim sum hingga seafood tumpah ruah ornamen warna merah emas berpadu dengan lanskap pagoda bertingkat menghiasi kawasan gemerlap ini tanpa henti', NULL, NULL),
(32, 'Petak Sembilan (Glodok Chinatown)', 'Jl. Kemenangan Raya, Glodok, Taman Sari', 'Representasi riil permukiman pecinan otentik masa lampau Batavia yang masih bernapas. Menyuguhkan hiruk pikuk aktivitas warga lokal di jalanan sempit. Semerbak bau asap hio persembahyangan mengiringi langkah menuju Vihara Dharma Bhakti. Pengunjung kerap berburu menu andalan secangkir es kopi susu Tak Kie yang legendaris, pia kacang hijau panas, ikan pihi, dan ramuan obat tradisional.', 'Jakarta Barat', 'DKI Jakarta', 'TransJakarta (Halte Glodok), MRT (Fase 2 kelak)', 'https://idetrips.com/wp-content/uploads/2023/02/glodok-chinatown-market-area-640x480.jpg', 0.00, 'Wisata Budaya', '06:00:00', '17:00:00', 'Setiap Hari', -6.14134100, 106.81393000, 'Wisata Budaya', 'Terkenal', 'gratis', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata budaya terkenal gratis jakarta barat dki jakarta transjakarta halte glodok mrt fase 2 kelak parkir toilet mushola area istirahat area merokok pembayaran tunai representasi riil permukiman pecinan otentik masa lampau batavia yang masih bernapas menyuguhkan hiruk pikuk aktivitas warga lokal di jalanan sempit semerbak bau asap hio persembahyangan mengiringi langkah menuju vihara dharma bhakti pengunjung kerap berburu menu andalan secangkir es kopi susu tak kie yang legendaris pia kacang hijau panas ikan pihi dan ramuan obat tradisional', NULL, NULL),
(33, 'Pelabuhan Sunda Kelapa', 'Jl. Mataram No.1, Penjaringan', 'Urat nadi logistik purba yang beraura pelayaran maskulin abad pertengahan. Wisatawan dikejutkan dengan parade perahu Pinisi kayu bersandar lurus memanjang bermil-mil, kuli panggul tradisional mengangkut sak semen. Menyajikan panorama matahari terbenam paling industrial dan dramatis berlatar buritan kapal kayu.', 'Jakarta Utara', 'DKI Jakarta', 'TransJakarta, KRL (Stasiun Jakarta Kota)', 'https://tse1.mm.bing.net/th/id/OIP.x0PGBEByNx0QbNFzdg9E8QHaD5?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 5000.00, 'Wisata Bahari', '06:00:00', '18:00:00', 'Setiap Hari', -6.12263000, 106.80899400, 'Wisata Bahari', 'Ikonik', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata bahari ikonik murah jakarta utara dki jakarta transjakarta krl stasiun jakarta kota parkir toilet mushola area istirahat area merokok pembayaran tunai urat nadi logistik purba yang beraura pelayaran maskulin abad pertengahan wisatawan dikejutkan dengan parade perahu pinisi kayu bersandar lurus memanjang bermil mil kuli panggul tradisional mengangkut sak semen menyajikan panorama matahari terbenam paling industrial dan dramatis berlatar buritan kapal kayu', NULL, NULL),
(34, 'Jembatan Kota Intan', 'Jl. Kali Besar Barat, Roa Malaka, Tambora', 'Saksi bisu tata kota rekayasa air peninggalan VOC. Merupakan satu-satunya jembatan gantung ungkit berbahan balok kayu ek merah di kawasan kota peninggalan abad ke-17 yang tersisa. Tempat ini memancarkan roman masa lalu Eropa di atas perairan Kali Besar.', 'Jakarta Barat', 'DKI Jakarta', 'KRL (Stasiun Jakarta Kota), TransJakarta', 'https://www.nagomisuites.com/wp-content/uploads/jembatan-kota-intan-profile1655004438.jpeg', 0.00, 'Wisata Sejarah', '00:00:00', '23:59:00', 'Setiap Hari', -6.13105400, 106.81044500, 'Wisata Sejarah', 'Hidden Gem', 'gratis', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata sejarah hidden gem gratis jakarta barat dki jakarta krl stasiun jakarta kota transjakarta parkir toilet mushola area istirahat area merokok pembayaran tunai saksi bisu tata kota rekayasa air peninggalan voc merupakan satu satunya jembatan gantung ungkit berbahan balok kayu ek merah di kawasan kota peninggalan abad ke 17 yang tersisa tempat ini memancarkan roman masa lalu eropa di atas perairan kali besar', NULL, NULL),
(35, 'Pulau Macan Eco Lodge', 'Kepulauan Seribu Utara', 'Definisi mutlak surga retret isolasi (seclusion retreat). Memiliki konsep pariwisata regeneratif eksklusif bertenaga panel surya. Akomodasi kamarnya tanpa dinding penghalang (seahorse oceanfront), ranjang di atas dek kayu mengarah tepat ke laut lepas, tanpa kelambu nyamuk karena kebersihan ekologis laut. Menyajikan kuliner panen organik kebun (farm-to-table buffet).', 'Jakarta Utara', 'DKI Jakarta', 'Kapal Cepat (Speedboat) dari Marina Ancol', 'https://tse1.mm.bing.net/th/id/OIP.NTcaI6aPrsk9buBF9-5ASQHaE8?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 2000000.00, 'Wisata Bahari', '00:00:00', '23:59:00', 'Tergantung Jadwal Kapal (Reservasi)', -5.59830900, 106.54937900, 'Wisata Bahari', 'Hidden Gem', 'mahal', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata bahari hidden gem mahal jakarta utara dki jakarta kapal cepat speedboat dari marina ancol parkir toilet mushola area istirahat area merokok pembayaran tunai definisi mutlak surga retret isolasi seclusion retreat memiliki konsep pariwisata regeneratif eksklusif bertenaga panel surya akomodasi kamarnya tanpa dinding penghalang seahorse oceanfront ranjang di atas dek kayu mengarah tepat ke laut lepas tanpa kelambu nyamuk karena kebersihan ekologis laut menyajikan kuliner panen organik kebun farm to table buffet', NULL, NULL),
(36, 'Pasar Santa', 'Jl. Cipaku I No.1, Petogogan, Kebayoran Baru', 'Fusi mengejutkan antara aroma lantai pasar basah penyedia komoditi sayur mayur dengan kultur hipster urban yang mendiami lantai paling atas. Melahirkan suasana pasar klandestin bawah tanah. Menjajakan baju bekas gaya jalanan, vinyl langka musik rilis terbatas, kopi seduh manual kaliber artisan, hingga menu fusi hot dog Meksiko-Lokal yang menantang pakem konvensional.', 'Jakarta Selatan', 'DKI Jakarta', 'Kendaraan Pribadi, Taksi Daring', 'https://asset.kompas.com/crops/MRKYXC6l78kFXB1iFknk0E9hyoE=/235x0:3610x2250/750x500/data/photo/2024/09/11/66e173abf1dd6.jpeg', 0.00, 'Wisata Kuliner', '10:00:00', '20:00:00', 'Setiap Hari', -6.23990300, 106.81214200, 'Wisata Kuliner', 'Terkenal', 'gratis', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata kuliner terkenal gratis jakarta selatan dki jakarta kendaraan pribadi taksi daring parkir toilet mushola area istirahat area merokok pembayaran tunai fusi mengejutkan antara aroma lantai pasar basah penyedia komoditi sayur mayur dengan kultur hipster urban yang mendiami lantai paling atas melahirkan suasana pasar klandestin bawah tanah menjajakan baju bekas gaya jalanan vinyl langka musik rilis terbatas kopi seduh manual kaliber artisan hingga menu fusi hot dog meksiko lokal yang menantang pakem konvensional', NULL, NULL),
(37, 'Jalan Sabang (Kawasan Kuliner Malam)', 'Jl. H. Agus Salim, Kebon Sirih, Menteng', 'Lorong koridor kuliner jalanan malam hari yang dipadati komuter setelah lelah bekerja. Hiruk pikuk tawar menawar bersatu dengan decak spatula dan wajan arang. Reputasi Jalan Sabang dibangun oleh deretan tenda pedagang sate ayam Madura bercitarasa kacang legit, nasi goreng kambing kehitaman, serta roti bakar bertopping lelehan keju klasik.', 'Jakarta Pusat', 'DKI Jakarta', 'MRT (Stasiun Bundaran HI), TransJakarta', 'https://tse2.mm.bing.net/th/id/OIP.V4MnVG0hUGSiWXNhpiAL1gHaE6?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 0.00, 'Wisata Kuliner', '17:00:00', '00:00:00', 'Setiap Hari', -6.18617900, 106.82511400, 'Wisata Kuliner', 'Terkenal', 'gratis', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata kuliner terkenal gratis jakarta pusat dki jakarta mrt stasiun bundaran hi transjakarta parkir toilet mushola area istirahat area merokok pembayaran tunai lorong koridor kuliner jalanan malam hari yang dipadati komuter setelah lelah bekerja hiruk pikuk tawar menawar bersatu dengan decak spatula dan wajan arang reputasi jalan sabang dibangun oleh deretan tenda pedagang sate ayam madura bercitarasa kacang legit nasi goreng kambing kehitaman serta roti bakar bertopping lelehan keju klasik', NULL, NULL),
(38, 'Galeri Nasional Indonesia', 'Jl. Medan Merdeka Timur No.14, Gambir', 'Institusi seni monumental pelat merah dengan gaya gedung putih era Hindia. Menyajikan ketenangan berkelas dengan penjagaan disiplin. Memamerkan mahakarya permanen lukisan dan patung perintis seni rupa murni Indonesia, termasuk instalasi Raden Saleh, sering menjadi ruang eksplorasi eksibisi seniman muda bergaya eksperimental berskala nasional.', 'Jakarta Pusat', 'DKI Jakarta', 'KRL (Stasiun Gambir), TransJakarta', 'https://asset.kompas.com/crops/dWErHPd2aaS8lTXZvFwO-h9AGYQ=/0x157:4320x3037/1200x800/data/photo/2024/04/02/660b9326d156d.jpg', 0.00, 'Wisata Budaya', '09:00:00', '16:00:00', 'Selasa - Minggu', -6.17813400, 106.83185700, 'Wisata Budaya', 'Terkenal', 'gratis', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata budaya terkenal gratis jakarta pusat dki jakarta krl stasiun gambir transjakarta parkir toilet mushola area istirahat area merokok pembayaran tunai institusi seni monumental pelat merah dengan gaya gedung putih era hindia menyajikan ketenangan berkelas dengan penjagaan disiplin memamerkan mahakarya permanen lukisan dan patung perintis seni rupa murni indonesia termasuk instalasi raden saleh sering menjadi ruang eksplorasi eksibisi seniman muda bergaya eksperimental berskala nasional', NULL, NULL),
(39, 'Taman Literasi Blok M (Martha Tiahahu)', 'Jl. Sisingamangaraja, Melawai, Kebayoran Baru', 'Rekayasa ruang transit yang mengawinkan estetika ekologis dengan literatur. Bentuk kolosalnya menyerupai piringan melingkar dengan koridor berlubang penuh tanaman hias tropis menjuntai. Menghadirkan amfiteater hijau dan rak perpustakaan mikro, berfungsi menjadi lokasi bersantai penawar tekanan komuter urban yang usai meretas stasiun bawah tanah.', 'Jakarta Selatan', 'DKI Jakarta', 'MRT (Stasiun Blok M BCA), TransJakarta', 'https://tse2.mm.bing.net/th/id/OIP.crxx9SCwVtSwgvojcp6oaAHaEJ?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 0.00, 'Wisata Edukasi', '07:00:00', '22:00:00', 'Setiap Hari', -6.24301300, 106.79873600, 'Wisata Edukasi', 'Terkenal', 'gratis', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata edukasi terkenal gratis jakarta selatan dki jakarta mrt stasiun blok m bca transjakarta parkir toilet mushola area istirahat area merokok pembayaran tunai rekayasa ruang transit yang mengawinkan estetika ekologis dengan literatur bentuk kolosalnya menyerupai piringan melingkar dengan koridor berlubang penuh tanaman hias tropis menjuntai menghadirkan amfiteater hijau dan rak perpustakaan mikro berfungsi menjadi lokasi bersantai penawar tekanan komuter urban yang usai meretas stasiun bawah tanah', NULL, NULL),
(40, 'Bayt Al-Qur\'an dan Museum Istiqlal (BQMI)', 'Kompleks TMII, Jl. Raya Taman Mini, Ceger', 'Gedung arsip berarsitektur modern dengan atmosfer relijius penuh penghormatan. Sangat edukatif memperlihatkan ragam penyalinan kitab suci, termasuk mushaf bersampul rajut emas pusaka Indonesia, kitab huruf braille bagi difabel, hingga sentuhan canggih pembalik mushaf lembar elektronik via sensor gerak pergelangan tangan, ditutup pajangan kaligrafi kontemporer asimilasi budaya Jawa-Arab.', 'Jakarta Timur', 'DKI Jakarta', 'LRT (Stasiun TMII), TransJakarta', 'https://tse3.mm.bing.net/th/id/OIP.Bp4LDlOYH7TD12Bm4bOkegHaE7?r=0&o=7rm=3&rs=1&pid=ImgDetMain&o=7&rm=3', 5000.00, 'Wisata Religi', '08:30:00', '15:30:00', 'Setiap Hari', -6.30256600, 106.88786200, 'Wisata Religi', 'Hidden Gem', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata religi hidden gem murah jakarta timur dki jakarta lrt stasiun tmii transjakarta parkir toilet mushola area istirahat area merokok pembayaran tunai gedung arsip berarsitektur modern dengan atmosfer relijius penuh penghormatan sangat edukatif memperlihatkan ragam penyalinan kitab suci termasuk mushaf bersampul rajut emas pusaka indonesia kitab huruf braille bagi difabel hingga sentuhan canggih pembalik mushaf lembar elektronik via sensor gerak pergelangan tangan ditutup pajangan kaligrafi kontemporer asimilasi budaya jawa arab', NULL, NULL),
(41, 'Kawah Putih', 'Jl. Raya Ciwidey Patengan Km 11', 'Danau kawah vulkanik eksotis bersuhu dingin dengan air biru kehijauan yang memukau.', 'Kab. Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Bus Rombongan', 'https://tse4.mm.bing.net/th/id/OIP.h2LWb_K-UbHeg51IRvordAHaFj?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 54500.00, 'Wisata Alam', '07:30:00', '17:00:00', 'Setiap Hari', -7.16615400, 107.40227900, 'Wisata Alam Vulkanik', 'Ikonik', 'sedang', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata alam vulkanik ikonik sedang kab bandung jawa barat kendaraan pribadi bus rombongan parkir toilet mushola area istirahat area merokok pembayaran tunai danau kawah vulkanik eksotis bersuhu dingin dengan air biru kehijauan yang memukau', NULL, NULL);
INSERT INTO `wisata` (`id_wisata`, `nama`, `alamat`, `deskripsi`, `kota`, `provinsi`, `transportasi`, `gambar`, `harga`, `tipe_wisata`, `jam_buka`, `jam_tutup`, `hari_operasional`, `latitude`, `longitude`, `kategori_wisata`, `trend`, `budget`, `fasilitas`, `fitur_cbf`, `created_at`, `updated_at`) VALUES
(42, 'Gunung Tangkuban Perahu', 'Kawasan Wilayah Lembang Utara', 'Gunung berapi aktif ikonik yang menawarkan pesona tiga kawah utama serta uap belerang hangat.', 'Bandung Barat', 'Jawa Barat', 'Kendaraan Pribadi, Bus Besar', 'https://tse3.mm.bing.net/th/id/OIP.B_hTi2Fl0c0mLKA5bub1DQHaDs?r=0&o=7rm=3&rs=1&pid=ImgDetMain&o=7&rm=3', 25000.00, 'Wisata Alam', '11:00:00', '20:00:00', 'Setiap Hari', -6.99850800, 108.74202300, 'Wisata Alam Vulkanik', 'Ikonik', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata alam vulkanik ikonik murah bandung barat jawa barat kendaraan pribadi bus besar parkir toilet mushola area istirahat area merokok pembayaran tunai gunung berapi aktif ikonik yang menawarkan pesona tiga kawah utama serta uap belerang hangat', NULL, NULL),
(43, 'Situ Patenggang', 'Jl. Situ Patengan, Area Rancabali', 'Danau alami dataran tinggi asri yang dikelilingi hamparan kebun teh dan restoran kapal pinisi raksasa.', 'Kab. Bandung', 'Jawa Barat', 'Kendaraan Pribadi', 'https://tse3.mm.bing.net/th/id/OIP.inKxN9uMDBukZd-7hOzMGgHaFj?r=0&o=7rm=3&rs=1&pid=ImgDetMain&o=7&rm=3', 25000.00, 'Wisata Alam', '09:00:00', '17:00:00', 'Setiap Hari', -7.16384300, 107.35874500, 'Wisata Alam Perairan', 'Terkenal', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata alam perairan terkenal murah kab bandung jawa barat kendaraan pribadi parkir toilet mushola area istirahat area merokok pembayaran tunai danau alami dataran tinggi asri yang dikelilingi hamparan kebun teh dan restoran kapal pinisi raksasa', NULL, NULL),
(44, 'Ranca Upas', 'Jl. Camp Ranca Upas, Rancabali', 'Bumi perkemahan rindang dengan fasilitas interaksi langsung memberi makan kawanan rusa jinak.', 'Kab. Bandung', 'Jawa Barat', 'Kendaraan Pribadi', 'https://tse4.mm.bing.net/th/id/OIP.8H_FCwKLG_DI7WSrjimswgHaE6?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 32500.00, 'Wisata Alam', '00:00:00', '00:00:00', 'Setiap Hari', -7.13836000, 107.39163100, 'Wisata Alam / Edukasi Fauna', 'Terkenal', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata alam edukasi fauna terkenal murah kab bandung jawa barat kendaraan pribadi parkir toilet mushola area istirahat area merokok pembayaran tunai bumi perkemahan rindang dengan fasilitas interaksi langsung memberi makan kawanan rusa jinak', NULL, NULL),
(45, 'Perkebunan Teh Rancabali', 'Area Patengan, Rancabali', 'Lanskap hijau perkebunan teh luas yang menenangkan, sangat ideal untuk relaksasi dan berfoto.', 'Kab. Bandung', 'Jawa Barat', 'Kendaraan Pribadi', 'https://tse2.mm.bing.net/th/id/OIP.jpDiGLrffVAOL1JEhJN_tQHaEK?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 0.00, 'Agrowisata', '00:00:00', '00:00:00', 'Setiap Hari', -7.15038300, 107.38129100, 'Agrowisata', 'Terkenal', 'gratis', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'agrowisata terkenal gratis kab bandung jawa barat kendaraan pribadi parkir toilet mushola area istirahat area merokok pembayaran tunai lanskap hijau perkebunan teh luas yang menenangkan sangat ideal untuk relaksasi dan berfoto', NULL, NULL),
(46, 'Tebing Keraton', 'Puncak Kordon, Desa Ciburial', 'Titik tebing curam terbaik untuk menikmati sunrise membelah lautan kabut hutan pinus dari ketinggian.', 'Bandung Barat', 'Jawa Barat', 'Motor / Kendaraan Pribadi', 'https://tse1.mm.bing.net/th/id/OIP.haz8lDGfTTCE9SMxaIH0TQHaEn?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 14500.00, 'Wisata Alam', '05:00:00', '16:00:00', 'Setiap Hari', -6.83454600, 107.66374100, 'Wisata Alam', 'Terkenal', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata alam terkenal murah bandung barat jawa barat motor kendaraan pribadi parkir toilet mushola area istirahat area merokok pembayaran tunai titik tebing curam terbaik untuk menikmati sunrise membelah lautan kabut hutan pinus dari ketinggian', NULL, NULL),
(47, 'Dago Dreampark', 'Jl. Dago Giri KM 2.2, Mekarwangi', 'Taman wisata keluarga yang menyajikan wahana memacu adrenalin dan spot foto melayang ekstrem.', 'Bandung Barat', 'Jawa Barat', 'Kendaraan Pribadi', 'https://tse4.mm.bing.net/th/id/OIP.DBRIk1eXWjEO_RKSS8ZAygHaE7?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 35000.00, 'Taman Hiburan', '08:00:00', '21:00:00', 'Setiap Hari', -6.84819300, 107.62604400, 'Taman Hiburan', 'Terkenal', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'taman hiburan terkenal murah bandung barat jawa barat kendaraan pribadi parkir toilet mushola area istirahat area merokok pembayaran tunai taman wisata keluarga yang menyajikan wahana memacu adrenalin dan spot foto melayang ekstrem', NULL, NULL),
(48, 'Orchid Forest Cikole', 'Genteng, Cikole Lembang', 'Taman konservasi anggrek terbesar se-Indonesia dengan desain estetik dan jembatan gantung memukau.', 'Bandung Barat', 'Jawa Barat', 'Kendaraan Pribadi', 'https://www.indonesia.travel/link/bbfaad433d7b4ca9b08932dc052a9da0.aspx', 45000.00, 'Wisata Alam', '08:00:00', '18:00:00', 'Setiap Hari', -6.77971900, 107.63516900, 'Wisata Alam / Buatan', 'Terkenal', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata alam buatan terkenal murah bandung barat jawa barat kendaraan pribadi parkir toilet mushola area istirahat area merokok pembayaran tunai taman konservasi anggrek terbesar se indonesia dengan desain estetik dan jembatan gantung memukau', NULL, NULL),
(49, 'Curug Malela', 'Wilayah Kab. Bandung Barat', 'Air terjun megah berdebit deras yang dijuluki sebagai \"Niagara Mini dari Bandung\".', 'Bandung Barat', 'Jawa Barat', 'Kendaraan Pribadi + Trekking', 'https://tse3.mm.bing.net/th/id/OIP.C-BnysEChZpfdofO0Z3IVQHaEK?r=0&o=7rm=3&rs=1&pid=ImgDetMain&o=7&rm=3', 10000.00, 'Wisata Alam', '08:00:00', '17:00:00', 'Setiap Hari', -7.01653400, 107.20764300, 'Wisata Alam', 'Hidden Gem', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata alam hidden gem murah bandung barat jawa barat kendaraan pribadi trekking parkir toilet mushola area istirahat area merokok pembayaran tunai air terjun megah berdebit deras yang dijuluki sebagai niagara mini dari bandung', NULL, NULL),
(50, 'Taman Wisata Bougenville', 'Jl. Gn. Puntang, Kec. Cimaung', 'Oase wisata alam asri di tepian sungai jernih, dilengkapi penginapan bambu dan taman bermain.', 'Kab. Bandung', 'Jawa Barat', 'Kendaraan Pribadi', 'https://travelspromo.com/wp-content/uploads/2020/10/Jembaatan-dengan-naga-di-Taman-Bougenville-Singkawang-Kalimantan-Barat-Mochamad-Nizwar-Syafuan-e1604373424541-1200x900.jpg', 35000.00, 'Wisata Alam', '08:00:00', '17:00:00', 'Setiap Hari', -7.11089500, 107.60235800, 'Wisata Alam', 'Hidden Gem', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata alam hidden gem murah kab bandung jawa barat kendaraan pribadi parkir toilet mushola area istirahat area merokok pembayaran tunai oase wisata alam asri di tepian sungai jernih dilengkapi penginapan bambu dan taman bermain', NULL, NULL),
(51, 'Situ Cileunca', 'Dataran Tinggi Pangalengan', 'Danau buatan luas yang menjadi pusat aktivitas arung jeram (rafting) menantang di Sungai Palayangan.', 'Kab. Bandung', 'Jawa Barat', 'Kendaraan Pribadi', 'https://tse1.mm.bing.net/th/id/OIP.dufM4vD3m7NfNwUuc2wsjAHaFE?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 10000.00, 'Wisata Alam', '00:00:00', '00:00:00', 'Setiap Hari', -7.19205500, 107.55089800, 'Wisata Alam', 'Terkenal', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata alam terkenal murah kab bandung jawa barat kendaraan pribadi parkir toilet mushola area istirahat area merokok pembayaran tunai danau buatan luas yang menjadi pusat aktivitas arung jeram rafting menantang di sungai palayangan', NULL, NULL),
(52, 'Kebun Bunga Begonia', 'Kecamatan Lembang', 'Hamparan taman botani dengan koleksi bunga warna-warni cerah yang sangat memanjakan mata.', 'Bandung Barat', 'Jawa Barat', 'Kendaraan Pribadi', 'https://tse2.mm.bing.net/th/id/OIP.1lhp7YwAX6GXYvlOPwe0mAHaEo?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 25000.00, 'Agrowisata', '08:00:00', '17:00:00', 'Setiap Hari', -6.82542500, 107.63776500, 'Agrowisata', 'Terkenal', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'agrowisata terkenal murah bandung barat jawa barat kendaraan pribadi parkir toilet mushola area istirahat area merokok pembayaran tunai hamparan taman botani dengan koleksi bunga warna warni cerah yang sangat memanjakan mata', NULL, NULL),
(53, 'Trans Studio Bandung', 'Jl. Jenderal Gatot Subroto No.289', 'Taman rekreasi indoor berskala besar dengan puluhan wahana mendebarkan dan pameran edukatif.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Bus', 'https://tse2.mm.bing.net/th?id=OIF.jBXmXt0IL6%2fKvI%2fr04SjTA&r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 250000.00, 'Taman Hiburan', '10:00:00', '17:00:00', 'Setiap Hari', -6.92529600, 107.63638500, 'Taman Hiburan', 'Ikonik', 'mahal', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'taman hiburan ikonik mahal bandung jawa barat kendaraan pribadi bus parkir toilet mushola area istirahat area merokok pembayaran tunai taman rekreasi indoor berskala besar dengan puluhan wahana mendebarkan dan pameran edukatif', NULL, NULL),
(54, 'Jalan Braga', 'Sumur Bandung', 'Jalan historis yang estetik dengan deretan arsitektur kolonial, kafe antik, dan hiburan malam.', 'Bandung', 'Jawa Barat', 'Kendaraan Umum, Pribadi', 'https://www.indonesia.travel/contentassets/baeb963dcb894db5ac4ed3582d4c799a/menikmati-berbagai-sudut-paling-instagenic-di-jalan-braga.jpg', 0.00, 'Wisata Sejarah', '00:00:00', '00:00:00', 'Setiap Hari', -6.91759300, 107.60955200, 'Wisata Sejarah / Budaya', 'Ikonik', 'gratis', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata sejarah budaya ikonik gratis bandung jawa barat kendaraan umum pribadi parkir toilet mushola area istirahat area merokok pembayaran tunai jalan historis yang estetik dengan deretan arsitektur kolonial kafe antik dan hiburan malam', NULL, NULL),
(55, 'Museum Gedung Sate', 'Jl. Diponegoro No. 22', 'Markas pemerintahan dengan menara ikonik tusuk sate yang di dalamnya terdapat museum arsitektur modern.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi', 'https://museum.co.id/wp-content/uploads/2020/09/gedung-sate-bandung-640x360-1.jpg', 5000.00, 'Wisata Sejarah', '09:00:00', '17:00:00', 'Setiap Hari', -6.90237300, 107.61914100, 'Wisata Sejarah / Edukasi', 'Ikonik', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata sejarah edukasi ikonik murah bandung jawa barat kendaraan pribadi parkir toilet mushola area istirahat area merokok pembayaran tunai markas pemerintahan dengan menara ikonik tusuk sate yang di dalamnya terdapat museum arsitektur modern', NULL, NULL),
(56, 'The Great Asia Africa', 'Jl. Raya Lembang – Bandung No.71', 'Taman tematik edukatif yang menyajikan arsitektur replika pedesaan dari tujuh negara benua Asia-Afrika.', 'Bandung Barat', 'Jawa Barat', 'Kendaraan Pribadi, Bus', 'https://bandungfoto.com/wp-content/uploads/2024/05/Harga-Tiket-Masuk-The-Great-Asia-Africa-Lembang-Bandung.webp', 50000.00, 'Wisata Buatan', '08:00:00', '18:00:00', 'Setiap Hari', -6.83263500, 107.60433800, 'Wisata Buatan', 'Terkenal', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata buatan terkenal murah bandung barat jawa barat kendaraan pribadi bus parkir toilet mushola area istirahat area merokok pembayaran tunai taman tematik edukatif yang menyajikan arsitektur replika pedesaan dari tujuh negara benua asia afrika', NULL, NULL),
(57, 'Floating Market Lembang', 'Jl. Grand Hotel No. 33E', 'Pasar apung kuliner tertata rapi di tepi danau yang menawarkan wahana viral seluncuran pelangi (rainbow slide).', 'Bandung Barat', 'Jawa Barat', 'Kendaraan Pribadi, Bus', 'https://assets.pikiran-rakyat.com/crop/0x0:0x0/x/photo/2025/09/15/4113394800.jpg', 35000.00, 'Wisata Buatan', '09:00:00', '19:00:00', 'Setiap Hari', -6.81751200, 107.61923900, 'Wisata Buatan / Kuliner', 'Terkenal', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata buatan kuliner terkenal murah bandung barat jawa barat kendaraan pribadi bus parkir toilet mushola area istirahat area merokok pembayaran tunai pasar apung kuliner tertata rapi di tepi danau yang menawarkan wahana viral seluncuran pelangi rainbow slide', NULL, NULL),
(58, 'Farmhouse Lembang', 'Jl. Raya Lembang No.108', 'Kompleks wisata gaya Eropa klasik, terkenal dengan replika Rumah Hobbit presisi dan penyewaan kostum.', 'Bandung Barat', 'Jawa Barat', 'Kendaraan Pribadi, Bus', 'https://tse3.mm.bing.net/th/id/OIP.d4pIysuBGC9JnvVhgEO_QgHaEK?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 35000.00, 'Wisata Buatan', '09:00:00', '18:00:00', 'Setiap Hari', -6.83283000, 107.60598300, 'Wisata Buatan', 'Terkenal', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata buatan terkenal murah bandung barat jawa barat kendaraan pribadi bus parkir toilet mushola area istirahat area merokok pembayaran tunai kompleks wisata gaya eropa klasik terkenal dengan replika rumah hobbit presisi dan penyewaan kostum', NULL, NULL),
(59, 'Kebun Stroberi Ciwidey', 'Jl. Ciwidey Patengan', 'Fasilitas perkebunan dataran tinggi yang menawarkan pengalaman memetik buah stroberi segar secara langsung.', 'Kab. Bandung', 'Jawa Barat', 'Kendaraan Pribadi', 'https://tse1.mm.bing.net/th/id/OIP.AMj3Fd4EE21mhxy2cNGOngHaE8?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 35000.00, 'Agrowisata', '11:00:00', '20:00:00', 'Setiap Hari', -7.11316900, 107.41837000, 'Agrowisata', 'Terkenal', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'agrowisata terkenal murah kab bandung jawa barat kendaraan pribadi parkir toilet mushola area istirahat area merokok pembayaran tunai fasilitas perkebunan dataran tinggi yang menawarkan pengalaman memetik buah stroberi segar secara langsung', NULL, NULL),
(60, 'Noah\'s Park', 'Jl. Sukanagara No. 20, Lembang', 'Arena petualangan alam seru yang menonjolkan seluncur luge kart tanpa mesin dan wahana ATV.', 'Bandung Barat', 'Jawa Barat', 'Kendaraan Pribadi', 'https://tse2.mm.bing.net/th/id/OIP.jOQzNwOJcgz5Oj7-Sj0EzQHaFi?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 20000.00, 'Taman Hiburan', '09:00:00', '17:00:00', 'Setiap Hari', -6.83009100, 107.63512400, 'Taman Hiburan', 'Terkenal', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'taman hiburan terkenal murah bandung barat jawa barat kendaraan pribadi parkir toilet mushola area istirahat area merokok pembayaran tunai arena petualangan alam seru yang menonjolkan seluncur luge kart tanpa mesin dan wahana atv', NULL, NULL),
(61, 'Dusun Bambu', 'Jl. Kolonel Masturi KM 11', 'Resor ramah lingkungan bernuansa budaya Sunda dengan restoran apung eksklusif dan pondok bambu.', 'Bandung Barat', 'Jawa Barat', 'Kendaraan Pribadi', 'https://tse1.mm.bing.net/th/id/OIP.rW5-3pD3_LtsHh-7etY3PQHaFj?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 30000.00, 'Ekowisata', '09:00:00', '20:00:00', 'Setiap Hari', -6.78932100, 107.57943800, 'Ekowisata', 'Terkenal', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'ekowisata terkenal murah bandung barat jawa barat kendaraan pribadi parkir toilet mushola area istirahat area merokok pembayaran tunai resor ramah lingkungan bernuansa budaya sunda dengan restoran apung eksklusif dan pondok bambu', NULL, NULL),
(62, 'Air Terjun Pelangi (Curug Cimahi)', 'Jl. Kolonel Masturi, Kertawangi', 'Air terjun setinggi 87 meter yang asri, menawarkan pemandangan magis kala butiran airnya membiaskan cahaya pelangi.', 'Bandung Barat', 'Jawa Barat', 'Kendaraan Pribadi', 'https://tse1.mm.bing.net/th/id/OIP._tLuTrf8cXNho2N4TgWNgQHaEt?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 20000.00, 'Wisata Alam', '07:00:00', '17:00:00', 'Setiap Hari', -6.79857300, 107.57628200, 'Wisata Alam', 'Terkenal', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata alam terkenal murah bandung barat jawa barat kendaraan pribadi parkir toilet mushola area istirahat area merokok pembayaran tunai air terjun setinggi 87 meter yang asri menawarkan pemandangan magis kala butiran airnya membiaskan cahaya pelangi', NULL, NULL),
(63, 'The Lodge Maribaya', 'Jl. Maribaya No.149/252', 'Destinasi menawan di tepi jurang dengan wahana foto ekstrem seperti Sky Tree dan sepeda udara berlatar hutan pinus.', 'Bandung Barat', 'Jawa Barat', 'Kendaraan Pribadi', 'https://mmc.tirto.id/image/2018/12/12/wisata-alam-the-lodge-antarafoto-_ratio-16x9.jpg', 42500.00, 'Wisata Alam', '09:00:00', '17:00:00', 'Setiap Hari', -6.82916300, 107.68743800, 'Wisata Alam / Buatan', 'Terkenal', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata alam buatan terkenal murah bandung barat jawa barat kendaraan pribadi parkir toilet mushola area istirahat area merokok pembayaran tunai destinasi menawan di tepi jurang dengan wahana foto ekstrem seperti sky tree dan sepeda udara berlatar hutan pinus', NULL, NULL),
(64, 'Kebun Binatang Bandung (Bandung Zoo)', 'Jl. Kebun Binatang No 6', 'Kebun satwa rindang di pusat kota yang dilengkapi area interaksi langsung untuk memberi makan hewan jinak.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Bus', 'https://cdn.antaranews.com/cache/1200x800/2025/08/20/b2767ebf-a769-4a8a-8877-42cf2b358ff2.jpeg', 55000.00, 'Wisata Edukasi', '09:00:00', '16:00:00', 'Setiap Hari', -6.88998700, 107.60701600, 'Wisata Edukasi', 'Ikonik', 'sedang', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata edukasi ikonik sedang bandung jawa barat kendaraan pribadi bus parkir toilet mushola area istirahat area merokok pembayaran tunai kebun satwa rindang di pusat kota yang dilengkapi area interaksi langsung untuk memberi makan hewan jinak', NULL, NULL),
(65, 'Museum Konferensi Asia Afrika', 'Jl. Asia Afrika No 65', 'Monumen sejarah tempat terselenggaranya diplomasi 29 negara yang melahirkan Gerakan Non-Blok pada 1955.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Bus', 'https://rencanamu.id/assets/file_uploaded/editor/1456410832-1430217804.jpg', 0.00, 'Wisata Sejarah', '09:00:00', '15:00:00', 'Setiap Hari', -6.92075800, 107.60959200, 'Wisata Sejarah', 'Ikonik', 'gratis', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata sejarah ikonik gratis bandung jawa barat kendaraan pribadi bus parkir toilet mushola area istirahat area merokok pembayaran tunai monumen sejarah tempat terselenggaranya diplomasi 29 negara yang melahirkan gerakan non blok pada 1955', NULL, NULL),
(66, 'Museum Geologi', 'Jl. Diponegoro No.57', 'Etalase pameran fosil raksasa dinosaurus T-Rex serta koleksi meteorit ekstraterestrial terlengkap.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi', 'https://tse4.mm.bing.net/th/id/OIP.kCYQw10QGgWwyK4Ov1drBAHaFj?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 2500.00, 'Wisata Edukasi', '09:00:00', '15:00:00', 'Setiap Hari', -6.90029100, 107.62200200, 'Wisata Edukasi', 'Ikonik', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata edukasi ikonik murah bandung jawa barat kendaraan pribadi parkir toilet mushola area istirahat area merokok pembayaran tunai etalase pameran fosil raksasa dinosaurus t rex serta koleksi meteorit ekstraterestrial terlengkap', NULL, NULL),
(67, 'Taman Hutan Raya Ir. H. Juanda', 'Komplek Tahura, Jl. Ir. H. Juanda', 'Hutan konservasi bernapas sejuk yang di dalamnya menyimpan situs Gua Jepang dan Gua Belanda.', 'Kab. Bandung', 'Jawa Barat', 'Kendaraan Pribadi', 'https://tse2.mm.bing.net/th/id/OIP.2n-y4PAHBBstqgKeUewreAHaEn?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 37000.00, 'Wisata Alam', '08:00:00', '16:00:00', 'Setiap Hari', -6.85824700, 107.63071200, 'Wisata Alam / Sejarah', 'Terkenal', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata alam sejarah terkenal murah kab bandung jawa barat kendaraan pribadi parkir toilet mushola area istirahat area merokok pembayaran tunai hutan konservasi bernapas sejuk yang di dalamnya menyimpan situs gua jepang dan gua belanda', NULL, NULL),
(68, 'Alun-Alun Bandung', 'Jalan Antapani Lama, Cicaheum', 'Ruang publik komunal santai dengan hamparan karpet rumput sintetis higienis berhadapan dengan Masjid Raya.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Bus', 'https://asset.kompas.com/crops/0VVR9TW-gSyx4UNy0s0SneSh0j0=/64x0:1414x900/1200x800/data/photo/2024/06/06/666154cb9fe14.jpg', 0.00, 'Wisata Budaya', '00:00:00', '00:00:00', 'Setiap Hari', -6.92164800, 107.60713700, 'Wisata Budaya', 'Ikonik', 'gratis', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata budaya ikonik gratis bandung jawa barat kendaraan pribadi bus parkir toilet mushola area istirahat area merokok pembayaran tunai ruang publik komunal santai dengan hamparan karpet rumput sintetis higienis berhadapan dengan masjid raya', NULL, NULL),
(69, 'Masjid Raya Al-Jabbar', 'Jl. Cimencrang No.14, Gedebage', 'Masjid megah berdesain futuristik mengapung di atas danau tanpa kubah konvensional.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi', 'https://bangsaonline.com/images/uploads/berita/700/8f3afc1b8a148779b0e9c21fd9390a21.jpg', 0.00, 'Wisata Religi', '00:00:00', '00:00:00', 'Setiap Hari', -6.94796500, 107.70351100, 'Wisata Religi', 'Ikonik', 'gratis', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata religi ikonik gratis bandung jawa barat kendaraan pribadi parkir toilet mushola area istirahat area merokok pembayaran tunai masjid megah berdesain futuristik mengapung di atas danau tanpa kubah konvensional', NULL, NULL),
(70, 'Vihara Vipassana Graha', 'Jl. Kolonel Masturi No.69', 'Rumah ibadah umat Buddha dengan arsitektur memukau khas kerajaan Thailand yang tenang.', 'Bandung Barat', 'Jawa Barat', 'Kendaraan Pribadi', 'https://tse4.mm.bing.net/th/id/OIP.HxH5Typ6dpz52eTGfPkcRgHaFj?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 0.00, 'Wisata Religi', '05:00:00', '18:00:00', 'Setiap Hari', -6.81134000, 107.59977200, 'Wisata Religi', 'Hidden Gem', 'gratis', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata religi hidden gem gratis bandung barat jawa barat kendaraan pribadi parkir toilet mushola area istirahat area merokok pembayaran tunai rumah ibadah umat buddha dengan arsitektur memukau khas kerajaan thailand yang tenang', NULL, NULL),
(71, 'Gereja Karmel Lembang', 'Jl. Karmel 1 No.51, Jayagiri', 'Tempat ziarah dan doa umat Katolik yang syahdu di perbukitan dingin Lembang.', 'Bandung Barat', 'Jawa Barat', 'Kendaraan Pribadi', 'https://tse4.mm.bing.net/th/id/OIP.sxQ3hr49KlCiTmheSkGXcwHaEK?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 0.00, 'Wisata Religi', '08:00:00', '16:00:00', 'Setiap Hari', -6.81234800, 107.61467600, 'Wisata Religi', 'Hidden Gem', 'gratis', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata religi hidden gem gratis bandung barat jawa barat kendaraan pribadi parkir toilet mushola area istirahat area merokok pembayaran tunai tempat ziarah dan doa umat katolik yang syahdu di perbukitan dingin lembang', NULL, NULL),
(72, 'Pesona Nirwana Water Park', 'Jl. Terusan Cibako, Soreang', 'Wahana air keluarga yang asri karena dibangun menyatu di antara lanskap tebing bebatuan.', 'Kab. Bandung', 'Jawa Barat', 'Kendaraan Pribadi', 'https://tse2.mm.bing.net/th/id/OIP.stH-ynUiW1oWh1HV5AqB2wHaE3?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 50000.00, 'Taman Hiburan', '09:00:00', '17:00:00', 'Setiap Hari', -7.04088000, 107.52696600, 'Taman Hiburan / Buatan', 'Hidden Gem', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'taman hiburan buatan hidden gem murah kab bandung jawa barat kendaraan pribadi parkir toilet mushola area istirahat area merokok pembayaran tunai wahana air keluarga yang asri karena dibangun menyatu di antara lanskap tebing bebatuan', NULL, NULL),
(73, 'Kiara Artha Park', 'Jl. Banten, Kebonwaru', 'Taman kota modern dengan fasilitas trem berkeliling dan pertunjukan tarian air mancur.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi', 'https://asset-2.tstatic.net/travel/foto/bank/images/Liburan-ke-Kiara-Artha-Park-Bandung-Jawa-Barat-lengkap-dengan-harga-tiket-masuk-terbaru-2024.jpg', 10000.00, 'Taman Hiburan', '09:00:00', '21:00:00', 'Setiap Hari', -6.91598200, 107.64236900, 'Taman Hiburan', 'Terkenal', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'taman hiburan terkenal murah bandung jawa barat kendaraan pribadi parkir toilet mushola area istirahat area merokok pembayaran tunai taman kota modern dengan fasilitas trem berkeliling dan pertunjukan tarian air mancur', NULL, NULL),
(74, 'Wisata Batu Kuda', 'Lereng Gunung Manglayang', 'Kawasan perkemahan teduh di tengah lebatnya tegakan hutan pinus, ideal untuk relaksasi.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi', 'https://teropongmedia.id/wp-content/uploads/2024/01/mengulik-sejarah-dan-asal-usul-batu-kuda-di-gunung-manglayang-oXJLH5bF18.webp', 10000.00, 'Wisata Alam', '05:00:00', '17:00:00', 'Setiap Hari', -6.89267900, 107.74557500, 'Wisata Alam', 'Hidden Gem', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata alam hidden gem murah bandung jawa barat kendaraan pribadi parkir toilet mushola area istirahat area merokok pembayaran tunai kawasan perkemahan teduh di tengah lebatnya tegakan hutan pinus ideal untuk relaksasi', NULL, NULL),
(75, 'Bukit Moko', 'Cimenyan', 'Puncak bukit dengan jalur curam yang menguras tenaga namun menawarkan panorama magis lautan lampu kota.', 'Kab. Bandung', 'Jawa Barat', 'Motor / SUV', 'https://storage.googleapis.com/finansialku_media/wordpress_media/2024/04/876d82d5-8-bukit-moko.webp', 15000.00, 'Wisata Alam', '00:00:00', '00:00:00', 'Setiap Hari', -6.84214500, 107.67694600, 'Wisata Alam', 'Terkenal', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata alam terkenal murah kab bandung jawa barat motor suv parkir toilet mushola area istirahat area merokok pembayaran tunai puncak bukit dengan jalur curam yang menguras tenaga namun menawarkan panorama magis lautan lampu kota', NULL, NULL),
(76, 'Saung Angklung Udjo', 'Jl. Padasuka No.118', 'Sentra konservasi mahakarya seni alat musik bambu Sunda yang menyuguhkan orkestra angklung interaktif.', 'Bandung', 'Jawa Barat', 'Kendaraan Pribadi, Bus', 'https://tse2.mm.bing.net/th/id/OIP.u1Dx989eu4ka1aDXbq8l4QHaFd?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 70325.00, 'Wisata Budaya', '08:00:00', '20:00:00', 'Setiap Hari', -6.89775900, 107.65574900, 'Wisata Budaya / Edukasi', 'Ikonik', 'sedang', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata budaya edukasi ikonik sedang bandung jawa barat kendaraan pribadi bus parkir toilet mushola area istirahat area merokok pembayaran tunai sentra konservasi mahakarya seni alat musik bambu sunda yang menyuguhkan orkestra angklung interaktif', NULL, NULL),
(77, 'Lawangwangi Creative Space', 'Jl. Dago Giri No.99', 'Galeri kurasi seni kontemporer memukau bersatu padu dengan fasilitas kafe berlatar pemandangan alam.', 'Bandung Barat', 'Jawa Barat', 'Kendaraan Pribadi', 'https://tse4.mm.bing.net/th/id/OIP.gbPAkaVrdOSSADM2vsIDOgHaE8?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 0.00, 'Wisata Budaya', '11:00:00', '22:00:00', 'Setiap Hari', -6.84765200, 107.62843300, 'Wisata Budaya', 'Hidden Gem', 'gratis', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata budaya hidden gem gratis bandung barat jawa barat kendaraan pribadi parkir toilet mushola area istirahat area merokok pembayaran tunai galeri kurasi seni kontemporer memukau bersatu padu dengan fasilitas kafe berlatar pemandangan alam', NULL, NULL),
(78, 'Sanghyang Heuleut', 'Rajamandala Kulon, Cipatat', 'Danau kawah purba hijau kebiruan yang terkurung secara dramatis di antara dinding tebing bebatuan karst kapur.', 'Bandung Barat', 'Jawa Barat', 'Kendaraan Pribadi', 'https://blue.kumparan.com/image/upload/fl_progressive,fl_lossy,c_fill,q_auto:best,w_640/v1570356048/nqzfyshhj5morqxhtfnc.jpg', 20000.00, 'Wisata Alam', '08:00:00', '16:00:00', 'Setiap Hari', -6.87622500, 107.34233600, 'Wisata Alam', 'Hidden Gem', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata alam hidden gem murah bandung barat jawa barat kendaraan pribadi parkir toilet mushola area istirahat area merokok pembayaran tunai danau kawah purba hijau kebiruan yang terkurung secara dramatis di antara dinding tebing bebatuan karst kapur', NULL, NULL),
(79, 'Lembang Park & Zoo', 'Lembang', 'Fasilitas hibrida unik yang menyatukan area observasi satwa lengkap dengan wahana bermain modern ala karnaval.', 'Bandung Barat', 'Jawa Barat', 'Kendaraan Pribadi', 'https://tse4.mm.bing.net/th/id/OIP.QaIELfy-p1RBVX4nTFmUpQHaEK?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 62500.00, 'Wisata Edukasi', '08:00:00', '18:00:00', 'Setiap Hari', -6.80396700, 107.59114900, 'Wisata Edukasi / Buatan', 'Terkenal', 'sedang', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata edukasi buatan terkenal sedang bandung barat jawa barat kendaraan pribadi parkir toilet mushola area istirahat area merokok pembayaran tunai fasilitas hibrida unik yang menyatukan area observasi satwa lengkap dengan wahana bermain modern ala karnaval', NULL, NULL),
(80, 'Curug Tilu Leuwi Opat', 'Ciwangun, Parongpong', 'Destinasi wisata komprehensif seluas lima hektar yang menyuguhkan jalinan sungai asri dan air terjun menawan.', 'Bandung Barat', 'Jawa Barat', 'Kendaraan Pribadi', 'https://tse4.mm.bing.net/th/id/OIP.6ZKn5kbI_qLvbA58NTzl2gHaE8?r=0&rs=1&pid=ImgDetMain&o=7&rm=3', 15000.00, 'Wisata Alam', '08:00:00', '17:00:00', NULL, -6.79066700, 107.58199000, 'Wisata Alam', 'Hidden Gem', 'murah', 'Parkir, Toilet, Mushola, Area Istirahat, Area Merokok, Pembayaran Tunai', 'wisata alam hidden gem murah bandung barat jawa barat kendaraan pribadi parkir toilet mushola area istirahat area merokok pembayaran tunai destinasi wisata komprehensif seluas lima hektar yang menyuguhkan jalinan sungai asri dan air terjun menawan', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appeals`
--
ALTER TABLE `appeals`
  ADD PRIMARY KEY (`id_appeals`);

--
-- Indexes for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD PRIMARY KEY (`id_bookmark`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `destinasi`
--
ALTER TABLE `destinasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `destinasi_images`
--
ALTER TABLE `destinasi_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `destinasi_images_destinasi_id_foreign` (`destinasi_id`);

--
-- Indexes for table `destinasi_kategori`
--
ALTER TABLE `destinasi_kategori`
  ADD PRIMARY KEY (`destinasi_id`,`kategori_id`),
  ADD KEY `destinasi_kategori_kategori_id_foreign` (`kategori_id`);

--
-- Indexes for table `detail`
--
ALTER TABLE `detail`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `detail_id_perencanaan_foreign` (`id_perencanaan`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD KEY `jadwal_id_perencanaan_foreign` (`id_perencanaan`);

--
-- Indexes for table `jarak`
--
ALTER TABLE `jarak`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jarak_id_wisata_foreign` (`id_wisata`),
  ADD KEY `jarak_id_kuliner_foreign` (`id_kuliner`),
  ADD KEY `jarak_id_penginapan_foreign` (`id_penginapan`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kuliner`
--
ALTER TABLE `kuliner`
  ADD PRIMARY KEY (`id_kuliner`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`),
  ADD KEY `menu_id_kuliner_foreign` (`id_kuliner`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `penginapan`
--
ALTER TABLE `penginapan`
  ADD PRIMARY KEY (`id_penginapan`);

--
-- Indexes for table `pesan`
--
ALTER TABLE `pesan`
  ADD PRIMARY KEY (`id_pesan`),
  ADD KEY `pesan_id_user_foreign` (`id_user`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id_ulasan`),
  ADD KEY `ratings_id_user_foreign` (`id_user`),
  ADD KEY `ratings_id_wisata_foreign` (`id_wisata`),
  ADD KEY `ratings_id_penginapan_foreign` (`id_penginapan`),
  ADD KEY `ratings_id_kuliner_foreign` (`id_kuliner`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `travel_plans`
--
ALTER TABLE `travel_plans`
  ADD PRIMARY KEY (`id_perencanaan`),
  ADD KEY `travel_plans_id_user_foreign` (`id_user`);

--
-- Indexes for table `travel_plan_items`
--
ALTER TABLE `travel_plan_items`
  ADD PRIMARY KEY (`id_item`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD PRIMARY KEY (`id_user_notifications`),
  ADD KEY `user_notifications_id_user_foreign` (`id_user`);

--
-- Indexes for table `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD PRIMARY KEY (`id_user_preferences`),
  ADD KEY `user_preferences_id_user_foreign` (`id_user`);

--
-- Indexes for table `wisata`
--
ALTER TABLE `wisata`
  ADD PRIMARY KEY (`id_wisata`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appeals`
--
ALTER TABLE `appeals`
  MODIFY `id_appeals` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bookmarks`
--
ALTER TABLE `bookmarks`
  MODIFY `id_bookmark` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `destinasi`
--
ALTER TABLE `destinasi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `destinasi_images`
--
ALTER TABLE `destinasi_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detail`
--
ALTER TABLE `detail`
  MODIFY `id_detail` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id_jadwal` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jarak`
--
ALTER TABLE `jarak`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kuliner`
--
ALTER TABLE `kuliner`
  MODIFY `id_kuliner` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=317;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `penginapan`
--
ALTER TABLE `penginapan`
  MODIFY `id_penginapan` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `pesan`
--
ALTER TABLE `pesan`
  MODIFY `id_pesan` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id_ulasan` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `travel_plans`
--
ALTER TABLE `travel_plans`
  MODIFY `id_perencanaan` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `travel_plan_items`
--
ALTER TABLE `travel_plan_items`
  MODIFY `id_item` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_notifications`
--
ALTER TABLE `user_notifications`
  MODIFY `id_user_notifications` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_preferences`
--
ALTER TABLE `user_preferences`
  MODIFY `id_user_preferences` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wisata`
--
ALTER TABLE `wisata`
  MODIFY `id_wisata` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `destinasi_images`
--
ALTER TABLE `destinasi_images`
  ADD CONSTRAINT `destinasi_images_destinasi_id_foreign` FOREIGN KEY (`destinasi_id`) REFERENCES `destinasi` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `destinasi_kategori`
--
ALTER TABLE `destinasi_kategori`
  ADD CONSTRAINT `destinasi_kategori_destinasi_id_foreign` FOREIGN KEY (`destinasi_id`) REFERENCES `destinasi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `destinasi_kategori_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `detail`
--
ALTER TABLE `detail`
  ADD CONSTRAINT `detail_id_perencanaan_foreign` FOREIGN KEY (`id_perencanaan`) REFERENCES `travel_plans` (`id_perencanaan`) ON DELETE CASCADE;

--
-- Constraints for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD CONSTRAINT `jadwal_id_perencanaan_foreign` FOREIGN KEY (`id_perencanaan`) REFERENCES `travel_plans` (`id_perencanaan`) ON DELETE CASCADE;

--
-- Constraints for table `jarak`
--
ALTER TABLE `jarak`
  ADD CONSTRAINT `jarak_id_kuliner_foreign` FOREIGN KEY (`id_kuliner`) REFERENCES `kuliner` (`id_kuliner`) ON DELETE CASCADE,
  ADD CONSTRAINT `jarak_id_penginapan_foreign` FOREIGN KEY (`id_penginapan`) REFERENCES `penginapan` (`id_penginapan`) ON DELETE CASCADE,
  ADD CONSTRAINT `jarak_id_wisata_foreign` FOREIGN KEY (`id_wisata`) REFERENCES `wisata` (`id_wisata`) ON DELETE CASCADE;

--
-- Constraints for table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `menu_id_kuliner_foreign` FOREIGN KEY (`id_kuliner`) REFERENCES `kuliner` (`id_kuliner`) ON DELETE CASCADE;

--
-- Constraints for table `pesan`
--
ALTER TABLE `pesan`
  ADD CONSTRAINT `pesan_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE SET NULL;

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_id_kuliner_foreign` FOREIGN KEY (`id_kuliner`) REFERENCES `kuliner` (`id_kuliner`) ON DELETE CASCADE,
  ADD CONSTRAINT `ratings_id_penginapan_foreign` FOREIGN KEY (`id_penginapan`) REFERENCES `penginapan` (`id_penginapan`) ON DELETE CASCADE,
  ADD CONSTRAINT `ratings_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `ratings_id_wisata_foreign` FOREIGN KEY (`id_wisata`) REFERENCES `wisata` (`id_wisata`) ON DELETE CASCADE;

--
-- Constraints for table `travel_plans`
--
ALTER TABLE `travel_plans`
  ADD CONSTRAINT `travel_plans_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD CONSTRAINT `user_notifications_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD CONSTRAINT `user_preferences_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

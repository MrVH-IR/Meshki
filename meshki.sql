-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2024 at 09:39 PM
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
-- Database: `meshki`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_sessions`
--

CREATE TABLE `admin_sessions` (
  `id` int(6) UNSIGNED NOT NULL,
  `admin_id` int(6) UNSIGNED NOT NULL,
  `session_token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `end_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_sessions`
--

INSERT INTO `admin_sessions` (`id`, `admin_id`, `session_token`, `created_at`, `end_at`) VALUES
(4, 2, '288d4544ff691b83aa696573ad5ad71be0c71bd3794ebd58b099b452211d6d02', '2024-09-27 18:48:55', '2024-09-27 23:48:55'),
(5, 2, '8f48ea67574278678b1a16ac865b99a77fb53ddad32e7f250e273d1c7157abca', '2024-09-29 18:14:11', '2024-09-29 23:14:11'),
(6, 2, '92a52b82233fe342939103ab0d6a2e25a4a3363d045226958af765fc7f2c5f26', '2024-10-09 11:07:41', '2024-10-09 16:07:41'),
(7, 2, '4d1e4fcd7f628ffb86d804677eda9fb4e92f70296de0dfa66061e4969ab7e797', '2024-10-09 16:38:01', '2024-10-09 21:38:01');

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbladmins`
--

CREATE TABLE `tbladmins` (
  `id` int(6) UNSIGNED NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(11) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbladmins`
--

INSERT INTO `tbladmins` (`id`, `username`, `password`, `phone`, `email`) VALUES
(2, 'root', '$2y$10$ZxgscxghWDoAD64njvGx8OM8xx42iLvf/sVPdUEq6ZeiladzL3b7u', '09111621911', 'vahdatmohammad0@gmail.com'),
(3, 'Lord', '$2y$10$4qjq3HgfUWVBn/J5q78Mkeo.DGozAPmX9mgBG.5IRBiobLjLPZHQa', '09120521391', 'vahdatmohammad1@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `tbllogs`
--

CREATE TABLE `tbllogs` (
  `id` int(6) UNSIGNED NOT NULL,
  `user_id` int(6) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `log_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblsongs`
--

CREATE TABLE `tblsongs` (
  `id` int(6) UNSIGNED NOT NULL,
  `artist` varchar(50) NOT NULL,
  `songName` varchar(100) NOT NULL,
  `genre` varchar(20) NOT NULL,
  `songPath` varchar(255) NOT NULL,
  `posterPath` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `upload_date` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblsongs`
--

INSERT INTO `tblsongs` (`id`, `artist`, `songName`, `genre`, `songPath`, `posterPath`, `description`, `tags`, `upload_date`) VALUES
(3, 'Ebi', 'Pelk', 'pop', 'mdata/09 Pelk.mp3', 'admin/posters/Ebi.jpg', 'Orem', '#ebi', '2024-09-29'),
(4, 'Simin Ghanem', 'Sib', 'classical', 'mdata/Simin Ghanem - Sib.mp3', 'admin/posters/AdobeStock_215384501_Preview.jpeg', 'سیمین قانم', '#simin , #ghanem', '2024-09-29'),
(5, 'Sogand', 'The Lom', 'pop', 'mdata/Sogand - The Lom.mp3', 'admin/posters/AdobeStock_209781122_Preview.jpeg', 'سوگند آهنگ دلوم', '#sogand , #delom', '2024-09-29'),
(6, 'The Mamas and The Papas', 'California Dreaming', 'classical', 'mdata/the_mamas_the_papas_-_california_dreamin.mp3', 'admin/posters/AdobeStock_213805044_Preview.jpeg', 'آهنگ California Dreaming از The Mamas The Papas', '#california , #dreaming', '2024-09-29'),
(8, 'Lady Gaga', 'Shallow', 'pop', 'mdata/12_-_shallow.mp3', 'admin/posters/AdobeStock_196405424_Preview.jpeg', 'آهنگ Shallow از Lady Gaga', '#shallow , #ladygaga', '2024-09-29'),
(9, 'Eagles', 'Hotel California', 'rock', 'mdata/Eagles.-.Hotel.California.mp3', 'admin/posters/AdobeStock_200782870_Preview.jpeg', 'آهنگ Hotel California از Eagles', '#HotelCalifornia , #Eagles', '2024-09-29'),
(10, 'Rihanna', 'Diamonds', 'pop', 'mdata/Rihanna-Diamonds@top-songs-top-songs.ir_.mp3', 'admin/posters/AdobeStock_196804977_Preview.jpeg', 'آهنگ Diamonds از Rihanna خواننده محبوب آمریکایی', '#rihanna , #diamonds', '2024-09-29'),
(11, 'Nirvana', 'Something In The Way', 'rock', 'mdata/nirvana_something_in_the_way.mp3', 'admin/posters/AdobeStock_196804987_Preview.jpeg', 'آهنگ Something In The Way از Nirvana خواننده محبوب آمریکایی', '#nirvanna , #rock', '2024-09-29'),
(12, 'David Bowie', 'Heroes', 'electronic', 'mdata/046_-_david_bowie_-_heroes.mp3', 'admin/posters/AdobeStock_203177099_Preview.jpeg', 'آهنگ Heroes از David Bowie خواننده محبوب آمریکایی', '#davidbowie , #heroes ,american , #america', '2024-09-29'),
(15, 'Linda Ronstadt', 'Long Long Time', 'classical', 'mdata/Linda Ronstadt - Long Long Time_GKAtM9xS-fA.mp3', 'admin/posters/AdobeStock_207859057_Preview.jpeg', 'آهگی زیبا از Linda Ronstadt به نام Long Long Time', '#american , #lastofus , #linda , #longtime', '2024-09-29'),
(16, 'Nirvana', 'Smells Like Teen Spirit', 'rock', 'mdata/14.nirvana_-_smells_like_teen_spirit.mp3', 'admin/posters/AdobeStock_206261969_Preview.jpeg', 'آهنگی زیبا از Nirvanna به نام Smells Like Teen Spirit', '#nirvanna , #smells , #american , rock', '2024-09-29'),
(18, 'Evanescence', 'My Immortal', 'rock', 'mdata/03 - My Immortal (Band Version).mp3', 'admin/posters/AdobeStock_196804977_Preview.jpeg', 'آهنگی زیبا از Evanescence به نام My Immortal', '#rock , #immortal , #roll , #evanescence , #my', '2024-09-29'),
(19, 'Billie Eilish', 'Six Feet Under', 'pop', 'mdata/Billie_Eilish_Six_Feet_Under_320.mp3', 'admin/posters/AdobeStock_207859040_Preview.jpeg', 'آهنگی زیبا از Billie Eilish به نام Six Feet Under', '#six#feet#under#billie#eilish', '2024-09-29'),
(20, 'CC Catch', 'Cause You Are Young', 'pop', 'mdata/01 CC Catch cause you are young.mp3', 'admin/posters/AdobeStock_272281824_Preview.jpeg', 'آهنگ زیبای Cause You Are Young از CC Catch', '#pop , #catch , #cc ,#old ,#classic , #young , #you', '2024-09-29'),
(21, 'Evanescence', 'Bring Me To Life', 'rock', 'mdata/01 - Bring Me To Life.mp3', 'admin/posters/AdobeStock_201812822_Preview.jpeg', 'آهنگ زیبای Bring Me To Life از Evanescence', '#rock , #Evanescence , #bring , #life , #to , #meshki', '2024-10-09'),
(22, 'Rihanna', 'Stay', 'pop', 'mdata/rihanna_ft._mikky_-_stay.mp3', 'admin/posters/AdobeStock_238446972_Preview.jpeg', 'آهنگ زیبای Stay از Rihanna', '#stay , #rihanna , #pop ,#mikky ,old , #american', '2024-10-09');

-- --------------------------------------------------------

--
-- Table structure for table `tblusers`
--

CREATE TABLE `tblusers` (
  `id` int(6) UNSIGNED NOT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `username` varchar(30) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `imgpath` varchar(255) NOT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `birthdate` date DEFAULT NULL,
  `referral` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblusers`
--

INSERT INTO `tblusers` (`id`, `firstname`, `lastname`, `username`, `email`, `password`, `imgpath`, `gender`, `reg_date`, `birthdate`, `referral`) VALUES
(3, 'Mohammad Reza', 'Vahdat', 'MrVH', 'vahdatmohammad0@gmail.com', '$2y$10$sC05.O8lsrQ3oymicoot9Ok4K/GxXnkau0lOhVuBsOgS7BczVRPiG', 'uploads/AdobeStock_196804987_Preview.jpeg', 'male', '2024-10-09 14:37:06', '1999-01-01', 'Social Media'),
(4, 'Mahyar', 'Vahdat', 'Mahyar', 'mahyar1999@gmail.com', '$2y$10$YyGWssZOwDTjhDvTiiiVwuYn4/8zgjkmc1HC6YZAgNHywApgvVtgK', '', 'male', '2024-10-09 14:38:07', '1985-03-19', 'friend'),
(5, 'Milad', 'Vahdat', 'Milad', 'miladvadat1999@gmail.com', '$2y$10$UbSuUyXgTMeMI/m60yQ44.hYs6bvl9MEaSHJCM6ERVsUrseY1clRu', '', 'male', '2024-10-09 14:43:38', '1982-09-20', 'search'),
(6, 'Elnaz', 'Pirozi', 'Elnaz', 'elnazpirozi1990@gmail.com', '$2y$10$.JsyZ0UslmH0FFxf50YwauPofHwqeD3uNLM6SiyXCr3EaPGMxXhmG', '', 'female', '2024-10-09 14:47:07', '1993-06-22', 'ad');

-- --------------------------------------------------------

--
-- Table structure for table `tblvids`
--

CREATE TABLE `tblvids` (
  `id` int(6) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `videoPath` varchar(255) NOT NULL,
  `thumbnailPath` varchar(255) NOT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblvids`
--

INSERT INTO `tblvids` (`id`, `title`, `description`, `videoPath`, `thumbnailPath`, `upload_date`) VALUES
(10, 'NDA', 'NDA By Billie Eilish', 'mdata/uploads/videos/موزیک ویدیو خارجی جدید و زیبا NDA از بیلی ایلیش + دانلود - ویدائو.mp4', 'mdata/uploads/posters/AdobeStock_196405424_Preview.jpeg', '2024-09-25 17:16:35'),
(11, 'Can See You', 'موزیک ویدیو زیبا از Taylor Swift به نام Can See You', 'mdata/uploads/videos/موزیک ویدیو خارجی جدید زیباI Can See You از taylor swift - ویدائو.mp4', 'mdata/uploads/posters/AdobeStock_196405424_Preview.jpeg', '2024-09-25 17:38:20'),
(12, 'Twelve Thirthy', 'موزیک ویدیو زیبا از The Mamas & Papas به نام Twelve Thirthy', 'mdata/uploads/videos/The Mamas & The Papas - Twelve Thirty Mamas & The Papas (Stereo) - Classic Hits Stereo - Free Download, Borrow, and Streaming - Internet Archive.mp4', 'mdata/uploads/posters/AdobeStock_196405424_Preview.jpeg', '2024-09-25 17:40:25'),
(13, 'Calm Down', 'موزیک ویدیو زیبا از Selena Gomez به نام Calm Down', 'mdata/uploads/videos/rema selena gomez - calm down (lyrics) - ویدائو.mp4', 'mdata/uploads/posters/AdobeStock_196405424_Preview.jpeg', '2024-09-25 17:41:13'),
(14, 'Message In A Bottle', 'موزیک ویدیو زیبا از Taylor Swift به نام Message In A Bottle', 'mdata/uploads/videos/Message In A Bottle از Taylor Swift + دانلود - ویدائو.mp4', 'mdata/uploads/posters/AdobeStock_196405424_Preview.jpeg', '2024-09-25 17:45:32'),
(15, 'Lithium', 'موزیک ویدیو زیبا از Evanescence به نام Lithium', 'mdata/uploads/videos/موزیک ویدیو خارجی Lithium از Evanescence + دانلود - ویدائو.mp4', 'mdata/uploads/posters/AdobeStock_196405424_Preview.jpeg', '2024-09-25 17:46:10'),
(16, 'Ramsey', 'موزیک ویدیو زیبا از Arcane League of Legends به نام Ramsey', 'mdata/uploads/videos/موزیک ویدیو خارجی Goodbye از Ramsey در انیمیشن Arcane League of Legends - ویدائو.mp4', 'mdata/uploads/posters/AdobeStock_196405424_Preview.jpeg', '2024-09-25 17:46:56'),
(17, 'Spring', 'آهنگی زیبا از Rammstein به نام Spring', 'mdata/uploads/videos/موزیک ویدئوی Spring از Rammstein با زیرنویس فارسی - طرفداری.mp4', 'mdata/uploads/posters/AdobeStock_196405424_Preview.jpeg', '2024-09-25 18:27:43'),
(18, 'Slow Down', 'موزیک ویدیو زیبا از Selena Gomez به نام Slow Down', 'mdata/uploads/videos/[DatMusic.IR] Selena Gomez - Slow Down.mp4', 'mdata/uploads/posters/AdobeStock_196405424_Preview.jpeg', '2024-09-25 18:32:55'),
(19, 'Come & Get It', 'موزیک ویدیو زیبا از Selena Gomez به نام Come & Get It', 'mdata/uploads/videos/[DatMusic.IR] Selena Gomez - Come & Get It.mp4', 'mdata/uploads/posters/AdobeStock_196405424_Preview.jpeg', '2024-09-25 18:49:28'),
(20, 'Bad Liar', 'موزیک ویدیو زیبا از Selena Gomez به نام Bad Liar', 'mdata/uploads/videos/[DatMusic.IR] Selena Gomez - Bad Liar.mp4', 'mdata/uploads/posters/AdobeStock_196405424_Preview.jpeg', '2024-09-25 18:50:41'),
(21, 'Look At Her Now', 'موزیک ویدیو زیبا از Selena Gomez به نام Look At Her Now', 'mdata/uploads/videos/[DatMusic.IR] Selena Gomez - Look At Her Now.mp4', 'mdata/uploads/posters/AdobeStock_196405424_Preview.jpeg', '2024-09-25 18:51:30'),
(22, 'Love You Like A Song', 'موزیک ویدیو زیبا از Selena Gomez به نام Love You Like A Song', 'mdata/uploads/videos/[DatMusic.IR] Selena Gomez - Love You Like A Love Song.mp4', 'mdata/uploads/posters/AdobeStock_196405424_Preview.jpeg', '2024-09-25 18:52:15');

-- --------------------------------------------------------

--
-- Table structure for table `users_sessions`
--

CREATE TABLE `users_sessions` (
  `id` int(6) UNSIGNED NOT NULL,
  `user_id` int(6) UNSIGNED NOT NULL,
  `session_token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `end_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_sessions`
--
ALTER TABLE `admin_sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `timestamp` (`timestamp`);

--
-- Indexes for table `tbladmins`
--
ALTER TABLE `tbladmins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbllogs`
--
ALTER TABLE `tbllogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblsongs`
--
ALTER TABLE `tblsongs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblusers`
--
ALTER TABLE `tblusers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblvids`
--
ALTER TABLE `tblvids`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_sessions`
--
ALTER TABLE `users_sessions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_sessions`
--
ALTER TABLE `admin_sessions`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbladmins`
--
ALTER TABLE `tbladmins`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbllogs`
--
ALTER TABLE `tbllogs`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblsongs`
--
ALTER TABLE `tblsongs`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tblusers`
--
ALTER TABLE `tblusers`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tblvids`
--
ALTER TABLE `tblvids`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users_sessions`
--
ALTER TABLE `users_sessions`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

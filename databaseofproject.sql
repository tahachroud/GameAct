-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 15, 2025 at 02:18 PM
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
-- Database: `gameact`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `added_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `game_id`, `quantity`, `added_at`) VALUES
(4, 1, 1, 1, '2025-12-14 16:46:04');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `user_id`, `content`, `created_at`) VALUES
(8, 11, 8, 'v', '2025-11-20 20:09:11'),
(9, 11, 8, '2', '2025-11-20 20:11:38'),
(11, 15, 8, 'taysssss', '2025-11-21 20:24:57'),
(12, 25, 5, 'praaa3', '2025-11-24 10:20:36'),
(13, 27, 8, 'hala hala', '2025-11-24 10:23:16'),
(14, 27, 8, 'zama', '2025-11-24 10:37:25'),
(18, 27, 5, 'DFG', '2025-11-25 11:51:43'),
(19, 28, 5, 'HHHH', '2025-11-25 13:44:36'),
(21, 28, 5, 'WAA', '2025-11-25 14:22:16'),
(22, 28, 5, 'HHH', '2025-11-25 14:23:03'),
(25, 30, 5, 'ouh', '2025-11-26 01:01:49'),
(26, 30, 5, 'wooh', '2025-11-26 01:43:41'),
(27, 30, 5, 'bonj', '2025-11-26 20:29:43'),
(29, 36, 8, 'waa', '2025-11-27 22:40:13'),
(30, 36, 5, 'hehe', '2025-11-27 22:58:04'),
(31, 27, 5, 'thank god', '2025-11-27 23:13:04'),
(32, 41, 5, 'ya rahmen', '2025-11-27 23:21:32'),
(33, 47, 5, 'HIHIHI', '2025-11-28 00:20:57'),
(34, 49, 5, 'ouhoih', '2025-11-28 00:37:43'),
(35, 49, 5, 'ala saadi', '2025-11-28 00:38:05'),
(37, 74, 5, 'heheheh', '2025-11-28 17:05:13'),
(38, 74, 5, 'hola', '2025-11-28 17:05:21'),
(39, 74, 5, 'ooooo', '2025-11-28 17:05:30'),
(40, 74, 5, 'hhhhhh', '2025-11-28 17:05:51'),
(41, 74, 5, 'waa mam', '2025-11-28 17:12:12'),
(42, 74, 5, 'werereyy', '2025-11-28 17:14:14'),
(43, 74, 5, 'wa broo', '2025-11-28 17:15:43'),
(44, 76, 5, 'BONSOIR', '2025-11-28 17:25:57'),
(45, 78, 5, 'moumou', '2025-11-28 17:46:15'),
(46, 78, 8, 'waa', '2025-11-28 17:47:03'),
(47, 84, 5, 'waa', '2025-11-28 22:07:06'),
(48, 85, 5, 'hhh', '2025-11-28 22:17:14'),
(49, 85, 8, 'are you', '2025-11-28 22:37:10'),
(50, 85, 8, 'hhhh', '2025-11-28 22:37:30'),
(51, 84, 8, 'zamaa', '2025-11-28 22:38:13'),
(52, 87, 5, 'hjkcds,', '2025-11-28 22:39:06'),
(53, 87, 5, 'jaww', '2025-11-28 23:13:42'),
(54, 89, 5, 'heyooo', '2025-11-29 15:57:05'),
(55, 89, 5, 'testt', '2025-11-29 15:57:26'),
(56, 89, 5, 'HIIIIIIIIIIII', '2025-11-30 23:08:35'),
(57, 89, 5, 'waa', '2025-12-01 21:31:58'),
(58, 93, 5, 'salut', '2025-12-01 23:59:57'),
(59, 93, 5, 'waa', '2025-12-02 00:00:16'),
(60, 93, 5, 'waa', '2025-12-02 00:01:11'),
(61, 93, 5, 'HOW ARE YOU', '2025-12-02 00:06:49'),
(62, 87, 5, 'ccccccccccccccccc', '2025-12-02 10:50:08'),
(63, 134, 5, 'helloooo comment', '2025-12-02 11:25:56'),
(66, 143, 5, 'hollla', '2025-12-02 13:08:58'),
(67, 147, 5, 'HOLA', '2025-12-02 15:34:10'),
(68, 151, 5, 'night', '2025-12-02 16:27:59'),
(69, 154, 5, 'WERERY', '2025-12-03 19:32:22'),
(70, 158, 5, 'doudiiiiiii', '2025-12-03 20:53:30'),
(71, 158, 5, 'wererey', '2025-12-05 01:00:42'),
(72, 166, 5, 'jjjjj', '2025-12-05 02:22:08'),
(73, 166, 5, 'yyyyyyyy', '2025-12-05 02:22:21'),
(74, 166, 5, 'waaa', '2025-12-05 02:32:34'),
(75, 167, 5, 'hiii', '2025-12-05 21:35:02'),
(76, 168, 5, 'aaaaaaaaa', '2025-12-05 22:45:37'),
(77, 168, 5, 'aaaaaaaaaaa', '2025-12-05 22:45:48'),
(78, 168, 5, 'aaaaa', '2025-12-05 22:45:56'),
(79, 168, 5, 'aaaaa', '2025-12-05 22:46:03'),
(80, 168, 5, 'aaaaaa', '2025-12-05 22:46:11'),
(81, 166, 5, 'GGGGG', '2025-12-09 10:58:27'),
(82, 175, 5, 'bonjour', '2025-12-09 13:59:18'),
(84, 175, 5, 'waaa', '2025-12-12 20:34:55'),
(85, 198, 5, 'hcbsqc', '2025-12-12 20:59:33'),
(86, 201, 5, 'waa', '2025-12-13 13:21:47'),
(87, 213, 5, 'kilmechmech', '2025-12-13 15:31:29'),
(88, 228, 5, 'waaaaa', '2025-12-13 18:39:34'),
(89, 228, 8, 'amine', '2025-12-13 18:41:12'),
(90, 228, 5, 'waaaa', '2025-12-13 18:56:21'),
(91, 230, 8, 'waaa', '2025-12-13 18:57:59'),
(92, 232, 5, 'HII', '2025-12-13 21:17:01'),
(93, 232, 8, 'HIHIHIHI', '2025-12-13 21:17:40'),
(94, 233, 5, 'HAMDOULH', '2025-12-13 21:18:33'),
(95, 247, 5, 'werer', '2025-12-13 23:34:58'),
(96, 258, 5, 'waaw', '2025-12-14 09:44:36'),
(97, 261, 5, 'faloussssy', '2025-12-14 11:12:11'),
(98, 264, 8, 'waaa', '2025-12-14 11:36:25'),
(0, 261, 5, 'Mmmm ya halloufa', '2025-12-14 19:52:11'),
(0, 7412594, 5, 'xdcvbn:', '2025-12-14 21:51:21');

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `id` int(11) NOT NULL,
  `titre` varchar(50) NOT NULL,
  `description` varchar(200) NOT NULL,
  `lieu` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `statut` varchar(50) NOT NULL,
  `heure_deb` varchar(50) NOT NULL,
  `heure_fin` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`id`, `titre`, `description`, `lieu`, `date`, `statut`, `heure_deb`, `heure_fin`) VALUES
(2, 'oooo', '562302584ytghbuhkjjihg', 'fdvd', '2025-10-28', 'terminÃ©', '02:33', '02:22'),
(4, 'Tournoi Overwatch', 'Tournoi Overwatch', 'Passage Saint-Sébastien, Paris, Ile-de-France, Fra', '2025-12-31', 'à venir', '00:30', '06:28');

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `storyline` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `is_free` tinyint(1) NOT NULL DEFAULT 0,
  `rating` decimal(3,2) DEFAULT 0.00,
  `image_path` varchar(255) DEFAULT NULL,
  `trailer_path` varchar(255) DEFAULT NULL,
  `zip_file_path` varchar(255) DEFAULT NULL,
  `download_link` varchar(255) DEFAULT NULL,
  `date_added` date NOT NULL,
  `downloads` int(11) NOT NULL DEFAULT 0,
  `likes` int(11) NOT NULL DEFAULT 0,
  `downloads_7days` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `title`, `category`, `description`, `storyline`, `price`, `is_free`, `rating`, `image_path`, `trailer_path`, `zip_file_path`, `download_link`, `date_added`, `downloads`, `likes`, `downloads_7days`) VALUES
(1, 'batman', 'Adventure', 'batman arkham asylum like', 'you spawn as batman and you defeat the villains and finish the game', 19.99, 0, 0.00, 'assets/images/games/game_693ff7f46ccac.png', 'https://www.youtube.com/watch?v=9pnK8akbd2M', NULL, 'https://dopebox.to', '2025-12-15', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `game_title` varchar(150) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `receipt_path` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `likes` int(11) NOT NULL DEFAULT 0,
  `shares` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `parent_id` int(11) DEFAULT NULL,
  `pdf` varchar(255) DEFAULT NULL,
  `link` text DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `content`, `image`, `likes`, `shares`, `created_at`, `parent_id`, `pdf`, `link`, `images`) VALUES
(1, 5, 'azerty', NULL, 0, 0, '2025-12-15 12:15:17', NULL, NULL, '', '[]'),
(2, 5, 'azerty', NULL, 0, 0, '2025-12-15 12:16:50', NULL, NULL, '', '[]'),
(3, 5, 'azerty', NULL, 0, 0, '2025-12-15 13:44:13', NULL, NULL, '', '[]');

-- --------------------------------------------------------

--
-- Table structure for table `promo_codes`
--

CREATE TABLE `promo_codes` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `type` enum('percentage','fixed','shipping') NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `expires_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `id_question` int(11) NOT NULL,
  `texte_question` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) NOT NULL,
  `option_d` varchar(255) NOT NULL,
  `reponse_correcte` char(1) NOT NULL,
  `explication` text DEFAULT NULL,
  `points` int(11) DEFAULT 100,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`id_question`, `texte_question`, `option_a`, `option_b`, `option_c`, `option_d`, `reponse_correcte`, `explication`, `points`, `date_creation`) VALUES
(1, 'What year was the original Super Mario Bros released?', '1983', '1985', '1987', '1989', 'B', 'Super Mario Bros was released in 1985 for the Nintendo Entertainment System', 100, '2025-12-14 08:48:46'),
(2, 'Which company created Pac-Man?', 'Atari', 'Namco', 'Sega', 'Nintendo', 'B', 'Pac-Man was created by Namco in 1980', 100, '2025-12-14 08:48:46'),
(3, 'What was the first home video game console?', 'Atari 2600', 'Magnavox Odyssey', 'Intellivision', 'ColecoVision', 'B', 'The Magnavox Odyssey was released in 1972', 100, '2025-12-14 08:48:46'),
(4, 'In Donkey Kong what is the name of the character who would later become Mario?', 'Jumpman', 'Plumber', 'Luigi', 'Mario', 'A', 'Mario was originally called Jumpman in Donkey Kong', 100, '2025-12-14 08:48:46'),
(5, 'Which game popularized the side-scrolling platformer genre?', 'Pitfall', 'Super Mario Bros', 'Sonic the Hedgehog', 'Mega Man', 'B', 'Super Mario Bros set the standard for side-scrolling platformers', 100, '2025-12-14 08:48:46'),
(6, 'What color was the original Game Boy?', 'Black', 'White', 'Gray', 'Blue', 'C', 'The original Game Boy came in gray color', 100, '2025-12-14 08:48:46'),
(7, 'Which arcade game featured a yellow circle eating dots?', 'Pac-Man', 'Dig Dug', 'Galaga', 'Centipede', 'A', 'Pac-Man is the iconic yellow dot-eating character', 100, '2025-12-14 08:48:46'),
(8, 'What was Sonic the Hedgehog original color in early development?', 'Red', 'Green', 'Blue', 'Yellow', 'A', 'Sonic was originally designed with red coloring before becoming blue', 100, '2025-12-14 08:48:46'),
(9, 'Which game series features Kratos as the main character?', 'Devil May Cry', 'God of War', 'Bayonetta', 'Ninja Gaiden', 'B', 'Kratos is the protagonist of the God of War series', 100, '2025-12-14 08:49:05'),
(10, 'What is the name of the main character in Devil May Cry?', 'Nero', 'Vergil', 'Dante', 'Trish', 'C', 'Dante is the primary protagonist of Devil May Cry', 100, '2025-12-14 08:49:05'),
(11, 'In which year was the first Tomb Raider game released?', '1994', '1996', '1998', '2000', 'B', 'The original Tomb Raider was released in 1996', 100, '2025-12-14 08:49:05'),
(12, 'What company developed the Uncharted series?', 'Rocksteady', 'Naughty Dog', 'Insomniac', 'Sucker Punch', 'B', 'Naughty Dog developed all Uncharted games', 100, '2025-12-14 08:49:05'),
(13, 'Which game introduced bullet time mechanics?', 'Max Payne', 'The Matrix', 'Half-Life', 'F.E.A.R.', 'A', 'Max Payne popularized bullet time in gaming', 100, '2025-12-14 08:49:05'),
(14, 'What is the main weapon in the Gears of War series?', 'Assault Rifle', 'Lancer', 'Shotgun', 'Sniper Rifle', 'B', 'The Lancer chainsaw rifle is iconic to Gears of War', 100, '2025-12-14 08:49:05'),
(15, 'Which ninja character appears in both Dead or Alive and Ninja Gaiden?', 'Hayabusa', 'Scorpion', 'Strider', 'Joe Musashi', 'A', 'Ryu Hayabusa appears in both game series', 100, '2025-12-14 08:49:05'),
(16, 'What year did Grand Theft Auto V release?', '2011', '2012', '2013', '2014', 'C', 'GTA V was released in September 2013', 100, '2025-12-14 08:49:05'),
(17, 'Which game features a character named Ezio Auditore?', 'Dishonored', 'Assassins Creed', 'Hitman', 'Splinter Cell', 'B', 'Ezio is the protagonist in Assassins Creed II', 100, '2025-12-14 08:49:05'),
(18, 'What is the name of Batmans butler in the Arkham series?', 'Alfred', 'Lucius', 'Gordon', 'Robin', 'A', 'Alfred Pennyworth is Batmans loyal butler', 100, '2025-12-14 08:49:05'),
(19, 'Which studio developed the Civilization series?', 'Paradox', 'Firaxis Games', 'Creative Assembly', 'Relic', 'B', 'Firaxis Games has developed Civilization since Civ III', 100, '2025-12-14 08:49:23'),
(20, 'What resource is used to train units in StarCraft?', 'Gold', 'Minerals', 'Credits', 'Energy', 'B', 'Minerals and Vespene Gas are the main resources in StarCraft', 100, '2025-12-14 08:49:23'),
(21, 'Which faction in Command and Conquer uses Tiberium?', 'GDI and Nod', 'Allies', 'Soviets', 'Scrin', 'A', 'Both GDI and Nod factions revolve around Tiberium', 100, '2025-12-14 08:49:23'),
(22, 'What is the maximum population cap in Age of Empires II?', '150', '200', '250', '300', 'B', 'The standard population limit in AoE II is 200', 100, '2025-12-14 08:49:23'),
(23, 'Which game series features the Protoss race?', 'Warhammer', 'StarCraft', 'Halo Wars', 'Supreme Commander', 'B', 'Protoss is one of three races in StarCraft', 100, '2025-12-14 08:49:23'),
(24, 'What year was the first Total War game released?', '1998', '2000', '2002', '2004', 'B', 'Shogun Total War was released in 2000', 100, '2025-12-14 08:49:23'),
(25, 'Which civilization is known for war elephants in Age of Empires?', 'Persians', 'Romans', 'Chinese', 'Vikings', 'A', 'Persians have unique war elephant units', 100, '2025-12-14 08:49:23'),
(26, 'What is the name of the AI in XCOM Enemy Unknown?', 'CENTRAL', 'VIGILO', 'ADVENT', 'COMMANDER', 'A', 'Central Officer Bradford coordinates XCOM operations', 100, '2025-12-14 08:49:23'),
(27, 'Which game introduced the fog of war mechanic?', 'Warcraft', 'Dune II', 'Command and Conquer', 'Age of Empires', 'B', 'Dune II pioneered many RTS mechanics including fog of war', 100, '2025-12-14 08:49:23'),
(28, 'What is the name of the Zerg Overmind in StarCraft?', 'Kerrigan', 'Overmind', 'Cerebrate', 'Daggoth', 'B', 'The Overmind is the central intelligence of the Zerg', 100, '2025-12-14 08:49:23'),
(29, 'Which studio created the Hearts of Iron series?', 'Firaxis', 'Paradox Interactive', 'Ensemble Studios', 'Petroglyph', 'B', 'Paradox Interactive develops grand strategy games like Hearts of Iron', 100, '2025-12-14 08:49:23'),
(30, 'What is the primary victory condition in Civilization?', 'Domination only', 'Multiple victory types', 'Economic only', 'Cultural only', 'B', 'Civilization offers multiple paths to victory', 100, '2025-12-14 08:49:23'),
(31, 'Which company develops the Final Fantasy series?', 'Capcom', 'Square Enix', 'Bandai Namco', 'Atlus', 'B', 'Square Enix has developed Final Fantasy since the merger', 100, '2025-12-14 08:49:42'),
(32, 'What is the name of the main character in The Witcher 3?', 'Vesemir', 'Geralt', 'Ciri', 'Yennefer', 'B', 'Geralt of Rivia is the protagonist of The Witcher series', 100, '2025-12-14 08:49:42'),
(33, 'Which game features a character named Cloud Strife?', 'Final Fantasy VI', 'Final Fantasy VII', 'Final Fantasy VIII', 'Final Fantasy IX', 'B', 'Cloud is the main character in Final Fantasy VII', 100, '2025-12-14 08:49:42'),
(34, 'What is the currency in The Elder Scrolls V Skyrim?', 'Crowns', 'Septims', 'Gold', 'Coins', 'B', 'Septims are the official currency in Skyrim', 100, '2025-12-14 08:49:42'),
(35, 'Which studio created the Dark Souls series?', 'Capcom', 'FromSoftware', 'Team Ninja', 'Platinum Games', 'B', 'FromSoftware developed the Dark Souls trilogy', 100, '2025-12-14 08:49:42'),
(36, 'What is the name of the world in World of Warcraft?', 'Azeroth', 'Tamriel', 'Eorzea', 'Sanctuary', 'A', 'Azeroth is the world where World of Warcraft takes place', 100, '2025-12-14 08:49:42'),
(37, 'Which game features Personas as summoned entities?', 'Shin Megami Tensei', 'Persona series', 'Pokemon', 'Digimon', 'B', 'Personas are the signature mechanic of the Persona series', 100, '2025-12-14 08:49:42'),
(38, 'What is the main hub area in Dark Souls called?', 'Nexus', 'Firelink Shrine', 'Majula', 'Hunters Dream', 'B', 'Firelink Shrine serves as the central hub in Dark Souls', 100, '2025-12-14 08:49:42'),
(39, 'Which RPG series features turn-based combat with ATB system?', 'Dragon Quest', 'Final Fantasy', 'Chrono Trigger', 'Breath of Fire', 'B', 'Final Fantasy popularized the Active Time Battle system', 100, '2025-12-14 08:49:42'),
(40, 'Which game is considered the first true FPS?', 'Doom', 'Wolfenstein 3D', 'Quake', 'Duke Nukem 3D', 'B', 'Wolfenstein 3D pioneered the FPS genre in 1992', 100, '2025-12-14 08:50:00'),
(41, 'What is the name of the main character in Half-Life?', 'Adrian Shephard', 'Gordon Freeman', 'Barney Calhoun', 'Alyx Vance', 'B', 'Gordon Freeman is the protagonist of Half-Life', 100, '2025-12-14 08:50:00'),
(42, 'Which studio developed Counter-Strike Global Offensive?', 'Infinity Ward', 'Valve', 'DICE', 'Bungie', 'B', 'Valve Corporation developed CS GO', 100, '2025-12-14 08:50:00'),
(43, 'What year was the original Call of Duty released?', '2001', '2003', '2005', '2007', 'B', 'Call of Duty was first released in 2003', 100, '2025-12-14 08:50:00'),
(44, 'Which weapon is iconic to the Halo series?', 'BFG', 'Energy Sword', 'Plasma Rifle', 'Battle Rifle', 'B', 'The Energy Sword is one of Halos most iconic weapons', 100, '2025-12-14 08:50:00'),
(45, 'What is the maximum team size in Overwatch competitive mode?', '4', '5', '6', '8', 'C', 'Overwatch competitive teams have 6 players', 100, '2025-12-14 08:50:00'),
(46, 'Which game introduced the Battle Royale mode Warzone?', 'Call of Duty', 'Battlefield', 'Apex Legends', 'Fortnite', 'A', 'Warzone is Call of Dutys battle royale mode', 100, '2025-12-14 08:50:00'),
(47, 'What is the name of the alien race in Halo?', 'Flood', 'Covenant', 'Prometheans', 'Forerunners', 'B', 'The Covenant is the main alien alliance in Halo', 100, '2025-12-14 08:50:00'),
(48, 'Which studio created the Battlefield series?', 'DICE', 'Infinity Ward', 'Treyarch', 'Respawn', 'A', 'DICE has developed the Battlefield franchise', 100, '2025-12-14 08:50:00'),
(49, 'What is the crowbar associated with in gaming?', 'Half-Life', 'BioShock', 'Dead Space', 'Resident Evil', 'A', 'Gordon Freemans crowbar is iconic in Half-Life', 100, '2025-12-14 08:50:00'),
(50, 'Which game mode involves planting and defusing bombs?', 'Team Deathmatch', 'Search and Destroy', 'Capture the Flag', 'Domination', 'B', 'Search and Destroy is the classic bomb mode', 100, '2025-12-14 08:50:00'),
(51, 'Which game popularized the MOBA genre?', 'League of Legends', 'Defense of the Ancients', 'Heroes of the Storm', 'Smite', 'B', 'DotA as a Warcraft III mod created the MOBA genre', 100, '2025-12-14 08:50:17'),
(52, 'How many players are on each team in League of Legends?', '3', '4', '5', '6', 'C', 'LoL teams consist of 5 players each', 100, '2025-12-14 08:50:17'),
(53, 'What is the name of the jungle monster that grants a team buff in LoL?', 'Dragon', 'Baron Nashor', 'Rift Herald', 'Elder Dragon', 'B', 'Baron Nashor provides powerful team buffs when defeated', 100, '2025-12-14 08:50:17'),
(54, 'Which company developed Dota 2?', 'Riot Games', 'Valve', 'Blizzard', 'Epic Games', 'B', 'Valve Corporation developed and published Dota 2', 100, '2025-12-14 08:50:17'),
(55, 'What does MOBA stand for?', 'Multiplayer Online Battle Arena', 'Massive Online Battle Area', 'Multiple Object Battle Arena', 'Major Online Battle Alliance', 'A', 'MOBA stands for Multiplayer Online Battle Arena', 100, '2025-12-14 08:50:17'),
(56, 'Which role typically farms in the bottom lane in LoL?', 'Top Laner', 'ADC', 'Mid Laner', 'Jungler', 'B', 'The ADC or Attack Damage Carry farms bottom lane', 100, '2025-12-14 08:50:17'),
(57, 'What is the main objective structure in MOBA games?', 'Nexus or Ancient', 'Towers', 'Barracks', 'Inhibitors', 'A', 'Destroying the enemys Nexus or Ancient wins the game', 100, '2025-12-14 08:50:17'),
(58, 'Which MOBA features gods from various mythologies?', 'Dota 2', 'League of Legends', 'Smite', 'Heroes of the Storm', 'C', 'Smite features gods and deities from world mythologies', 100, '2025-12-14 08:50:17'),
(59, 'What year was League of Legends officially released?', '2007', '2009', '2011', '2013', 'B', 'League of Legends was released in October 2009', 100, '2025-12-14 08:50:17'),
(60, 'Which item is essential for vision control in MOBAs?', 'Potions', 'Wards', 'Boots', 'Elixirs', 'B', 'Wards provide vision and map control in MOBAs', 100, '2025-12-14 08:50:17'),
(61, 'Which company publishes the FIFA video game series?', 'Konami', 'EA Sports', '2K Sports', 'Sega', 'B', 'EA Sports has published FIFA games since 1993', 100, '2025-12-14 08:50:40'),
(62, 'What is the competing soccer game to FIFA?', 'Winning Eleven', 'Pro Evolution Soccer', 'Football Manager', 'Rocket League', 'B', 'PES is the main competitor to FIFA', 100, '2025-12-14 08:50:40'),
(63, 'Which basketball game series is most popular?', 'NBA Live', 'NBA 2K', 'NBA Jam', 'NBA Street', 'B', 'NBA 2K is the leading basketball simulation series', 100, '2025-12-14 08:50:40'),
(64, 'What sport is featured in Madden NFL?', 'Baseball', 'Basketball', 'American Football', 'Hockey', 'C', 'Madden NFL is an American Football simulation', 100, '2025-12-14 08:50:40'),
(65, 'Which game features extreme sports like skateboarding?', 'SSX', 'Tony Hawk Pro Skater', 'Skate', 'Steep', 'B', 'Tony Hawks Pro Skater popularized skateboarding games', 100, '2025-12-14 08:50:40'),
(66, 'What is the hockey game series called?', 'NHL series', 'Ice Hockey', 'Hockey League', 'Pro Hockey', 'A', 'EA Sports publishes the NHL hockey game series', 100, '2025-12-14 08:50:40'),
(67, 'Which game mode allows you to manage a sports team?', 'Quick Play', 'Career Mode', 'Exhibition', 'Tournament', 'B', 'Career Mode lets you manage teams over multiple seasons', 100, '2025-12-14 08:50:40'),
(68, 'What does FUT stand for in FIFA?', 'Full Ultimate Team', 'FIFA Ultimate Team', 'Fast Ultimate Tournament', 'Football Union Teams', 'B', 'FUT stands for FIFA Ultimate Team', 100, '2025-12-14 08:50:40'),
(69, 'Which racing game series is known for simulation realism?', 'Need for Speed', 'Gran Turismo', 'Burnout', 'Ridge Racer', 'B', 'Gran Turismo is famous for its realistic driving simulation', 100, '2025-12-14 08:50:55'),
(70, 'What company develops the Forza series?', 'Polyphony Digital', 'Turn 10 Studios', 'Codemasters', 'Criterion Games', 'B', 'Turn 10 Studios develops Forza Motorsport', 100, '2025-12-14 08:50:55'),
(71, 'Which racing game features illegal street racing?', 'Gran Turismo', 'Forza', 'Need for Speed', 'Project CARS', 'C', 'Need for Speed is known for street racing themes', 100, '2025-12-14 08:50:55'),
(72, 'What is the karting game featuring Nintendo characters?', 'Crash Team Racing', 'Mario Kart', 'Diddy Kong Racing', 'Sonic Racing', 'B', 'Mario Kart is Nintendos iconic kart racing series', 100, '2025-12-14 08:50:55'),
(73, 'Which game series focuses on Formula 1 racing?', 'F1 series', 'Grid', 'Dirt', 'WRC', 'A', 'The F1 series is the official Formula 1 racing game', 100, '2025-12-14 08:50:55'),
(74, 'What is the crash-focused racing game series?', 'Destruction Derby', 'Burnout', 'Flatout', 'Wreckfest', 'B', 'Burnout is famous for spectacular crashes', 100, '2025-12-14 08:50:55'),
(75, 'Which studio created Gran Turismo?', 'Turn 10', 'Polyphony Digital', 'Playground Games', 'Evolution Studios', 'B', 'Polyphony Digital develops the Gran Turismo series', 100, '2025-12-14 08:50:55'),
(76, 'What type of racing does Dirt series specialize in?', 'Formula 1', 'Rally', 'Street Racing', 'Kart Racing', 'B', 'Dirt series focuses on rally and off-road racing', 100, '2025-12-14 08:50:55'),
(77, 'Which game allows you to race with rocket-powered cars?', 'Trackmania', 'Rocket League', 'Hot Wheels', 'Distance', 'B', 'Rocket League features rocket-powered vehicular soccer', 100, '2025-12-14 08:50:55'),
(78, 'fffffffffffffffffffffffffffffffffffffff', 'fffffffffffffffffffff', 'fffffffffffffffffff', 'ffffffffffffffffffff', 'ffffffffffff', 'B', '', 100, '2025-12-15 13:07:09'),
(79, 'dddddddddddddddddddddddddddddddddddddddddddddddd', 'dddddddddddddddddd', 'dddddddddddddddd', 'dd', 'dddddddddd', 'C', '', 100, '2025-12-15 13:07:09'),
(80, 'dssssssssssssssssssssssss', 'ssssssssssss', 'ssssssssssssss', 'ssssssss', 'sssssssssss', 'C', '', 100, '2025-12-15 13:07:09'),
(81, 'qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq', 'qqqqqqqqqqqqqqqqqq', 'qqqqqqqqqqqqqqq', 'qqqqqqqqqqq', 'qqqqqqqqqqqqqqq', 'B', '', 100, '2025-12-15 13:07:09'),
(82, 'kkkkkkkkkkkkkkkkkkkkkkkkkkkk', 'kkkkkkkkkkk', 'kkkkkkk', 'kkkkkkkkkkkkkkkkk', 'kkkkkk', 'A', '', 100, '2025-12-15 13:07:09'),
(83, 'jjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjj', 'jjjjjjjjjjjjjj', 'jjjjjjj', 'jjjj', 'jjjjjjjjjjjjjjjj', 'A', '', 100, '2025-12-15 13:07:09'),
(84, 'hhhhhhhhhhhhhhhhhhhhhhhhh', 'hhhhhh', 'hhhhhhhhhhh', 'hhhhhhh', 'hhhhhhhhhhhhhhhhh', 'D', '', 100, '2025-12-15 13:07:09'),
(85, 'ooooooooooooooooooooooooooo', 'oooooooooooo', 'oooooooooooooo', 'oooooo', 'ooooooooooooooooooo', 'D', '', 100, '2025-12-15 13:07:09'),
(86, 'iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii', 'iiiiiiiiiiiiiii', 'iiiiiiiiiiiiii', 'iiiiiiiiiiii', 'iiiiiiiiiiiiiiiiiiiiii', 'C', '', 100, '2025-12-15 13:07:09');

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

CREATE TABLE `quiz` (
  `id_quiz` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `categorie` varchar(50) NOT NULL,
  `difficulte` enum('easy','medium','hard') DEFAULT 'medium',
  `image_url` varchar(255) DEFAULT 'popular-01.jpg',
  `id_createur` int(11) NOT NULL,
  `nombre_questions` int(11) DEFAULT 0,
  `nombre_completions` int(11) DEFAULT 0,
  `statut` enum('active','pending','deleted') DEFAULT 'active',
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_modification` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz`
--

INSERT INTO `quiz` (`id_quiz`, `titre`, `description`, `categorie`, `difficulte`, `image_url`, `id_createur`, `nombre_questions`, `nombre_completions`, `statut`, `date_creation`, `date_modification`) VALUES
(3, 'Retro Gaming Legends', 'Test your knowledge about classic games from the 80s and 90s that shaped gaming history', 'retro', 'easy', 'popular-01.jpg', 25, 8, 0, 'active', '2025-12-14 08:47:43', '2025-12-14 08:47:43'),
(4, 'Action Game Masters', 'Challenge yourself with questions about the most intense action games ever created', 'action', 'medium', 'popular-02.jpg', 25, 10, 0, 'active', '2025-12-14 08:47:43', '2025-12-14 08:47:43'),
(5, 'Strategy Genius Test', 'Prove your strategic thinking with this advanced quiz on complex strategy games', 'strategy', 'hard', 'popular-03.jpg', 25, 12, 0, 'active', '2025-12-14 08:47:43', '2025-12-14 08:47:43'),
(6, 'RPG Adventure Quiz', 'Journey through questions about epic role-playing games and their rich stories', 'rpg', 'easy', 'popular-04.jpg', 25, 9, 0, 'active', '2025-12-14 08:47:43', '2025-12-14 08:47:43'),
(7, 'First Person Shooter Pro', 'Test your knowledge of the most popular FPS games and their mechanics', 'fps', 'medium', 'popular-05.jpg', 25, 11, 0, 'active', '2025-12-14 08:47:43', '2025-12-14 10:46:56'),
(8, 'MOBA Expert Challenge', 'Advanced quiz for true MOBA enthusiasts and competitive players', 'moba', 'hard', 'popular-06.jpg', 25, 10, 0, 'active', '2025-12-14 08:47:43', '2025-12-14 08:47:43'),
(9, 'Sports Gaming Basics', 'Fun quiz about sports games that brought athletics to your screen', 'sports', 'easy', 'popular-07.jpg', 25, 8, 0, 'active', '2025-12-14 08:47:43', '2025-12-14 08:47:43'),
(10, 'Racing Championship', 'Rev your engines and test your knowledge of racing game history', 'racing', 'medium', 'popular-08.jpg', 25, 9, 0, 'active', '2025-12-14 08:47:43', '2025-12-14 08:47:43'),
(11, 'aaaaaaaaaaaaaaaaaaaaaa', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'action', 'medium', 'quiz/quiz_1765804029_4237.jpg', 25, 9, 0, 'active', '2025-12-15 13:07:09', '2025-12-15 13:07:09');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_question`
--

CREATE TABLE `quiz_question` (
  `id_quiz` int(11) NOT NULL,
  `id_question` int(11) NOT NULL,
  `ordre_question` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz_question`
--

INSERT INTO `quiz_question` (`id_quiz`, `id_question`, `ordre_question`) VALUES
(3, 1, 0),
(3, 2, 0),
(3, 3, 0),
(3, 4, 0),
(3, 5, 0),
(3, 6, 0),
(3, 7, 0),
(3, 8, 0),
(4, 9, 0),
(4, 10, 0),
(4, 11, 0),
(4, 12, 0),
(4, 13, 0),
(4, 14, 0),
(4, 15, 0),
(4, 16, 0),
(4, 17, 0),
(4, 18, 0),
(5, 19, 0),
(5, 20, 0),
(5, 21, 0),
(5, 22, 0),
(5, 23, 0),
(5, 24, 0),
(5, 25, 0),
(5, 26, 0),
(5, 27, 0),
(5, 28, 0),
(5, 29, 0),
(5, 30, 0),
(6, 31, 0),
(6, 32, 0),
(6, 33, 0),
(6, 34, 0),
(6, 35, 0),
(6, 36, 0),
(6, 37, 0),
(6, 38, 0),
(6, 39, 0),
(7, 40, 0),
(7, 41, 0),
(7, 42, 0),
(7, 43, 0),
(7, 44, 0),
(7, 45, 0),
(7, 46, 0),
(7, 47, 0),
(7, 48, 0),
(7, 49, 0),
(7, 50, 0),
(8, 51, 0),
(8, 52, 0),
(8, 53, 0),
(8, 54, 0),
(8, 55, 0),
(8, 56, 0),
(8, 57, 0),
(8, 58, 0),
(8, 59, 0),
(8, 60, 0),
(9, 61, 0),
(9, 62, 0),
(9, 63, 0),
(9, 64, 0),
(9, 65, 0),
(9, 66, 0),
(9, 67, 0),
(9, 68, 0),
(10, 69, 0),
(10, 70, 0),
(10, 71, 0),
(10, 72, 0),
(10, 73, 0),
(10, 74, 0),
(10, 75, 0),
(10, 76, 0),
(10, 77, 0),
(11, 78, 1),
(11, 79, 2),
(11, 80, 3),
(11, 81, 4),
(11, 82, 5),
(11, 83, 6),
(11, 84, 7),
(11, 85, 8),
(11, 86, 9);

-- --------------------------------------------------------

--
-- Table structure for table `quiz_result`
--

CREATE TABLE `quiz_result` (
  `id_result` int(11) NOT NULL,
  `id_quiz` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `score_total` int(11) NOT NULL,
  `reponses_correctes` int(11) NOT NULL,
  `temps_ecoule` int(11) NOT NULL COMMENT 'Time in seconds',
  `pourcentage` decimal(5,2) NOT NULL,
  `date_completion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz_result`
--

INSERT INTO `quiz_result` (`id_result`, `id_quiz`, `id_user`, `score_total`, `reponses_correctes`, `temps_ecoule`, `pourcentage`, `date_completion`) VALUES
(11, 9, 2, 700, 7, 17, 87.50, '2025-12-14 10:36:37'),
(12, 9, 13, 800, 8, 14, 100.00, '2025-12-14 10:40:24'),
(13, 9, 13, 700, 7, 23, 87.50, '2025-12-15 00:03:26'),
(14, 4, 25, 200, 2, 42, 20.00, '2025-12-15 12:15:55'),
(15, 11, 25, 300, 3, 21, 33.33, '2025-12-15 13:07:34');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `name` varchar(25) NOT NULL,
  `lastname` varchar(25) NOT NULL,
  `email` varchar(25) NOT NULL,
  `avatar` varchar(255) DEFAULT '',
  `level` int(11) DEFAULT 0,
  `xp` int(11) DEFAULT 0,
  `badges` varchar(255) DEFAULT '',
  `password` varchar(255) NOT NULL,
  `cin` int(11) NOT NULL,
  `gender` varchar(25) NOT NULL,
  `location` varchar(50) NOT NULL,
  `age` int(11) NOT NULL,
  `role` varchar(25) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  `secret_2fa` varchar(32) DEFAULT NULL,
  `is_superadmin` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `lastname`, `email`, `avatar`, `level`, `xp`, `badges`, `password`, `cin`, `gender`, `location`, `age`, `role`, `reset_token`, `reset_expires`, `secret_2fa`, `is_superadmin`, `created_at`) VALUES
(2, 'WejdeneChroud', 'Wejdene', 'Chroud', 'wejden@gmail.com', '', 0, 0, '', '$2y$10$8OixB1iITJjzqU7c0y', 87654321, 'female', 'Ariana', 22, 'client', 'b7ce7188578d52c40d718aced60ed7340de45d59f78d97a50a', '2025-12-02 17:35:05', NULL, 0, '2025-12-14 23:58:01'),
(4, 'NourKahl', 'Nour', 'Kahl', 'nourkahl@gmail.com', '', 0, 0, '', '$2y$10$BBmMlJ389vOnTlwreP', 14725836, 'female', 'Ariana', 14725836, 'client', NULL, NULL, NULL, 0, '2025-12-14 23:58:01'),
(12, 'SalmaKhessiba', 'Salma', 'Khessiba', 'salma@gmail.com', '', 0, 0, '', '12345678', 10203040, 'female', 'Ariana', 20, 'client', '8c7ada0bd90881bd0f9a2ca51ddbc6dfe8bb42bd85474232aa', '2025-12-02 15:19:20', NULL, 0, '2025-12-14 23:58:01'),
(13, 'KhalilChroud', 'Khalil', 'Chroud', 'khalil@gmail.com', '', 0, 0, '', '$2y$10$VddhCBhIYWPkX3sTTuJDR.WJy4khlNSSfw3K4my.m9viIx40qEsnK', 12345656, 'male', 'rouen', 29, 'client', NULL, NULL, NULL, 0, '2025-12-14 23:58:01'),
(25, 'TahaChroud', 'Taha', 'Chroud', 'tahachroud06@gmail.com', '', 0, 0, '', '$2y$10$Z3n.YywSva1lTSAxdmTpuey98FlhBNdRWwdaicfe9kRzc6APYkCtm', 12345678, 'male', 'Ariana', 20, 'admin', 'df80e407fb46a8d66ca3787616cc4d17ba407e38118d8746e9d100193e1d2e88', '2025-12-15 10:15:36', '2CSBY7U7GYZFYCDJLQWDTVMWP4JYINTP', 1, '2025-12-14 23:58:01'),
(27, 'TahaChroud', 'Taha', 'Chroud', 'mail@mail.com', '', 0, 0, '', '$2y$10$9qV51TX8Y1AEry0zsBBhVerHbmTZjYar4SolfFzL15SUu1nUu1l9W', 66666666, 'male', 'ARIANA', 25, 'admin', NULL, NULL, NULL, 0, '2025-12-14 23:58:01'),
(28, 'TahaChroud', 'Taha', 'Chroud', 'gmail@gmail.com', '', 0, 0, '', '$2y$10$3qKMinHcFnBcqyuV2dUAUOd3lS445lc1LJ2npuu0fj.Yol3fb6fRS', 62626262, 'male', 'Ariana', 25, 'admin', NULL, NULL, NULL, 0, '2025-12-14 23:58:01'),
(29, 'ZeinebMokni', 'Zeineb', 'Mokni', 'zeinebmokni1@gmail.com', '', 0, 0, '', '$2y$10$mjfvGvov2S7hXowFnbOVS.s3PYmYaDAhTELOAdzbnINXTUyvvk6V.', 63524174, 'female', 'Ariana', 20, 'client', NULL, NULL, 'PXYXPNUSH5JWIB4WTM7P3U67SRBWP7IN', 0, '2025-12-14 23:58:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_games_title` (`title`),
  ADD KEY `idx_games_category` (`category`),
  ADD KEY `idx_games_rating` (`rating`),
  ADD KEY `idx_games_is_free` (`is_free`),
  ADD KEY `idx_games_downloads7` (`downloads_7days`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_orders_user` (`user_id`),
  ADD KEY `idx_orders_game` (`game_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `promo_codes`
--
ALTER TABLE `promo_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`id_question`);

--
-- Indexes for table `quiz`
--
ALTER TABLE `quiz`
  ADD PRIMARY KEY (`id_quiz`),
  ADD KEY `id_createur` (`id_createur`),
  ADD KEY `categorie` (`categorie`),
  ADD KEY `statut` (`statut`);

--
-- Indexes for table `quiz_question`
--
ALTER TABLE `quiz_question`
  ADD PRIMARY KEY (`id_quiz`,`id_question`),
  ADD KEY `id_question` (`id_question`);

--
-- Indexes for table `quiz_result`
--
ALTER TABLE `quiz_result`
  ADD PRIMARY KEY (`id_result`),
  ADD KEY `id_quiz` (`id_quiz`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `promo_codes`
--
ALTER TABLE `promo_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `id_question` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `quiz`
--
ALTER TABLE `quiz`
  MODIFY `id_quiz` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `quiz_result`
--
ALTER TABLE `quiz_result`
  MODIFY `id_result` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `quiz`
--
ALTER TABLE `quiz`
  ADD CONSTRAINT `quiz_ibfk_1` FOREIGN KEY (`id_createur`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_question`
--
ALTER TABLE `quiz_question`
  ADD CONSTRAINT `quiz_question_ibfk_1` FOREIGN KEY (`id_quiz`) REFERENCES `quiz` (`id_quiz`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_question_ibfk_2` FOREIGN KEY (`id_question`) REFERENCES `question` (`id_question`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_result`
--
ALTER TABLE `quiz_result`
  ADD CONSTRAINT `quiz_result_ibfk_1` FOREIGN KEY (`id_quiz`) REFERENCES `quiz` (`id_quiz`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_result_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 4.6.5.1deb1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 22, 2017 at 09:48 PM
-- Server version: 5.7.16-0ubuntu2
-- PHP Version: 5.6.29-1+deb.sury.org~xenial+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `realestates`
--

-- --------------------------------------------------------

--
-- Table structure for table `ad`
--

CREATE TABLE `ad` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `area` decimal(8,0) UNSIGNED NOT NULL DEFAULT '0',
  `bedrooms` smallint(4) UNSIGNED NOT NULL DEFAULT '1',
  `baths` smallint(4) UNSIGNED NOT NULL DEFAULT '0',
  `type_id` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `user_id` int(10) UNSIGNED NOT NULL,
  `country_id` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `city_id` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `location_id` int(10) UNSIGNED DEFAULT NULL,
  `category_id` smallint(2) UNSIGNED NOT NULL DEFAULT '1',
  `publish_category_id` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` smallint(2) UNSIGNED NOT NULL DEFAULT '0',
  `position` smallint(4) UNSIGNED NOT NULL DEFAULT '4',
  `tags` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ad`
--

INSERT INTO `ad` (`id`, `title`, `description`, `price`, `area`, `bedrooms`, `baths`, `type_id`, `user_id`, `country_id`, `city_id`, `location_id`, `category_id`, `publish_category_id`, `date`, `status`, `position`, `tags`) VALUES
(35, 'Beograd, Palilula, Karaburma, Patrisa Lumumbe', '<span style=\"color: #000000; font-family: Arial; font-size: 13px; line-height: 17px; background-color: #ffffff;\">Predato za legalizaciju &ndash; U procesu legalizacije.</span><br style=\"color: #000000; font-family: Arial; font-size: 13px; line-height: 17px; background-color: #ffffff;\" /><br style=\"color: #000000; font-family: Arial; font-size: 13px; line-height: 17px; background-color: #ffffff;\" /><span style=\"color: #000000; font-family: Arial; font-size: 13px; line-height: 17px; background-color: #ffffff;\">Odlična garsonjera na dobrom mestu. Blizu prodavnica, po&scaron;te, vrtića, &scaron;kole, doma zdravlja, prevoza.</span>', '18500.00', '200', 0, 0, 2, 4, 1, 1, 3, 1, 2, '2017-01-22 19:54:06', 0, 4, '');

-- --------------------------------------------------------

--
-- Table structure for table `ad_category`
--

CREATE TABLE `ad_category` (
  `id` int(4) UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL,
  `slug` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ad_category`
--

INSERT INTO `ad_category` (`id`, `name`, `slug`) VALUES
(1, 'Prodaja', 'prodaja'),
(2, 'Izdavanje', 'izdavanje'),
(3, 'Zamena', 'zamena');

-- --------------------------------------------------------

--
-- Table structure for table `ad_images`
--

CREATE TABLE `ad_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL,
  `position` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `ad_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ad_images`
--

INSERT INTO `ad_images` (`id`, `name`, `position`, `ad_id`) VALUES
(59, 'c77338769ec32e6d782772fe39c259e8.jpg', 1, 35),
(60, '7b2d87c2be966a4f9d5dacfee99e70bd.jpg', 2, 35);

-- --------------------------------------------------------

--
-- Table structure for table `ad_image_collections`
--

CREATE TABLE `ad_image_collections` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL,
  `image_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ad_product_filters`
--

CREATE TABLE `ad_product_filters` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `filter_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `ad_product_filters`
--

INSERT INTO `ad_product_filters` (`id`, `product_id`, `filter_id`) VALUES
(3, 35, 78),
(4, 35, 63);

-- --------------------------------------------------------

--
-- Table structure for table `ad_publish_category`
--

CREATE TABLE `ad_publish_category` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL,
  `slug` varchar(80) NOT NULL,
  `tags` varchar(255) NOT NULL,
  `filter_name` varchar(80) NOT NULL,
  `status` smallint(2) UNSIGNED NOT NULL DEFAULT '1',
  `position` int(10) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ad_publish_category`
--

INSERT INTO `ad_publish_category` (`id`, `name`, `slug`, `tags`, `filter_name`, `status`, `position`) VALUES
(1, 'Agencija', 'agencija', 'agencije,agenciski,agencia,agent,agenti,pravnik', 'agencijski', 1, 1),
(2, 'Vlasnik', 'vlasnik', 'vlasnik,gazda,vlasnici,vlasnica', 'vlasnik', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `ad_specification`
--

CREATE TABLE `ad_specification` (
  `id` int(10) UNSIGNED NOT NULL,
  `type_id` int(4) UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL,
  `slug` varchar(80) NOT NULL,
  `position` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `status` smallint(2) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `ad_specification`
--

INSERT INTO `ad_specification` (`id`, `type_id`, `name`, `slug`, `position`, `status`) VALUES
(1, 1, 'Prateći objekti', 'prateci_objekti', 3, 1),
(2, 1, 'Pomagala', 'pomagala', 6, 1),
(3, 1, 'Vrsta grejanja', 'vrsta_grejanja', 4, 1),
(4, 1, 'Vrsta goriva', 'vrsta_goriva', 5, 1),
(5, 1, 'Stanje objekta', 'stanje_objekta', 2, 1),
(6, 1, 'Tip kuće', 'tip_kuce', 1, 1),
(7, 2, 'Tip stana', 'tip stana', 2, 1),
(8, 2, 'Prateći objekti', 'prateci_objekti', 3, 1),
(9, 2, 'Pomagala', 'pomagala', 4, 1),
(10, 2, 'Vrsta grejanja', 'vrsta_grejanja', 5, 1),
(11, 2, 'Vrsta goriva', 'vrsta_goriva', 6, 1),
(12, 2, 'Ekstra dodaci', 'ekstra_dodaci', 7, 1),
(18, 3, 'Oprema', 'oprema', 1, 1),
(19, 6, 'Prostorije', 'prostorije', 1, 1),
(20, 6, 'Prateći objekti', 'prateci_objekti', 2, 1),
(21, 6, 'Vrsta grejanja', 'vrsta_grejanja', 3, 1),
(22, 6, 'Vrsta goriva', 'vrsta_goriva', 4, 1),
(23, 6, 'Tip', 'tip', 5, 1),
(24, 2, 'Prevoz', 'prevoz', 8, 1),
(25, 2, 'Stanje objekta', 'stanje_objekta', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ad_specification_filter`
--

CREATE TABLE `ad_specification_filter` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL,
  `slug` varchar(80) NOT NULL,
  `type` smallint(2) UNSIGNED NOT NULL DEFAULT '1',
  `position` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `status` smallint(2) UNSIGNED NOT NULL DEFAULT '1',
  `specification_id` int(10) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ad_specification_filter`
--

INSERT INTO `ad_specification_filter` (`id`, `name`, `slug`, `type`, `position`, `status`, `specification_id`) VALUES
(1, 'Terasa', 'terasa', 1, 1, 1, 1),
(2, 'Parking', 'parking', 1, 2, 1, 1),
(3, 'Bašta', 'basta', 1, 3, 1, 1),
(4, 'Garaža', 'garaza', 1, 4, 1, 1),
(5, 'Ostava', 'ostava', 1, 5, 1, 1),
(6, 'Podrum', 'podrum', 1, 6, 1, 1),
(7, 'Balkon', 'balkon', 1, 7, 1, 1),
(8, 'Lođa', 'lodja', 1, 8, 1, 1),
(9, 'Bazen', 'bazen', 1, 9, 1, 1),
(10, 'Francuski balkon', 'franciski_balkon', 1, 10, 1, 1),
(11, 'Bunar', 'bunar', 1, 11, 1, 1),
(12, 'Vodovod', 'vodovod', 1, 12, 1, 1),
(13, 'Lift', 'lift', 1, 1, 1, 2),
(14, 'Rampa za invalidska kolica', 'rampa_za_invalidska_kolica', 1, 2, 1, 2),
(15, 'Etažno grejanje / grejno telo', 'etazno_grejanje_grejno_telo', 1, 1, 1, 3),
(16, 'Centralno grejanje', 'centralno_grejanje', 1, 2, 1, 3),
(17, 'Daljinsko grejanje', 'daljinsko_grejanje', 1, 3, 1, 3),
(18, 'Gas', 'gas', 1, 1, 1, 4),
(19, 'Čvrsta goriva', 'cvrsta_goriva', 1, 2, 1, 4),
(20, 'Struja', 'struja', 1, 3, 1, 4),
(21, 'Pelet', 'pelet', 1, 4, 1, 4),
(22, 'Lož ulje', 'loz_ulje', 1, 5, 1, 4),
(23, 'Standardna', 'standardna', 1, 1, 1, 5),
(24, 'Novogradnja', 'novogradnja', 1, 2, 1, 5),
(25, 'Renovirano', 'renovirano', 1, 3, 1, 5),
(26, 'Starogradnja', 'starogradnja', 1, 4, 1, 5),
(27, 'U izgradnji', 'u_izgradnji', 1, 5, 1, 5),
(28, 'Samostojeća kuća', 'samostojeca_kuca', 1, 1, 1, 6),
(29, 'Kuće u nizu', 'kuce_u_nizu', 1, 2, 1, 6),
(30, 'Dvojne kuće', 'dvojne_kuce', 1, 3, 1, 6),
(31, 'Duplex kuće', 'duplex_kuce', 1, 4, 1, 6),
(32, 'Kuće na uglu', 'kuce_na_uglu', 1, 5, 1, 6),
(33, 'Apartmanske kuće', 'apartmanske_kuce', 1, 6, 1, 6),
(34, 'Standardan', 'standardan', 1, 1, 1, 7),
(35, 'Apartman', 'apartman', 1, 2, 1, 7),
(36, 'Dupleks', 'dupleks', 1, 3, 1, 7),
(37, 'Delux', 'delux', 1, 4, 1, 7),
(38, 'Terasa', 'terasa', 1, 1, 1, 8),
(39, 'Podrum', 'podrum', 1, 2, 1, 8),
(40, 'Garaža', 'garaža', 1, 3, 1, 8),
(41, 'Parking', 'parking', 1, 4, 1, 8),
(42, 'Potkrovlje', 'potkrovlje', 1, 5, 1, 7),
(43, 'Televizor', 'televizor', 1, 1, 1, 18),
(44, 'Klima', 'klima', 1, 2, 1, 18),
(45, 'Frižider', 'frizider', 1, 3, 1, 18),
(46, 'Magacinski prostor', 'magacinski prostor', 1, 1, 1, 19),
(51, 'Lift', 'lift', 1, 1, 1, 9),
(52, 'Rampa za invalidska kolica', 'rampa_za_invalidska_kolica', 1, 2, 1, 9),
(53, 'Etažno grejanje / grejno telo ', 'etazno_grejanje_grejno telo ', 1, 1, 1, 10),
(54, 'Centralno grejanje', 'centralno_grejanje', 1, 2, 1, 10),
(55, 'Daljinsko grejanje', 'daljinsko_grejanje', 1, 3, 1, 10),
(56, 'Gas', 'gas', 1, 1, 1, 11),
(57, 'Čvrsta goriva', 'cvrsta_goriva', 1, 2, 1, 11),
(58, 'Struja', 'struja', 1, 3, 1, 11),
(59, 'Lož ulje', 'loz_ulje', 1, 4, 1, 11),
(60, 'Klima', 'klima', 1, 1, 1, 12),
(61, 'Kablovska televizija', 'kablovksa_televizija', 1, 2, 1, 12),
(62, 'Internet', 'internet', 1, 3, 1, 12),
(63, 'Video nadzor', 'video nadzor', 1, 4, 1, 12),
(64, 'Interfon', 'interfon', 1, 5, 1, 12),
(65, 'Telefon', 'telefon', 1, 6, 1, 12),
(66, 'Autobus', 'autobus', 1, 1, 1, 24),
(67, 'Trolejbus', 'trolejbus', 1, 2, 1, 24),
(68, 'Tramvaj', 'tramvaj', 1, 3, 1, 24),
(69, 'Voz', 'voz', 1, 4, 1, 24),
(70, 'Penthaus', 'penthaus', 1, 6, 1, 7),
(71, 'Nadogradnja', 'nadogradnja', 1, 8, 1, 7),
(72, 'Salonac', 'salonac', 1, 8, 1, 7),
(73, 'Dvorištni stan', 'dvoristni_stan', 1, 9, 1, 7),
(74, 'Nisko prizemlje', 'nisko_prizemlje', 1, 10, 1, 7),
(75, 'Visoko prizemlje', 'visoko prizemlje', 1, 11, 1, 7),
(76, 'U kući', 'u_kuci', 1, 12, 1, 7),
(77, 'Suteren', 'suteren', 1, 13, 1, 7),
(78, 'Standardna', 'standardna', 2, 1, 1, 25),
(79, 'Novogradnja', 'novogradnja', 2, 2, 1, 25),
(80, 'U izgradnji', 'u_izgradnji', 2, 3, 1, 25),
(81, 'Starogradnja', 'starogradnja', 2, 4, 1, 25);

-- --------------------------------------------------------

--
-- Table structure for table `ad_type`
--

CREATE TABLE `ad_type` (
  `id` int(10) UNSIGNED NOT NULL,
  `search_name` varchar(120) NOT NULL,
  `name` varchar(120) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `area` varchar(20) NOT NULL DEFAULT 'm<sup>2</sup>',
  `tags` varchar(255) NOT NULL,
  `position` int(4) UNSIGNED NOT NULL DEFAULT '1',
  `status` smallint(2) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `ad_type`
--

INSERT INTO `ad_type` (`id`, `search_name`, `name`, `slug`, `area`, `tags`, `position`, `status`) VALUES
(1, 'Кuće', 'Kuća', 'kuca', 'm<sup>2</sup>', 'kuca,kucica,vila', 2, 1),
(2, 'Stanovi/Apartmani', 'Stan/Apartman', 'stan-apartman', 'm<sup>2</sup>', 'zgrada,zgrade,stanovi,garsonjere,apartman,soliter,soliteri,stancic', 1, 1),
(3, 'Bungalovi', 'Bungalov', 'bungalov', 'm<sup>2</sup>', 'bungalovi, kampovi', 3, 1),
(4, 'Placevi', 'Plac', 'plac', 'a', 'plac,vikendica,vikendicu,odmor,basta', 7, 1),
(5, 'Zemljišta', 'Zemljište', 'zemljiste', 'h', 'zemlja,obradivo,obradiva,poljoprivredno,poljoprivredna,jutro,ar,ari', 8, 1),
(6, 'Poslovni objekti', 'Poslovni objekat', 'poslovni-objekat', 'm<sup>2</sup>', 'magacin,magacini,stovarista,stovariste,kancelarija,hladnjaca', 4, 1),
(7, 'Komercijalni objekti', 'Komercijlani objekat', 'komercijalni-objekat', 'm<sup>2</sup>', 'prodavnica,lokal,robna,roba,usluga,agencija', 5, 1),
(8, 'Garaže', 'Garaža', 'garaza', 'm<sup>2</sup>', 'garaze', 6, 1),
(9, 'Pomoćni objekti', 'Pomoćni objekti', 'pomocni_objekti', 'm<sup>2</sup>', 'pomocni objekti,skladista,ostvae,hobi,supe', 9, 1),
(10, 'Soba', 'Soba', 'soba', 'm<sup>2</sup>', 'sobe, izdavanje soba', 10, 1);

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE `city` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL,
  `slug` varchar(80) NOT NULL,
  `country_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `city`
--

INSERT INTO `city` (`id`, `name`, `slug`, `country_id`) VALUES
(1, 'Beograd', 'beograd', 1),
(2, 'Mladenovac', 'mladenovac', 1),
(3, 'Beočin', 'beocin', 1),
(4, 'Markovac', 'markovac', 1);

-- --------------------------------------------------------

--
-- Table structure for table `city_images`
--

CREATE TABLE `city_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL,
  `city_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `city_locations`
--

CREATE TABLE `city_locations` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL,
  `slug` varchar(80) NOT NULL,
  `city_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `city_locations`
--

INSERT INTO `city_locations` (`id`, `name`, `slug`, `city_id`) VALUES
(1, 'Čukarica', 'cukarica', 1),
(2, 'Novi Beograd', 'novi-beograd', 1),
(3, 'Palilula', 'palilula', 1),
(4, 'Rakovica', 'rakovica', 1),
(5, 'Savski venac', 'savski-venac', 1),
(6, 'Stari grad', 'stari-grad', 1),
(7, 'Voždovac', 'vozdovac', 1),
(8, 'Vračar', 'vracar', 1),
(9, 'Zemun', 'zemun', 1),
(10, 'Zvezdara', 'zvezdara', 1),
(11, 'Barajevo', 'barajevo', 1),
(12, 'Grocka', 'grocka', 1),
(13, 'Lazarevac', 'lazarevac', 1),
(14, 'Mladenovac', 'mladenovac', 1),
(15, 'Obrenovac', 'obrenovac', 1),
(16, 'Sopot', 'sopot', 1),
(17, 'Surčin', 'surcin', 1);

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE `country` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`id`, `name`) VALUES
(1, 'Srbija');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(120) DEFAULT NULL,
  `password` varchar(120) DEFAULT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `image` varchar(60) DEFAULT NULL,
  `type` smallint(2) UNSIGNED NOT NULL DEFAULT '1' COMMENT '1-user,2-agency,3-agent',
  `company` varchar(120) DEFAULT NULL,
  `status` smallint(2) NOT NULL DEFAULT '0',
  `token` varchar(255) DEFAULT NULL,
  `admin` smallint(2) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `first_name`, `last_name`, `phone`, `image`, `type`, `company`, `status`, `token`, `admin`) VALUES
(4, 'dmozar@gmail.com', 'b8ef704b8c1bfbd0d0c27d47fed1ca18', 'Damir', 'Mozar', '0658227701', NULL, 1, NULL, 1, '183f64c604594cee776dcaf115f586cf', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ad`
--
ALTER TABLE `ad`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `type_id` (`type_id`),
  ADD KEY `publish_category_id` (`publish_category_id`),
  ADD KEY `bedrooms` (`bedrooms`),
  ADD KEY `country_id` (`country_id`),
  ADD KEY `city_id` (`city_id`),
  ADD KEY `location_id` (`location_id`),
  ADD KEY `category_id` (`category_id`);
ALTER TABLE `ad` ADD FULLTEXT KEY `title` (`title`);
ALTER TABLE `ad` ADD FULLTEXT KEY `tags` (`tags`);

--
-- Indexes for table `ad_category`
--
ALTER TABLE `ad_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ad_images`
--
ALTER TABLE `ad_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ad_id` (`ad_id`);

--
-- Indexes for table `ad_image_collections`
--
ALTER TABLE `ad_image_collections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `image_id` (`image_id`);

--
-- Indexes for table `ad_product_filters`
--
ALTER TABLE `ad_product_filters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `filter_id` (`filter_id`);

--
-- Indexes for table `ad_publish_category`
--
ALTER TABLE `ad_publish_category`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `ad_publish_category` ADD FULLTEXT KEY `name` (`name`);
ALTER TABLE `ad_publish_category` ADD FULLTEXT KEY `tags` (`tags`);

--
-- Indexes for table `ad_specification`
--
ALTER TABLE `ad_specification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`type_id`);

--
-- Indexes for table `ad_specification_filter`
--
ALTER TABLE `ad_specification_filter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `specification_id` (`specification_id`);

--
-- Indexes for table `ad_type`
--
ALTER TABLE `ad_type`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `ad_type` ADD FULLTEXT KEY `search_name` (`search_name`);
ALTER TABLE `ad_type` ADD FULLTEXT KEY `name` (`name`);
ALTER TABLE `ad_type` ADD FULLTEXT KEY `slug` (`slug`);
ALTER TABLE `ad_type` ADD FULLTEXT KEY `tags` (`tags`);

--
-- Indexes for table `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`id`),
  ADD KEY `country_id` (`country_id`);
ALTER TABLE `city` ADD FULLTEXT KEY `name` (`name`);
ALTER TABLE `city` ADD FULLTEXT KEY `slug` (`slug`);
ALTER TABLE `city` ADD FULLTEXT KEY `name_2` (`name`);
ALTER TABLE `city` ADD FULLTEXT KEY `slug_2` (`slug`);

--
-- Indexes for table `city_images`
--
ALTER TABLE `city_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `city_id` (`city_id`);

--
-- Indexes for table `city_locations`
--
ALTER TABLE `city_locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `city_id` (`city_id`);
ALTER TABLE `city_locations` ADD FULLTEXT KEY `name` (`name`);
ALTER TABLE `city_locations` ADD FULLTEXT KEY `slug` (`slug`);

--
-- Indexes for table `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ad`
--
ALTER TABLE `ad`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT for table `ad_category`
--
ALTER TABLE `ad_category`
  MODIFY `id` int(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `ad_images`
--
ALTER TABLE `ad_images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;
--
-- AUTO_INCREMENT for table `ad_image_collections`
--
ALTER TABLE `ad_image_collections`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `ad_product_filters`
--
ALTER TABLE `ad_product_filters`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `ad_publish_category`
--
ALTER TABLE `ad_publish_category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `ad_specification`
--
ALTER TABLE `ad_specification`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `ad_specification_filter`
--
ALTER TABLE `ad_specification_filter`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;
--
-- AUTO_INCREMENT for table `ad_type`
--
ALTER TABLE `ad_type`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `city_images`
--
ALTER TABLE `city_images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `city_locations`
--
ALTER TABLE `city_locations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `country`
--
ALTER TABLE `country`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `ad`
--
ALTER TABLE `ad`
  ADD CONSTRAINT `cityConst` FOREIGN KEY (`city_id`) REFERENCES `city` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `countryConst` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `locationConst` FOREIGN KEY (`location_id`) REFERENCES `city_locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pubCatConst` FOREIGN KEY (`publish_category_id`) REFERENCES `ad_publish_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `typeAdConst` FOREIGN KEY (`type_id`) REFERENCES `ad_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `userAdConst` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ad_images`
--
ALTER TABLE `ad_images`
  ADD CONSTRAINT `adImgConst` FOREIGN KEY (`ad_id`) REFERENCES `ad` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ad_image_collections`
--
ALTER TABLE `ad_image_collections`
  ADD CONSTRAINT `imageCollConst` FOREIGN KEY (`image_id`) REFERENCES `ad_images` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ad_product_filters`
--
ALTER TABLE `ad_product_filters`
  ADD CONSTRAINT `filtConst` FOREIGN KEY (`filter_id`) REFERENCES `ad_specification_filter` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prodFiltConst` FOREIGN KEY (`product_id`) REFERENCES `ad` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ad_specification`
--
ALTER TABLE `ad_specification`
  ADD CONSTRAINT `specCatConst` FOREIGN KEY (`type_id`) REFERENCES `ad_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ad_specification_filter`
--
ALTER TABLE `ad_specification_filter`
  ADD CONSTRAINT `specifConst` FOREIGN KEY (`specification_id`) REFERENCES `ad_specification` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `city`
--
ALTER TABLE `city`
  ADD CONSTRAINT `countryIndxConstr` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `city_images`
--
ALTER TABLE `city_images`
  ADD CONSTRAINT `cityImageIndxConst` FOREIGN KEY (`city_id`) REFERENCES `city` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `city_locations`
--
ALTER TABLE `city_locations`
  ADD CONSTRAINT `locationCityConst` FOREIGN KEY (`city_id`) REFERENCES `city` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

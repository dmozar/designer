-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 02, 2017 at 03:56 PM
-- Server version: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

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
-- Table structure for table `design`
--

CREATE TABLE `design` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(250) NOT NULL,
  `img` varchar(60) DEFAULT NULL,
  `json` text NOT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `design`
--

INSERT INTO `design` (`id`, `title`, `img`, `json`, `created`, `updated`, `user_id`) VALUES
(20, 'COOLER MASTER gejmerski miš Devastator MS2K', NULL, '{"desktop":{"width":1120,"height":"700","json":"<div id=\\"design-editor\\" class=\\"editor noselect\\" spellcheck=\\"false\\" data-id=\\"\\" data-url=\\"http://dev.gigatron.rs/designer/load\\" style=\\"height: 701px; width: 1120px;\\"><div id=\\"uuid_119cef57-8bc8-dcc3-4a2c-144f32d70343\\" class=\\"group-element ui-draggable ui-draggable-handle\\" data-action=\\"Editor.EditBackground\\" style=\\"background-image: url(&quot;http://wallpaper-gallery.net/images/texture-wallpapers-hd/texture-wallpapers-hd-22.jpg&quot;); background-repeat: no-repeat; background-size: cover; background-position: center center; height: 697.5px; width: 1120px;\\"></div><div id=\\"uuid_35f64b4b-8f07-0284-4441-fedf15e44463\\" class=\\"group-element ui-draggable ui-draggable-handle\\" data-action=\\"Editor.EditImage\\" style=\\"left: 692px; top: 35px;\\"><img class=\\"editable-img\\" src=\\"http://gigatronshop.com/img/products/medium/image589d8a14711d3.png\\"></div><div id=\\"uuid_aa1d7d9d-eeba-8e18-1dc7-4b6b89d93a74\\" class=\\"group-element ui-draggable ui-draggable-handle\\" data-action=\\"Editor.EditImage\\" style=\\"left: 213px; top: 205px;\\"><img class=\\"editable-img\\" src=\\"http://reneemullingslewis.com/wp-content/uploads/2014/08/happy-woman-jumping.png\\"></div><div id=\\"uuid_d8f2eb79-7001-62f3-975c-76cd9a17296a\\" class=\\"group-element ui-draggable ui-draggable-handle\\" data-action=\\"Editor.EditText\\" style=\\"left: 49px; top: 35px;\\">\\n\\n\\n\\n\\n<h1><span style=\\"color: #ffffff;\\">COOLER MASTER gejmerski miš Devastator MS2K</span></h1>\\n\\n</div><div id=\\"uuid_da1fb42e-aebe-be9d-02a4-90b89cac8780\\" class=\\"group-element ui-draggable-dragging ui-draggable ui-draggable-handle\\" data-action=\\"Editor.EditText\\" style=\\"left: 526px; top: 371px;\\">\\n\\n\\n\\n\\n<span style=\\"color: #ffffff;\\"><small class=\\"old-price\\">Stara cena: 3.990 RSD</small> </span><br><span class=\\"usteda\\"><span style=\\"color: #ffffff;\\">Ušteda:</span> <span style=\\"color: #ff9900;\\"><strong>700 RSD</strong></span></span>\\n<h5><span style=\\"color: #ffffff; font-size: 24pt;\\">3.290 RSD</span></h5>\\n\\n</div></div>"},"tablet":{"width":"1024","height":"700","json":"<div id=\\"design-editor\\" class=\\"editor noselect\\" spellcheck=\\"false\\" data-id=\\"\\" data-url=\\"http://dev.gigatron.rs/designer/load\\" style=\\"height: 301px; width: 1024px;\\"><div id=\\"uuid_119cef57-8bc8-dcc3-4a2c-144f32d70343\\" class=\\"group-element ui-draggable ui-draggable-handle\\" data-action=\\"Editor.EditBackground\\" style=\\"background-image: url(&quot;http://wallpaper-gallery.net/images/texture-wallpapers-hd/texture-wallpapers-hd-22.jpg&quot;); background-repeat: no-repeat; background-size: cover; background-position: center center; height: 697.5px; width: 1116px;\\"></div><div id=\\"uuid_35f64b4b-8f07-0284-4441-fedf15e44463\\" class=\\"group-element ui-draggable ui-draggable-handle\\" data-action=\\"Editor.EditImage\\" style=\\"left: 572px; top: 77px;\\"><img class=\\"editable-img\\" src=\\"http://gigatronshop.com/img/products/medium/image589d8a14711d3.png\\"></div><div id=\\"uuid_aa1d7d9d-eeba-8e18-1dc7-4b6b89d93a74\\" class=\\"group-element ui-draggable ui-draggable-handle\\" data-action=\\"Editor.EditImage\\" style=\\"left: 112px; top: 85px;\\"><img class=\\"editable-img\\" src=\\"http://reneemullingslewis.com/wp-content/uploads/2014/08/happy-woman-jumping.png\\"></div><div id=\\"uuid_d8f2eb79-7001-62f3-975c-76cd9a17296a\\" class=\\"group-element ui-draggable ui-draggable-handle ui-draggable-dragging\\" data-action=\\"Editor.EditText\\" style=\\"left: 74px; top: 47px;\\">\\n\\n\\n\\n\\n<h1><span style=\\"color: #ffffff;\\">COOLER MASTER gejmerski miš Devastator MS2K</span></h1>\\n\\n</div><div id=\\"uuid_da1fb42e-aebe-be9d-02a4-90b89cac8780\\" class=\\"group-element ui-draggable ui-draggable-handle\\" data-action=\\"Editor.EditText\\" style=\\"left: 436px; top: 382px;\\">\\n\\n\\n\\n\\n<span style=\\"color: #ffffff;\\"><small class=\\"old-price\\">Stara cena: 3.990 RSD</small> </span><br><span class=\\"usteda\\"><span style=\\"color: #ffffff;\\">Ušteda:</span> <span style=\\"color: #ff9900;\\"><strong>700 RSD</strong></span></span>\\n<h5><span style=\\"color: #ffffff; font-size: 24pt;\\">3.290 RSD</span></h5>\\n\\n</div></div>"},"mobile-hd":{"width":"768","height":"700","json":"<div id=\\"design-editor\\" class=\\"editor noselect\\" spellcheck=\\"false\\" data-id=\\"\\" data-url=\\"http://dev.gigatron.rs/designer/load\\" style=\\"height: 301px; width: 768px;\\"><div id=\\"uuid_119cef57-8bc8-dcc3-4a2c-144f32d70343\\" class=\\"group-element ui-draggable ui-draggable-handle\\" data-action=\\"Editor.EditBackground\\" style=\\"background-image: url(&quot;http://wallpaper-gallery.net/images/texture-wallpapers-hd/texture-wallpapers-hd-22.jpg&quot;); background-repeat: no-repeat; background-size: cover; background-position: center center; height: 697.5px; width: 1116px;\\"></div><div id=\\"uuid_35f64b4b-8f07-0284-4441-fedf15e44463\\" class=\\"group-element ui-draggable ui-draggable-handle\\" data-action=\\"Editor.EditImage\\" style=\\"left: 383px; top: 130px;\\"><img class=\\"editable-img\\" src=\\"http://gigatronshop.com/img/products/medium/image589d8a14711d3.png\\"></div><div id=\\"uuid_aa1d7d9d-eeba-8e18-1dc7-4b6b89d93a74\\" class=\\"group-element ui-draggable ui-draggable-handle ui-draggable-dragging\\" data-action=\\"Editor.EditImage\\" style=\\"left: 104px; top: 109px;\\"><img class=\\"editable-img\\" src=\\"http://reneemullingslewis.com/wp-content/uploads/2014/08/happy-woman-jumping.png\\"></div><div id=\\"uuid_d8f2eb79-7001-62f3-975c-76cd9a17296a\\" class=\\"group-element ui-draggable ui-draggable-handle\\" data-action=\\"Editor.EditText\\" style=\\"left: 74px; top: 47px;\\">\\n\\n\\n\\n\\n<h1><span style=\\"color: #ffffff;\\">COOLER MASTER gejmerski miš Devastator MS2K</span></h1>\\n\\n</div><div id=\\"uuid_da1fb42e-aebe-be9d-02a4-90b89cac8780\\" class=\\"group-element ui-draggable ui-draggable-handle\\" data-action=\\"Editor.EditText\\" style=\\"left: 331px; top: 482px;\\">\\n\\n\\n\\n\\n<span style=\\"color: #ffffff;\\"><small class=\\"old-price\\">Stara cena: 3.990 RSD</small> </span><br><span class=\\"usteda\\"><span style=\\"color: #ffffff;\\">Ušteda:</span> <span style=\\"color: #ff9900;\\"><strong>700 RSD</strong></span></span>\\n<h5><span style=\\"color: #ffffff; font-size: 24pt;\\">3.290 RSD</span></h5>\\n\\n</div></div>"},"mobile":{"width":"480","height":"700","json":"<div id=\\"design-editor\\" class=\\"editor noselect\\" spellcheck=\\"false\\" data-id=\\"\\" data-url=\\"http://dev.gigatron.rs/designer/load\\" style=\\"height: 301px; width: 480px;\\"><div id=\\"uuid_119cef57-8bc8-dcc3-4a2c-144f32d70343\\" class=\\"group-element ui-draggable ui-draggable-handle\\" data-action=\\"Editor.EditBackground\\" style=\\"background-image: url(&quot;http://wallpaper-gallery.net/images/texture-wallpapers-hd/texture-wallpapers-hd-22.jpg&quot;); background-repeat: no-repeat; background-size: cover; background-position: center center; height: 697.5px; width: 1116px;\\"></div><div id=\\"uuid_35f64b4b-8f07-0284-4441-fedf15e44463\\" class=\\"group-element ui-draggable ui-draggable-handle\\" data-action=\\"Editor.EditImage\\" style=\\"left: 272px; top: 184px;\\"><img class=\\"editable-img\\" src=\\"http://gigatronshop.com/img/products/medium/image589d8a14711d3.png\\"></div><div id=\\"uuid_aa1d7d9d-eeba-8e18-1dc7-4b6b89d93a74\\" class=\\"group-element ui-draggable ui-draggable-handle\\" data-action=\\"Editor.EditImage\\" style=\\"left: 107px; top: 143px; z-index: 6;\\"><img class=\\"editable-img\\" src=\\"http://reneemullingslewis.com/wp-content/uploads/2014/08/happy-woman-jumping.png\\"></div><div id=\\"uuid_d8f2eb79-7001-62f3-975c-76cd9a17296a\\" class=\\"group-element ui-draggable ui-draggable-handle\\" data-action=\\"Editor.EditText\\" style=\\"left: 43px; top: 18px;\\">\\n\\n\\n\\n\\n<h1><span style=\\"color: #ffffff;\\">COOLER MASTER gejmerski miš Devastator MS2K</span></h1>\\n\\n</div><div id=\\"uuid_da1fb42e-aebe-be9d-02a4-90b89cac8780\\" class=\\"group-element ui-draggable ui-draggable-handle ui-draggable-dragging\\" data-action=\\"Editor.EditText\\" style=\\"left: 192px; top: 512px;\\">\\n\\n\\n\\n\\n<span style=\\"color: #ffffff;\\"><small class=\\"old-price\\">Stara cena: 3.990 RSD</small> </span><br><span class=\\"usteda\\"><span style=\\"color: #ffffff;\\">Ušteda:</span> <span style=\\"color: #ff9900;\\"><strong>700 RSD</strong></span></span>\\n<h5><span style=\\"color: #ffffff; font-size: 24pt;\\">3.290 RSD</span></h5>\\n\\n</div></div>"},"mobile-small":{"width":"340","height":"700","json":"<div id=\\"design-editor\\" class=\\"editor noselect\\" spellcheck=\\"false\\" data-id=\\"\\" data-url=\\"http://dev.gigatron.rs/designer/load\\" style=\\"height: 301px; width: 340px;\\"><div id=\\"uuid_119cef57-8bc8-dcc3-4a2c-144f32d70343\\" class=\\"group-element ui-draggable ui-draggable-handle\\" data-action=\\"Editor.EditBackground\\" style=\\"background-image: url(&quot;http://wallpaper-gallery.net/images/texture-wallpapers-hd/texture-wallpapers-hd-22.jpg&quot;); background-repeat: no-repeat; background-size: cover; background-position: center center; height: 697.5px; width: 1116px;\\"></div><div id=\\"uuid_35f64b4b-8f07-0284-4441-fedf15e44463\\" class=\\"group-element ui-draggable ui-draggable-handle\\" data-action=\\"Editor.EditImage\\" style=\\"left: 131px; top: 220px;\\"><img class=\\"editable-img\\" src=\\"http://gigatronshop.com/img/products/medium/image589d8a14711d3.png\\"></div><div id=\\"uuid_aa1d7d9d-eeba-8e18-1dc7-4b6b89d93a74\\" class=\\"group-element ui-draggable ui-draggable-handle\\" data-action=\\"Editor.EditImage\\" style=\\"left: 18px; top: 169px; z-index: 6;\\"><img class=\\"editable-img\\" src=\\"http://reneemullingslewis.com/wp-content/uploads/2014/08/happy-woman-jumping.png\\"></div><div id=\\"uuid_d8f2eb79-7001-62f3-975c-76cd9a17296a\\" class=\\"group-element ui-draggable ui-draggable-handle ui-draggable-dragging\\" data-action=\\"Editor.EditText\\" style=\\"left: 23px; top: 12px;\\">\\n\\n\\n\\n\\n<h1><span style=\\"color: #ffffff;\\">COOLER MASTER gejmerski miš Devastator MS2K</span></h1>\\n\\n</div><div id=\\"uuid_da1fb42e-aebe-be9d-02a4-90b89cac8780\\" class=\\"group-element ui-draggable ui-draggable-handle\\" data-action=\\"Editor.EditText\\" style=\\"left: 110px; top: 536px;\\">\\n\\n\\n\\n\\n<span style=\\"color: #ffffff;\\"><small class=\\"old-price\\">Stara cena: 3.990 RSD</small> </span><br><span class=\\"usteda\\"><span style=\\"color: #ffffff;\\">Ušteda:</span> <span style=\\"color: #ff9900;\\"><strong>700 RSD</strong></span></span>\\n<h5><span style=\\"color: #ffffff; font-size: 24pt;\\">3.290 RSD</span></h5>\\n\\n</div></div>"}}', '2017-03-01 15:11:27', '2017-03-01 15:11:27', 4);

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
(4, 'dmozar@gmail.com', 'b8ef704b8c1bfbd0d0c27d47fed1ca18', 'Damir', 'Mozar', '0658227701', NULL, 1, NULL, 1, '1cb3748eba94a38615aaa191a74a7bc0', 1);

--
-- Indexes for dumped tables
--

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
-- Indexes for table `design`
--
ALTER TABLE `design`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
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
-- AUTO_INCREMENT for table `design`
--
ALTER TABLE `design`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `city`
--
ALTER TABLE `city`
  ADD CONSTRAINT `countryIndxConstr` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `city_locations`
--
ALTER TABLE `city_locations`
  ADD CONSTRAINT `locationCityConst` FOREIGN KEY (`city_id`) REFERENCES `city` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `design`
--
ALTER TABLE `design`
  ADD CONSTRAINT `userdesignConstr` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

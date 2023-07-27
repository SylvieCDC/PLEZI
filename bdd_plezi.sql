-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 26, 2023 at 01:59 PM
-- Server version: 8.0.31
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bdd_plezi`
--

-- --------------------------------------------------------

--
-- Table structure for table `adresses_de_livraison`
--

DROP TABLE IF EXISTS `adresses_de_livraison`;
CREATE TABLE IF NOT EXISTS `adresses_de_livraison` (
  `Id_adresses_de_livraison` int NOT NULL AUTO_INCREMENT,
  `nom_adresse` varchar(250) DEFAULT NULL,
  `numero_adresse` int DEFAULT NULL,
  `rue_adresse` varchar(250) DEFAULT NULL,
  `code_postal` int DEFAULT NULL,
  `ville` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`Id_adresses_de_livraison`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `avoir`
--

DROP TABLE IF EXISTS `avoir`;
CREATE TABLE IF NOT EXISTS `avoir` (
  `Id_user` int NOT NULL,
  `Id_adresses_de_livraison` int NOT NULL,
  PRIMARY KEY (`Id_user`,`Id_adresses_de_livraison`),
  KEY `Id_adresses_de_livraison` (`Id_adresses_de_livraison`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `Id_categorie` int NOT NULL AUTO_INCREMENT,
  `nom_categorie` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`Id_categorie`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`Id_categorie`, `nom_categorie`) VALUES
(1, 'Starters'),
(2, 'Bokits'),
(3, 'Bowls'),
(4, 'Salades'),
(5, 'Sauces'),
(6, 'Desserts'),
(7, 'Boissons');

-- --------------------------------------------------------

--
-- Table structure for table `commandes`
--

DROP TABLE IF EXISTS `commandes`;
CREATE TABLE IF NOT EXISTS `commandes` (
  `Id_commande` int NOT NULL AUTO_INCREMENT,
  `date_commande` datetime DEFAULT NULL,
  `prix_commande` float NOT NULL,
  `Id_user` int NOT NULL,
  PRIMARY KEY (`Id_commande`),
  KEY `Id_user` (`Id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `composer`
--

DROP TABLE IF EXISTS `composer`;
CREATE TABLE IF NOT EXISTS `composer` (
  `Id_produit` int NOT NULL,
  `Id_commande` int NOT NULL,
  PRIMARY KEY (`Id_produit`,`Id_commande`),
  KEY `Id_commande` (`Id_commande`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produits`
--

DROP TABLE IF EXISTS `produits`;
CREATE TABLE IF NOT EXISTS `produits` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre_produit` varchar(250) DEFAULT NULL,
  `enonce_produit` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `image_produit` varchar(250) DEFAULT NULL,
  `prix_produit` varchar(250) DEFAULT NULL,
  `Id_categorie` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Id_categorie` (`Id_categorie`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `produits`
--

INSERT INTO `produits` (`id`, `titre_produit`, `enonce_produit`, `image_produit`, `prix_produit`, `Id_categorie`) VALUES
(1, 'Frites de patate douce', 'Des frites apportant cette touche de douceur qui convient à certains\r\n        plats, comme les bokits !', '../upload_images/b656507e7d5aaf17c9e96e7efc229588.jpeg', '6', 1),
(29, 'Alloco', 'Banane Plantain', '../upload_images/a80798ad227425c61e1171d24597310d.png', '6.50', 1),
(30, 'Coleslaw', 'Mélange de chou et de carottes râpées', '../upload_images/e7a6d2c1c16c43334ef6fbd1168feb18.jpg', '6', 1),
(31, 'Salade Pikliz', 'Salade exotique: Pikliz, mangue, papaye et avocat', '../upload_images/b5ee2c28b01a3317c8b55bba6bbe2b3c.jpeg', '7', 1),
(32, 'Accras de Morue', 'Petit beignet farci avec de la morue', '../upload_images/5325350d6a060264d6a7c8ad7959b33d.jpeg', '8', 1),
(33, 'Bokit Poulet Boucane', 'Souskay de morue, brin de ciboulette, piment doux, salade, tomate, sauce créoline', '../upload_images/0d5f74589c50dacdb3101a89abd9c12f.jpg', '15', 2),
(34, 'Bokit Des Mers', 'Souskay de morue, brin de ciboulette, piment doux, salade, tomate, sauce créoline', '../upload_images/44ee0f56b0ccf43e38a8695aa7b2079a.jpeg', '17.50', 2),
(35, 'Bokit Végétarien', 'Légumes grillés (aubergine, courgette, carotte), sauce créoline', '../upload_images/a6e6be548503a6b46b1ec0a77f0768ce.jpg', '17', 2),
(36, 'Le Césaire', 'Riz sauvage cuit façon pilaf, haricot rouge, colombo de poulet, poivrons grille, piment doux', '../upload_images/9af76d37aafa878c545cf20ff1d3f7f2.jpg', '13', 3),
(37, 'Le Nardale', 'Riz noir, lentilles dahl, émincé de poulet boucané, sauce créoline', '../upload_images/441fede180c21f380c06134553247d60.jpg', '14', 3),
(38, 'Le Condé', 'Riz safrané, rougail saucisse, dés d’avocat, sauce créoline', '../upload_images/c1c623e43434eb24d48c2dc24aaa2178.jpg', '15', 3),
(39, 'Le Dumas', 'Riz noir , tilapia grillé, haricot blanc, purée d’avocat', '../upload_images/918cff05c1dc014fee78bc5f2fef9742.jpg', '16', 3),
(40, 'Salade UN', 'Quinoa, purée d’avocat, dés de tomate, lardons végétaux, dés de chèvre, crouton à l’ail', '../upload_images/3ad799ea1a9a698d64a1cf56c2a4ea55.jpg', '16', 4),
(41, 'Salade DEUX', 'Blé cuit, combinaison de fruits (mangue, passion, pomme), fêta, tomate', '../upload_images/08805d30b9a490f836175c280f006bc4.jpg', '17', 4),
(42, 'Sauce chien', 'Tomate, oignon, piment végétarien, persil, ciboule, citron vert, ail', '../upload_images/0057913867d063688865367e9dc79b5e.jpeg', NULL, 5),
(43, 'Sauce créoline', '', '../upload_images/3f8fd9f7259271d5eb1a3947bac4e5b8.jpeg', NULL, 5),
(44, 'Sauce mangue', 'Mangue, moutarde, curry, vin blanc, fond de veau ou de poisson', '../upload_images/9a31fcdd74a16192a79fc0adaeec62a9.jpeg', NULL, 5),
(45, 'Sauce curry', '', '../upload_images/beedd17e5e746de3656d217dc2a71fb3.jpeg', NULL, 5),
(46, 'Sauce coco lova', '', '../upload_images/a3edcc8f7c94685b3c8e56a5685f5738.jpeg', NULL, 5),
(47, 'Blanc manger', 'Mangue passion ', '../upload_images/6d55b24ce7597c63537c54db5877cc04.jpeg', '7.50', 6),
(48, 'Gâteau aux chocolats', 'Façon Antillaise', '../upload_images/ceffb8fa647544af89272969f6db5d19.jpeg', '8.50', 6),
(49, 'Mélange de fruits frais', 'papaye, mangue, goyave, fruit de la passion, banane', '../upload_images/4b780f7e5c37ad2215cdbea2129bab50.jpeg', '7', 6),
(50, 'Floup', 'Glace', '../upload_images/b77225fbdeb8440a612b39b09303e748.jpg', '3', 6),
(51, 'Cristalline', '', '../upload_images/f76d55e9c0dbee2c7dd0b62d9aeff963.png', '2', 7),
(52, 'Perrier', '', '../upload_images/4ea74c812773fb751c8f69d4c5932b2e.png', '3', 7),
(53, 'Vita malt', '', '../upload_images/6c98fb7f123cb8b56e2b26e33c9b766f.png', '5', 7),
(54, 'Maaza', 'Jus de mangue', '../upload_images/adf8f6fa15076e5075358b2429d9130c.jpg', '5.50', 7),
(55, 'Mont Pelé', 'Jus Goyave Ananas', '../upload_images/5dd699410594f19d64beb7826a85131f.jpeg', '5.50', 7),
(56, 'Mont Pelé', 'Jus Tropical', '../upload_images/7050d6d7cd23555d09c2c44db4b8c57b.jpeg', '5', 7),
(57, 'Soda l’Ordinaire', '', '../upload_images/b072f16fe12506f48b5e6e87632fae14.jpg', '5', 7),
(58, 'Eaux de Coco', '', '../upload_images/ec0294c9cd12c083b448995c5309e08e.png', '4', 7),
(59, 'Coca Cola', '', '../upload_images/f3b5448a4ebd95f6a78068204439b77d.jpg', '5', 7),
(60, 'Lipton Ice Tea', '', '../upload_images/9ae129a15f6115ed0bb34beeab2edd7e.jpg', '4', 7),
(61, 'test', 'test', '../upload_images/54ffaf8847ab28de8a94866f1e079e06.jpg', '58', 4),
(62, 'test', 'blabla', '../upload_images/aa1c9aacfcba31ccb9b42a25a0f1acad.jpg', '8', 4);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `Id_role` int NOT NULL AUTO_INCREMENT,
  `nom_role` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Id_role`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`Id_role`, `nom_role`) VALUES
(0, 'invité'),
(1, 'admin'),
(2, 'users');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `Id_user` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(250) DEFAULT NULL,
  `prenom` varchar(250) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `pass` varchar(250) DEFAULT NULL,
  `telephone` varchar(50) DEFAULT NULL,
  `token` varchar(250) DEFAULT NULL,
  `inscription_date` date DEFAULT NULL,
  `Id_role` int NOT NULL,
  PRIMARY KEY (`Id_user`),
  UNIQUE KEY `Id_role` (`Id_role`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Id_user`, `nom`, `prenom`, `email`, `pass`, `telephone`, `token`, `inscription_date`, `Id_role`) VALUES
(2, 'Admin', 'Plézi', 'plezicaribbean@gmail.com', '$2y$10$zXH9lgPBSU./IHPM4WWkUOKabvdTDg2wUsZf7069mnp1mvJP13i4a', '0614535454', 'efe0b0d578135313d57ccb62a440ce4d36dc8093f4d8e7b22d3f82c2362ecc02', '2023-07-20', 1),
(3, 'CARREIRA DA CRUZ', 'Sylvie', 'harmonyme.free@gmail.com', '$2y$10$j5gPE.cvC/7HV9Yi2tp2zOYBeP3GQqmQJYbJfU2WMo/RNaZK9iUqq', '0614538035', '901d74d6108019faf51a21c39ef8e4d50ec37fec04e30dadf0b3bbe46c78290a', '2023-07-25', 2);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `avoir`
--
ALTER TABLE `avoir`
  ADD CONSTRAINT `avoir_ibfk_1` FOREIGN KEY (`Id_user`) REFERENCES `users` (`Id_user`),
  ADD CONSTRAINT `avoir_ibfk_2` FOREIGN KEY (`Id_adresses_de_livraison`) REFERENCES `adresses_de_livraison` (`Id_adresses_de_livraison`);

--
-- Constraints for table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`Id_user`) REFERENCES `users` (`Id_user`);

--
-- Constraints for table `composer`
--
ALTER TABLE `composer`
  ADD CONSTRAINT `composer_ibfk_1` FOREIGN KEY (`Id_produit`) REFERENCES `produits` (`id`),
  ADD CONSTRAINT `composer_ibfk_2` FOREIGN KEY (`Id_commande`) REFERENCES `commandes` (`Id_commande`);

--
-- Constraints for table `produits`
--
ALTER TABLE `produits`
  ADD CONSTRAINT `produits_ibfk_1` FOREIGN KEY (`Id_categorie`) REFERENCES `categories` (`Id_categorie`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`Id_role`) REFERENCES `role` (`Id_role`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

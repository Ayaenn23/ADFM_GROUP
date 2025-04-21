-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 19 avr. 2025 à 19:05
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `adfm`
--

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `CIN` varchar(12) NOT NULL COMMENT 'votre cin',
  `nom` varchar(110) NOT NULL,
  `prénom` varchar(110) NOT NULL,
  `email` varchar(300) NOT NULL,
  `téléphone` varchar(100) NOT NULL,
  `mot_de_passe` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`CIN`, `nom`, `prénom`, `email`, `téléphone`, `mot_de_passe`) VALUES
('AA12345', 'Lhbiri', 'Douaa', 'lhbiridouaa@gmail.com', '0634569801', 'douaa1234'),
('AB19845', 'Darij', 'Mariam', 'darijmariam@gmail.com', '0698451019', 'mariam1234'),
('AA37845', 'EN-NASIRY', 'Aya', 'ayaennasiri@gmail.com', '0634810144', 'aya1234'),
('AA29458', 'BENCHAHBI', 'Fatima-ezzahrae', 'fatibenchahbi@gmail.com', '0601456283', 'fati1234');



--
-- Index pour les tables déchargées
--

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`CIN`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
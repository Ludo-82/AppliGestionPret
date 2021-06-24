-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 22 juin 2021 à 12:58
-- Version du serveur :  5.7.31
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `pret`
--

-- --------------------------------------------------------

--
-- Structure de la table `boncommande`
--

DROP TABLE IF EXISTS `boncommande`;
CREATE TABLE IF NOT EXISTS `boncommande` (
  `NumCommande` varchar(30) NOT NULL,
  `ApercuCommande` json NOT NULL,
  PRIMARY KEY (`NumCommande`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `calendrier`
--

DROP TABLE IF EXISTS `calendrier`;
CREATE TABLE IF NOT EXISTS `calendrier` (
  `DateDispo` date NOT NULL COMMENT 'Format : JJ/MM/AAAA',
  PRIMARY KEY (`DateDispo`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `calendrier`
--

INSERT INTO `calendrier` (`DateDispo`) VALUES
('2021-06-17'),
('2021-06-22'),
('2021-06-23'),
('2021-06-24'),
('2021-06-25'),
('2021-06-26'),
('2021-07-01');

-- --------------------------------------------------------

--
-- Structure de la table `categorie_mat`
--

DROP TABLE IF EXISTS `categorie_mat`;
CREATE TABLE IF NOT EXISTS `categorie_mat` (
  `CodeCateMat` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `Type` varchar(30) NOT NULL COMMENT 'PC, Tablette ou Clé 3G',
  `Marque` varchar(30) DEFAULT NULL,
  `Modele` varchar(50) NOT NULL,
  `Stockage` varchar(30) DEFAULT NULL,
  `RAM` varchar(30) DEFAULT NULL,
  `CarteGraphique` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`CodeCateMat`),
  UNIQUE KEY `CodeCateMat` (`CodeCateMat`),
  UNIQUE KEY `CodeCateMat_2` (`CodeCateMat`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `categorie_mat`
--

INSERT INTO `categorie_mat` (`CodeCateMat`, `Type`, `Marque`, `Modele`, `Stockage`, `RAM`, `CarteGraphique`) VALUES
(1, 'Ordinateur', 'HP', 'EliteBook', '500 GB', '4GO', 'NVIDIA'),
(2, 'Tablette', 'Apple', 'Ipad Air 2', '128', '', ''),
(3, 'Ordinateur', 'MSI', 'GT70', '750Go', '8GB', 'NVIDIA'),
(4, 'Ordinateur', 'ASUS', 'CB10', '32Go', '4GB', NULL),
(5, 'Tablette', 'Samsung', 'A7', NULL, NULL, NULL),
(6, 'Caméra', 'Logitech', 'C505e', NULL, NULL, NULL),
(7, 'Caméra', 'Logitech', 'C920', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `concerner`
--

DROP TABLE IF EXISTS `concerner`;
CREATE TABLE IF NOT EXISTS `concerner` (
  `CodeCateMat` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `NumCommande` varchar(30) NOT NULL,
  UNIQUE KEY `CodeCateMat` (`CodeCateMat`),
  KEY `NumCommande` (`NumCommande`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `contrat`
--

DROP TABLE IF EXISTS `contrat`;
CREATE TABLE IF NOT EXISTS `contrat` (
  `NumCont` int(11) NOT NULL,
  `ApercuCont` json DEFAULT NULL,
  `EmailGes` varchar(50) NOT NULL,
  PRIMARY KEY (`NumCont`),
  UNIQUE KEY `NumCont` (`NumCont`),
  KEY `EmailGes` (`EmailGes`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `contrat`
--

INSERT INTO `contrat` (`NumCont`, `ApercuCont`, `EmailGes`) VALUES
(1, 'null', 'marcel.pagnol@ut-capitole.com'),
(2, 'null', 'marcel.pagnol@ut-capitole.com'),
(3, NULL, 'marcel.pagnol@ut-capitole.com'),
(4, 'null', 'marcel.pagnol@ut-capitole.com'),
(5, 'null', 'marcel.pagnol@ut-capitole.com');

-- --------------------------------------------------------

--
-- Structure de la table `demander`
--

DROP TABLE IF EXISTS `demander`;
CREATE TABLE IF NOT EXISTS `demander` (
  `DateDemande` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Format : JJ/MM/AAAA, 00h00',
  `DateEmpSouhaite` date DEFAULT NULL COMMENT 'Format : JJ/MM/AAAA, 00h00',
  `Etat` tinyint(1) NOT NULL,
  `CodeCateMat` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `EmailEmp` varchar(50) NOT NULL,
  PRIMARY KEY (`DateDemande`,`CodeCateMat`,`EmailEmp`),
  KEY `EmailEmp` (`EmailEmp`),
  KEY `CodeCateMat` (`CodeCateMat`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `demander`
--

INSERT INTO `demander` (`DateDemande`, `DateEmpSouhaite`, `Etat`, `CodeCateMat`, `EmailEmp`) VALUES
('2021-06-13 13:05:54', '2021-06-23', 0, 7, 'bilal.bil@gmail.com'),
('2021-06-20 13:07:43', '2021-06-22', 0, 4, 'flo.flo@gmail.com'),
('2021-06-21 13:05:54', '2021-06-22', 0, 5, 'robin.rob@gmail.com'),
('2021-06-22 14:39:59', '2021-07-22', 1, 4, 'ludo.clave@gmail.com'),
('2021-06-22 14:48:41', '2021-07-22', 1, 4, 'ludo.clave@gmail.com');

-- --------------------------------------------------------

--
-- Structure de la table `emprunter`
--

DROP TABLE IF EXISTS `emprunter`;
CREATE TABLE IF NOT EXISTS `emprunter` (
  `DateRetrait` date NOT NULL COMMENT 'Format : JJ/MM/AAAA',
  `DateRetourSouhaite` date DEFAULT NULL COMMENT 'Format : JJ/MM/AAAA, 00h00',
  `DateRetourReel` date DEFAULT NULL COMMENT 'Format : JJ/MM/AAAA',
  `NumMat` varchar(30) NOT NULL,
  `EmailEmp` varchar(50) NOT NULL,
  `NumCont` int(11) NOT NULL,
  PRIMARY KEY (`NumMat`,`EmailEmp`,`NumCont`),
  UNIQUE KEY `NumCont` (`NumCont`) USING BTREE,
  KEY `NumMat` (`NumMat`),
  KEY `EmailEmp` (`EmailEmp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `emprunter`
--

INSERT INTO `emprunter` (`DateRetrait`, `DateRetourSouhaite`, `DateRetourReel`, `NumMat`, `EmailEmp`, `NumCont`) VALUES
('2021-06-22', '2021-06-22', '2021-06-22', 'O2001', 'ludo.clave@gmail.com', 4),
('2021-06-22', NULL, '2021-06-22', 'O2004', 'ludo.clave@gmail.com', 5),
('2021-03-02', '2021-06-22', '2021-06-22', 'O515', 'ning.n@gmail.com', 3),
('2021-04-01', '2021-06-22', NULL, 'T1027', 'geoffrey.philip@gmail.com', 2),
('2021-06-01', '2021-06-22', NULL, 'T1028', 'axel.michel@gmail.com', 1);

-- --------------------------------------------------------

--
-- Structure de la table `emprunteur`
--

DROP TABLE IF EXISTS `emprunteur`;
CREATE TABLE IF NOT EXISTS `emprunteur` (
  `EmailEmp` varchar(50) NOT NULL,
  `NomEmp` varchar(30) NOT NULL,
  `PrenomEmp` varchar(30) NOT NULL,
  `MdpEmp` varchar(30) NOT NULL COMMENT 'Utilisez au moins huit caractères avec des lettres et des chiffres',
  `CategorieEmp` varchar(30) NOT NULL COMMENT 'Etudiant, Personnel ou autre',
  `CodeForma` varchar(30) NOT NULL,
  PRIMARY KEY (`EmailEmp`),
  UNIQUE KEY `EmailEmp` (`EmailEmp`),
  KEY `CodeForma` (`CodeForma`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `emprunteur`
--

INSERT INTO `emprunteur` (`EmailEmp`, `NomEmp`, `PrenomEmp`, `MdpEmp`, `CategorieEmp`, `CodeForma`) VALUES
('andre.michaux@ut-capitole.com', 'MICHAUX', 'André', '1@ndréMichaux', 'etudiant', 'L3TI'),
('axel.michel@gmail.com', 'Michel', 'Axel', 'Axeldu31', 'Etudiant', 'L3TI'),
('bilal.bil@gmail.com', 'Benzema', 'Bilal', '$foot31', 'Etudiant', 'M1 2IS'),
('flo.flo@gmail.com', 'Delahouse', 'Florian', '$Florian31', 'Etudiant', 'M2 2IS'),
('geoffrey.philip@gmail.com', 'Philibon', 'Geoffrey', '$Geoffrey31', 'Etudiant', 'LEM'),
('julien.aligon@gmail.com', 'Aligon', 'Julien', '$Julien31', 'Professeur', 'Aut'),
('ludo.clave@gmail.com', 'Clavé', 'Ludovic', '', 'Etudiant', 'L3TI'),
('melissa.dumoulin@gmail.com', 'Dumoulin', 'Mélissa', '$Melissa', 'Etudiant', 'M1 2IS'),
('nathalie.valles-parlangeau@ut-capitole.fr', 'VALLES', 'Nathalie', '', 'Autres', 'Aut'),
('ning.n@gmail.com', 'He', 'Ning', '$Ning', 'Etudiant', 'M2 IPM'),
('robin.rob@gmail.com', 'Jean', 'Robin', '$Robin3222', 'Etudiant', 'L3TI'),
('rongqin.rhttp@gmail.com', 'Fu', 'Rongqin', '$Rongqin', 'Etudiant', 'M1 IDA'),
('tymoté.tym@gmail.com', 'Bertrac', 'Tymothé', 'Bertrac31', 'Etudiant', 'L3TI'),
('ygassihoun@gmail.com', 'GASSIHOUN', 'Yao Guershom', '$Kebab31', 'Professeur', 'Aut');

-- --------------------------------------------------------

--
-- Structure de la table `etre_dispo`
--

DROP TABLE IF EXISTS `etre_dispo`;
CREATE TABLE IF NOT EXISTS `etre_dispo` (
  `EmailGes` varchar(50) NOT NULL,
  `DateDispo` date NOT NULL COMMENT 'Format : JJ/MM/AAAA',
  `HeureDispo` varchar(30) NOT NULL,
  PRIMARY KEY (`EmailGes`,`DateDispo`,`HeureDispo`),
  KEY `EmailGes` (`EmailGes`),
  KEY `DateDispo` (`DateDispo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `etre_dispo`
--

INSERT INTO `etre_dispo` (`EmailGes`, `DateDispo`, `HeureDispo`) VALUES
('marcel.pagnol@ut-capitole.com', '2021-06-22', '9H-16H'),
('marcel.pagnol@ut-capitole.com', '2021-06-23', '13H-17H'),
('marcel.pagnol@ut-capitole.com', '2021-06-23', '8H-12H'),
('marcel.pagnol@ut-capitole.com', '2021-06-24', '8H-12H'),
('marcel.pagnol@ut-capitole.com', '2021-06-25', '9H-16H'),
('marcel.pagnol@ut-capitole.com', '2021-06-26', '9H-12H');

-- --------------------------------------------------------

--
-- Structure de la table `formation`
--

DROP TABLE IF EXISTS `formation`;
CREATE TABLE IF NOT EXISTS `formation` (
  `CodeForma` varchar(30) NOT NULL,
  `LibelleForma` varchar(50) NOT NULL,
  PRIMARY KEY (`CodeForma`),
  UNIQUE KEY `CodeForma` (`CodeForma`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `formation`
--

INSERT INTO `formation` (`CodeForma`, `LibelleForma`) VALUES
('Aut', 'AUTRE'),
('L3TI', 'MIASHS L3 TI'),
('LEM', 'Licence Economie et MIASHS'),
('LP RTAI', 'Licence Pro RTAI'),
('M1 2IS', 'Master 1 2IS'),
('M1 IDA', 'Master 1 IDA'),
('M1 IM', 'Master 1 Ingenierie Métier'),
('M2 2IS', 'Master2 2IS'),
('M2 IDA', 'Master2 IDA'),
('M2 IPM', 'Master2 IPM'),
('M2 ISIAD', 'Master2 ISIAD');

-- --------------------------------------------------------

--
-- Structure de la table `gestionnaire`
--

DROP TABLE IF EXISTS `gestionnaire`;
CREATE TABLE IF NOT EXISTS `gestionnaire` (
  `EmailGes` varchar(50) NOT NULL,
  `NomGes` varchar(30) NOT NULL,
  `PrenomGes` varchar(30) NOT NULL,
  `TelGes` varchar(10) NOT NULL COMMENT 'Format : 00.00.00.00.00',
  PRIMARY KEY (`EmailGes`),
  UNIQUE KEY `EmailGes` (`EmailGes`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `gestionnaire`
--

INSERT INTO `gestionnaire` (`EmailGes`, `NomGes`, `PrenomGes`, `TelGes`) VALUES
('marcel.pagnol@ut-capitole.com', 'PAGNOL', 'Marcel', '0324345465');

-- --------------------------------------------------------

--
-- Structure de la table `maintenir`
--

DROP TABLE IF EXISTS `maintenir`;
CREATE TABLE IF NOT EXISTS `maintenir` (
  `EmailVac` varchar(50) NOT NULL,
  `NumMat` varchar(30) NOT NULL,
  `DateIntervention` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date du jour',
  `DateSAV` date DEFAULT NULL COMMENT 'Format : JJ/MM/AAAA',
  `DateRecup` date DEFAULT NULL COMMENT 'Format : JJ/MM/AAAA',
  `Commentaire` varchar(100) DEFAULT NULL,
  `EtatMaintenir` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`DateIntervention`),
  KEY `NumMat` (`NumMat`),
  KEY `EmailVac` (`EmailVac`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `maintenir`
--

INSERT INTO `maintenir` (`EmailVac`, `NumMat`, `DateIntervention`, `DateSAV`, `DateRecup`, `Commentaire`, `EtatMaintenir`) VALUES
('franck.ravat@gmail.com', 'O2001', '2021-06-22 14:43:52', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `materiel`
--

DROP TABLE IF EXISTS `materiel`;
CREATE TABLE IF NOT EXISTS `materiel` (
  `NumMat` varchar(30) NOT NULL,
  `DateReception` date NOT NULL COMMENT 'Format : JJ/MM/AAAA',
  `EtatMat` varchar(30) NOT NULL COMMENT 'Retiré du parc, SAV, disponible, retourné, emprunté, récupéré',
  `CodeCateMat` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`NumMat`),
  UNIQUE KEY `NumMat` (`NumMat`),
  KEY `CodeCateMat` (`CodeCateMat`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `materiel`
--

INSERT INTO `materiel` (`NumMat`, `DateReception`, `EtatMat`, `CodeCateMat`) VALUES
('C0001', '2021-06-22', 'Disponible', 7),
('C0002', '2021-06-22', 'Disponible', 7),
('C0003', '2021-06-22', 'Disponible', 7),
('C0004', '2021-06-22', 'Disponible', 7),
('C0005', '2021-06-22', 'Disponible', 7),
('C1001', '2021-06-22', 'Disponible', 6),
('C1002', '2021-06-22', 'Disponible', 6),
('C1003', '2021-06-22', 'Disponible', 6),
('C1004', '2021-06-22', 'Disponible', 6),
('C1005', '2021-06-22', 'Disponible', 6),
('C1006', '2021-06-22', 'Disponible', 6),
('O00001', '2021-06-01', 'Disponible', 1),
('O2001', '2021-06-22', 'Disponible', 4),
('O2002', '2021-06-22', 'Disponible', 4),
('O2003', '2021-06-22', 'Disponible', 4),
('O2004', '2021-06-22', 'Récupéré', 4),
('O2005', '2021-06-22', 'Disponible', 4),
('O501', '2021-06-21', 'Disponible', 3),
('O503', '2021-06-21', 'Disponible', 3),
('O515', '2021-06-21', 'Retourné', 3),
('O530', '2021-06-21', 'Disponible', 3),
('O552', '2021-06-21', 'Disponible', 3),
('O574', '2021-06-21', 'Disponible', 3),
('T00001', '2021-06-02', 'Disponible', 2),
('T1002', '2021-06-22', 'Disponible', 5),
('T1003', '2021-06-22', 'Disponible', 5),
('T1025', '2021-06-22', 'Disponible', 5),
('T1026', '2021-06-22', 'Disponible', 5),
('T1027', '2021-06-22', 'Emprunté', 5),
('T1028', '2021-06-22', 'Emprunté', 5);

-- --------------------------------------------------------

--
-- Structure de la table `vacataire`
--

DROP TABLE IF EXISTS `vacataire`;
CREATE TABLE IF NOT EXISTS `vacataire` (
  `EmailVac` varchar(50) NOT NULL,
  `NomVac` varchar(30) NOT NULL,
  `PrenomVac` varchar(30) NOT NULL,
  `TelVac` varchar(10) NOT NULL COMMENT 'Format : 00.00.00.00.00',
  PRIMARY KEY (`EmailVac`),
  UNIQUE KEY `EmailVac` (`EmailVac`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `vacataire`
--

INSERT INTO `vacataire` (`EmailVac`, `NomVac`, `PrenomVac`, `TelVac`) VALUES
('franck.ravat@gmail.com', 'Ravat', 'Franck', '0605040318'),
('ludo.ludovic@gmail.com', 'Clavé', 'Ludovic', '0102030608'),
('omar.hakim@gmail.com', 'Hakim', 'Omar', '0102030506');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `concerner`
--
ALTER TABLE `concerner`
  ADD CONSTRAINT `concerner_ibfk_1` FOREIGN KEY (`CodeCateMat`) REFERENCES `categorie_mat` (`CodeCateMat`),
  ADD CONSTRAINT `concerner_ibfk_2` FOREIGN KEY (`NumCommande`) REFERENCES `boncommande` (`NumCommande`);

--
-- Contraintes pour la table `contrat`
--
ALTER TABLE `contrat`
  ADD CONSTRAINT `contrat_ibfk_1` FOREIGN KEY (`EmailGes`) REFERENCES `gestionnaire` (`EmailGes`);

--
-- Contraintes pour la table `demander`
--
ALTER TABLE `demander`
  ADD CONSTRAINT `demander_ibfk_1` FOREIGN KEY (`CodeCateMat`) REFERENCES `categorie_mat` (`CodeCateMat`),
  ADD CONSTRAINT `demander_ibfk_2` FOREIGN KEY (`EmailEmp`) REFERENCES `emprunteur` (`EmailEmp`);

--
-- Contraintes pour la table `emprunter`
--
ALTER TABLE `emprunter`
  ADD CONSTRAINT `emprunter_ibfk_2` FOREIGN KEY (`NumMat`) REFERENCES `materiel` (`NumMat`),
  ADD CONSTRAINT `emprunter_ibfk_3` FOREIGN KEY (`NumCont`) REFERENCES `contrat` (`NumCont`),
  ADD CONSTRAINT `emprunter_ibfk_4` FOREIGN KEY (`EmailEmp`) REFERENCES `emprunteur` (`EmailEmp`);

--
-- Contraintes pour la table `emprunteur`
--
ALTER TABLE `emprunteur`
  ADD CONSTRAINT `emprunteur_ibfk_1` FOREIGN KEY (`CodeForma`) REFERENCES `formation` (`CodeForma`);

--
-- Contraintes pour la table `etre_dispo`
--
ALTER TABLE `etre_dispo`
  ADD CONSTRAINT `etre_dispo_ibfk_2` FOREIGN KEY (`EmailGes`) REFERENCES `gestionnaire` (`EmailGes`),
  ADD CONSTRAINT `etre_dispo_ibfk_3` FOREIGN KEY (`DateDispo`) REFERENCES `calendrier` (`DateDispo`);

--
-- Contraintes pour la table `maintenir`
--
ALTER TABLE `maintenir`
  ADD CONSTRAINT `maintenir_ibfk_1` FOREIGN KEY (`NumMat`) REFERENCES `materiel` (`NumMat`),
  ADD CONSTRAINT `maintenir_ibfk_2` FOREIGN KEY (`EmailVac`) REFERENCES `vacataire` (`EmailVac`);

--
-- Contraintes pour la table `materiel`
--
ALTER TABLE `materiel`
  ADD CONSTRAINT `materiel_ibfk_1` FOREIGN KEY (`CodeCateMat`) REFERENCES `categorie_mat` (`CodeCateMat`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

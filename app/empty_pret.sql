-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 24 juin 2021 à 13:14
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
-- Base de données : `empty_pret`
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

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

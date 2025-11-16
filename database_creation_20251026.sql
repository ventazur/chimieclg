-- Create syntax for TABLE 'activity_log'
CREATE TABLE `activity_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(250) DEFAULT NULL,
  `adresse_ip` varchar(15) DEFAULT NULL,
  `user_agent` varchar(250) DEFAULT NULL,
  `date` varchar(19) DEFAULT NULL,
  `epoch` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=462848 DEFAULT CHARSET=utf8mb3;

-- Create syntax for TABLE 'cours'
CREATE TABLE `cours` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `ordre` float NOT NULL DEFAULT '100',
  `nom_court` varchar(30) NOT NULL DEFAULT '',
  `nom_complet` varchar(100) DEFAULT NULL,
  `code` varchar(10) NOT NULL DEFAULT '',
  `code_court` varchar(3) NOT NULL DEFAULT '',
  `ponderation` varchar(5) NOT NULL DEFAULT '',
  `page_disponible` tinyint(1) NOT NULL DEFAULT '0',
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `prealables` varchar(20) DEFAULT NULL,
  `programme` varchar(5) DEFAULT NULL,
  `description` mediumtext,
  `document` varchar(200) DEFAULT NULL,
  `document_apercu` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb3;

-- Create syntax for TABLE 'documents'
CREATE TABLE `documents` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nom_du_fichier` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(200) NOT NULL DEFAULT '',
  `type` varchar(6) DEFAULT '',
  `cat_code` varchar(5) DEFAULT '',
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `secure` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb3;

-- Create syntax for TABLE 'documents_categories'
CREATE TABLE `documents_categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `code` varchar(5) NOT NULL DEFAULT '',
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `ordre` tinyint NOT NULL DEFAULT '50',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3;

-- Create syntax for TABLE 'personnes'
CREATE TABLE `personnes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `prenom` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `bureau` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `poste` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `casier` varchar(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `sexe` varchar(2) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `enseignant` tinyint(1) NOT NULL DEFAULT '0',
  `technicien` tinyint(1) NOT NULL DEFAULT '0',
  `coordination_d` tinyint(1) NOT NULL DEFAULT '0',
  `coordination_p` tinyint(1) NOT NULL DEFAULT '0',
  `webmestre` tinyint(1) NOT NULL DEFAULT '0',
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `courriel` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=46 DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'ressources'
CREATE TABLE `ressources` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(500) NOT NULL DEFAULT '',
  `code_cours` varchar(3) NOT NULL DEFAULT '',
  `category` varchar(10) DEFAULT '',
  `ordre` float NOT NULL DEFAULT '100',
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3;

-- Create syntax for TABLE 'ressources_categories'
CREATE TABLE `ressources_categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `code` varchar(10) NOT NULL DEFAULT '',
  `generale` tinyint(1) NOT NULL DEFAULT '0',
  `ordre` float NOT NULL DEFAULT '100',
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3;

-- Create syntax for TABLE 'activity_log'
CREATE TABLE `activity_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(250) DEFAULT NULL,
  `adresse_ip` varchar(15) DEFAULT NULL,
  `user_agent` varchar(250) DEFAULT NULL,
  `date` varchar(19) DEFAULT NULL,
  `epoch` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb3;

-- Create syntax for TABLE 'cours'
CREATE TABLE `cours` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `ordre` float NOT NULL DEFAULT '100',
  `programme_id` int unsigned DEFAULT NULL,
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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb3;

-- Create syntax for TABLE 'personnes'
CREATE TABLE `personnes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `prenom` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `bureau` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `enseignant` tinyint(1) NOT NULL DEFAULT '0',
  `technicien` tinyint(1) NOT NULL DEFAULT '0',
  `coordination_d` tinyint(1) NOT NULL DEFAULT '0',
  `coordination_p` tinyint(1) NOT NULL DEFAULT '0',
  `webmestre` tinyint(1) NOT NULL DEFAULT '0',
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `courriel` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'programmes'
CREATE TABLE `programmes` (
  `programme_id` int unsigned NOT NULL AUTO_INCREMENT,
  `ordre` smallint unsigned DEFAULT NULL,
  `code_programme` varchar(15) NOT NULL,
  `nom` varchar(150) NOT NULL,
  `abreviation` varchar(5) DEFAULT NULL,
  `actif` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`programme_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- Create syntax for TABLE 'r'
CREATE TABLE `r` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `nom` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `url` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `fichier` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `code_cat` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'OTH',
  `code_scat` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `dependance_id` int unsigned DEFAULT NULL,
  `icone` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `ordre` float NOT NULL DEFAULT '100',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb3;

-- Create syntax for TABLE 'r_cats'
CREATE TABLE `r_cats` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `code_cat` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `ordre` float NOT NULL DEFAULT '100',
  `generale` tinyint(1) NOT NULL DEFAULT '1',
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb3;

-- Create syntax for TABLE 'r_scats'
CREATE TABLE `r_scats` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `code_cat` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `code_scat` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `ordre` float NOT NULL DEFAULT '100',
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb3;

-- Create syntax for TABLE 'securite_connexion_ips'
CREATE TABLE `securite_connexion_ips` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `adresse_ip` varchar(19) NOT NULL,
  `adresse_ip_bin` varbinary(16) NOT NULL,
  `date` varchar(19) NOT NULL,
  `epoch` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_ip_bin` (`adresse_ip_bin`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

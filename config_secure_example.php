<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ==================================================================
//
// config_secure.php
//
// ------------------------------------------------------------------
//
// Ce fichier contient les informations sensibles.
// Il doit etre place a l'exterieur de la racine du site.
// On le charge a partir de config/config_site.php.
//
// ==================================================================

// ------------------------------------------------------------------
//
// Encryption
//
// Un clef aleatoire de 32 caracteres, lettres et chiffres,
// hexadecimaux.
//
// $ head /dev/urandom | tr -dc 'a-f0-9' | head -c 32
//
// ------------------------------------------------------------------

$key = 'abcdef0123456789abdcdef012345678';

// ------------------------------------------------------------------
//
// Encryption Library
//
// ------------------------------------------------------------------

$config['encryption_settings'] = array(
    'driver' => 'mcrypt',
    'cipher' => 'aes-128',
    'mode'   => 'ctr',
    'key'    => hex2bin($key)
);

// ------------------------------------------------------------------
//
// Databases
//
// ------------------------------------------------------------------

$config['database_parametres'] = array(
    'active_group' => 'default',
    'default'	   => ['dbname' => 'nom', 'username' => 'nom', 'password' => 'motdepasse'],
    'dev'		   => ['dbname' => 'nom', 'username' => 'nom', 'password' => 'motdepasse']
);

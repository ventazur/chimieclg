<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 *
 * Configuration du site
 *
 * ---------------------------------------------------------------------------- */

//
// Informations sensibles pour l'operation du site
//

require(APPPATH . '../../config_secure.php');

/* ----------------------------------------------------------------------------
 *
 * Icones pour les fichiers
 *
 * ---------------------------------------------------------------------------- */
$config['icones_types'] = [
	'doc'	=> 'bi-filetype-doc',
	'docx'  => 'bi-filetype-docx',
	'gif'	=> 'bi-filetype-gif',
	'jpeg' 	=> 'bi-filetype-jpg',
	'jpg' 	=> 'bi-filetype-jpg',
	'mov'	=> 'bi-filetype-mov',
	'mp3'	=> 'bi-filetype-mp3',
	'mp4' 	=> 'bi-filetype-mp4',
	'otf'	=> 'bi-filetype-otf',
	'pdf' 	=> 'bi-filetype-pdf',
	'png'   => 'bi-filetype-png',
	'ppsx'	=> 'bi-file-earmark-pptx',
	'ppt'	=> 'bi-file-earmark-ppt',
	'pptx'	=> 'bi-file-earmark-pptx'
];

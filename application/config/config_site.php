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
 * Securite
 *
 * ---------------------------------------------------------------------------- */

$config['ips_exclude_turnstile'] = array(
	'204.19.10.193'
);

//
// Motif d'identification des robots dans le user_agent, utilise pour
// les exclure des statistiques de /admin/activite
//

$config['robots_user_agents'] = 'bot|crawl|spider|slurp|scan|curl|wget|python|java|go-http|http-client|headless|preview|fetch|monitor|semrush|ahrefs|facebookexternalhit|archive|feed|scrap|probe|nmap|masscan';

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

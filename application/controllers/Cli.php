<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 *
 * CLI
 *
 * ----------------------------------------------------------------------------
 *
 * /usr/bin/php index.php cli fetch_ai_crawler_ips
 *
 * ---------------------------------------------------------------------------- */

class Cli extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		if ( ! is_cli())
		{
			die('Doit etre execute en CLI');
		}

		$this->load->driver('cache', array('adapter' => 'file'));
	}

    /* --------------------------------------------------------------------------------------------
     *
     * Rafraichir le cache des plages d'IP des robots IA (OpenAI, Anthropic/Claude)
     *
     * Voir Usager_model::fetch_ai_crawler_ips() pour le detail. Prevu pour tourner
     * quotidiennement via cron, apres la mise a jour de la source partagee.
     *
     * -------------------------------------------------------------------------------------------- */
	public function fetch_ai_crawler_ips()
	{
		$summary = $this->Usager_model->fetch_ai_crawler_ips();

		foreach ($summary as $cache_key => $count)
		{
			echo "{$cache_key} : {$count} plage(s)\n";
		}
	}

} // class

<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 *
 * USAGER MODEL
 *
 * ---------------------------------------------------------------------------- */

class Usager_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

	/* ------------------------------------------------------------------------
	 *
     * Log de l'activite du site 
	 *
     *------------------------------------------------------------------------- */
    function log_activity()
    {
		if (in_array($_SERVER['REMOTE_ADDR'], $this->config->item('ne_pas_logger_ips')))
            return TRUE;

        $this->load->library('user_agent');

        $data = array(
            'url'        => str_replace($this->config->item('base_url'), '', current_url()),
            'adresse_ip' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $this->agent->agent_string(),
            'date'       => date_humanize(date('U'), TRUE),
            'epoch'      => date('U')
        ); 

        $this->db->insert('activity_log', $data);
        return;
    }

} // class

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

    /* --------------------------------------------------------------------------------------------
     *
     * Verifier si l'adresse IP est autorisee
     *
     * -------------------------------------------------------------------------------------------- */
	function verifier_ip_autorise()
	{
		$ip_client = $this->input->ip_address();

		if (empty($ip_client)) 
		{
			return FALSE;
		}

		$ip_safe = $this->db->escape($ip_client);

		$this->db->select('1');
		$this->db->from('securite_connexion_ips');
		$this->db->where("adresse_ip_bin = INET6_ATON($ip_safe)", NULL, FALSE);

		$this->db->limit(1);

		return ($this->db->get()->num_rows() > 0);
	}

    /* --------------------------------------------------------------------------------------------
     *
     * Ajout d'une adresse IP autorisee
     *
     * -------------------------------------------------------------------------------------------- */
	public function ajouter_ip_autorise()
	{
		$ip_client = $this->input->ip_address();

		$data = array(
			'adresse_ip'     => $ip_client,
			'date'           => date_humanize(date('U'), TRUE),
			'epoch'          => date('U')
		);

		$ip_safe = $this->db->escape($ip_client);

		$this->db->set('adresse_ip_bin', "INET6_ATON($ip_safe)", FALSE);

		return $this->db->insert('securite_connexion_ips', $data);
	}

} // class

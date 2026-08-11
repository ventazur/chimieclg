<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 *
 * ADMIN MODEL
 *
 * ---------------------------------------------------------------------------- */

class Admin_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

	/* ------------------------------------------------------------------------
	 *
     * Verifier le mot de passe d'administration
     *
     * Comparaison a temps constant. Tant que 'admin_password' est absent ou
     * vide dans config_secure.php, aucune connexion n'est possible.
	 *
     *------------------------------------------------------------------------- */
    function verifier_mot_de_passe($saisie)
    {
        $mot_de_passe = $this->config->item('admin_password');

        if (empty($mot_de_passe) || ! is_string($saisie) || $saisie === '')
            return FALSE;

        return hash_equals($mot_de_passe, $saisie);
    }

	/* ------------------------------------------------------------------------
	 *
     * Ajouter le filtre d'exclusion des robots (par user_agent) a la requete
     * courante du query builder
	 *
     *------------------------------------------------------------------------- */
    private function _exclure_robots()
    {
        $motif = $this->config->item('robots_user_agents');

        if ( ! empty($motif))
        {
            $motif_safe = $this->db->escape($motif);

            $this->db->where("IFNULL(user_agent, '') NOT REGEXP $motif_safe", NULL, FALSE);
        }
    }

	/* ------------------------------------------------------------------------
	 *
     * Sommaire de l'activite pour les N derniers jours (0 ou moins = tout
     * l'historique)
	 *
     *------------------------------------------------------------------------- */
    function sommaire($jours = 90)
    {
        // Pages vues + visiteurs distincts, robots exclus

        $this->db->select("COUNT(*) AS pages_vues, COUNT(DISTINCT CONCAT(adresse_ip, '|', IFNULL(user_agent, ''))) AS visiteurs", FALSE);
        $this->db->from('activity_log');

        if ($jours > 0)
            $this->db->where('epoch >=', time() - ($jours * 86400));

        $this->_exclure_robots();

        $totaux = $this->db->get()->row_array();

        // Lignes ecartees comme robots, pour verification

        $this->db->select('COUNT(*) AS n', FALSE);
        $this->db->from('activity_log');

        if ($jours > 0)
            $this->db->where('epoch >=', time() - ($jours * 86400));

        $motif = $this->config->item('robots_user_agents');

        if ( ! empty($motif))
        {
            $motif_safe = $this->db->escape($motif);
            $this->db->where("IFNULL(user_agent, '') REGEXP $motif_safe", NULL, FALSE);
        }
        else
        {
            $this->db->where('1 = 0', NULL, FALSE);
        }

        $robots = $this->db->get()->row_array();

        return array(
            'pages_vues' => (int) ($totaux['pages_vues'] ?? 0),
            'visiteurs'  => (int) ($totaux['visiteurs'] ?? 0),
            'robots'     => (int) ($robots['n'] ?? 0)
        );
    }

	/* ------------------------------------------------------------------------
	 *
     * Visites par jour (visiteurs distincts + pages vues), robots exclus
     * (0 ou moins = tout l'historique)
	 *
     *------------------------------------------------------------------------- */
    function visites_par_jour($jours = 90)
    {
        $this->db->select("LEFT(date, 10) AS jour, COUNT(*) AS pages_vues, COUNT(DISTINCT CONCAT(adresse_ip, '|', IFNULL(user_agent, ''))) AS visiteurs", FALSE);
        $this->db->from('activity_log');

        if ($jours > 0)
            $this->db->where('epoch >=', time() - ($jours * 86400));

        $this->_exclure_robots();
        $this->db->group_by('jour');
        $this->db->order_by('jour', 'asc');

        $query = $this->db->get();

        if ( ! $query->num_rows() > 0)
             return array();

        return $query->result_array();
    }

	/* ------------------------------------------------------------------------
	 *
     * Pages les plus populaires (par nombre de hits), robots exclus
     * (0 ou moins = tout l'historique)
	 *
     *------------------------------------------------------------------------- */
    function pages_populaires($jours = 90, $limite = 50)
    {
        $this->db->select("url, COUNT(*) AS hits, COUNT(DISTINCT CONCAT(adresse_ip, '|', IFNULL(user_agent, ''))) AS visiteurs", FALSE);
        $this->db->from('activity_log');

        if ($jours > 0)
            $this->db->where('epoch >=', time() - ($jours * 86400));

        $this->_exclure_robots();
        $this->db->group_by('url');
        $this->db->order_by('hits', 'desc');
        $this->db->limit($limite);

        $query = $this->db->get();

        if ( ! $query->num_rows() > 0)
             return array();

        return $query->result_array();
    }

} // class

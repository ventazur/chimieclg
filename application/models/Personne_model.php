<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 *
 * Personne Model
 *
 * ---------------------------------------------------------------------------- */

class Personne_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    // ------------------------------------------------------------------------
    //
    // Enseignant(e)s
    //
    // ------------------------------------------------------------------------

	/* ------------------------------------------------------------------------
	 *
	 * Lister les enseignants
	 *
     *------------------------------------------------------------------------- */
	function lister_enseignants()
	{
        $this->db->from     ('personnes');
        $this->db->where    ('enseignant', 1);
        $this->db->where    ('actif', 1);
        $this->db->order_by ('nom', 'asc');
        $this->db->order_by ('prenom', 'asc');
        
        $query = $this->db->get();
        
        if ( ! $query->num_rows() > 0)
             return FALSE;
                                                                                                                                                                                                                                  
        return $query->result_array();
    }

	/* ------------------------------------------------------------------------
	 *
     * Récupérer les infos d'une personne
	 *
     *------------------------------------------------------------------------- */
    function recuperer_info_personne($personne_id)
    {
        $this->db->from ('personnes');
        $this->db->where('id', $personne_id);
        $this->db->where('actif', 1);
        $this->db->limit(1);
        
        $query = $this->db->get();
        
        if ( ! $query->num_rows() > 0)
             return FALSE;
                                                                                                                                                                                                                                  
        return $query->row_array();
    }

	/* ------------------------------------------------------------------------
	 *
     * Nombre d'enseignants actifs
	 *
     *------------------------------------------------------------------------- */
    function nombre_enseignants()
    {
        $nombre_enseignants = count($this->lister_enseignants());

        if ( ! $nombre_enseignants > 0)
        {
            return 'plus de 12';
        }

        return $nombre_enseignants;
    }

    // ------------------------------------------------------------------------
    //
    // Technicien(ne)s
    //
    // ------------------------------------------------------------------------
    
	/* ------------------------------------------------------------------------
	 *
	 * Lister les techniciens.ennes
	 *
     *------------------------------------------------------------------------- */
	function lister_techniciens()
	{
        $this->db->from     ('personnes');
        $this->db->where    ('technicien', 1);
        $this->db->where    ('actif', 1);
        $this->db->order_by ('nom', 'asc');
        $this->db->order_by ('prenom', 'asc');
        
        $query = $this->db->get();
        
        if ( ! $query->num_rows() > 0)
             return FALSE;
                                                                                                                                                                                                                                  
        return $query->result_array();
    }
}

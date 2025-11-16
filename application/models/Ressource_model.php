<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 *
 * Ressource Model
 *
 * ---------------------------------------------------------------------------- */

class Ressource_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

	/* ------------------------------------------------------------------------
	 *
     * Lister toutes les catégories (ou celles spécifiées par leur code).
	 *
     *------------------------------------------------------------------------- */
	function lister_categories($categories_codes = array(), $generale = 0)
    {
        $this->db->from  ('ressources_categories');
        $this->db->where ('generale', $generale);
        $this->db->where ('actif', 1);

        if ( ! empty($categories_codes))
        {
            $this->db->where_in('code', $categories_codes);
        }

        $this->db->order_by('nom', 'asc');

        $query = $this->db->get();
        
        if ( ! $query->num_rows() > 0)
             return FALSE;
                                                                                                                                                                                                                                  
        return $query->result_array();
    }

	/* ------------------------------------------------------------------------
	 *
     * Lister toutes les ressources actives d'un court donné.
	 *
     *------------------------------------------------------------------------- */
    function lister_ressources($code_cours)
    {
        $this->db->from  ('ressources');
        $this->db->where ('code_cours', strtoupper($code_cours));
        $this->db->where ('actif', 1);
        
        $query = $this->db->get();
        
        if ( ! $query->num_rows() > 0)
             return FALSE;
                                                                                                                                                                                                                                  
        return $query->result_array();
    }

	/* ------------------------------------------------------------------------
	 *
     * Lister les ressources d'un cours
	 *
     *------------------------------------------------------------------------- */
    function lister_ressources_cours()
    {
        $this->db->from  ('ressources');
        $this->db->where ('actif', 1);
        
        $query = $this->db->get();
        
        if ( ! $query->num_rows() > 0)
             return array();

        $rc = array();

        foreach($query->result_array() as $r)
        {
            if ( ! empty($r['code_cours']) && ! in_array(strtolower($r['code_cours']), $rc))
            {
                $rc[] = strtolower($r['code_cours']);
            }
        }

        return $rc;
    }

    /* ------------------------------------------------------------------------
     *
     * Lister les ressources generales
     *
     * ------------------------------------------------------------------------ */
    function lister_ressources_generales()
    {
        $this->db->select   ('r.*');

        $this->db->from     ('ressources as r, ressources_categories as rc');
        $this->db->where    ('r.category = rc.code');
        $this->db->where    ('rc.generale', 1);
        $this->db->where    ('r.actif', 1);
        $this->db->order_by ('r.ordre', 'ASC');

        $query = $this->db->get();
        
        if ( ! $query->num_rows() > 0)
            return FALSE;
                                                                                                                                                                                                                                  
        return $query->result_array();
    }

} // class

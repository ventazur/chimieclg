<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 *
 * Cours Model
 *
 * ---------------------------------------------------------------------------- */

class Cours_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

	/* ------------------------------------------------------------------------
	 *
     * Recuperer tous les cours
	 *
     *------------------------------------------------------------------------- */
	function lister_cours()
    {   
        $this->db->from     ('cours');
        $this->db->where    ('actif', 1);
		$this->db->order_by ('ordre', 'asc');

        $query = $this->db->get();
        
        if ( ! $query->num_rows() > 0)
            return FALSE;

        return $query->result_array();
    }
}

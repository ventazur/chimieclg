<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 *
 * Document Model
 *
 * ---------------------------------------------------------------------------- */

class Document_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

	/* ------------------------------------------------------------------------
	 *
	 * Lister les categories
	 *
     *------------------------------------------------------------------------- */
	function lister_categories()
	{
        $this->db->from     ('documents_categories');
        $this->db->where    ('actif', 1);
        $this->db->order_by ('ordre', 'asc');
        $this->db->order_by ('nom', 'asc');
        
        $query = $this->db->get();
        
        if ( ! $query->num_rows() > 0)
            return array();

        $categories = [];

        foreach($query->result_array() as $r)
        {
            $categories[$r['code']] = $r;
        }

        return $categories;
    }

	/* ------------------------------------------------------------------------
	 *
	 * Lister les documents actifs
	 *
     *------------------------------------------------------------------------- */
    function lister_documents()
    {
        $this->db->from  ('documents');
        $this->db->where ('actif', 1);
        $this->db->where ('secure', 0);
        
        $query = $this->db->get();
        
        if ( ! $query->num_rows() > 0)
             return FALSE;
                                                                                                                                                                                                                                  
        return $query->result_array();
    }
}

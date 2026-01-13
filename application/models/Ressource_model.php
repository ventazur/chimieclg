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
     * Extraire toutes les ressources
	 *
     *------------------------------------------------------------------------- */
	function extraire_ressources($opt = array())
	{
		$opt = array_merge(
			[ ],
			$opt
		);

        $this->db->from ('r');
		$this->db->where('actif', 1);
	
        $this->db->order_by('ordre', 'asc');
        $this->db->order_by('nom', 'asc');

        $query = $this->db->get();
        
        if ( ! $query->num_rows() > 0)
             return array();
                                                                                                                                                                                                                                  
        return $query->result_array();
    }

	/* ------------------------------------------------------------------------
	 *
     * Extraire toutes les ressources en ordre
	 *
     *------------------------------------------------------------------------- */
	function extraire_ressources_ordre($req_cat = NULL, $opt = array())
	{
		//
		// extraction des ressources
		//

		$ressources = $this->extraire_ressources();

		if (empty($req_cat))
		{	
			$cats = $this->extraire_cats(['generale' => TRUE, 'cats' => array_column($ressources, 'code_cat')]);
		}
		else
		{
			$req_cat = strtoupper($req_cat);
			$cats = $this->extraire_cats(['cats' => [$req_cat]]);
		}

		//
		// extraction des categories et sous-categoreis
		//

		$cats_liste = array_column($cats, 'code_cat');
		$cats = array_keys_swap($cats, 'code_cat');

		$scats = $this->extraire_scats(['scats' => array_column($cats, 'code_cat')]);
		$scats_liste = array_column($scats, 'code_scat');
		$scats = ['NOSCAT' => ['code_scat' => 'NOSCAT']] + $scats;
		$scats = array_keys_swap($scats, 'code_scat');

		//
		// classification des ressources par categorie et sous-categorie
		//
		// - pour respecter l'ordre defini dans la base de donnees
		// - pour faciliter l'affichage
		//
		
		$ressources_ordre = [];

		foreach($cats as $cat => $c)
		{
			foreach($scats as $scat => $s)
			{
				if ($scat != 'NOSCAT' && $s['code_cat'] != $cat)
					continue;

				foreach($ressources as $r)
				{
					if ($r['code_scat'] == NULL) $r['code_scat'] = 'NOSCAT';

					if ($r['code_cat'] != $cat) continue;
					if ($r['code_scat'] != $scat) continue;

					if ( ! array_key_exists($cat, $ressources_ordre))
						$ressources_ordre[$cat] = [];

					if ( ! array_key_exists($scat, $ressources_ordre[$cat]))
						$ressources_ordre[$cat][$scat] = [];

					$ressources_ordre[$cat][$scat][] = $r;
				}
			}
		}

		//
		// preparation de la vue
		//

		return [
			'req_cat' 	 => $req_cat,
			'cats' 	  	 => $cats,
			'scats'      => $scats,
			'ressources' => $ressources_ordre
		];
	}

	/* ------------------------------------------------------------------------
	 *
     * Extraire toutes les catégories
	 *
     *------------------------------------------------------------------------- */
	function extraire_cats($opt = array())
	{
		$opt = array_merge(
			[
				'generale' => FALSE, // TRUE = les ressources qui vont sur la page des ressources, FALSE = toutes les ressources
				'cats'	   => []	// tableau des categories a extraire
			],
			$opt
		);

        $this->db->from  ('r_cats');
        $this->db->where ('actif', 1);

		if ($opt['generale'])
		{
			$this->db->where('generale', $opt['generale']);
		}

		if ( ! empty($opt['cats']))
		{
			$this->db->where_in('code_cat', $opt['cats']);
		}

        $this->db->order_by('ordre', 'asc');
        $this->db->order_by('nom', 'asc');

        $query = $this->db->get();
        
        if ( ! $query->num_rows() > 0)
             return array();
                                                                                                                                                                                                                                  
        return $query->result_array();
	}

	/* ------------------------------------------------------------------------
	 *
     * Extraire toutes les sous-catégories
	 *
     *------------------------------------------------------------------------- */
	function extraire_scats($opt = array())
	{
		$opt = array_merge(
			[
				'scats'	   => [] // tableau des categories a extraire
			],
			$opt
		);

        $this->db->from  ('r_scats');
        $this->db->where ('actif', 1);

		if ( ! empty($opt['cats']))
		{
			$this->db->where_in('code_scat', $opt['scats']);
		}

        $this->db->order_by('ordre', 'asc');
        $this->db->order_by('nom', 'asc');

        $query = $this->db->get();
        
        if ( ! $query->num_rows() > 0)
             return array();
                                                                                                                                                                                                                                  
        return $query->result_array();
    }


	/*
	 *
	 *
	 * --- ANCIENNES CATEGORIES (DOCUMENTS) -----------------------------------
	 *
	 *
	 */


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

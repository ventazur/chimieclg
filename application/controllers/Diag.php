<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 *
 * Diag (Diagramme)
 * 
 * ---------------------------------------------------------------------------- */

class Diag extends MY_Controller 
{
	public function __construct()
    {
        parent::__construct();

		$this->data['current_controller'] = strtolower(__CLASS__);
	}

    /* ------------------------------------------------------------------------
     *
     * Index
     *
     * ------------------------------------------------------------------------ */
	public function index()
	{
		$this->_affichage();
	}

    /* ------------------------------------------------------------------------
     *
     * Affichage
     *
     * ------------------------------------------------------------------------ */
	public function _affichage()
    {
        $this->load->view('diagramme');
	}
}

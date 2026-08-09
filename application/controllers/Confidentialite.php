<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 *
 * Politique de confidentialité
 *
 * ---------------------------------------------------------------------------- */

class Confidentialite extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->view('commons/header_view', $this->data);
		$this->load->view('commons/nav_view', $this->data);
		$this->load->view('confidentialite_view', $this->data);
		$this->load->view('commons/footer_view');
	}
}

/* End of file Confidentialite.php */
/* Location: ./application/controllers/Confidentialite.php */

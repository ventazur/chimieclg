<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 *
 * Enseignants
 * 
 * ---------------------------------------------------------------------------- */

Class Enseignants extends MY_Controller 
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Personne_model');
    } 

	public function index()
	{
        $this->data['enseignants'] = $this->Personne_model->lister_enseignants();
        $this->data['techniciens'] = $this->Personne_model->lister_techniciens();

        $this->_display_view();
	}

    public function _display_view()
    {
        $this->load->view('commons/header_view', $this->data);
        $this->load->view('commons/nav_view', $this->data);
        $this->load->view('enseignants/enseignants_view', $this->data);
        $this->load->view('commons/footer_view');
    }
}

/* End of file enseignants.php */
/* Location: ./application/controllers/enseignants.php */

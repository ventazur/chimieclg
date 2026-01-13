<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 *
 * Ressources
 * 
 * ---------------------------------------------------------------------------- */

Class Ressources extends MY_Controller 
{
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('Ressource_model');
    } 

	public function _remap()
	{
		//
		// requete d'une categorie specifique
		//

		$req_cat = $this->uri->segment(2); 

		if (empty($req_cat) || ! ctype_alnum($req_cat) || strlen($req_cat) > 25)
		{
			$req_cat = NULL;	
		}

		$data = $this->Ressource_model->extraire_ressources_ordre($req_cat);

		$this->data = array_merge($data, $this->data);

        $this->_display_view();
	}

    public function _display_view($page = '')
    {
        $this->load->view('commons/header_view', $this->data);
        $this->load->view('commons/nav_view', $this->data);
        $this->load->view('ressources/ressources_view', $this->data);
        $this->load->view('commons/footer_view');
    }
}

/* End of file ressources.php */
/* Location: ./application/controllers/ressources.php */

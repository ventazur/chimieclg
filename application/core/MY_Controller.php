<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        //
        // Init
        //

        $this->data['controller'] = ($this->uri->segment(1) ? $this->uri->segment(1) : 'home');

		$this->is_DEV = $this->config->item('is_DEV');

		//
		// Le site n'a pas de systeme de compte personnel.
		//

	    $this->logged_in = FALSE;

		//
		// Enregistrer l'activite du site
		//

		if ( ! $this->input->is_ajax_request()) {
			$this->Usager_model->log_activity();
		}
    }

} // class

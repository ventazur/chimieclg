<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        //
        // Init
        //
		
		$this->encryption->initialize(
			$this->config->item('encryption_settings')
		);

		$this->data['controller'] = ($this->uri->segment(1) ? $this->uri->segment(1) : 'home');

		$this->is_DEV = $this->config->item('is_DEV');

		$this->load->driver('cache', array('adapter' => 'file'));
		$this->load->library('user_agent');

		//
		// Le site n'a pas de systeme de compte personnel.
		//

	    $this->logged_in = FALSE;

		//
		// CF Turnstile
		//
	
		$this->est_humain = ($this->is_DEV ? TRUE : $this->Usager_model->verifier_ip_autorise());
		// $this->est_humain = $this->Usager_model->verifier_ip_autorise();

		// Verifier par la session

		if ( ! $this->est_humain && isset($_SESSION['est_humain']) && $_SESSION['est_humain'] == TRUE)
		{
			$this->est_humain = TRUE;
		}

		// Laisser passer les robots legitimes verifies (Googlebot, OpenAI, Anthropic/Claude)

		if ( ! $this->est_humain &&
			 ! $this->input->is_ajax_request() &&
			 (   $this->Usager_model->verify_googlebot()
			  || $this->Usager_model->verify_openai_bot()
			  || $this->Usager_model->verify_claude_bot() ))
		{
			$this->est_humain = TRUE;
		}

		// Redirection si necessaire

		if ( ! $this->est_humain)
		{
			$_SESSION['turnstile_redirect'] = current_url();

			redirect('bot/challenge');
			exit;
		}

		//
		// Enregistrer l'activite du site
		//

		if ( ! $this->input->is_ajax_request()) 
		{
			$this->Usager_model->log_activity();
		}
    }

} // class

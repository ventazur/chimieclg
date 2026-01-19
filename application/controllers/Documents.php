<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 *
 * Documents (OBSOLETE)
 *
 * ---------------------------------------------------------------------------- */ 

Class Documents extends MY_Controller 
{
    public function __construct()
    {
        parent::__construct();
    } 

	public function index()
	{
		//
		// La page des documents est maintenant redirigee vers celle des ressources pour
		// conserver la retro-compatibilite des liens.
		//

		redirect('ressources');
		exit;	
	}
}

/* End of file documents.php */
/* Location: ./application/controllers/documents.php */

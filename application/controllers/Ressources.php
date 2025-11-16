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

	public function index()
    {
        $ressources = $this->Ressource_model->lister_ressources_generales();
        $categories = $this->Ressource_model->lister_categories(array(), 1);

        $categories = array_keys_swap($categories, 'code');

        // Extraire les categories utiles

        $categories_codes  = array_column($categories, 'code');
        $categories_utiles = array();

        if ( ! empty($categories_codes))
        {
            foreach($categories_codes as $code)
            {
                foreach($ressources as $r)
                {
                    if ($r['category'] == $code)
                    {
                        $categories_utiles[] = $code;
                        break;
                    }
                }
            }
        }

        $this->data = array_merge($this->data, 
            array(
                'ressources' => $ressources,
                'categories' => $categories,
                'categories_utiles' => $categories_utiles
            )
        );

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

/* End of file documents.php */
/* Location: ./application/controllers/documents.php */

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 *
 * Cours
 *
 * ---------------------------------------------------------------------------- */

Class Cours extends MY_Controller 
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Cours_model');
        $this->load->model('Ressource_model');

		$this->data = array_merge(
			array(
				'controller' => strtolower(__CLASS__)
			),
			$this->data
		);
    }

    public function _remap($method)
    {
        $valid_methods = ['index', 'choix', 'organigramme'];

        //
        // Extraire les cours disponibles
        // +
        // Ajouter les cours aux methodes valides
        //

		$cours = $this->Cours_model->lister_cours();

        $cours_disponibles = array();
        $prealables = array();

        if ( ! empty($cours))
        {
            foreach($cours as $c)
            {
                $code_court = strtolower($c['code_court']);
                $cours_disponibles[$code_court] = $c; 

                if ( ! empty($c['prealables']))
                {
                    $prealables[$code_court] = strtolower($c['prealables']);
                }

                if ($c['page_disponible'])
                {
                    $valid_methods[] = strtolower($c['code_court']);
                }
            }
        }

		$this->data['cours_disponibles'] = $cours_disponibles;
        $this->data['prealables'] = $prealables;

        if ( ! in_array($method, $valid_methods) && ! array_key_exists($method, $this->data['cours_disponibles']))
        {
            redirect();
            exit;
        }
        else 
        {
            switch($method) 
            {
                case 'index' :
                    $this->data['ressources_cours'] = $this->Ressource_model->lister_ressources_cours();
                    $this->_display_view('list');
                    break;

                case 'organigramme' :
                    $this->_display_view($method);
                    break;
            
				case 'choix' :
					// version 1, page_max = 17
					// version 2, page_max = 14 (H2024)

					$this->data['choix_version']  = 2; 
                    $this->data['choix_page_max'] = 14;
                    $this->data['choix_page']     = $this->uri->segment(3) ?? 'page1';

                    // surete
                    if ( ! preg_match('/page/', $this->data['choix_page']))
                        $this->data['choix_page'] = 'page1';

                    $this->data['choix_page_no'] = preg_replace('/[^0-9]/', '', $this->data['choix_page']);

                    if ($this->data['choix_page_no'] > $this->data['choix_page_max'])
                    {
                        redirect(base_url() . 'cours/choix/page' . $this->data['choix_page_max']); 
                        exit;
                    }

                    if ($this->data['choix_page_no'] < 1)
                    {
                        redirect(base_url() . 'cours/choix'); 
                        exit;
                    }

                    if (empty($this->data['choix_page_no']))
                        $this->data['choix_page_no'] = 1;

                    $this->_display_view('choix');
                    break;

				default :
                    $this->data['cours'] = $this->data['cours_disponibles'][$method];

					$ressources = $this->Ressource_model->lister_ressources($method);
					$categories = array();

					if ($ressources)
					{
					   $ordre = array_column($ressources, 'ordre');
					   array_multisort($ressources, SORT_DESC, $ordre); 

						foreach($ressources as $r)
						{
							if ( ! in_array($r['category'], $categories))
							{
								$categories[] = $r['category'];
							}
						}

						$categories = $this->Ressource_model->lister_categories($categories); 
						$categories = array_keys_swap($categories, 'code');
					}

					$this->data['categories'] = $categories;
					$this->data['ressources'] = $ressources;

					//
					// Utilisons le nouveau gabarit si la description du cours est dans la base de donnes.
					//

                    if (array_key_exists('description', $this->data['cours']) && ! empty($this->data['cours']['description']))
                    {
                        $this->_display_view('gabarit');
                    }
                    else
                    {
                        $this->_display_view($method);
                    }

                    break;
            }

            return;
        }
    }

    public function index()
    {
        $cours = $this->Cours_model->lister_cours();

        $cours_disponibles = array();
        $prealables = array();

        if ( ! empty($cours))
        {
            foreach($cours as $c)
            {
                $code_court = strtolower($c['code_court']);
                $cours_disponibles[$code_court] = $c; 

                if ( ! empty($c['prealables']))
                {
                    $prealables[$code_court] = strtolower($c['prealables']);
                }
            }
        }

        $this->data['cours_disponibles'] = $cours_disponibles;

        $this->data['prealables'] = $prealables;

        $this->_display_view('list');
    }

    public function _display_view($page = '')
    {
        $this->load->view('commons/header_view', $this->data);
        $this->load->view('commons/nav_view', $this->data);
        $this->load->view('cours/cours_' . $page . '_view', $this->data);
        $this->load->view('commons/footer_view');
    }
}

/* End of file cours.php */
/* Location: ./application/controllers/cours.php */

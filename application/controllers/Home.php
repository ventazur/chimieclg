<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller 
{
	public function index()
    {
        $this->data['nombre_enseignants'] = $this->Personne_model->nombre_enseignants();

        $this->load->view('commons/header_view', $this->data);
        $this->load->view('commons/nav_view', $this->data);
        $this->load->view('home_view', $this->data);
        $this->load->view('commons/footer_view');
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */

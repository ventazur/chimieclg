<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chimie extends MY_Controller 
{
	public function index()
	{
        $this->load->view('commons/header_view', $this->data);
        $this->load->view('commons/nav_view', $this->data);
        $this->load->view('chimie_view');
        $this->load->view('commons/footer_view');
	}
}

/* End of file chimie.php */
/* Location: ./application/controllers/chimie.php */

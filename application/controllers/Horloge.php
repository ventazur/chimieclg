<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* --------------------------------------------------------------------
 *
 * HORLOGE
 *
 * --------------------------------------------------------------------
 *
 * version 2 (2025)
 *
 * -------------------------------------------------------------------- */

class Horloge extends MY_Controller 
{
	public function __construct()
	{
		parent::__construct();

        $this->current_controller = strtolower(__CLASS__);
    }

	public function index()
    {
        $this->_display();
    }

    public function v($v)
    {
        $versions_acceptees = [1, 2];

        if ( ! in_array($v, $versions_acceptees))
        {
            redirect(base_url() . 'horloge');
            exit;
        }

        $this->_display($v);
    }

    public function ping()
    {
        echo json_encode(
            array(
                'epoch' => date('U'),
                'heure' => date('H:i:s')
            )
        );
    }

	/* ---------------------------------------------------------------------------
	 *
	 * Affichage
	 *
	 * --------------------------------------------------------------------------- */ 

    function _display($v = 'index')
	{
		if ($v == 'index')
		{
			$this->load->view('commons/header_view', $this->data);
			$this->load->view('commons/nav_view', $this->data);
        	$this->load->view('horloge/horloge_index_view', $this->data);
			$this->load->view('commons/footer_view');

			return;
		}

		$this->load->view('horloge/horloge_header_view', $this->data);

        if (ctype_digit($v))
        {
            $this->load->view('horloge/horloge_version_' . $v . '_view', $this->data); 
        }
        else
        {
            $this->load->view('horloge/horloge_index_view', $this->data); 
		}

		$this->load->view('horloge/horloge_footer_view', $this->data);
    }
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */

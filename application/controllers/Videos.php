<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 *
 * Videos
 * 
 * ---------------------------------------------------------------------------- */

Class Videos extends MY_Controller 
{
    public function __construct()
    {
        parent::__construct();
    } 

	public function index()
    {
        $this->_display_view();
	}

    public function _display_view($page = '')
    {
        $this->load->view('commons/header_view', $this->data);
        $this->load->view('commons/nav_view', $this->data);
        $this->load->view('videos/videos_view', $this->data);
        $this->load->view('commons/footer_view');
    }
}

/* End of file videos.php */
/* Location: ./application/controllers/videos.php */

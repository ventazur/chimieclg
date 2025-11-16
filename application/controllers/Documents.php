<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 *
 * Documents
 *
 * ---------------------------------------------------------------------------- */ 

Class Documents extends MY_Controller 
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Document_model');

        $this->data = [
            'controller' => strtolower(__CLASS__)
        ];
    } 

	public function index()
    {
        $this->data['categories'] = $this->Document_model->lister_categories();
        $this->data['documents']  = $this->Document_model->lister_documents();

        //
        // Enlever les categories vides
        //

        if ($this->data['documents'] && $this->data['categories'])
        {
            foreach($this->data['categories'] as $key => $cat)
            {
                $vide = TRUE;

                foreach($this->data['documents'] as $doc)
                {
                    if ($doc['cat_code'] == $cat['code'])
                    {
                        $vide = FALSE;
                    }
                }

                if ($vide)
                {
                    unset($this->data['categories'][$key]);
                }
            }
        }

        $this->_display_view();
	}

    public function _display_view()
    {
        $this->load->view('commons/header_view', $this->data);
        $this->load->view('commons/nav_view', $this->data);
        $this->load->view($this->data['controller'] . '/documents_view', $this->data);
        $this->load->view('commons/footer_view');
    }
}

/* End of file documents.php */
/* Location: ./application/controllers/documents.php */

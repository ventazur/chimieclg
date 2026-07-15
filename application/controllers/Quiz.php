<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 *
 * Quiz
 *
 * ---------------------------------------------------------------------------- */

Class Quiz extends MY_Controller
{
    private $quizzes;

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('chimie');

        $this->quizzes = quiz_liste_disponibles();
    }

    public function index()
    {
        $this->data['quizzes'] = $this->quizzes;

        $this->_display_view('index');
    }

    public function cs()
    {
        $this->data['quiz'] = $this->quizzes['cs'];

        $this->_display_view('cs');
    }

    /* -------------------------------------------------------------------
     *
     * (AJAX) Lot de nombres pour un quiz donne
     *
     * ------------------------------------------------------------------- */
    public function lot($slug = '')
    {
        if ( ! $this->input->is_ajax_request())
        {
            exit('No direct script access allowed');
        }

        if ( ! array_key_exists($slug, $this->quizzes))
        {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array()));
        }

        $taille_lot = 20;
        $lot = array();

        switch ($slug)
        {
            case 'cs' :
                for ($i = 0; $i < $taille_lot; $i++)
                {
                    $lot[] = cs_generer_nombre();
                }
                break;
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($lot));
    }

    public function _display_view($page = '')
    {
        $this->load->view('commons/header_view', $this->data);
        $this->load->view('commons/nav_view', $this->data);
        $this->load->view('quiz/quiz_' . $page . '_view', $this->data);
        $this->load->view('commons/footer_view');
    }
}

/* End of file quiz.php */
/* Location: ./application/controllers/quiz.php */

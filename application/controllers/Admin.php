<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* --------------------------------------------------------------------
 *
 * Admin Controller
 *
 * Panneau d'administration protege par mot de passe. N'etend PAS
 * MY_Controller : on evite ainsi le portail Turnstile (destine aux
 * visiteurs publics) et on evite de polluer activity_log avec les
 * visites de l'administrateur lui-meme.
 *
 * -------------------------------------------------------------------- */

class Admin extends CI_Controller
{
	// Nombre d'essais de mot de passe autorises avant blocage temporaire

	const MAX_ESSAIS      = 5;
	const FENETRE_ESSAIS  = 900; // 15 minutes

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Admin_model');
	}

    // ------------------------------------------------------------------------
    //
    // Formulaire de connexion, ou redirection si deja connecte
    //
    // ------------------------------------------------------------------------
	public function index()
	{
		if ( ! empty($_SESSION['admin_logged_in']))
		{
			redirect('admin/activite');
			exit;
		}

		$this->data['erreur'] = ($this->session->flashdata('admin_erreur') ?: '');

		$this->load->view('commons/header_view', $this->data);
		$this->load->view('commons/nav_view', $this->data);
		$this->load->view('admin/login_view', $this->data);
		$this->load->view('commons/footer_view');
	}

    // ------------------------------------------------------------------------
    //
    // Traitement de la connexion
    //
    // ------------------------------------------------------------------------
	public function login()
	{
		if ($this->_trop_essais())
		{
			$this->session->set_flashdata('admin_erreur', "Trop de tentatives. Reessayez dans quelques minutes.");
			redirect('admin');
			exit;
		}

		$mot_de_passe = $this->input->post('mot_de_passe');

		if ($this->Admin_model->verifier_mot_de_passe($mot_de_passe))
		{
			unset($_SESSION['admin_essais']);

			$_SESSION['admin_logged_in'] = TRUE;

			$this->session->sess_regenerate(TRUE);

			redirect('admin/activite');
			exit;
		}

		$this->_enregistrer_echec();

		$this->session->set_flashdata('admin_erreur', "Mot de passe incorrect.");

		redirect('admin');
		exit;
	}

    // ------------------------------------------------------------------------
    //
    // Deconnexion
    //
    // ------------------------------------------------------------------------
	public function logout()
	{
		unset($_SESSION['admin_logged_in']);

		redirect('admin');
		exit;
	}

    // ------------------------------------------------------------------------
    //
    // Tableau de bord de l'activite du site
    //
    // ------------------------------------------------------------------------
	public function activite()
	{
		$this->_exiger_connexion();

		$periodes = array(7 => '7 jours', 30 => '30 jours', 90 => '90 jours', 0 => 'Tout');

		$jours_get = $this->input->get('jours');

		$jours = ($jours_get === NULL) ? 90 : (int) $jours_get;

		if ( ! array_key_exists($jours, $periodes))
			$jours = 90;

		$this->data['jours']            = $jours;
		$this->data['periodes']         = $periodes;
		$this->data['sommaire']         = $this->Admin_model->sommaire($jours);
		$this->data['visites_par_jour'] = $this->Admin_model->visites_par_jour($jours);
		$this->data['pages_populaires'] = $this->Admin_model->pages_populaires($jours);

		$this->load->view('commons/header_view', $this->data);
		$this->load->view('commons/nav_view', $this->data);
		$this->load->view('admin/activite_view', $this->data);
		$this->load->view('commons/footer_view');
	}

    // ------------------------------------------------------------------------
    //
    // Exiger une session admin active, sinon rediriger vers la connexion
    //
    // ------------------------------------------------------------------------
	private function _exiger_connexion()
	{
		if (empty($_SESSION['admin_logged_in']))
		{
			redirect('admin');
			exit;
		}
	}

    // ------------------------------------------------------------------------
    //
    // Etranglement des tentatives de mot de passe (par session)
    //
    // ------------------------------------------------------------------------
	private function _trop_essais()
	{
		if (empty($_SESSION['admin_essais']))
			return FALSE;

		$essais = $_SESSION['admin_essais'];

		if (time() - $essais['depuis'] > self::FENETRE_ESSAIS)
		{
			unset($_SESSION['admin_essais']);
			return FALSE;
		}

		return ($essais['n'] >= self::MAX_ESSAIS);
	}

	private function _enregistrer_echec()
	{
		if (empty($_SESSION['admin_essais']) || (time() - $_SESSION['admin_essais']['depuis'] > self::FENETRE_ESSAIS))
		{
			$_SESSION['admin_essais'] = array('n' => 0, 'depuis' => time());
		}

		$_SESSION['admin_essais']['n']++;
	}
}

/* End of file Admin.php */
/* Location: ./application/controllers/Admin.php */

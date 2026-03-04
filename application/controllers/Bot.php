<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* --------------------------------------------------------------------
 *
 * Bot Controller
 *
 * -------------------------------------------------------------------- */

class Bot extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
	}

    // ------------------------------------------------------------------------
    //
    // Cloudflare Turnstile - Challenge
    //
    // ------------------------------------------------------------------------
	public function challenge()
	{
		$this->load->view( 'challenge'); 
	}

    // ------------------------------------------------------------------------
    //
    // Cloudflare Turnstile - Verify
    //
    // ------------------------------------------------------------------------
	public function verify_turnstile()
	{
		$token  = $this->input->post('cf-turnstile-response');

		if ($this->config->item('is_DEV'))
		{
			$secret = $this->config->item('cf_turnstile_dev')['secret_key'];
		}
		else
		{
			$secret = $this->config->item('cf_turnstile')['secret_key'];
		}

		$response = file_get_contents($this->config->item('cf_turnstile')['verify_url'], false, stream_context_create([
			"http" => [
				"method"  => "POST",
				"header"  => "Content-type: application/x-www-form-urlencoded\r\n",
				"content" => http_build_query([
					"secret"   => $secret,
					"response" => $token,
					"remoteip" => $this->input->ip_address()
				])
			]
		]));

		$result = json_decode($response, true);

		if ( ! empty($result['success']) && $result['success'] === TRUE) 
		{
			// 
			// succes
			//

			$_SESSION['est_humain'] = TRUE;

			if (isset($_SESSION['turnstile_redirect']) && ! empty($_SESSION['turnstile_redirect']))
			{
				redirect($_SESSION['turnstile_redirect']);
				exit;
			}
		} 
		else 
		{
			//
			// echec
			//

			redirect('bot/challenge');
			exit;
		}

		redirect(base_url());
		exit;
	}
}

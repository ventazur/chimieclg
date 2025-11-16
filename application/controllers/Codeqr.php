<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use chillerlan\QRCode\{QRCode, QROptions};
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\QRImage;

// use chillerlan\QRCode\Output\QRGdImagePNG;

class Codeqr extends MY_Controller 
{
	public function __construct()
	{
		parent::__construct();

		if (strpos($this->config->item('domain'), 'chimieclg.ca') === FALSE)
		{
			#redirect(base_url());
			#exit;
        }
	}

	public function index()
    {
        $data = 'https://chimieclg.ca';

        $this->data['qr_img1'] = $this->_generer_codeqr($data);        
        $this->data['qr_img2'] = $this->_generer_codeqr($data, 2);        

        $this->load->view('codeqr', $this->data);
    }

    public function _generer_codeqr($data, $version = 1)
    {
        $qrcode = new QRCode;

        $options = new QROptions;

        $options->eccLevel = EccLevel::H;
        $options->addQuietzone = TRUE;

        if ($version == 2)
        {
            $options->drawCircularModules = true;
            $options->circleRadius        = 0.40;
            $options->keepAsSquare        = [
                QRMatrix::M_FINDER_DARK,
                QRMatrix::M_FINDER_DOT,
                QRMatrix::M_ALIGNMENT_DARK,
            ];

            $options->svgDefs = '
                <linearGradient id="gradient" x1="100%" y2="100%">
                    <stop stop-color="#D70071" offset="0"/>
                    <stop stop-color="#9C4E97" offset="0.5"/>
                    <stop stop-color="#0035A9" offset="1"/>
                </linearGradient>
                <style><![CDATA[
                    .dark{fill: url(#gradient);}
                    .light{fill: #fff; }
                ]]></style>';
        }

        $qrcode->setOptions($options);

        $data = trim($data);

        return $qrcode->render($data);
    }

    public function generer()
    {
        if ( ! $this->input->is_ajax_request()) 
        {
            exit('No direct script access allowed');
        }

        $post_data = $this->input->post();

        $data = trim($post_data['qrdata']);

        if (strlen($data) > 368)
        {
            echo json_encode(FALSE);
            return;
        }

        $qr_img1 = $this->_generer_codeqr($data);
        $qr_img2 = $this->_generer_codeqr($data, 2);

        echo json_encode(
            array('qr_img1' => $qr_img1, 'qr_img2' => $qr_img2)
        );
        return;
    }
}


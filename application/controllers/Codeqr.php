<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 *
 * CODE QR
 *
 * ---------------------------------------------------------------------------- */

use chillerlan\QRCode\{QRCode, QROptions};
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\QRImage;

class Codeqr extends MY_Controller 
{
	public function __construct()
	{
		parent::__construct();

		/*
		if (strpos($this->config->item('domain'), 'chimieclg.ca') === FALSE)
		{
			redirect(base_url());
			exit;
		}
		*/
	}

	/* -------------------------------------------------------------------
	 *
	 * index
	 *
	 * ------------------------------------------------------------------- */
	public function index()
    {
        $data = 'https://chimieclg.ca';

		$this->data['qr_img1'] = $this->_generer_codeqr_png($data);
        $this->data['qr_img2'] = $this->_generer_codeqr_png($data, FALSE);
        $this->data['qr_img3'] = $this->_generer_codeqr_svg($data);
        $this->data['qr_img4'] = $this->_generer_codeqr_svg($data, 2);        

        $this->load->view('codeqr', $this->data);
    }

	/* -------------------------------------------------------------------
	 *
	 * (AJAX) Generer un code QR
	 *
	 * ------------------------------------------------------------------- */
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

		echo json_encode(
			array(
				'qr_img1' => $this->_generer_codeqr_png($data),
				'qr_img2' => $this->_generer_codeqr_png($data, FALSE),
				'qr_img3' => $this->_generer_codeqr_svg($data),
				'qr_img4' => $this->_generer_codeqr_svg($data, 2)
			)
		);
        return;
	}

	/* -------------------------------------------------------------------
	 *
	 * Generer un code QR SVG
	 *
	 * ------------------------------------------------------------------- */
    public function _generer_codeqr_svg($data, $version = 1)
    {
        $qrcode = new QRCode;

        $options = new QROptions;

        $options->eccLevel = EccLevel::H;
        $options->addQuietzone = TRUE;
		$options->quietzoneSize = 3;

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

	/* -------------------------------------------------------------------
	 *
	 * Generer un code QR PNG
	 *
	 * ------------------------------------------------------------------- */
	public function _generer_codeqr_png($data, $logo = TRUE)
	{
		$options = new \chillerlan\QRCode\QROptions([
			'version'			  => \chillerlan\QRCode\Common\Version::AUTO,
			// 'versionMin'		  => 5,	
			'eccLevel'            => \chillerlan\QRCode\Common\EccLevel::H,
			'addQuietzone'    	  => true,
			'quietzoneSize'       => 3,
			'scale'               => 20,
			'outputType'          => \chillerlan\QRCode\QRCode::OUTPUT_IMAGE_PNG, 
			'pngCompression' 	  => 5,
			'imageBase64'         => false
		]);

		if ($logo)
		{
			$options->version = 7;
			$options->quietzoneSize = 5;
			$options->addLogoSpace = TRUE;
			$options->logoSpaceWidth = 12;
			$options->logoSpaceHeight = 12;
		}

		$qrcode = new \chillerlan\QRCode\QRCode($options);
		
		$qrImage = $qrcode->render($data);

		// Transformation en ressource GD
		$qrResource = imagecreatefromstring($qrImage);
		
		if ( ! $qrResource) 
		{
			die("Erreur : La bibliothèque n'a pas généré un format d'image valide.");
		}

		$logoPath = FCPATH . 'assets/img/logoCLG_2025.png';

		if ($logo && file_exists($logoPath)) 
		{
			$logoResource = imagecreatefrompng($logoPath);

			imagealphablending($qrResource, true);
			imagesavealpha($qrResource, true);

			// Taille maximale autorisée (définie par l'espace reserve)
			$maxW = $options->logoSpaceWidth * $options->scale;
			$maxH = $options->logoSpaceHeight * $options->scale;

			// Dimensions réelles du logo source
			$origW = imagesx($logoResource);
			$origH = imagesy($logoResource);

			// Calcul du ratio pour ne pas deformer
			$ratio = min($maxW / $origW, $maxH / $origH);
			
			// Nouvelles dimensions proportionnelles
			$targetW = (int)($origW * $ratio);
			$targetH = (int)($origH * $ratio);

			// Centrage
			$destX = (imagesx($qrResource) - $targetW) / 2;
			$destY = (imagesy($qrResource) - $targetH) / 2;

			// Dessiner le cadre noir
			$black = imagecolorallocate($qrResource, 0, 0, 0);
			
			$spaceX = 10;
			$spaceY = 25;	
					
			imagerectangle(
				$qrResource, 
				(int) ($destX + $spaceX), 
				(int) ($destY - $spaceY), 
				(int) ($destX + $targetW - $spaceX),
				(int) ($destY + $targetH + $spaceY), 
				$black
			);

			// Fusion haute qualite
			imagecopyresampled(
				$qrResource, $logoResource, 
				(int) $destX, (int) $destY, 0, 0, 
				$targetW, $targetH, $origW, $origH
			);
			
			imagedestroy($logoResource);
		}

		ob_start();
		imagepng($qrResource);
		$imageData = ob_get_contents();
		ob_end_clean();

		imagedestroy($qrResource);

		$image = 'data:image/png;base64,' . base64_encode($imageData);

		return $image;
	}
}


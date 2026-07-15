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

		$type = $post_data['type'] ?? 'all';

		switch ($type)
		{
			case '1': echo json_encode(['qr_img1' => $this->_generer_codeqr_png($data)]); break;
			case '2': echo json_encode(['qr_img2' => $this->_generer_codeqr_png($data, FALSE)]); break;
			case '3': echo json_encode(['qr_img3' => $this->_generer_codeqr_svg($data)]); break;
			case '4': echo json_encode(['qr_img3' => $this->_generer_codeqr_svg($data), 'qr_img4' => $this->_generer_codeqr_svg($data, 2)]); break;
			default:
				echo json_encode(array(
					'qr_img1' => $this->_generer_codeqr_png($data),
					'qr_img2' => $this->_generer_codeqr_png($data, FALSE),
					'qr_img3' => $this->_generer_codeqr_svg($data),
					'qr_img4' => $this->_generer_codeqr_svg($data, 2)
				));
		}
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
                    <stop stop-color="#D22630" offset="0"/>
                    <stop stop-color="#D22630" offset="0.5"/>
                    <stop stop-color="#D22630" offset="1"/>
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
			'versionMin'   		  => 4,
			'eccLevel'            => \chillerlan\QRCode\Common\EccLevel::H,
			'addQuietzone'    	  => true,
			'quietzoneSize'       => 3,
			'scale'               => 20,
			'outputType'          => \chillerlan\QRCode\QRCode::OUTPUT_IMAGE_PNG, 
			'pngCompression' 	  => 5,
			'imageBase64'         => false
		]);

		$qrcode = new \chillerlan\QRCode\QRCode($options);

		// Rayon (en modules) de la zone circulaire reservee au logo
		$rayonModules = 6.5;

		if ($logo)
		{
			foreach (\chillerlan\QRCode\Common\Mode::INTERFACES as $dataInterface)
			{
				if ($dataInterface::validateString($data))
				{
					$qrcode->addSegment(new $dataInterface($data));
					break;
				}
			}

			$matrix = $qrcode->getQRMatrix();

			// Nettoyer uniquement les modules (blocs) entiers dont le centre
			// se trouve dans le cercle, pour un contour "en escalier" propre
			// plutot que de couper des blocs a mi-chemin.
			$taille = $matrix->getSize();
			$centreModule = $taille / 2;

			for ($y = 0; $y < $taille; $y++)
			{
				for ($x = 0; $x < $taille; $x++)
				{
					$dx = ($x + 0.5) - $centreModule;
					$dy = ($y + 0.5) - $centreModule;

					if (sqrt($dx * $dx + $dy * $dy) <= $rayonModules)
					{
						$matrix->set($x, $y, false, QRMatrix::M_LOGO);
					}
				}
			}

			$qrImage = $qrcode->renderMatrix($matrix);
		}
		else
		{
			$qrImage = $qrcode->render($data);
		}

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

			$centerX = (int) (imagesx($qrResource) / 2);
			$centerY = (int) (imagesy($qrResource) / 2);

			$diametre = $rayonModules * 2 * $options->scale;

			// Dimensions réelles du logo source
			$origW = imagesx($logoResource);
			$origH = imagesy($logoResource);

			// Calcul du ratio pour inscrire le logo dans le cercle
			$padding = 4;
			$maxLogo = $diametre - $padding * 2;
			$ratio   = min($maxLogo / $origW, $maxLogo / $origH);

			// Nouvelles dimensions proportionnelles
			$targetW = (int)($origW * $ratio);
			$targetH = (int)($origH * $ratio);

			// Centrage
			$destX = $centerX - $targetW / 2;
			$destY = $centerY - $targetH / 2;

			// Fusion haute qualite
			imagecopyresampled(
				$qrResource, $logoResource,
				(int) $destX, (int) $destY, 0, 0,
				$targetW, $targetH, $origW, $origH
			);

		}

		ob_start();
		imagepng($qrResource);
		$imageData = ob_get_contents();
		ob_end_clean();

		$image = 'data:image/png;base64,' . base64_encode($imageData);

		return $image;
	}
}


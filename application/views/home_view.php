<?
/* ----------------------------------------------------------------------------
 *
 * HOME VIEW
 *
 * ---------------------------------------------------------------------------- */ ?>

<? 
	$choix_de_cours = FALSE;

	// La periode de choix de cours automne
	$cdc_debut_a = (int) date('Y') . '0927';
	$cdc_fin_a   = (int) date('Y') . '1010';

	// La periode de choix de cours hiver
	$cdc_debut_h = (int) date('Y') . '0220';
	$cdc_fin_h   = (int) date('Y') . '0310';

	$ajd = (int) date('Ymd');

	if ($ajd >= $cdc_debut_a && $ajd <= $cdc_fin_a)
	{
		$choix_de_cours = TRUE;
	}

	if ($ajd >= $cdc_debut_h && $ajd <= $cdc_fin_h)
	{
		$choix_de_cours = TRUE;
	}
?>

<div id="home" class="page-contenu">
<div class="container">    

    <div id="messages-aux-etudiants" class="row">

        <div  class="col-12">

            <div class="message-aux-etudiants d-none">
                <strong>Le Collège recherche des tuteurs et des tutrices pour les cours de chimie.</strong><br />
                Si vous avez de bonnes notes dans ces cours, vous pourriez aider une ou plusieurs personnes dans le cadre de l'Aide par les pairs.<br />
                Il s'agit d'un travail rémunéré 14,50$ l'heure.<br /> 
                Pour vous inscrire, présentez-vous au local <strong>D-210</strong>.
            </div>

            <div class="message-aux-etudiants d-none" style="text-align: center">
                Assurez-vous de consulter
				<strong><a href="<?= base_url(); ?>cours/choix">les cours de chimie offerts</a> !</strong>
            </div>

			<div class="message-aux-etudiants <?= $choix_de_cours ? '' : 'd-none'; ?>">
                <strong>C'est la période de choix de cours !</strong><br />
                Pour ceux qui n'ont pas encore fait leur choix de cours, assurez-vous de consulter les cours 
                de chimie offerts sous l'onglet
                <strong><a href="https://chimie.clg.qc.ca/cours">Cours</a></strong>. 
            </div>

        </div> <!-- .col-12 -->

    </div> <!-- #messages-aux-etudiants.row -->

    <div id="home-contenu" class="row">

        <div class="col">

            <p style="font-size: 32px; color: #d22630; font-weight: 600">
                Bienvenue !
            </p>

            <div class="space-h"></div>

            <p>
                Le département de chimie du Collège Lionel-Groulx réunit <?= $nombre_enseignants; ?> personnes enseignantes qui dispensent des cours de la formation spécifique et complémentaire.
            </p>

            <p>
                Les étudiants et étudiantes ont accès à des installations de première qualité et des instruments d'analyse à la fine pointe de la technologie.
            </p>

            <p>
                Nous avons trois salles de laboratoire, deux salles de mesure, une salle d'instrumentation et une salle de fermentation à leur disposition.  
                Ceci permet d'offrir aux étudiants des cours aussi variés que la chimie de l'alimentation, la chimie médicinale et même un cours de chimie du vin !
			</p>

        </div>

        <div id="home-carousel-wrap" class="col d-none d-md-block">

            <div id="home-carousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#home-carousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Image 1"></button>
                    <button type="button" data-bs-target="#home-carousel" data-bs-slide-to="1" aria-label="Image 2"></button>
                    <button type="button" data-bs-target="#home-carousel" data-bs-slide-to="2" aria-label="Image 3"></button>
                    <button type="button" data-bs-target="#home-carousel" data-bs-slide-to="3" aria-label="Image 4"></button>
                    <button type="button" data-bs-target="#home-carousel" data-bs-slide-to="4" aria-label="Image 5"></button>
                    <button type="button" data-bs-target="#home-carousel" data-bs-slide-to="5" aria-label="Image 6"></button>
                    <button type="button" data-bs-target="#home-carousel" data-bs-slide-to="6" aria-label="Image 7"></button>
                    <button type="button" data-bs-target="#home-carousel" data-bs-slide-to="7" aria-label="Image 8"></button>
                    <button type="button" data-bs-target="#home-carousel" data-bs-slide-to="8" aria-label="Image 9"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="<?= base_url() . 'assets/img/carousel/img1.jpg'; ?>" class="d-block w-100">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Laboratoire N-201</h5>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="<?= base_url() . 'assets/img/carousel/img2.jpg'; ?>" class="d-block w-100">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Laboratoire N-216</h5>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="<?= base_url() . 'assets/img/carousel/img3.jpg'; ?>" class="d-block w-100">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Laboratoire N-216</h5>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="<?= base_url() . 'assets/img/carousel/img4.jpg'; ?>" class="d-block w-100">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Laboratoire N-213</h5>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="<?= base_url() . 'assets/img/carousel/img5.jpg'; ?>" class="d-block w-100">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Laboratoire N-213</h5>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="<?= base_url() . 'assets/img/carousel/img6.jpg'; ?>" class="d-block w-100">
                    </div>
                    <div class="carousel-item">
                        <img src="<?= base_url() . 'assets/img/carousel/img7.jpg'; ?>" class="d-block w-100">
                    </div>
                    <div class="carousel-item">
                        <img src="<?= base_url() . 'assets/img/carousel/img8.jpg'; ?>" class="d-block w-100">
                    </div>
                    <div class="carousel-item">
                        <img src="<?= base_url() . 'assets/img/carousel/img9.jpg'; ?>" class="d-block w-100">
                    </div>
                </div> <!-- .carousel-inner -->
            </div> <!-- .carousel -->

        </div> <!-- col-6 -->

    </div> <!-- #home-contenu.row -->

</div> <!-- .container -->
</div> <!-- #home -->

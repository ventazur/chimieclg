<!doctype html>

<?
/* ----------------------------------------------------------------------------
 *
 * CODE QR
 *
 * ---------------------------------------------------------------------------- */ ?>

<html>
<head>
    <title>Collège Lionel-Groulx - Département de chimie</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" /> 
    <meta charset="UTF-8" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

	<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha256-MBffSnbbXwHCuZtgPYiwMQbfE7z+GOZ7fBPCNB06Z98=" crossorigin="anonymous"> -->

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400&subset=latin,latin-ext" type="text/css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">


    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/chimie.css?<?= date('U'); ?>" />

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

     <script src="<?= base_url(); ?>assets/js/codeqr.js?<?= date('U'); ?>"></script>

    <script>
        var base_url = "<?= base_url(); ?>";
        var cct      = "<?= $this->security->get_csrf_hash(); ?>";
    </script>
<?
/* --------------------------------------------------------------------
 *
 * Styles
 *
 * -------------------------------------------------------------------- */ ?>
<style>
    body {
        font-family: Lato;
    }
</style>


</head>

<body style="background: #f3f3f3">

<div class="container mb-5">

<div id="codeqr" class="row mt-5">

	<?
 	/* ------------------------------------------------------------------
	 *
	 * Formulaire (URL)
	 *
	 * ------------------------------------------------------------------ */ ?>

	<div class="col-md-6" style="padding: 40px; background: #fff">

		<div style="font-size: 2em;">
			<a href="<?= base_url(); ?>" style="text-decoration: none">
				<img style="max-width: 20px; vertical-align: text-bottom;" src="<?= base_url() . 'assets/img/logoCLG_i.png'; ?>" />
			</a>
			<span style="margin-left: 10px; font-weight: 400">CODE QR</span>
		</div>

		<hr />

		<div id="codeqr-form">

			<div class="mt-4 mb-2">
				Entrez le URL :
			</div>

			<div class="input-group">
				<input style="font-size: 1.1em;" class="form-control" id="codeqr-data" rows="3" value="https://chimieclg.ca" placeholder="Entrez les données ou URL" />
				<span id="codeqr-spinner" class="input-group-text d-none">
					<span class="spinner-border spinner-border-sm text-primary" role="status"></span>
				</span>
				<span id="codeqr-check" class="input-group-text d-none" style="color: #198754">
					<i class="bi bi-check-lg"></i>
				</span>
			</div>

			<div id="generer-erreur" class="d-none mt-3" style="color: #d22630; font-weight: 400; text-align: center; padding: 8px; border: 1px solid #d22630; border-radius: 5px;">
				<i class="bi bi-exclamation-circle" style="margin-right: 5px"></i>
				URL trop long
			</div>

        </div> <!-- #codeqr-form -->

		<div class="mt-4 mb-2">
			Choissisez la version du CodeQR à générer :
		</div>
			
		<div class="btn-group w-100" role="group" aria-label="Sélection de version">
			<button type="button" id="codeqr1-choix" class="codeqr-choix btn btn-primary">PNG logo</button>
		 	<button type="button" id="codeqr2-choix" class="codeqr-choix btn btn-outline-primary">PNG</button>
		  	<button type="button" id="codeqr3-choix" class="codeqr-choix btn btn-outline-primary">SVG</button>
			<button type="button" id="codeqr4-choix" class="codeqr-choix btn btn-outline-primary">SVG rouge</button>
		</div>

	</div>

	<?
 	/* ------------------------------------------------------------------
	 *
	 * CodeQR genere
	 *
	 * ------------------------------------------------------------------ */ ?>

	<div id="codeqr-png-logo" class="codeqr-genere col-md-6 mt-3 mt-md-0">

		<?
	 	/* --------------------------------------------------------------
		 *
		 * CodeQR - PNG - sans logo
		 *
		 * -------------------------------------------------------------- */ ?>

		<div id="codeqr1" class="codeqr-genere text-center" style="vertical-align: top">
			<a id="save-btn1" href="#">
				<img id="codeqr1-img" class="img-fluid mw-100" style="max-width: 800px" src="<?= $qr_img1; ?>" />
            </a>
		</div>

		<?
		/* ------------------------------------------------------------------
		 *
		 * CodeQR - PNG - sans logo
		 *
		 * ------------------------------------------------------------------ */ ?>

		<div id="codeqr2" class="d-none codeqr-genere text-center" style="vertical-align: top">
			<a id="save-btn2" href="#">
				<img id="codeqr2-img" class="img-fluid mw-100" style="max-width: 800px" src="<?= $qr_img2; ?>" />
            </a>
		</div>

		<?
		/* ------------------------------------------------------------------
		 *
		 * CodeQR - SVG - sans logo - noir & blanc
		 *
		 * ------------------------------------------------------------------ */ ?>

		<div id="codeqr3" class="d-none codeqr-genere text-center" style="vertical-align: top">
			<a id="save-btn3" href="#">
				<img id="codeqr3-img" class="img-fluid mw-100" style="max-width: 800px" src="<?= $qr_img3; ?>" />
            </a>
		</div>

		<?
		/* ------------------------------------------------------------------
		 *
		 * CodeQR - SVG - sans logo - couleurs
		 *
		 * ------------------------------------------------------------------ */ ?>

		<div id="codeqr4" class="d-none codeqr-genere text-center" style="vertical-align: top">
			<a id="save-btn4" href="#">
				<img id="codeqr4-img" class="img-fluid mw-100" style="max-width: 800px" src="<?= $qr_img4; ?>" />
            </a>
		</div>

		<div class="mt-2 mt-md-3" style="text-align: center; background: #fff; padding: 15px;">
			<i class="bi bi-exclamation-circle" style="color: crimson; margin-right: 5px"></i>
			Cliquez sur le code QR pour le télécharger
		</div>

    </div> <!-- .col-5 -->

</div> <!-- .row -->

</div> <!-- .container -->

</body>
</html>

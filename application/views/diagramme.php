<? 
/* --------------------------------------------------------------------
 *
 * Diagramme de chevauchement des incertitudes
 *
 * -------------------------------------------------------------------- */ ?>
<!doctype html>
<html lang="en">

<head>
    <?
    /* ------------------------------------------------------------------------
     *
     * Meta
     *
     * ------------------------------------------------------------------------ */ ?>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>
        Diagramme de chevauchement des incertitudes
	</title>

    <?
    /* ------------------------------------------------------------------------
     *
     * Preconnect & DNS Prefetch
     *
     * ------------------------------------------------------------------------ */ ?>

    <link rel="dns-prefetch" href="https://fonts.googleapis.com/">
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net/">

    <?
    /* ------------------------------------------------------------------------
     *
     * Les scripts (qui doivent etre charges avant les autres)
     *
     * ------------------------------------------------------------------------ */ ?>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <?
    /* ----------------------------------------------------------------------------
     *	
     * Les scripts (externes)
     *
     * ---------------------------------------------------------------------------- */ ?>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@3.0.1/dist/chartjs-plugin-annotation.min.js"></script>
	<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>

	<script src="<?= base_url() . 'assets/js/diag.js?' . date('U'); ?>"></script>

    <?
    /* ------------------------------------------------------------------------
     *
     * Les fonts
     *
     * ------------------------------------------------------------------------ */ ?>

    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400|Ubuntu+Mono&display=swap" rel="stylesheet">

    <?
    /* ------------------------------------------------------------------------
     *
     * Les styles (externes)
     *
     * ------------------------------------------------------------------------ */ ?>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

</head>

<body>

<div id="diagramme" class="mt-4">

<div class="container-fluid">
        
<div class="row">
<div class="col-8 offset-2">

		<div class="row">
			<div class="col-12">
				<h4>Générateur de diagramme de chevauchement des incertitudes</h4>
				<hr />
			</div>
		</div> <!-- .row -->

		<?
		/* ------------------------------------------------------------
		 * 
         * Diagramme
         *
		 * ------------------------------------------------------------ */ ?>

		<div class="row mt-2">

			<div class="col-12">
				<div id="canvas-wrap" style="border: 1px solid #222; padding: 25px">
					<i class="bi bi-exclamation-circle" style="color: crimson"></i> 
					<span style="color: crimson">Aucune figure</span>
				</div>
			</div>

			<div id="download-image-wrap" class="col-12 d-none" style="margin-top: 20px">
                <div id="download-image" class="btn btn-sm btn-primary">
                    <i class="bi bi-download" style="margin-right: 5px"></i>
                    Télécharger la figure
                </div>
			</div>

		</div>

		<?
		/* ------------------------------------------------------------
		 * 
         * Parametres
         *
		 * ------------------------------------------------------------ */ ?>

        <div class="row" style="margin-top: 30px">

            <div class="col-12 mb-5">

				<h5 class="mb-3">
					Paramètres
				</h5>

				<form id="form-diagramme" style="border: 1px solid #ccc; border-radius: 3px; padding: 20px; background: #f8f9fa;">

					<div class="form-row">

						<div class="form-group col-md-4">
							<label for="vexp">V expérimentale</label>
							<input type="number" step="0.01" class="form-control" id="vexp" value="6">
						</div>
						<div class="form-group col-md-4">
							<label for="dvexp">△V expérimentale</label>
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text" id="basic-addon1">±</span>
								</div>
								<input type="number" step="0.01" class="form-control" id="dvexp" value="1">
							</div>
						</div>
						<div class="form-group col-md-4">
							<label for="unites">Unités</label>
							<input type="text" class="form-control" id="unites" value="mL">
						</div>


					</div> <!-- .form-row -->

					<div class="form-row">

						<div class="form-group col-md-4">
							<label for="vadm">V admise</label>
							<input type="number" step="0.01" class="form-control" id="vadm" value="5">
						</div>
						<div class="form-group col-md-4">
							<label for="dvadm">△V admise</label>
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text" id="basic-addon1">±</span>
								</div>
								<input type="number" step="0.01" class="form-control" id="dvadm" value="1">
							</div>
						</div>
					</div>
				
					<div class="form-row">

						<div class="form-group col-md-4">
							<label for="xmin">Axe X minimum</label>
							<input type="number" class="form-control" id="xmin">
						</div>

						<div class="form-group col-md-4">
							<label for="xmax">Axe X maximum</label>
							<input type="number" class="form-control" id="xmax">
						</div>

						<div class="form-group col-md-4">
							<label for="decaxe">Axe X décimales</label>
							<input type="number" class="form-control" id="decaxe" value="2">
						</div>

					</div> <!-- .form-row -->

					<div id="generer-action" class="btn btn-outline-primary mt-2">Générer</div>
				</form>

            </div>

        </div>

</div> <!-- .col -->
</div> <!-- .row -->
</div> <!-- .container-fluid -->
</div> <!-- #diagramme -->

</body>

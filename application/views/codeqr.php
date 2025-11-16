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

<body style="background: #fff">

<div class="container-fluid">

<div class="row mb-3">
	<div class="col-12 text-center mt-5">

        <div id="page-titre">

            <h3>Code QR</h3>

        </div>
	</div>
</div> <!-- .row -->

<div id="codeqr" class="row">
    <div class="col-1"></div>

    <div class="col-5">
        <div id="codeqr1" class="text-center">
            <a id="save-btn1" href="#">
                <img id="codeqr1-img" src="<?= $qr_img1; ?>" />
            </a>
        </div>
    </div> <!-- .col-4 -->

    <div class="col-5">
        <div id="codeqr2" class="text-center">
            <a id="save-btn2" href="#">
                <img id="codeqr2-img" src="<?= $qr_img2; ?>" />
            </a>
        </div>
    </div> <!-- .col-4 -->

    <div class="col-1"></div>
</div> <!-- .row -->

<div class="row">

    <div class="col-3"></div>

    <div class="col-6">

		<div id="codeqr-form">

            <?= form_open(); ?>

            <div class="mt-3">
                <input class="form-control" id="codeqr-data" rows="3" maxlength="100" value="https://chimieclg.ca" placeholder="Entrez les données" />
            </div>

            <div id="codeqr-generer" class="btn btn-sm btn-outline-primary mt-3">Générer votre code QR</div>

            </form>

        </div>

	</div> <!-- .col-8 -->

    <div class="col-3"></div>

</div> <!-- .row -->
</div> <!-- .container -->

</body>
</html>

<!doctype html>
<html>
<head>
    <title>Collège Lionel-Groulx - Département de chimie</title>

    <meta name="viewport" content="width=device-width, initial-scale=1" /> 
    <meta charset="UTF-8" />

	<?
	/* ----------------------------------------------------------------
	 *
	 * Favicon
	 *
	 * ---------------------------------------------------------------- */ ?>

	<link rel="icon" type="image/x-icon" href="<?= base_url() . 'assets/img/favicon.ico'; ?>">

	<?
	/* ----------------------------------------------------------------
	 *
	 * Bootstrap
	 *
	 * ---------------------------------------------------------------- */ ?>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

	<?
	/* ----------------------------------------------------------------
	 *
	 * Polices
	 *
	 * ---------------------------------------------------------------- */ ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400&subset=latin,latin-ext" type="text/css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;500;600&family=Zilla+Slab:wght@300;400;600&display=swap">

	<?
	/* ----------------------------------------------------------------
	 *
	 * Icones
	 *
	 * ---------------------------------------------------------------- */ ?>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

	<?
	/* ----------------------------------------------------------------
	 *
	 * Stylesheets locaux
	 *
	 * ---------------------------------------------------------------- */ ?>
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/chimie.css?<?= date('U'); ?>" />

	<?
	/* ----------------------------------------------------------------
	 *
	 * Javascript
	 *
	 * ---------------------------------------------------------------- */ ?>

	<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <script>
        var base_url = "<?= base_url(); ?>";
        var cct      = "<?= $this->security->get_csrf_hash(); ?>";
    </script>

</head>

<body>

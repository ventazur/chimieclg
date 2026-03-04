<html>
<head>
	<title>CLG > Chimie > Vérification</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400&subset=latin,latin-ext" type="text/css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;500;600&family=Zilla+Slab:wght@300;400;600&display=swap">

	<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
</head>

<body style="font-family: Arial">

	<div style="margin: auto; margin-top: 50px; text-align: center">

		<img id="footer-logo" src="<?= base_url() . 'assets/img/logoCLG_2019.svg'; ?>" />

		<p style="margin-top: 30px">
			<span style="vertical-align: middle; font-family: Montserrat; font-weight: 600; font-size: 2.4em;">
				<img class="d-inline" src="<?= base_url() . 'assets/img/logoCLG_i.png'; ?>" style="vertical-align: top; height: 30px; margin-top: 6px;"  />
				CHIMIE
			</span>
		</p>

		<p style="font-family: Lato; font-weight: 300; margin-top: 30px; margin-bottom: 20px;">
			Nous sommes désolés de cette interruption.
		</p>

	</div>

		<? if ($this->config->item('is_DEV')) : ?>

			<div style="margin-auto: margin-top: 50px; text-align: center" 
				 class="cf-turnstile" 
				 data-sitekey="<?= $this->config->item('cf_turnstile_dev')['site_key_pass_v']; ?>"
				 data-callback="onTurnstileSuccess"
				 data-theme="auto">
			</div>

		<? else : ?>

			<div style="margin-auto: margin-top: 50px; text-align: center" 
				 class="cf-turnstile"
				 data-sitekey="<?= $this->config->item('cf_turnstile')['site_key']; ?>"
				 data-callback="onTurnstileSuccess"
				 data-action="bot-redirect"
				 data-theme="auto">
			</div>

		<? endif; ?>

		<form id="cf-form" method="post" action="/bot/verify_turnstile">
			<input type="hidden" name="cf-turnstile-response" id="cf-response">
			<input type="hidden" name="ci_csrf_token" value="<?= $this->security->get_csrf_hash(); ?>">
		</form>

		<script>
			function onTurnstileSuccess(token) {
				document.getElementById('cf-response').value = token;
				document.getElementById('cf-form').submit();
			}
		</script>

	</div>
</body>
</html>

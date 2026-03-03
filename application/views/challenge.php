<html>
<head>
	<title>CLG > Chimie > Vérification</title>

	<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
</head>

<body style="font-family: Arial">

	<div style="margin: auto; text-align: center">

		<p style="margin-top: 45px; font-weight: bold; font-size: 1.2em">Collège Lionel-Groulx > Département de chimie</p>

		<p style="margin-top: 40px;">
			Nous sommes désolés de cette interruption.
		</p>

	</div>

		<div style="margin-auto: margin-top: 40px; text-align: center" 
			 class="cf-turnstile"
			 data-sitekey="<?= $this->config->item('cf_turnstile')['site_key']; ?>"
			 data-callback="onTurnstileSuccess"
			 data-action="bot-redirect"
			 data-theme="auto">
		</div>

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

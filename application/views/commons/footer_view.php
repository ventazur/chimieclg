<?
/* ----------------------------------------------------------------------------
 *
 * Footer
 *
 * ---------------------------------------------------------------------------- */ ?>

<nav id="footer-nav" class="navbar navbar-expand-lg">

    <div class="container">

		<div class="col-6">
			<a href="https://clg.qc.ca">
				<img id="footer-logo" src="<?= base_url() . 'assets/img/logoCLG_2019.svg'; ?>" />
			</a>
        </div>

        <div class="col-6" style="text-align: right;">
			&copy; <?= date('Y'); ?>, Collège Lionel-Groulx. Tous droits réservés.
			<br /><br />
            Outils : 
            <a href="<?= base_url() . 'horloge'; ?>" style="text-decoration: none">Horloge</a>,
			<a href="<?= base_url() . 'codeqr'; ?>" style="text-decoration: none">CodeQR</a>

			<div class="mt-2">
				<i class="bi bi-github"></i> <a href="https://github.com/ventazur/chimieclg" target="_blank" style="font-weight: 300; text-decoration: none">GitHub</a>
			</div>
        </div>

    </div>
</nav>

<script src="<?= base_url(); ?>assets/js/chimie.js"></script>

</body>
</html>

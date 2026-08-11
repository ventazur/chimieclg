<?
/* ----------------------------------------------------------------------------
 *
 * Connexion admin
 *
 * ---------------------------------------------------------------------------- */ ?>

<div id="admin" class="page-contenu">
<div class="container">

	<div class="page-titre"><i class="bi bi-lock"></i> Administration</div>

	<div class="row justify-content-center">
		<div class="col-md-5 col-lg-4">

			<? if ( ! empty($erreur)) : ?>
				<div class="alert alert-danger py-2">
					<?= html_escape($erreur); ?>
				</div>
			<? endif; ?>

			<div class="card shadow-sm my-4">
				<div class="card-body">

					<?= form_open('admin/login'); ?>

						<div class="mb-3">
							<label for="mot_de_passe" class="form-label">Mot de passe</label>
							<input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" autofocus required>
						</div>

						<button type="submit" class="btn btn-primary w-100">Connexion</button>

					<?= form_close(); ?>

				</div>
			</div>

		</div>
	</div>

</div>
</div>

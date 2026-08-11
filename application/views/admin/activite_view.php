<?
/* ----------------------------------------------------------------------------
 *
 * Tableau de bord de l'activite du site
 *
 * ---------------------------------------------------------------------------- */ ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js" integrity="sha384-NrKB+u6Ts6AtkIhwPixiKTzgSKNblyhlk0Sohlgar9UHUBzai/sgnNNWWd291xqt" crossorigin="anonymous"></script>

<div id="admin" class="page-contenu">
<div class="container">

	<div class="page-titre d-flex justify-content-between align-items-center" style="margin-bottom: 22px;">
		<span><i class="bi bi-graph-up"></i> Activité du site</span>
		<a href="<?= base_url('admin/logout'); ?>" class="btn btn-outline-light btn-sm">
			<i class="bi bi-box-arrow-right"></i> Déconnexion
		</a>
	</div>

	<? // ------------------------------------------------------------------
	   // Selecteur de periode
	   // ------------------------------------------------------------------ ?>

	<div class="mb-4">
		<div class="btn-group w-100" role="group">
			<? foreach ($periodes as $valeur => $etiquette) : ?>
				<a href="<?= base_url('admin/activite?jours=' . $valeur); ?>"
				   class="btn btn-<?= ($valeur == $jours ? 'primary' : 'outline-primary'); ?> flex-fill">
					<?= html_escape($etiquette); ?>
				</a>
			<? endforeach; ?>
		</div>
	</div>

	<? // ------------------------------------------------------------------
	   // Sommaire
	   // ------------------------------------------------------------------ ?>

	<div class="row g-3 mb-4">
		<div class="col-md-4">
			<div class="card text-center shadow-sm">
				<div class="card-body">
					<div class="fs-2"><?= number_format($sommaire['pages_vues']); ?></div>
					<div class="text-muted">Pages vues</div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card text-center shadow-sm">
				<div class="card-body">
					<div class="fs-2"><?= number_format($sommaire['visiteurs']); ?></div>
					<div class="text-muted">Visiteurs distincts</div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card text-center shadow-sm">
				<div class="card-body">
					<div class="fs-2"><?= number_format($sommaire['robots']); ?></div>
					<div class="text-muted">Lignes robots écartées</div>
				</div>
			</div>
		</div>
	</div>

	<? // ------------------------------------------------------------------
	   // Graphique - visites par jour
	   // ------------------------------------------------------------------ ?>

	<div class="card shadow-sm mb-4">
		<div class="card-body">
			<h6 class="card-title">Visites par jour</h6>
			<canvas id="graphique-visites" height="90"></canvas>
		</div>
	</div>

	<? // ------------------------------------------------------------------
	   // Tableau - pages populaires
	   // ------------------------------------------------------------------ ?>

	<div class="card shadow-sm mb-4">
		<div class="card-body">
			<h6 class="card-title">Pages les plus populaires</h6>

			<? if (empty($pages_populaires)) : ?>

				<p class="text-muted mb-0">Aucune donnée pour cette période.</p>

			<? else : ?>

				<div class="table-responsive">
					<table class="table table-striped table-sm">
						<thead>
							<tr>
								<th>Page</th>
								<th class="text-end">Hits</th>
								<th class="text-end">Visiteurs distincts</th>
							</tr>
						</thead>
						<tbody>
							<? foreach ($pages_populaires as $page) : ?>
								<tr>
									<td>
										<a href="<?= base_url($page['url']); ?>" target="_blank" rel="noopener">
											<?= html_escape($page['url'] !== '' ? $page['url'] : '(accueil)'); ?>
										</a>
									</td>
									<td class="text-end"><?= number_format($page['hits']); ?></td>
									<td class="text-end"><?= number_format($page['visiteurs']); ?></td>
								</tr>
							<? endforeach; ?>
						</tbody>
					</table>
				</div>

			<? endif; ?>

		</div>
	</div>

</div>
</div>

<script>
	(function () {
		var donnees = <?= json_encode($visites_par_jour); ?>;

		var jours      = donnees.map(function (d) { return d.jour; });
		var pages_vues = donnees.map(function (d) { return parseInt(d.pages_vues, 10); });
		var visiteurs  = donnees.map(function (d) { return parseInt(d.visiteurs, 10); });

		new Chart(document.getElementById('graphique-visites'), {
			type: 'line',
			data: {
				labels: jours,
				datasets: [
					{
						label: 'Pages vues',
						data: pages_vues,
						borderColor: '#0d6efd',
						backgroundColor: 'rgba(13, 110, 253, 0.1)',
						tension: 0.2
					},
					{
						label: 'Visiteurs',
						data: visiteurs,
						borderColor: '#198754',
						backgroundColor: 'rgba(25, 135, 84, 0.1)',
						tension: 0.2
					}
				]
			},
			options: {
				responsive: true,
				scales: {
					y: { beginAtZero: true }
				}
			}
		});
	})();
</script>

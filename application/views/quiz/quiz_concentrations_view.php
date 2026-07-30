<?
/* ----------------------------------------------------------------------------
 *
 * Quiz > Conversion d'unités de concentration
 *
 * ---------------------------------------------------------------------------- */ ?>

<style>
	@import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,340;9..144,500&display=swap');

	#quiz-concentrations a {
		text-decoration: none;
	}

	#quiz-concentrations .page-titre {
		margin-top: -30px;
	}

	#quiz-concentrations-zone {
		box-sizing: border-box;
		margin-top: 24px;
		border: 1px solid #dbdcdd;
		border-radius: 8px;
		padding: 48px 56px;
	}

	#quiz-concentrations p.quiz-concentrations-description {
		margin-top: 0;
		font-size: 1.05em;
		color: #444;
		text-align: center;
	}

	#quiz-concentrations-mesure {
		position: relative;
		margin-top: 44px;
	}

	.quiz-concentrations-filet {
		border-top: 1px solid #d7d3c8;
	}

	#quiz-concentrations-score-wrap {
		position: absolute;
		top: -13px;
		right: 0;
	}

	#quiz-concentrations-score {
		display: inline-block;
		background: #ffcc00;
		color: #1c1c1c;
		font-family: Montserrat, Lato, sans-serif;
		font-weight: 700;
		font-size: 0.72em;
		letter-spacing: 0.04em;
		padding: 4px 10px;
		white-space: nowrap;
	}

	#quiz-concentrations-contenu {
		padding: 32px 0 8px;
	}

	#quiz-concentrations-enonce {
		text-align: center;
		font-size: 1.15em;
		line-height: 1.5;
		color: #1c1c1c;
		max-width: 640px;
		margin: 0 auto;
	}

	.quiz-concentrations-unite {
		font-family: Montserrat, Lato, sans-serif;
		font-weight: 700;
		font-size: 1.4em;
		color: #1c1c1c;
	}

	#quiz-concentrations-donnees {
		margin: 24px auto 20px;
		max-width: 640px;
		text-align: center;
		font-size: 1.15em;
		line-height: 1.5;
		color: #1c1c1c;
	}

	#quiz-concentrations-donnees .quiz-concentrations-donnee {
		display: inline-block;
		margin: 4px 12px;
	}

	#quiz-concentrations-reponse {
		margin-top: 40px;
		display: flex;
		align-items: baseline;
		justify-content: center;
		gap: 10px;
		flex-wrap: wrap;
	}

	#quiz-concentrations-input-mantisse {
		width: 120px;
		text-align: center;
		font-family: 'Fraunces', Georgia, serif;
		font-size: 1.4em;
	}

	.quiz-concentrations-fois-dix {
		font-family: 'Fraunces', Georgia, serif;
		font-size: 1.4em;
		color: #1c1c1c;
	}

	#quiz-concentrations-exposant-wrap {
		align-self: flex-start;
		transform: translateY(-10px);
	}

	#quiz-concentrations-input-exposant {
		width: 74px;
		text-align: center;
		font-family: 'Fraunces', Georgia, serif;
		font-size: 0.95em;
	}

	#quiz-concentrations-envoyer,
	#quiz-concentrations-suivant {
		font-family: Montserrat, Lato, sans-serif;
		font-weight: 600;
		font-size: 0.9em;
		letter-spacing: 0.06em;
		text-transform: uppercase;
		padding: 12px 38px;
		border-radius: 999px;
		background: #d22630;
		border-color: #d22630;
	}

	#quiz-concentrations-envoyer:hover,
	#quiz-concentrations-envoyer:focus,
	#quiz-concentrations-suivant:hover,
	#quiz-concentrations-suivant:focus {
		background: #a3222c;
		border-color: #a3222c;
	}

	#quiz-concentrations-envoyer:disabled {
		background: #d22630;
		border-color: #d22630;
		opacity: 0.3;
	}

	#quiz-concentrations-remise-zero {
		font-family: Montserrat, Lato, sans-serif;
		font-weight: 600;
		font-size: 0.82em;
		letter-spacing: 0.04em;
		text-transform: uppercase;
		background: none;
		border: none;
		color: #a3222c;
		padding: 12px 6px;
	}

	#quiz-concentrations-remise-zero:hover,
	#quiz-concentrations-remise-zero:focus {
		text-decoration: underline;
		color: #a3222c;
	}

	#quiz-concentrations-resultat {
		width: 100%;
		min-height: 90px;
		display: flex;
		flex-direction: column;
		justify-content: center;
		text-align: left;
		padding: 6px 0 6px 22px;
		border-left: 3px solid transparent;
	}

	#quiz-concentrations-resultat.reussi {
		border-left-color: #146c2e;
	}

	#quiz-concentrations-resultat.echec {
		border-left-color: #a3222c;
	}

	#quiz-concentrations-resultat-titre {
		font-family: Montserrat, Lato, sans-serif;
		font-size: 0.85em;
		font-weight: 700;
		letter-spacing: 0.08em;
		text-transform: uppercase;
	}

	#quiz-concentrations-resultat-titre .quiz-concentrations-no-uppercase {
		text-transform: none;
	}

	#quiz-concentrations-resultat.reussi #quiz-concentrations-resultat-titre {
		color: #146c2e;
	}

	#quiz-concentrations-resultat.echec #quiz-concentrations-resultat-titre {
		color: #a3222c;
	}

	#quiz-concentrations-explication {
		font-size: 1em;
		line-height: 1.55;
		color: #444;
		margin-top: 8px;
	}

	.quiz-concentrations-chaine {
		display: block;
		width: max-content;
		max-width: 100%;
		margin: 0 auto;
		overflow-x: auto;
		white-space: nowrap;
		padding: 10px 0;
	}

	.quiz-concentrations-fraction {
		display: inline-flex;
		flex-direction: column;
		align-items: center;
		vertical-align: middle;
		font-size: 0.92em;
		margin: 0 6px;
	}

	.quiz-concentrations-fraction-num,
	.quiz-concentrations-fraction-den {
		padding: 1px 4px;
		white-space: nowrap;
	}

	.quiz-concentrations-fraction-num {
		border-bottom: 1px solid #444;
	}

	@media (max-width: 480px)
	{
		#quiz-concentrations-enonce {
			font-size: 1em;
		}

		.quiz-concentrations-unite {
			font-size: 1.1em;
		}

		#quiz-concentrations-score-wrap {
			position: static;
			display: block;
			text-align: center;
			margin-bottom: 8px;
		}
	}

	@media (prefers-reduced-motion: reduce)
	{
		#quiz-concentrations * {
			transition: none !important;
		}
	}
</style>

<div id="quiz-concentrations" class="page-contenu">
<div class="container">

    <div class="page-titre"><?= $quiz['titre']; ?></div>

    <div class="col-12">

		<div id="quiz-concentrations-zone">

			<p class="quiz-concentrations-description"><?= $quiz['description']; ?></p>

			<div id="quiz-concentrations-mesure">
				<div class="quiz-concentrations-filet"></div>

				<div id="quiz-concentrations-score-wrap">
					<div id="quiz-concentrations-score">Aucun essai pour l'instant</div>
				</div>

				<div id="quiz-concentrations-contenu">
					<p id="quiz-concentrations-enonce">&nbsp;</p>

					<div id="quiz-concentrations-donnees"></div>
				</div>

				<div class="quiz-concentrations-filet"></div>
			</div>

			<div id="quiz-concentrations-reponse">
				<input type="text" id="quiz-concentrations-input-mantisse" class="form-control" inputmode="decimal" autocomplete="off" aria-label="Mantisse">
				<span class="quiz-concentrations-fois-dix">× 10</span>
				<span id="quiz-concentrations-exposant-wrap">
					<input type="text" id="quiz-concentrations-input-exposant" class="form-control" inputmode="numeric" autocomplete="off" aria-label="Exposant">
				</span>
				<span id="quiz-concentrations-unite-reponse" class="quiz-concentrations-unite"></span>
			</div>

			<div class="text-center mt-5">
				<button type="button" id="quiz-concentrations-envoyer" class="btn btn-primary mt-2" disabled>Envoyer</button>
			</div>

			<div id="quiz-concentrations-resultat" class="d-none mt-5">
				<div id="quiz-concentrations-resultat-titre"></div>
				<div id="quiz-concentrations-explication"></div>
			</div>

			<div id="quiz-concentrations-suivant-wrap" class="text-center mt-5 d-none">
				<button type="button" id="quiz-concentrations-suivant" class="btn btn-primary">Suivant</button>
				<button type="button" id="quiz-concentrations-remise-zero" class="ms-2">Remise à zéro</button>
			</div>

		</div> <!-- #quiz-concentrations-zone -->

        <div class="space"></div>

    </div> <!-- .col-12 -->
</div> <!-- .container -->
</div> <!-- #quiz-concentrations -->

<script src="<?= base_url() . 'assets/js/quiz_concentrations.js?' . date('U'); ?>"></script>

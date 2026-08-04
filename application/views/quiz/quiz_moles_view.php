<?
/* ----------------------------------------------------------------------------
 *
 * Quiz > Moles, molécules, atomes, protons, électrons
 *
 * ---------------------------------------------------------------------------- */ ?>

<style>
	@import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,340;9..144,500&display=swap');

	#quiz-moles a {
		text-decoration: none;
	}

	#quiz-moles .page-titre {
		margin-top: -30px;
	}

	#quiz-moles-zone {
		box-sizing: border-box;
		margin-top: 24px;
		border: 1px solid #dbdcdd;
		border-radius: 8px;
		padding: 48px 56px;
	}

	#quiz-moles p.quiz-moles-description {
		margin-top: 0;
		font-size: 1.05em;
		color: #444;
		text-align: center;
	}

	#quiz-moles-mesure {
		position: relative;
		margin-top: 44px;
	}

	.quiz-moles-filet {
		border-top: 1px solid #d7d3c8;
	}

	#quiz-moles-score-wrap {
		position: absolute;
		top: -13px;
		right: 0;
	}

	#quiz-moles-score {
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

	#quiz-moles-contenu {
		padding: 32px 0 8px;
	}

	#quiz-moles-enonce {
		text-align: center;
		font-size: 1.15em;
		line-height: 1.5;
		color: #1c1c1c;
		max-width: 640px;
		margin: 0 auto;
	}

	.quiz-moles-unite {
		font-family: Montserrat, Lato, sans-serif;
		font-weight: 700;
		font-size: 1.4em;
		color: #1c1c1c;
	}

	#quiz-moles-donnees {
		margin: 24px auto 20px;
		max-width: 640px;
		text-align: center;
		font-size: 1.15em;
		line-height: 1.5;
		color: #1c1c1c;
	}

	#quiz-moles-donnees .quiz-moles-donnee {
		display: inline-block;
		margin: 4px 12px;
	}

	#quiz-moles-reponse {
		margin-top: 40px;
		display: flex;
		align-items: baseline;
		justify-content: center;
		gap: 10px;
		flex-wrap: wrap;
	}

	#quiz-moles-input-mantisse {
		width: 120px;
		text-align: center;
		font-family: 'Fraunces', Georgia, serif;
		font-size: 1.4em;
	}

	.quiz-moles-fois-dix {
		font-family: 'Fraunces', Georgia, serif;
		font-size: 1.4em;
		color: #1c1c1c;
	}

	#quiz-moles-exposant-wrap {
		align-self: flex-start;
		transform: translateY(-10px);
	}

	#quiz-moles-input-exposant {
		width: 74px;
		text-align: center;
		font-family: 'Fraunces', Georgia, serif;
		font-size: 0.95em;
	}

	#quiz-moles-envoyer,
	#quiz-moles-suivant {
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

	#quiz-moles-envoyer:hover,
	#quiz-moles-envoyer:focus,
	#quiz-moles-suivant:hover,
	#quiz-moles-suivant:focus {
		background: #a3222c;
		border-color: #a3222c;
	}

	#quiz-moles-envoyer:disabled {
		background: #d22630;
		border-color: #d22630;
		opacity: 0.3;
	}

	#quiz-moles-remise-zero {
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

	#quiz-moles-remise-zero:hover,
	#quiz-moles-remise-zero:focus {
		text-decoration: underline;
		color: #a3222c;
	}

	#quiz-moles-resultat {
		width: 100%;
		min-height: 90px;
		display: flex;
		flex-direction: column;
		justify-content: center;
		text-align: left;
		padding: 6px 0 6px 22px;
		border-left: 3px solid transparent;
	}

	#quiz-moles-resultat.reussi {
		border-left-color: #146c2e;
	}

	#quiz-moles-resultat.echec {
		border-left-color: #a3222c;
	}

	#quiz-moles-resultat-titre {
		font-family: Montserrat, Lato, sans-serif;
		font-size: 0.85em;
		font-weight: 700;
		letter-spacing: 0.08em;
		text-transform: uppercase;
	}

	#quiz-moles-resultat-titre .quiz-moles-no-uppercase {
		text-transform: none;
	}

	#quiz-moles-resultat.reussi #quiz-moles-resultat-titre {
		color: #146c2e;
	}

	#quiz-moles-resultat.echec #quiz-moles-resultat-titre {
		color: #a3222c;
	}

	#quiz-moles-explication {
		font-size: 1em;
		line-height: 1.55;
		color: #444;
		margin-top: 8px;
	}

	.quiz-moles-chaine {
		display: block;
		width: max-content;
		max-width: 100%;
		margin: 0 auto;
		overflow-x: auto;
		white-space: nowrap;
		padding: 10px 0;
	}

	.quiz-moles-fraction {
		display: inline-flex;
		flex-direction: column;
		align-items: center;
		vertical-align: middle;
		font-size: 0.92em;
		margin: 0 6px;
	}

	.quiz-moles-fraction-num,
	.quiz-moles-fraction-den {
		padding: 1px 4px;
		white-space: nowrap;
	}

	.quiz-moles-fraction-num {
		border-bottom: 1px solid #444;
	}

	@media (max-width: 480px)
	{
		#quiz-moles-enonce {
			font-size: 1em;
		}

		.quiz-moles-unite {
			font-size: 1.1em;
		}

		#quiz-moles-score-wrap {
			position: static;
			display: block;
			text-align: center;
			margin-bottom: 8px;
		}
	}

	@media (prefers-reduced-motion: reduce)
	{
		#quiz-moles * {
			transition: none !important;
		}
	}
</style>

<div id="quiz-moles" class="page-contenu">
<div class="container">

    <div class="page-titre"><?= $quiz['titre']; ?></div>

    <div class="col-12">

		<div id="quiz-moles-zone">

			<p class="quiz-moles-description"><?= $quiz['description']; ?></p>

			<div id="quiz-moles-mesure">
				<div class="quiz-moles-filet"></div>

				<div id="quiz-moles-score-wrap">
					<div id="quiz-moles-score">Aucun essai pour l'instant</div>
				</div>

				<div id="quiz-moles-contenu">
					<p id="quiz-moles-enonce">&nbsp;</p>

					<div id="quiz-moles-donnees"></div>
				</div>

				<div class="quiz-moles-filet"></div>
			</div>

			<div id="quiz-moles-reponse">
				<input type="text" id="quiz-moles-input-mantisse" class="form-control" inputmode="decimal" autocomplete="off" aria-label="Mantisse">
				<span class="quiz-moles-fois-dix">× 10</span>
				<span id="quiz-moles-exposant-wrap">
					<input type="text" id="quiz-moles-input-exposant" class="form-control" inputmode="numeric" autocomplete="off" aria-label="Exposant">
				</span>
				<span id="quiz-moles-unite-reponse" class="quiz-moles-unite"></span>
			</div>

			<div class="text-center mt-5">
				<button type="button" id="quiz-moles-envoyer" class="btn btn-primary mt-2" disabled>Envoyer</button>
			</div>

			<div id="quiz-moles-resultat" class="d-none mt-5">
				<div id="quiz-moles-resultat-titre"></div>
				<div id="quiz-moles-explication"></div>
			</div>

			<div id="quiz-moles-suivant-wrap" class="text-center mt-5 d-none">
				<button type="button" id="quiz-moles-suivant" class="btn btn-primary">Suivant</button>
				<button type="button" id="quiz-moles-remise-zero" class="ms-2">Remise à zéro</button>
			</div>

		</div> <!-- #quiz-moles-zone -->

        <div class="space"></div>

    </div> <!-- .col-12 -->
</div> <!-- .container -->
</div> <!-- #quiz-moles -->

<script src="<?= base_url() . 'assets/js/quiz_moles.js?' . date('U'); ?>"></script>

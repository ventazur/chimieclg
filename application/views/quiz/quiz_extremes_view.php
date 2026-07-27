<?
/* ----------------------------------------------------------------------------
 *
 * Quiz > Moyenne et incertitude par la méthode des extrêmes
 *
 * ---------------------------------------------------------------------------- */ ?>

<style>
	@import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,340;9..144,500&display=swap');

	#quiz-extremes a {
		text-decoration: none;
	}

	#quiz-extremes .page-titre {
		margin-top: -30px;
	}

	#quiz-extremes-zone {
		box-sizing: border-box;
		margin-top: 24px;
		border: 1px solid #dbdcdd;
		border-radius: 8px;
		padding: 48px 56px;
	}

	#quiz-extremes p.quiz-extremes-description {
		margin-top: 0;
		font-size: 1.05em;
		color: #444;
		text-align: center;
	}

	#quiz-extremes-mesure {
		position: relative;
		margin-top: 44px;
	}

	.quiz-extremes-filet {
		border-top: 1px solid #d7d3c8;
	}

	#quiz-extremes-score-wrap {
		position: absolute;
		top: -13px;
		right: 0;
	}

	#quiz-extremes-score {
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

	#quiz-extremes-mesures {
		display: flex;
		justify-content: center;
		flex-wrap: wrap;
		gap: 28px;
		padding: 32px 12px;
	}

	.quiz-extremes-mesure-item {
		font-family: 'Fraunces', Georgia, serif;
		font-optical-sizing: auto;
		font-weight: 340;
		font-size: 1.7em;
		color: #1c1c1c;
		text-align: center;
	}

	.quiz-extremes-caption {
		margin-top: 6px;
		font-family: Montserrat, Lato, sans-serif;
		font-size: 0.76em;
		font-weight: 600;
		letter-spacing: 0.12em;
		text-transform: uppercase;
		color: #8a8d90;
		text-align: center;
	}

	#quiz-extremes-reponse {
		margin-top: 48px;
		display: flex;
		align-items: baseline;
		justify-content: center;
		flex-wrap: wrap;
		gap: 10px;
	}

	.quiz-extremes-input {
		width: 110px;
		text-align: center;
		font-family: 'Fraunces', Georgia, serif;
		font-size: 1.3em;
	}

	.quiz-extremes-pm {
		font-family: 'Fraunces', Georgia, serif;
		font-size: 1.3em;
		color: #1c1c1c;
	}

	#quiz-extremes-envoyer,
	#quiz-extremes-suivant {
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

	#quiz-extremes-envoyer:hover,
	#quiz-extremes-envoyer:focus,
	#quiz-extremes-suivant:hover,
	#quiz-extremes-suivant:focus {
		background: #a3222c;
		border-color: #a3222c;
	}

	#quiz-extremes-envoyer:disabled {
		background: #d22630;
		border-color: #d22630;
		opacity: 0.3;
	}

	#quiz-extremes-remise-zero {
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

	#quiz-extremes-remise-zero:hover,
	#quiz-extremes-remise-zero:focus {
		text-decoration: underline;
		color: #a3222c;
	}

	#quiz-extremes-resultat {
		max-width: 620px;
		margin-left: auto;
		margin-right: auto;
		min-height: 90px;
		display: flex;
		flex-direction: column;
		justify-content: center;
		text-align: left;
		padding: 6px 0 6px 22px;
		border-left: 3px solid transparent;
	}

	#quiz-extremes-resultat.reussi {
		border-left-color: #146c2e;
	}

	#quiz-extremes-resultat.echec {
		border-left-color: #a3222c;
	}

	#quiz-extremes-resultat-titre {
		font-family: Montserrat, Lato, sans-serif;
		font-size: 0.85em;
		font-weight: 700;
		letter-spacing: 0.08em;
		text-transform: uppercase;
	}

	#quiz-extremes-resultat.reussi #quiz-extremes-resultat-titre {
		color: #146c2e;
	}

	#quiz-extremes-resultat.echec #quiz-extremes-resultat-titre {
		color: #a3222c;
	}

	#quiz-extremes-explication {
		font-size: 1em;
		line-height: 1.55;
		color: #444;
		margin-top: 8px;
	}

	@media (max-width: 480px)
	{
		#quiz-extremes-mesures {
			gap: 18px;
		}

		.quiz-extremes-mesure-item {
			font-size: 1.3em;
		}

		#quiz-extremes-score-wrap {
			position: static;
			display: block;
			text-align: center;
			margin-bottom: 8px;
		}
	}
</style>

<div id="quiz-extremes" class="page-contenu">
<div class="container">

    <div class="page-titre"><?= $quiz['titre']; ?></div>

    <div class="col-12">

		<div id="quiz-extremes-zone">

			<p class="quiz-extremes-description"><?= $quiz['description']; ?></p>

			<div id="quiz-extremes-mesure">
				<div class="quiz-extremes-filet"></div>

				<div id="quiz-extremes-score-wrap">
					<div id="quiz-extremes-score">Aucun essai pour l'instant</div>
				</div>

				<div id="quiz-extremes-mesures">&nbsp;</div>

				<div class="quiz-extremes-filet"></div>
				<div class="quiz-extremes-caption">Calculez la moyenne et son incertitude par la méthode des extrêmes</div>
			</div>

			<div id="quiz-extremes-reponse">
				<input type="text" id="quiz-extremes-input-moyenne" class="quiz-extremes-input form-control" inputmode="decimal" autocomplete="off" aria-label="Moyenne">
				<span class="quiz-extremes-pm">±</span>
				<input type="text" id="quiz-extremes-input-incertitude" class="quiz-extremes-input form-control" inputmode="decimal" autocomplete="off" aria-label="Incertitude">
				<span id="quiz-extremes-unite-reponse" class="quiz-extremes-pm"></span>
			</div>

			<div class="text-center mt-5">
				<button type="button" id="quiz-extremes-envoyer" class="btn btn-primary" disabled>Envoyer</button>
			</div>

			<div id="quiz-extremes-resultat" class="d-none mt-5">
				<div id="quiz-extremes-resultat-titre"></div>
				<div id="quiz-extremes-explication"></div>
			</div>

			<div id="quiz-extremes-suivant-wrap" class="text-center mt-5 d-none">
				<button type="button" id="quiz-extremes-suivant" class="btn btn-primary">Suivant</button>
				<button type="button" id="quiz-extremes-remise-zero" class="ms-2">Remise à zéro</button>
			</div>

		</div> <!-- #quiz-extremes-zone -->

        <div class="space"></div>

    </div> <!-- .col-12 -->
</div> <!-- .container -->
</div> <!-- #quiz-extremes -->

<script src="<?= base_url() . 'assets/js/quiz_extremes.js?' . date('U'); ?>"></script>

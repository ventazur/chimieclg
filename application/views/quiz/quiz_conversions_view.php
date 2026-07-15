<?
/* ----------------------------------------------------------------------------
 *
 * Quiz > Conversion d'unités
 *
 * ---------------------------------------------------------------------------- */ ?>

<style>
	@import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,340;9..144,500&display=swap');

	#quiz-conversions a {
		text-decoration: none;
	}

	#quiz-conversions .page-titre {
		margin-top: -30px;
	}

	#quiz-conversions-zone {
		box-sizing: border-box;
		margin-top: 24px;
		border: 1px solid #dbdcdd;
		border-radius: 8px;
		padding: 48px 56px;
	}

	#quiz-conversions p.quiz-conversions-description {
		margin-top: 0;
		font-size: 1.05em;
		color: #444;
		text-align: center;
	}

	#quiz-conversions-mesure {
		position: relative;
		margin-top: 44px;
	}

	.quiz-conversions-filet {
		border-top: 1px solid #d7d3c8;
	}

	#quiz-conversions-score-wrap {
		position: absolute;
		top: -13px;
		right: 0;
	}

	#quiz-conversions-score {
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

	#quiz-conversions-valeur {
		text-align: center;
		padding: 32px 0 40px;
	}

	#quiz-conversions-depart-nombre {
		font-family: 'Fraunces', Georgia, serif;
		font-optical-sizing: auto;
		font-weight: 340;
		font-size: 2.3em;
		color: #1c1c1c;
	}

	#quiz-conversions-fleche {
		margin: 0 18px;
		font-size: 1.8em;
		color: #8a8d90;
	}

	.quiz-conversions-unite {
		font-family: Montserrat, Lato, sans-serif;
		font-weight: 700;
		font-size: 1.6em;
		color: #1c1c1c;
	}

	#quiz-conversions-indice {
		margin-top: 24px;
		text-align: center;
		font-size: 0.9em;
		font-style: italic;
		color: #8a8d90;
	}

	#quiz-conversions-reponse {
		margin-top: 48px;
		display: flex;
		align-items: baseline;
		justify-content: center;
		gap: 10px;
		flex-wrap: wrap;
	}

	#quiz-conversions-input-mantisse {
		width: 120px;
		text-align: center;
		font-family: 'Fraunces', Georgia, serif;
		font-size: 1.4em;
	}

	.quiz-conversions-fois-dix {
		font-family: 'Fraunces', Georgia, serif;
		font-size: 1.4em;
		color: #1c1c1c;
	}

	#quiz-conversions-exposant-wrap {
		align-self: flex-start;
		transform: translateY(-10px);
	}

	#quiz-conversions-input-exposant {
		width: 74px;
		text-align: center;
		font-family: 'Fraunces', Georgia, serif;
		font-size: 0.95em;
	}

	#quiz-conversions-envoyer,
	#quiz-conversions-suivant {
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

	#quiz-conversions-envoyer:hover,
	#quiz-conversions-envoyer:focus,
	#quiz-conversions-suivant:hover,
	#quiz-conversions-suivant:focus {
		background: #a3222c;
		border-color: #a3222c;
	}

	#quiz-conversions-envoyer:disabled {
		background: #d22630;
		border-color: #d22630;
		opacity: 0.3;
	}

	#quiz-conversions-remise-zero {
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

	#quiz-conversions-remise-zero:hover,
	#quiz-conversions-remise-zero:focus {
		text-decoration: underline;
		color: #a3222c;
	}

	#quiz-conversions-resultat {
		max-width: 560px;
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

	#quiz-conversions-resultat.reussi {
		border-left-color: #146c2e;
	}

	#quiz-conversions-resultat.echec {
		border-left-color: #a3222c;
	}

	#quiz-conversions-resultat-titre {
		font-family: Montserrat, Lato, sans-serif;
		font-size: 0.85em;
		font-weight: 700;
		letter-spacing: 0.08em;
		text-transform: uppercase;
	}

	#quiz-conversions-resultat.reussi #quiz-conversions-resultat-titre {
		color: #146c2e;
	}

	#quiz-conversions-resultat.echec #quiz-conversions-resultat-titre {
		color: #a3222c;
	}

	#quiz-conversions-explication {
		font-size: 1em;
		line-height: 1.55;
		color: #444;
		margin-top: 8px;
	}

	@media (max-width: 480px)
	{
		#quiz-conversions-depart-nombre {
			font-size: 1.6em;
		}

		.quiz-conversions-unite {
			font-size: 1.2em;
		}

		#quiz-conversions-score-wrap {
			position: static;
			display: block;
			text-align: center;
			margin-bottom: 8px;
		}
	}
</style>

<div id="quiz-conversions" class="page-contenu">
<div class="container">

    <div class="page-titre"><?= $quiz['titre']; ?></div>

    <div class="col-12">

		<div id="quiz-conversions-zone">

			<p class="quiz-conversions-description"><?= $quiz['description']; ?></p>

			<div id="quiz-conversions-mesure">
				<div class="quiz-conversions-filet"></div>

				<div id="quiz-conversions-score-wrap">
					<div id="quiz-conversions-score">Aucun essai pour l'instant</div>
				</div>

				<div id="quiz-conversions-valeur">
					<span id="quiz-conversions-depart-nombre">&nbsp;</span>
					<span id="quiz-conversions-depart-unite" class="quiz-conversions-unite"></span>
					<span id="quiz-conversions-fleche">→</span>
					<span id="quiz-conversions-arrivee" class="quiz-conversions-unite">&nbsp;</span>
				</div>

				<div class="quiz-conversions-filet"></div>
			</div>

			<div id="quiz-conversions-indice" class="d-none">Indice : 1 cm³ = 1 mL</div>

			<div id="quiz-conversions-reponse">
				<input type="text" id="quiz-conversions-input-mantisse" class="form-control" inputmode="decimal" autocomplete="off" aria-label="Mantisse">
				<span class="quiz-conversions-fois-dix">× 10</span>
				<span id="quiz-conversions-exposant-wrap">
					<input type="text" id="quiz-conversions-input-exposant" class="form-control" inputmode="numeric" autocomplete="off" aria-label="Exposant">
				</span>
				<span id="quiz-conversions-unite-reponse" class="quiz-conversions-unite"></span>
			</div>

			<div class="text-center mt-5">
				<button type="button" id="quiz-conversions-envoyer" class="btn btn-primary mt-2" disabled>Envoyer</button>
			</div>

			<div id="quiz-conversions-resultat" class="d-none mt-5">
				<div id="quiz-conversions-resultat-titre"></div>
				<div id="quiz-conversions-explication"></div>
			</div>

			<div id="quiz-conversions-suivant-wrap" class="text-center mt-5 d-none">
				<button type="button" id="quiz-conversions-suivant" class="btn btn-primary">Suivant</button>
				<button type="button" id="quiz-conversions-remise-zero" class="ms-2">Remise à zéro</button>
			</div>

		</div> <!-- #quiz-conversions-zone -->

        <div class="space"></div>

    </div> <!-- .col-12 -->
</div> <!-- .container -->
</div> <!-- #quiz-conversions -->

<script src="<?= base_url() . 'assets/js/quiz_conversions.js?' . date('U'); ?>"></script>

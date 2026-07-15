<?
/* ----------------------------------------------------------------------------
 *
 * Quiz > Nombre d'états quantiques
 *
 * ---------------------------------------------------------------------------- */ ?>

<style>
	@import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,340;9..144,500&display=swap');

	#quiz-eq a {
		text-decoration: none;
	}

	#quiz-eq .page-titre {
		margin-top: -30px;
	}

	#quiz-eq-zone {
		box-sizing: border-box;
		margin-top: 24px;
		border: 1px solid #dbdcdd;
		border-radius: 8px;
		padding: 48px 56px;
	}

	#quiz-eq p.quiz-eq-description {
		margin-top: 0;
		font-size: 1.05em;
		color: #444;
		text-align: center;
	}

	#quiz-eq-mesure {
		position: relative;
		margin-top: 44px;
	}

	.quiz-eq-filet {
		border-top: 1px solid #d7d3c8;
	}

	#quiz-eq-score-wrap {
		position: absolute;
		top: -13px;
		right: 0;
	}

	#quiz-eq-score {
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

	#quiz-eq-valeurs {
		display: flex;
		justify-content: center;
		gap: 36px;
		flex-wrap: wrap;
		padding: 40px 0;
	}

	.quiz-eq-valeur {
		font-family: 'Fraunces', Georgia, serif;
		font-optical-sizing: auto;
		font-weight: 340;
		font-size: 2.6em;
		color: #1c1c1c;
	}

	.quiz-eq-caption {
		margin-top: 6px;
		font-family: Montserrat, Lato, sans-serif;
		font-size: 0.76em;
		font-weight: 600;
		letter-spacing: 0.12em;
		text-transform: uppercase;
		color: #8a8d90;
		text-align: center;
	}

	#quiz-eq-reponse {
		margin-top: 32px;
	}

	#quiz-eq-input {
		max-width: 140px;
		margin: 0 auto;
		text-align: center;
		font-family: 'Fraunces', Georgia, serif;
		font-size: 1.4em;
	}

	#quiz-eq-envoyer,
	#quiz-eq-suivant {
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

	#quiz-eq-envoyer:hover,
	#quiz-eq-envoyer:focus,
	#quiz-eq-suivant:hover,
	#quiz-eq-suivant:focus {
		background: #a3222c;
		border-color: #a3222c;
	}

	#quiz-eq-envoyer:disabled {
		background: #d22630;
		border-color: #d22630;
		opacity: 0.3;
	}

	#quiz-eq-remise-zero {
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

	#quiz-eq-remise-zero:hover,
	#quiz-eq-remise-zero:focus {
		text-decoration: underline;
		color: #a3222c;
	}

	#quiz-eq-resultat {
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

	#quiz-eq-resultat.reussi {
		border-left-color: #146c2e;
	}

	#quiz-eq-resultat.echec {
		border-left-color: #a3222c;
	}

	#quiz-eq-resultat-titre {
		font-family: Montserrat, Lato, sans-serif;
		font-size: 0.85em;
		font-weight: 700;
		letter-spacing: 0.08em;
		text-transform: uppercase;
	}

	#quiz-eq-resultat.reussi #quiz-eq-resultat-titre {
		color: #146c2e;
	}

	#quiz-eq-resultat.echec #quiz-eq-resultat-titre {
		color: #a3222c;
	}

	#quiz-eq-explication {
		font-size: 1em;
		line-height: 1.55;
		color: #444;
		margin-top: 8px;
	}

	@media (max-width: 480px)
	{
		.quiz-eq-valeur {
			font-size: 1.8em;
		}

		#quiz-eq-score-wrap {
			position: static;
			display: block;
			text-align: center;
			margin-bottom: 8px;
		}
	}
</style>

<div id="quiz-eq" class="page-contenu">
<div class="container">

    <div class="page-titre"><?= $quiz['titre']; ?></div>

    <div class="col-12">

		<div id="quiz-eq-zone">

			<p class="quiz-eq-description"><?= $quiz['description']; ?></p>

			<div id="quiz-eq-mesure">
				<div class="quiz-eq-filet"></div>

				<div id="quiz-eq-score-wrap">
					<div id="quiz-eq-score">Aucun essai pour l'instant</div>
				</div>

				<div id="quiz-eq-valeurs">&nbsp;</div>

				<div class="quiz-eq-filet"></div>
				<div class="quiz-eq-caption">Combien d'états quantiques ?</div>
			</div>

			<div id="quiz-eq-reponse" class="text-center">
				<input type="number" id="quiz-eq-input" class="form-control" min="0" step="1" inputmode="numeric" autocomplete="off" aria-label="Nombre d'états quantiques">
			</div>

			<div class="text-center mt-4">
				<button type="button" id="quiz-eq-envoyer" class="btn btn-primary mt-2" disabled>Envoyer</button>
			</div>

			<div id="quiz-eq-resultat" class="d-none mt-5">
				<div id="quiz-eq-resultat-titre"></div>
				<div id="quiz-eq-explication"></div>
			</div>

			<div id="quiz-eq-suivant-wrap" class="text-center mt-5 d-none">
				<button type="button" id="quiz-eq-suivant" class="btn btn-primary">Suivant</button>
				<button type="button" id="quiz-eq-remise-zero" class="ms-2">Remise à zéro</button>
			</div>

		</div> <!-- #quiz-eq-zone -->

        <div class="space"></div>

    </div> <!-- .col-12 -->
</div> <!-- .container -->
</div> <!-- #quiz-eq -->

<script src="<?= base_url() . 'assets/js/quiz_eq.js?' . date('U'); ?>"></script>

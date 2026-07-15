<?
/* ----------------------------------------------------------------------------
 *
 * Quiz > Cases quantiques
 *
 * ---------------------------------------------------------------------------- */ ?>

<style>
	@import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,340;9..144,500&display=swap');

	#quiz-cases a {
		text-decoration: none;
	}

	#quiz-cases .page-titre {
		margin-top: -30px;
	}

	#quiz-cases-zone {
		box-sizing: border-box;
		margin-top: 24px;
		border: 1px solid #dbdcdd;
		border-radius: 8px;
		padding: 48px 56px;
	}

	#quiz-cases p.quiz-cases-description {
		margin-top: 0;
		font-size: 1.05em;
		color: #444;
		text-align: center;
	}

	#quiz-cases-mesure {
		position: relative;
		margin-top: 44px;
	}

	.quiz-cases-filet {
		border-top: 1px solid #d7d3c8;
	}

	#quiz-cases-score-wrap {
		position: absolute;
		top: -13px;
		right: 0;
	}

	#quiz-cases-score {
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

	#quiz-cases-element {
		text-align: center;
		padding: 32px 0 8px;
	}

	#quiz-cases-element-symbole {
		font-family: 'Fraunces', Georgia, serif;
		font-optical-sizing: auto;
		font-weight: 340;
		font-size: 2.6em;
		color: #1c1c1c;
	}

	#quiz-cases-element-details {
		margin-top: 6px;
		font-family: Montserrat, Lato, sans-serif;
		font-size: 0.8em;
		font-weight: 600;
		letter-spacing: 0.08em;
		text-transform: uppercase;
		color: #8a8d90;
	}

	#quiz-cases-diagramme {
		display: flex;
		flex-direction: row;
		align-items: flex-end;
		justify-content: center;
		flex-wrap: wrap;
		gap: 26px;
		padding: 28px 12px;
		max-width: 620px;
		margin: 0 auto;
	}

	.quiz-cases-sous-couche {
		display: flex;
		flex-direction: column;
		align-items: center;
	}

	.quiz-cases-etiquette {
		margin-top: 8px;
		font-family: Montserrat, Lato, sans-serif;
		font-size: 0.82em;
		font-weight: 600;
		color: #1c1c1c;
	}

	.quiz-cases-boites {
		display: flex;
	}

	.quiz-cases-boite {
		width: 40px;
		height: 40px;
		flex-shrink: 0;
		border: 1px solid #8a8d90;
		margin-left: -1px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 1.1em;
		color: #1c1c1c;
	}

	.quiz-cases-boite:first-child {
		margin-left: 0;
	}

	#quiz-cases-questions {
		display: flex;
		flex-direction: column;
		gap: 22px;
		max-width: 620px;
		margin: 32px auto 0;
	}

	.quiz-cases-question {
		border-left: 3px solid #d7d3c8;
		padding: 4px 0 4px 18px;
	}

	.quiz-cases-contexte {
		font-family: Montserrat, Lato, sans-serif;
		font-size: 0.72em;
		font-weight: 700;
		letter-spacing: 0.08em;
		text-transform: uppercase;
		color: #1c1c1c;
	}

	.quiz-cases-contexte sup.quiz-cases-charge {
		font-size: 1.15em;
		font-weight: 800;
		vertical-align: super;
	}

	.quiz-cases-contexte .quiz-cases-symbole {
		text-transform: none;
	}

	.quiz-cases-enonce {
		margin-top: 4px;
		font-size: 1.02em;
		color: #1c1c1c;
	}

	.quiz-cases-reponse {
		margin-top: 10px;
	}

	.quiz-cases-reponse input {
		max-width: 140px;
		text-align: center;
		font-family: 'Fraunces', Georgia, serif;
		font-size: 1.15em;
	}

	.quiz-cases-question-resultat {
		margin-top: 8px;
		font-size: 0.92em;
		line-height: 1.5;
	}

	.quiz-cases-question-resultat.reussi {
		color: #146c2e;
	}

	.quiz-cases-question-resultat.echec {
		color: #a3222c;
	}

	.quiz-cases-question-explication {
		margin-top: 3px;
		color: #444;
	}

	#quiz-cases-envoyer,
	#quiz-cases-suivant {
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

	#quiz-cases-envoyer:hover,
	#quiz-cases-envoyer:focus,
	#quiz-cases-suivant:hover,
	#quiz-cases-suivant:focus {
		background: #a3222c;
		border-color: #a3222c;
	}

	#quiz-cases-envoyer:disabled {
		background: #d22630;
		border-color: #d22630;
		opacity: 0.3;
	}

	#quiz-cases-remise-zero {
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

	#quiz-cases-remise-zero:hover,
	#quiz-cases-remise-zero:focus {
		text-decoration: underline;
		color: #a3222c;
	}

	#quiz-cases-resultat-global {
		max-width: 620px;
		margin: 40px auto 0;
		text-align: center;
		font-family: Montserrat, Lato, sans-serif;
		font-size: 0.85em;
		font-weight: 700;
		letter-spacing: 0.08em;
		text-transform: uppercase;
	}

	@media (max-width: 480px)
	{
		#quiz-cases-element-symbole {
			font-size: 1.8em;
		}

		#quiz-cases-score-wrap {
			position: static;
			display: block;
			text-align: center;
			margin-bottom: 8px;
		}

		.quiz-cases-boite {
			width: 30px;
			height: 30px;
		}
	}
</style>

<div id="quiz-cases" class="page-contenu">
<div class="container">

    <div class="page-titre"><?= $quiz['titre']; ?></div>

    <div class="col-12">

		<div id="quiz-cases-zone">

			<p class="quiz-cases-description"><?= $quiz['description']; ?></p>

			<div id="quiz-cases-mesure">
				<div class="quiz-cases-filet"></div>

				<div id="quiz-cases-score-wrap">
					<div id="quiz-cases-score">Aucun essai pour l'instant</div>
				</div>

				<div id="quiz-cases-element">&nbsp;</div>

				<div id="quiz-cases-diagramme"></div>

				<div class="quiz-cases-filet"></div>
			</div>

			<div id="quiz-cases-questions"></div>

			<div class="text-center mt-4">
				<button type="button" id="quiz-cases-envoyer" class="btn btn-primary mt-2" disabled>Envoyer</button>
			</div>

			<div id="quiz-cases-resultat-global" class="d-none"></div>

			<div id="quiz-cases-suivant-wrap" class="text-center mt-5 d-none">
				<button type="button" id="quiz-cases-suivant" class="btn btn-primary">Suivant</button>
				<button type="button" id="quiz-cases-remise-zero" class="ms-2">Remise à zéro</button>
			</div>

		</div> <!-- #quiz-cases-zone -->

        <div class="space"></div>

    </div> <!-- .col-12 -->
</div> <!-- .container -->
</div> <!-- #quiz-cases -->

<script src="<?= base_url() . 'assets/js/quiz_cases.js?' . date('U'); ?>"></script>

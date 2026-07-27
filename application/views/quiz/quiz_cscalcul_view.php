<?
/* ----------------------------------------------------------------------------
 *
 * Quiz > Chiffres significatifs d'un calcul
 *
 * ---------------------------------------------------------------------------- */ ?>

<style>
	@import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,340;9..144,500&display=swap');

	#quiz-cscalcul a {
		text-decoration: none;
	}

	#quiz-cscalcul .page-titre {
		margin-top: -30px;
	}

	#quiz-cscalcul-zone {
		box-sizing: border-box;
		margin-top: 24px;
		border: 1px solid #dbdcdd;
		border-radius: 8px;
		padding: 48px 56px;
	}

	#quiz-cscalcul p.quiz-cscalcul-description {
		margin-top: 0;
		font-size: 1.05em;
		color: #444;
		text-align: center;
	}

	#quiz-cscalcul-mesure {
		position: relative;
		margin-top: 44px;
	}

	.quiz-cscalcul-filet {
		border-top: 1px solid #d7d3c8;
	}

	#quiz-cscalcul-score-wrap {
		position: absolute;
		top: -13px;
		right: 0;
	}

	#quiz-cscalcul-score {
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

	#quiz-cscalcul-equation {
		font-family: 'Fraunces', Georgia, serif;
		font-optical-sizing: auto;
		font-weight: 340;
		font-size: 2.4em;
		line-height: 1.3;
		color: #1c1c1c;
		text-align: center;
		padding: 32px 12px 8px;
	}

	#quiz-cscalcul-equation sup {
		font-size: 0.55em;
	}

	.quiz-cscalcul-caption {
		margin-top: 6px;
		font-family: Montserrat, Lato, sans-serif;
		font-size: 0.76em;
		font-weight: 600;
		letter-spacing: 0.12em;
		text-transform: uppercase;
		color: #8a8d90;
		text-align: center;
	}

	#quiz-cscalcul-choix {
		display: flex;
		justify-content: center;
		flex-wrap: wrap;
		gap: 16px;
		margin-top: 40px;
	}

	.quiz-cscalcul-choix {
		width: 56px;
		height: 56px;
		padding: 0;
		border-radius: 50%;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		font-family: 'Fraunces', Georgia, serif;
		font-weight: 500;
		font-size: 1.3em;
		transition: transform 0.12s ease, border-color 0.12s ease;
	}

	.quiz-cscalcul-choix.btn-outline-dark {
		background: transparent;
		color: #1c1c1c;
		border: 1.5px solid #c9c6bc;
	}

	.quiz-cscalcul-choix.btn-outline-dark:hover {
		border-color: #1c1c1c;
	}

	.quiz-cscalcul-choix.btn-dark {
		background: #1c1c1c;
		color: #fdfcf9;
		border: 1.5px solid #1c1c1c;
	}

	.quiz-cscalcul-choix.btn-success {
		background: #fdfcf9;
		color: #146c2e;
		border: 1.5px solid #146c2e;
	}

	.quiz-cscalcul-choix.btn-danger {
		background: #fdfcf9;
		color: #a3222c;
		border: 1.5px solid #a3222c;
	}

	.quiz-cscalcul-choix:active {
		transform: scale(0.92);
	}

	.quiz-cscalcul-choix:disabled {
		opacity: 0.5;
	}

	#quiz-cscalcul-envoyer,
	#quiz-cscalcul-suivant {
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

	#quiz-cscalcul-envoyer:hover,
	#quiz-cscalcul-envoyer:focus,
	#quiz-cscalcul-suivant:hover,
	#quiz-cscalcul-suivant:focus {
		background: #a3222c;
		border-color: #a3222c;
	}

	#quiz-cscalcul-envoyer:disabled {
		background: #d22630;
		border-color: #d22630;
		opacity: 0.3;
	}

	#quiz-cscalcul-remise-zero {
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

	#quiz-cscalcul-remise-zero:hover,
	#quiz-cscalcul-remise-zero:focus {
		text-decoration: underline;
		color: #a3222c;
	}

	#quiz-cscalcul-resultat {
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

	#quiz-cscalcul-resultat.reussi {
		border-left-color: #146c2e;
	}

	#quiz-cscalcul-resultat.echec {
		border-left-color: #a3222c;
	}

	#quiz-cscalcul-resultat-titre {
		font-family: Montserrat, Lato, sans-serif;
		font-size: 0.85em;
		font-weight: 700;
		letter-spacing: 0.08em;
		text-transform: uppercase;
	}

	#quiz-cscalcul-resultat.reussi #quiz-cscalcul-resultat-titre {
		color: #146c2e;
	}

	#quiz-cscalcul-resultat.echec #quiz-cscalcul-resultat-titre {
		color: #a3222c;
	}

	#quiz-cscalcul-reponses {
		font-size: 1em;
		line-height: 1.55;
		color: #1c1c1c;
		margin-top: 8px;
	}

	#quiz-cscalcul-reponses strong {
		font-size: 1.25em;
	}

	#quiz-cscalcul-explication {
		font-size: 1em;
		line-height: 1.55;
		color: #444;
		margin-top: 8px;
	}

	@media (max-width: 480px)
	{
		#quiz-cscalcul-equation {
			font-size: 1.5em;
		}

		.quiz-cscalcul-choix {
			width: 48px;
			height: 48px;
			font-size: 1.1em;
		}

		#quiz-cscalcul-score-wrap {
			position: static;
			display: block;
			text-align: center;
			margin-bottom: 8px;
		}
	}

	@media (prefers-reduced-motion: reduce)
	{
		.quiz-cscalcul-choix {
			transition: none;
		}
	}
</style>

<div id="quiz-cscalcul" class="page-contenu">
<div class="container">

    <div class="page-titre"><?= $quiz['titre']; ?></div>

    <div class="col-12">

		<div id="quiz-cscalcul-zone">

			<p class="quiz-cscalcul-description"><?= $quiz['description']; ?></p>

			<div id="quiz-cscalcul-mesure">
				<div class="quiz-cscalcul-filet"></div>

				<div id="quiz-cscalcul-score-wrap">
					<div id="quiz-cscalcul-score">Aucun essai pour l'instant</div>
				</div>

				<div id="quiz-cscalcul-equation">&nbsp;</div>

				<div class="quiz-cscalcul-filet"></div>
				<div class="quiz-cscalcul-caption">Combien de chiffres significatifs la réponse doit-elle avoir ?</div>
			</div>

			<div id="quiz-cscalcul-choix" role="group" aria-label="Choix du nombre de chiffres significatifs">
				<button type="button" class="quiz-cscalcul-choix btn btn-outline-dark" data-valeur="1">1</button>
				<button type="button" class="quiz-cscalcul-choix btn btn-outline-dark" data-valeur="2">2</button>
				<button type="button" class="quiz-cscalcul-choix btn btn-outline-dark" data-valeur="3">3</button>
				<button type="button" class="quiz-cscalcul-choix btn btn-outline-dark" data-valeur="4">4</button>
				<button type="button" class="quiz-cscalcul-choix btn btn-outline-dark" data-valeur="5">5</button>
				<button type="button" class="quiz-cscalcul-choix btn btn-outline-dark" data-valeur="6">6</button>
			</div>

			<div class="text-center mt-5">
				<button type="button" id="quiz-cscalcul-envoyer" class="btn btn-primary" disabled>Envoyer</button>
			</div>

			<div id="quiz-cscalcul-resultat" class="d-none mt-5">
				<div id="quiz-cscalcul-resultat-titre"></div>
				<div id="quiz-cscalcul-reponses"></div>
				<div id="quiz-cscalcul-explication"></div>
			</div>

			<div id="quiz-cscalcul-suivant-wrap" class="text-center mt-5 d-none">
				<button type="button" id="quiz-cscalcul-suivant" class="btn btn-primary">Suivant</button>
				<button type="button" id="quiz-cscalcul-remise-zero" class="ms-2">Remise à zéro</button>
			</div>

		</div> <!-- #quiz-cscalcul-zone -->

        <div class="space"></div>

    </div> <!-- .col-12 -->
</div> <!-- .container -->
</div> <!-- #quiz-cscalcul -->

<script src="<?= base_url() . 'assets/js/quiz_cscalcul.js?' . date('U'); ?>"></script>

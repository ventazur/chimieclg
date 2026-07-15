<?
/* ----------------------------------------------------------------------------
 *
 * Quiz > Fonctions organiques
 *
 * ---------------------------------------------------------------------------- */ ?>

<style>
	@import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,340;9..144,500&display=swap');

	#quiz-fonctions a {
		text-decoration: none;
	}

	#quiz-fonctions .page-titre {
		margin-top: -30px;
	}

	#quiz-fonctions-zone {
		box-sizing: border-box;
		margin-top: 24px;
		border: 1px solid #dbdcdd;
		border-radius: 8px;
		padding: 48px 56px;
	}

	#quiz-fonctions p.quiz-fonctions-description {
		margin-top: 0;
		font-size: 1.05em;
		color: #444;
		text-align: center;
	}

	#quiz-fonctions-entete {
		position: relative;
		margin-top: 24px;
		margin-bottom: 36px;
	}

	#quiz-fonctions-score-wrap {
		position: absolute;
		top: -13px;
		right: 0;
	}

	#quiz-fonctions-score {
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

	#quiz-fonctions-consigne {
		font-family: Montserrat, Lato, sans-serif;
		font-size: 0.88em;
		font-weight: 600;
		letter-spacing: 0.02em;
		color: #444;
		text-align: center;
	}

	#quiz-fonctions-consigne strong {
		color: #1c1c1c;
	}

	/* ------------------------------------------------------------
	 * Colonnes source / cible
	 * ------------------------------------------------------------ */

	#quiz-fonctions-colonnes {
		display: flex;
		gap: 40px;
		justify-content: center;
	}

	.quiz-fonctions-colonne {
		flex: 1 1 0;
		max-width: 340px;
		display: flex;
		flex-direction: column;
		gap: 14px;
	}

	.quiz-fonctions-jeton {
		box-sizing: border-box;
		width: 100%;
		min-height: 84px;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		text-align: center;
		padding: 10px 14px;
		border: 1.5px solid #c9c6bc;
		border-radius: 8px;
		background: #fdfcf9;
		font-family: 'Fraunces', Georgia, serif;
		font-size: 1.1em;
		color: #1c1c1c;
		transition: transform 0.12s ease, border-color 0.12s ease;
	}

	.quiz-fonctions-colonne-cibles .quiz-fonctions-jeton {
		font-family: Montserrat, Lato, sans-serif;
		font-size: 0.92em;
		font-weight: 600;
	}

	.quiz-fonctions-jeton sup {
		font-size: 0.65em;
	}

	.quiz-fonctions-jeton sub {
		font-size: 0.75em;
	}

	.cible-ligne {
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 8px;
	}

	.cible-marque {
		font-family: Montserrat, Lato, sans-serif;
	}

	.quiz-fonctions-cible.correct .cible-marque {
		color: #146c2e;
	}

	.quiz-fonctions-cible.incorrect .cible-marque {
		color: #a3222c;
	}

	.quiz-fonctions-source {
		cursor: grab;
	}

	.quiz-fonctions-source:active {
		cursor: grabbing;
	}

	.quiz-fonctions-source.selectionne {
		border-color: #1c1c1c;
		background: #1c1c1c;
		color: #fdfcf9;
		transform: scale(0.97);
	}

	.quiz-fonctions-source.placee {
		opacity: 0.35;
		pointer-events: none;
	}

	.quiz-fonctions-cible.survol {
		border-color: #1c1c1c;
		background: #f2f1ec;
	}

	.quiz-fonctions-cible.occupee {
		cursor: default;
	}

	.quiz-fonctions-jeton.correct {
		border-color: #146c2e;
		background: #fdfcf9;
		color: #146c2e;
	}

	.quiz-fonctions-jeton.incorrect {
		border-color: #a3222c;
		background: #fdfcf9;
		color: #a3222c;
	}

	.quiz-fonctions-correction {
		display: block;
		margin-top: 6px;
		font-family: 'Fraunces', Georgia, serif;
		font-size: 1.2em;
		font-weight: 500;
		color: #146c2e;
	}

	/* ------------------------------------------------------------
	 * Actions
	 * ------------------------------------------------------------ */

	#quiz-fonctions-continuer,
	#quiz-fonctions-remise-zero {
		font-family: Montserrat, Lato, sans-serif;
		font-weight: 600;
		font-size: 0.9em;
		letter-spacing: 0.06em;
		text-transform: uppercase;
	}

	#quiz-fonctions-continuer {
		padding: 12px 38px;
		border-radius: 999px;
		background: #d22630;
		border-color: #d22630;
	}

	#quiz-fonctions-continuer:hover,
	#quiz-fonctions-continuer:focus {
		background: #a3222c;
		border-color: #a3222c;
	}

	#quiz-fonctions-remise-zero {
		background: none;
		border: none;
		color: #a3222c;
		padding: 12px 6px;
	}

	#quiz-fonctions-remise-zero:hover,
	#quiz-fonctions-remise-zero:focus {
		text-decoration: underline;
		color: #a3222c;
	}

	@media (max-width: 600px)
	{
		#quiz-fonctions-colonnes {
			flex-direction: column;
			align-items: center;
		}

		.quiz-fonctions-colonne {
			max-width: 100%;
			width: 100%;
		}

		#quiz-fonctions-score-wrap {
			position: static;
			display: block;
			text-align: center;
			margin-bottom: 8px;
		}
	}

	@media (prefers-reduced-motion: reduce)
	{
		.quiz-fonctions-jeton {
			transition: none;
		}
	}
</style>

<div id="quiz-fonctions" class="page-contenu">
<div class="container">

    <div class="page-titre"><?= $quiz['titre']; ?></div>

    <div class="col-12">

		<div id="quiz-fonctions-zone">

			<p class="quiz-fonctions-description"><?= $quiz['description']; ?></p>

			<div id="quiz-fonctions-entete">
				<div id="quiz-fonctions-score-wrap">
					<div id="quiz-fonctions-score">Aucun essai pour l'instant</div>
				</div>

				<div id="quiz-fonctions-consigne">Glissez chaque <strong id="quiz-fonctions-consigne-source">structure</strong> sur le bon <strong id="quiz-fonctions-consigne-cible">nom</strong></div>
			</div>

			<div id="quiz-fonctions-colonnes">
				<div id="quiz-fonctions-sources" class="quiz-fonctions-colonne quiz-fonctions-colonne-sources"></div>
				<div id="quiz-fonctions-cibles" class="quiz-fonctions-colonne quiz-fonctions-colonne-cibles"></div>
			</div>

			<div id="quiz-fonctions-continuer-wrap" class="text-center mt-5 d-none">
				<button type="button" id="quiz-fonctions-continuer" class="btn btn-primary">Continuer</button>
				<button type="button" id="quiz-fonctions-remise-zero" class="ms-2">Remise à zéro</button>
			</div>

		</div> <!-- #quiz-fonctions-zone -->

        <div class="space"></div>

    </div> <!-- .col-12 -->
</div> <!-- .container -->
</div> <!-- #quiz-fonctions -->

<script src="<?= base_url() . 'assets/js/quiz_fonctions.js?' . date('U'); ?>"></script>

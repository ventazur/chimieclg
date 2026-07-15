<?
/* ----------------------------------------------------------------------------
 *
 * Quiz > Nom des ions et molécules
 *
 * ---------------------------------------------------------------------------- */ ?>

<style>
	@import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,340;9..144,500&display=swap');

	#quiz-nomen a {
		text-decoration: none;
	}

	#quiz-nomen .page-titre {
		margin-top: -30px;
	}

	#quiz-nomen-zone {
		box-sizing: border-box;
		margin-top: 24px;
		border: 1px solid #dbdcdd;
		border-radius: 8px;
		padding: 48px 56px;
	}

	#quiz-nomen p.quiz-nomen-description {
		margin-top: 0;
		font-size: 1.05em;
		color: #444;
		text-align: center;
	}

	#quiz-nomen-entete {
		position: relative;
		margin-top: 24px;
		margin-bottom: 36px;
	}

	#quiz-nomen-score-wrap {
		position: absolute;
		top: -13px;
		right: 0;
	}

	#quiz-nomen-score {
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

	#quiz-nomen-consigne {
		font-family: Montserrat, Lato, sans-serif;
		font-size: 0.88em;
		font-weight: 600;
		letter-spacing: 0.02em;
		color: #444;
		text-align: center;
	}

	#quiz-nomen-consigne strong {
		color: #1c1c1c;
	}

	/* ------------------------------------------------------------
	 * Colonnes source / cible
	 * ------------------------------------------------------------ */

	#quiz-nomen-colonnes {
		display: flex;
		gap: 40px;
		justify-content: center;
	}

	.quiz-nomen-colonne {
		flex: 1 1 0;
		max-width: 340px;
		display: flex;
		flex-direction: column;
		gap: 14px;
	}

	.quiz-nomen-jeton {
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

	.quiz-nomen-colonne-cibles .quiz-nomen-jeton {
		font-family: Montserrat, Lato, sans-serif;
		font-size: 0.92em;
		font-weight: 600;
	}

	.quiz-nomen-jeton sup {
		font-size: 0.65em;
	}

	.quiz-nomen-jeton sub {
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

	.quiz-nomen-cible.correct .cible-marque {
		color: #146c2e;
	}

	.quiz-nomen-cible.incorrect .cible-marque {
		color: #a3222c;
	}

	.quiz-nomen-source {
		cursor: grab;
	}

	.quiz-nomen-source:active {
		cursor: grabbing;
	}

	.quiz-nomen-source.selectionne {
		border-color: #1c1c1c;
		background: #1c1c1c;
		color: #fdfcf9;
		transform: scale(0.97);
	}

	.quiz-nomen-source.placee {
		opacity: 0.35;
		pointer-events: none;
	}

	.quiz-nomen-cible.survol {
		border-color: #1c1c1c;
		background: #f2f1ec;
	}

	.quiz-nomen-cible.occupee {
		cursor: default;
	}

	.quiz-nomen-jeton.correct {
		border-color: #146c2e;
		background: #fdfcf9;
		color: #146c2e;
	}

	.quiz-nomen-jeton.incorrect {
		border-color: #a3222c;
		background: #fdfcf9;
		color: #a3222c;
	}

	.quiz-nomen-correction {
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

	#quiz-nomen-continuer,
	#quiz-nomen-remise-zero {
		font-family: Montserrat, Lato, sans-serif;
		font-weight: 600;
		font-size: 0.9em;
		letter-spacing: 0.06em;
		text-transform: uppercase;
	}

	#quiz-nomen-continuer {
		padding: 12px 38px;
		border-radius: 999px;
		background: #d22630;
		border-color: #d22630;
	}

	#quiz-nomen-continuer:hover,
	#quiz-nomen-continuer:focus {
		background: #a3222c;
		border-color: #a3222c;
	}

	#quiz-nomen-remise-zero {
		background: none;
		border: none;
		color: #a3222c;
		padding: 12px 6px;
	}

	#quiz-nomen-remise-zero:hover,
	#quiz-nomen-remise-zero:focus {
		text-decoration: underline;
		color: #a3222c;
	}

	@media (max-width: 600px)
	{
		#quiz-nomen-colonnes {
			flex-direction: column;
			align-items: center;
		}

		.quiz-nomen-colonne {
			max-width: 100%;
			width: 100%;
		}

		#quiz-nomen-score-wrap {
			position: static;
			display: block;
			text-align: center;
			margin-bottom: 8px;
		}
	}

	@media (prefers-reduced-motion: reduce)
	{
		.quiz-nomen-jeton {
			transition: none;
		}
	}
</style>

<div id="quiz-nomen" class="page-contenu">
<div class="container">

    <div class="page-titre"><?= $quiz['titre']; ?></div>

    <div class="col-12">

		<div id="quiz-nomen-zone">

			<p class="quiz-nomen-description"><?= $quiz['description']; ?></p>

			<div id="quiz-nomen-entete">
				<div id="quiz-nomen-score-wrap">
					<div id="quiz-nomen-score">Aucun essai pour l'instant</div>
				</div>

				<div id="quiz-nomen-consigne">Glissez chaque <strong id="quiz-nomen-consigne-source">formule</strong> sur le bon <strong id="quiz-nomen-consigne-cible">nom</strong></div>
			</div>

			<div id="quiz-nomen-colonnes">
				<div id="quiz-nomen-sources" class="quiz-nomen-colonne quiz-nomen-colonne-sources"></div>
				<div id="quiz-nomen-cibles" class="quiz-nomen-colonne quiz-nomen-colonne-cibles"></div>
			</div>

			<div id="quiz-nomen-continuer-wrap" class="text-center mt-5 d-none">
				<button type="button" id="quiz-nomen-continuer" class="btn btn-primary">Continuer</button>
				<button type="button" id="quiz-nomen-remise-zero" class="ms-2">Remise à zéro</button>
			</div>

		</div> <!-- #quiz-nomen-zone -->

        <div class="space"></div>

    </div> <!-- .col-12 -->
</div> <!-- .container -->
</div> <!-- #quiz-nomen -->

<script src="<?= base_url() . 'assets/js/quiz_nomen.js?' . date('U'); ?>"></script>

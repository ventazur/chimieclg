<?
/* ----------------------------------------------------------------------------
 *
 * Quiz > Chiffres significatifs
 *
 * ---------------------------------------------------------------------------- */ ?>

<style>
	@import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,340;9..144,500&display=swap');

	#quiz-cs a {
		text-decoration: none;
	}

	#quiz-cs .page-titre {
		margin-top: -30px;
	}

	#quiz-cs-zone {
		box-sizing: border-box;
		margin-top: 24px;
		border: 1px solid #dbdcdd;
		border-radius: 8px;
		padding: 48px 56px;
	}

	#quiz-cs p.quiz-cs-description {
		margin-top: 0;
		font-size: 1.05em;
		color: #444;
		text-align: center;
	}

	/* ------------------------------------------------------------
	 * Zone de mesure : le nombre, encadre de filets, comme une
	 * legende de figure scientifique.
	 * ------------------------------------------------------------ */

	#quiz-cs-mesure {
		position: relative;
		margin-top: 44px;
	}

	.quiz-cs-filet {
		border-top: 1px solid #d7d3c8;
	}

	#quiz-cs-score-wrap {
		position: absolute;
		top: -13px;
		right: 0;
	}

	#quiz-cs-score {
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

	#quiz-cs-nombre {
		font-family: 'Fraunces', Georgia, serif;
		font-optical-sizing: auto;
		font-weight: 340;
		font-size: 4.6em;
		line-height: 1.25;
		color: #1c1c1c;
		text-align: center;
	}

	#quiz-cs-nombre sup {
		font-size: 0.5em;
	}

	.quiz-cs-caption {
		margin-top: 6px;
		font-family: Montserrat, Lato, sans-serif;
		font-size: 0.76em;
		font-weight: 600;
		letter-spacing: 0.12em;
		text-transform: uppercase;
		color: #8a8d90;
		text-align: center;
	}

	/* ------------------------------------------------------------
	 * Clavier de reponses : cercles espaces, pas un btn-group.
	 * ------------------------------------------------------------ */

	#quiz-cs-choix {
		display: flex;
		justify-content: center;
		flex-wrap: wrap;
		gap: 16px;
		margin-top: 40px;
	}

	.quiz-cs-choix {
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

	.quiz-cs-choix.btn-outline-dark {
		background: transparent;
		color: #1c1c1c;
		border: 1.5px solid #c9c6bc;
	}

	.quiz-cs-choix.btn-outline-dark:hover {
		border-color: #1c1c1c;
	}

	.quiz-cs-choix.btn-dark {
		background: #1c1c1c;
		color: #fdfcf9;
		border: 1.5px solid #1c1c1c;
	}

	.quiz-cs-choix.btn-success {
		background: #fdfcf9;
		color: #146c2e;
		border: 1.5px solid #146c2e;
	}

	.quiz-cs-choix.btn-danger {
		background: #fdfcf9;
		color: #a3222c;
		border: 1.5px solid #a3222c;
	}

	.quiz-cs-choix:active {
		transform: scale(0.92);
	}

	.quiz-cs-choix:disabled {
		opacity: 0.5;
	}

	/* ------------------------------------------------------------
	 * Actions
	 * ------------------------------------------------------------ */

	#quiz-cs-envoyer,
	#quiz-cs-suivant {
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

	#quiz-cs-envoyer:hover,
	#quiz-cs-envoyer:focus,
	#quiz-cs-suivant:hover,
	#quiz-cs-suivant:focus {
		background: #a3222c;
		border-color: #a3222c;
	}

	#quiz-cs-envoyer:disabled {
		background: #d22630;
		border-color: #d22630;
		opacity: 0.3;
	}

	#quiz-cs-remise-zero {
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

	#quiz-cs-remise-zero:hover,
	#quiz-cs-remise-zero:focus {
		text-decoration: underline;
		color: #a3222c;
	}

	/* ------------------------------------------------------------
	 * Resultat : accent de marge, pas un bloc pastel.
	 * ------------------------------------------------------------ */

	#quiz-cs-resultat {
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

	#quiz-cs-resultat.reussi {
		border-left-color: #146c2e;
	}

	#quiz-cs-resultat.echec {
		border-left-color: #a3222c;
	}

	#quiz-cs-resultat-titre {
		font-family: Montserrat, Lato, sans-serif;
		font-size: 0.85em;
		font-weight: 700;
		letter-spacing: 0.08em;
		text-transform: uppercase;
	}

	#quiz-cs-resultat.reussi #quiz-cs-resultat-titre {
		color: #146c2e;
	}

	#quiz-cs-resultat.echec #quiz-cs-resultat-titre {
		color: #a3222c;
	}

	#quiz-cs-explication {
		font-size: 1em;
		line-height: 1.55;
		color: #444;
		margin-top: 8px;
	}

	@media (max-width: 480px)
	{
		#quiz-cs-nombre {
			font-size: 2.8em;
		}

		.quiz-cs-choix {
			width: 48px;
			height: 48px;
			font-size: 1.1em;
		}

		#quiz-cs-score-wrap {
			position: static;
			display: block;
			text-align: center;
			margin-bottom: 8px;
		}
	}

	@media (prefers-reduced-motion: reduce)
	{
		.quiz-cs-choix {
			transition: none;
		}
	}
</style>

<div id="quiz-cs" class="page-contenu">
<div class="container">

    <div class="page-titre"><?= $quiz['titre']; ?></div>

    <div class="col-12">

		<div id="quiz-cs-zone">

			<p class="quiz-cs-description"><?= $quiz['description']; ?></p>

			<div id="quiz-cs-mesure">
				<div class="quiz-cs-filet"></div>

				<div id="quiz-cs-score-wrap">
					<div id="quiz-cs-score">Aucun essai pour l'instant</div>
				</div>

				<div id="quiz-cs-nombre">&nbsp;</div>

				<div class="quiz-cs-filet"></div>
				<div class="quiz-cs-caption">Combien de chiffres significatifs ?</div>
			</div>

			<div id="quiz-cs-choix" role="group" aria-label="Choix du nombre de chiffres significatifs (une ou plusieurs reponses)">
				<button type="button" class="quiz-cs-choix btn btn-outline-dark" data-valeur="1">1</button>
				<button type="button" class="quiz-cs-choix btn btn-outline-dark" data-valeur="2">2</button>
				<button type="button" class="quiz-cs-choix btn btn-outline-dark" data-valeur="3">3</button>
				<button type="button" class="quiz-cs-choix btn btn-outline-dark" data-valeur="4">4</button>
				<button type="button" class="quiz-cs-choix btn btn-outline-dark" data-valeur="5">5</button>
				<button type="button" class="quiz-cs-choix btn btn-outline-dark" data-valeur="6">6</button>
			</div>

			<div class="text-center mt-5">
				<button type="button" id="quiz-cs-envoyer" class="btn btn-primary" disabled>Envoyer</button>
			</div>

			<div id="quiz-cs-resultat" class="d-none mt-5">
				<div id="quiz-cs-resultat-titre"></div>
				<div id="quiz-cs-explication"></div>
			</div>

			<div id="quiz-cs-suivant-wrap" class="text-center mt-5 d-none">
				<button type="button" id="quiz-cs-suivant" class="btn btn-primary">Suivant</button>
				<button type="button" id="quiz-cs-remise-zero" class="ms-2">Remise à zéro</button>
			</div>

		</div> <!-- #quiz-cs-zone -->

        <div class="space"></div>

    </div> <!-- .col-12 -->
</div> <!-- .container -->
</div> <!-- #quiz-cs -->

<script src="<?= base_url() . 'assets/js/quiz_cs.js?' . date('U'); ?>"></script>

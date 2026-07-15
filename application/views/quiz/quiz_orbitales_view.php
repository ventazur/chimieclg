<?
/* ----------------------------------------------------------------------------
 *
 * Quiz > Nombre d'orbitales
 *
 * ---------------------------------------------------------------------------- */ ?>

<style>
	@import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,340;9..144,500&display=swap');

	#quiz-orb a {
		text-decoration: none;
	}

	#quiz-orb .page-titre {
		margin-top: -30px;
	}

	#quiz-orb-zone {
		box-sizing: border-box;
		margin-top: 24px;
		border: 1px solid #dbdcdd;
		border-radius: 8px;
		padding: 48px 56px;
	}

	#quiz-orb p.quiz-orb-description {
		margin-top: 0;
		font-size: 1.05em;
		color: #444;
		text-align: center;
	}

	#quiz-orb-mesure {
		position: relative;
		margin-top: 44px;
	}

	.quiz-orb-filet {
		border-top: 1px solid #d7d3c8;
	}

	#quiz-orb-score-wrap {
		position: absolute;
		top: -13px;
		right: 0;
	}

	#quiz-orb-score {
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

	#quiz-orb-valeurs {
		display: flex;
		justify-content: center;
		gap: 36px;
		flex-wrap: wrap;
		padding: 40px 0;
	}

	.quiz-orb-valeur {
		font-family: 'Fraunces', Georgia, serif;
		font-optical-sizing: auto;
		font-weight: 340;
		font-size: 2.6em;
		color: #1c1c1c;
	}

	.quiz-orb-caption {
		margin-top: 6px;
		font-family: Montserrat, Lato, sans-serif;
		font-size: 0.76em;
		font-weight: 600;
		letter-spacing: 0.12em;
		text-transform: uppercase;
		color: #8a8d90;
		text-align: center;
	}

	#quiz-orb-reponse {
		margin-top: 8px;
	}

	#quiz-orb-input {
		max-width: 140px;
		margin: 0 auto;
		text-align: center;
		font-family: 'Fraunces', Georgia, serif;
		font-size: 1.4em;
	}

	#quiz-orb-envoyer,
	#quiz-orb-suivant {
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

	#quiz-orb-envoyer:hover,
	#quiz-orb-envoyer:focus,
	#quiz-orb-suivant:hover,
	#quiz-orb-suivant:focus {
		background: #a3222c;
		border-color: #a3222c;
	}

	#quiz-orb-envoyer:disabled {
		background: #d22630;
		border-color: #d22630;
		opacity: 0.3;
	}

	#quiz-orb-remise-zero {
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

	#quiz-orb-remise-zero:hover,
	#quiz-orb-remise-zero:focus {
		text-decoration: underline;
		color: #a3222c;
	}

	#quiz-orb-resultat {
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

	#quiz-orb-resultat.reussi {
		border-left-color: #146c2e;
	}

	#quiz-orb-resultat.echec {
		border-left-color: #a3222c;
	}

	#quiz-orb-resultat-titre {
		font-family: Montserrat, Lato, sans-serif;
		font-size: 0.85em;
		font-weight: 700;
		letter-spacing: 0.08em;
		text-transform: uppercase;
	}

	#quiz-orb-resultat.reussi #quiz-orb-resultat-titre {
		color: #146c2e;
	}

	#quiz-orb-resultat.echec #quiz-orb-resultat-titre {
		color: #a3222c;
	}

	#quiz-orb-explication {
		font-size: 1em;
		line-height: 1.55;
		color: #444;
		margin-top: 8px;
	}

	@media (max-width: 480px)
	{
		.quiz-orb-valeur {
			font-size: 1.8em;
		}

		#quiz-orb-score-wrap {
			position: static;
			display: block;
			text-align: center;
			margin-bottom: 8px;
		}
	}
</style>

<div id="quiz-orb" class="page-contenu">
<div class="container">

    <div class="page-titre"><?= $quiz['titre']; ?></div>

    <div class="col-12">

		<div id="quiz-orb-zone">

			<p class="quiz-orb-description"><?= $quiz['description']; ?></p>

			<div id="quiz-orb-mesure">
				<div class="quiz-orb-filet"></div>

				<div id="quiz-orb-score-wrap">
					<div id="quiz-orb-score">Aucun essai pour l'instant</div>
				</div>

				<div id="quiz-orb-valeurs">&nbsp;</div>

				<div class="quiz-orb-filet"></div>
				<div class="quiz-orb-caption">Combien d'orbitales ?</div>
			</div>

			<div id="quiz-orb-reponse" class="text-center">
				<input type="number" id="quiz-orb-input" class="form-control" min="0" step="1" inputmode="numeric" autocomplete="off" aria-label="Nombre d'orbitales">
			</div>

			<div class="text-center mt-4">
				<button type="button" id="quiz-orb-envoyer" class="btn btn-primary" disabled>Envoyer</button>
			</div>

			<div id="quiz-orb-resultat" class="d-none mt-5">
				<div id="quiz-orb-resultat-titre"></div>
				<div id="quiz-orb-explication"></div>
			</div>

			<div id="quiz-orb-suivant-wrap" class="text-center mt-5 d-none">
				<button type="button" id="quiz-orb-suivant" class="btn btn-primary">Suivant</button>
				<button type="button" id="quiz-orb-remise-zero" class="ms-2">Remise à zéro</button>
			</div>

		</div> <!-- #quiz-orb-zone -->

        <div class="space"></div>

    </div> <!-- .col-12 -->
</div> <!-- .container -->
</div> <!-- #quiz-orb -->

<script src="<?= base_url() . 'assets/js/quiz_orbitales.js?' . date('U'); ?>"></script>

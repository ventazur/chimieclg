<?
/* ----------------------------------------------------------------------------
 *
 * Quiz > Chiffres significatifs
 *
 * ---------------------------------------------------------------------------- */ ?>

<style>
	#quiz-cs a {
		text-decoration: none;
	}

	#quiz-cs .page-titre {
		margin-top: -30px;
	}

	#quiz-cs p {
		font-size: 1.15em;
	}

	#quiz-cs-nombre {
		font-size: 5em;
		font-weight: 300;
		text-align: center;
		padding: 40px 15px;
	}

	.quiz-cs-choix {
		min-width: 55px;
		font-size: 1.6em;
		padding-top: 12px;
		padding-bottom: 12px;
	}

	#quiz-cs-resultat {
		max-width: 600px;
		min-height: 100px;
		margin-left: auto;
		margin-right: auto;
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
		border-radius: 5px;
		padding: 20px;
		text-align: center;
		font-size: 1.3em;
	}

	#quiz-cs-resultat.reussi {
		background: #e7f5ec;
		color: #146c2e;
	}

	#quiz-cs-resultat.echec {
		background: #fbe9ea;
		color: #a3222c;
	}

	#quiz-cs-resultat-titre {
		font-weight: 600;
	}

	#quiz-cs-explication {
		font-size: 1em;
		line-height: 1.5;
		color: #333;
		margin-top: 14px;
	}

	#quiz-cs-score-wrap {
		text-align: center;
	}

	#quiz-cs-score {
		display: inline-block;
		background: #ffd600;
		color: #222;
		font-weight: 700;
		font-size: 1.2em;
		padding: 14px 32px;
		border-radius: 20px;
	}

	#quiz-cs-suivant,
	#quiz-cs-envoyer,
	#quiz-cs-remise-zero {
		font-size: 1.2em;
		padding: 10px 30px;
	}

	#quiz-cs-carte {
		border: 1px solid #dbdcdd;
		border-radius: 5px;
		padding: 40px;
	}
</style>

<div id="quiz-cs" class="page-contenu">
<div class="container">

    <div class="page-titre"><?= $quiz['titre']; ?></div>

    <div class="col-12">

		<p class="mt-4"><?= $quiz['description']; ?></p>

		<div id="quiz-cs-carte">

			<div id="quiz-cs-score-wrap">
				<div id="quiz-cs-score">Aucun essai pour l'instant</div>
			</div>

			<div id="quiz-cs-nombre">&nbsp;</div>

			<div id="quiz-cs-choix" class="btn-group w-100" role="group" aria-label="Choix du nombre de chiffres significatifs (une ou plusieurs reponses)">
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

			<div id="quiz-cs-resultat" class="d-none mt-4">
				<div id="quiz-cs-resultat-titre"></div>
				<div id="quiz-cs-explication"></div>
			</div>

			<div id="quiz-cs-suivant-wrap" class="text-center mt-5 d-none">
				<button type="button" id="quiz-cs-suivant" class="btn btn-primary">Suivant</button>
				<button type="button" id="quiz-cs-remise-zero" class="btn btn-outline-danger ms-2">Remise à zéro</button>
			</div>

		</div> <!-- #quiz-cs-carte -->

        <div class="space"></div>

    </div> <!-- .col-12 -->
</div> <!-- .container -->
</div> <!-- #quiz-cs -->

<script src="<?= base_url() . 'assets/js/quiz_cs.js?' . date('U'); ?>"></script>

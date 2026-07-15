<?
/* ----------------------------------------------------------------------------
 *
 * Quiz > index
 *
 * ---------------------------------------------------------------------------- */ ?>

<style>
	#quiz a {
		text-decoration: none;
	}

	#quiz .page-titre {
		margin-top: -30px;
	}

	.quiz-carte {
		display: block;
		border: 1px solid #dbdcdd;
		border-radius: 5px;
		padding: 15px 20px;
		color: inherit;
	}

	.quiz-carte:hover {
		background: #f8f9fa;
	}

	.quiz-carte .quiz-carte-titre {
		font-weight: 600;
		font-size: 1.2em;
	}
</style>

<div id="quiz" class="page-contenu">
<div class="container">

    <div class="page-titre">Quiz</div>

    <div class="col-12">

		<? if (empty($quizzes)) : ?>

			Aucun quiz disponible pour l'instant.

		<? else : ?>

			<? foreach ($quizzes as $slug => $q) : ?>

				<a class="quiz-carte mb-3" href="<?= base_url() . 'quiz/' . $slug; ?>">
					<div class="quiz-carte-titre"><?= $q['titre']; ?></div>
					<div class="quiz-carte-description"><?= $q['description']; ?></div>
				</a>

			<? endforeach; ?>

		<? endif; ?>

        <div class="space"></div>

    </div> <!-- .col-12 -->
</div> <!-- .container -->
</div> <!-- #quiz -->

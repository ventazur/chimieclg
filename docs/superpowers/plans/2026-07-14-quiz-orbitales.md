# Quiz Orbitales Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add a new training quiz (slug `orbitales`) where the student determines the number of orbitals implied by a partial combination of quantum numbers (n, l, ml).

**Architecture:** Follow the existing `cs` (chiffres significatifs) quiz pattern exactly: a pure-PHP generator function in `application/helpers/chimie_helper.php` produces question data server-side; `Quiz.php` exposes a page action and an AJAX `lot()` batch endpoint; a view + a dedicated JS file handle client-side state, scoring (localStorage), and interaction. The only structural difference from `cs` is the answer widget: a numeric input instead of a fixed row of circular buttons, because the correct answer ranges from 0 to 49 depending on question type.

**Tech Stack:** PHP 8.5 / CodeIgniter (no PHPUnit in this project — verification is done via ad-hoc CLI scripts run with `php`, plus manual browser testing), jQuery (already loaded site-wide), Bootstrap classes (already used by `cs`).

## Global Constraints

- No test framework exists in this repo (checked: no PHPUnit, `composer.json` only requires `chillerlan/php-qrcode`). Verification uses standalone `php` CLI scripts that `require` the helper file directly (with `BASEPATH` defined) plus a final manual browser pass — do not attempt to introduce PHPUnit.
- Comments inside PHP files in this codebase are ASCII-only (no accents), even though French. User-facing strings (`titre`, `description`, `explication`, view markup) DO use proper French accents. Follow this split exactly, matching `chimie_helper.php`'s existing style.
- Question types are equiprobable (20% each): n seul, n+l, l seul, n+ml, n+l+ml.
- Among types that support a trap (n+l, n+ml, n+l+ml), traps (invalid combination, answer 0) occur ~5% of the time; the other ~95% are valid combinations.
- n ranges 1–7. l ranges 0–6 (used both as a valid sub-value of n, and standalone). Validity rules: `0 <= l <= n-1`, `-l <= ml <= l`.
- Explanation text is shown to the student only when the answer is wrong (never on a correct answer) — matches the `cs` quiz's existing behavior.
- Spec: `docs/superpowers/specs/2026-07-14-quiz-orbitales-design.md`.

---

### Task 1: Generator functions in `chimie_helper.php`

**Files:**
- Modify: `application/helpers/chimie_helper.php` (insert new functions between the end of `cs_generer_scientifique()`/`cs_generer_nombre()` block and `quiz_liste_disponibles()`, i.e. right before the existing `quiz_liste_disponibles()` function at what is currently line 442; also modify the body of `quiz_liste_disponibles()` itself)
- Test: `/tmp/verify_orbitales_helper.php` (throwaway CLI script, not committed)

**Interfaces:**
- Produces: `orbitales_generer_question(): array` — the single entry point `Quiz.php`'s `lot()` action will call. Returns:
  ```php
  [
      'affichage'   => array,   // ordered subset of ['n' => int, 'l' => int, 'ml' => int], only the keys relevant to this question type
      'valeur'      => int,     // expected answer, >= 0
      'explication' => string,  // shown only when the student answers incorrectly
  ]
  ```
- Produces: `quiz_liste_disponibles()` gains an `'orbitales'` key: `['titre' => string, 'description' => string]`, read by both `Quiz::orbitales()` (Task 2) and the quiz index page (`Quiz::index()`, unchanged, already iterates the manifest generically).

- [ ] **Step 1: Write the verification script (acts as the failing test)**

Create `/tmp/verify_orbitales_helper.php`:

```php
<?php
define('BASEPATH', true);
require '/var/www/ventbleu.com/chimie/public/application/helpers/chimie_helper.php';

function check(bool $cond, string $msg): void
{
    if ( ! $cond)
    {
        fwrite(STDERR, "FAIL: $msg\n");
        exit(1);
    }
}

$vus = array('n' => 0, 'n_l' => 0, 'l' => 0, 'n_ml' => 0, 'n_l_ml' => 0);

for ($i = 0; $i < 20000; $i++)
{
    $q = orbitales_generer_question();

    check(is_array($q['affichage']), 'affichage doit etre un array');
    check(is_int($q['valeur']) && $q['valeur'] >= 0, 'valeur doit etre un entier >= 0');
    check(is_string($q['explication']) && $q['explication'] !== '', 'explication ne doit pas etre vide');

    $cles = array_keys($q['affichage']);
    sort($cles);

    if ($cles === array('n'))
    {
        $vus['n']++;
        $n = $q['affichage']['n'];
        check($n >= 1 && $n <= 7, "n hors plage : $n");
        check($q['valeur'] === $n * $n, "n seul : attendu n^2, recu {$q['valeur']} pour n=$n");
    }
    elseif ($cles === array('l', 'n'))
    {
        $vus['n_l']++;
        $n = $q['affichage']['n'];
        $l = $q['affichage']['l'];
        if ($l >= $n)
        {
            check($q['valeur'] === 0, "n+l piege : attendu 0, recu {$q['valeur']} pour n=$n l=$l");
        }
        else
        {
            check($q['valeur'] === 2 * $l + 1, "n+l valide : attendu 2l+1, recu {$q['valeur']} pour n=$n l=$l");
        }
    }
    elseif ($cles === array('l'))
    {
        $vus['l']++;
        $l = $q['affichage']['l'];
        check($l >= 0 && $l <= 6, "l hors plage : $l");
        check($q['valeur'] === 2 * $l + 1, "l seul : attendu 2l+1, recu {$q['valeur']} pour l=$l");
    }
    elseif ($cles === array('ml', 'n'))
    {
        $vus['n_ml']++;
        $n  = $q['affichage']['n'];
        $ml = $q['affichage']['ml'];
        if (abs($ml) >= $n)
        {
            check($q['valeur'] === 0, "n+ml piege : attendu 0, recu {$q['valeur']} pour n=$n ml=$ml");
        }
        else
        {
            check($q['valeur'] === $n - abs($ml), "n+ml valide : attendu n-|ml|, recu {$q['valeur']} pour n=$n ml=$ml");
        }
    }
    elseif ($cles === array('l', 'ml', 'n'))
    {
        $vus['n_l_ml']++;
        $n  = $q['affichage']['n'];
        $l  = $q['affichage']['l'];
        $ml = $q['affichage']['ml'];
        $valide = ($l >= 0 && $l <= $n - 1 && abs($ml) <= $l);
        check($q['valeur'] === ($valide ? 1 : 0), "n+l+ml : attendu " . ($valide ? 1 : 0) . ", recu {$q['valeur']} pour n=$n l=$l ml=$ml");
    }
    else
    {
        check(false, 'combinaison de cles inattendue : ' . implode(',', $cles));
    }
}

foreach ($vus as $type => $compte)
{
    check($compte > 0, "type $type jamais genere sur 20000 tirages");
}

$liste = quiz_liste_disponibles();
check(array_key_exists('orbitales', $liste), "quiz_liste_disponibles() doit contenir la cle 'orbitales'");
check(isset($liste['orbitales']['titre'], $liste['orbitales']['description']), "l'entree 'orbitales' doit avoir titre et description");

echo "OK\n";
```

- [ ] **Step 2: Run it to confirm it fails (functions don't exist yet)**

Run: `php /tmp/verify_orbitales_helper.php`
Expected: Fatal error — `Call to undefined function orbitales_generer_question()`

- [ ] **Step 3: Implement the generator functions**

Insert the following into `application/helpers/chimie_helper.php`, immediately before the existing `quiz_liste_disponibles()` function (i.e. right after `cs_generer_nombre()` ends, before the `/* quiz_liste_disponibles() */` banner comment):

```php
/* ----------------------------------------------------------------------------
 *
 * orbitales_generer_question()
 *
 * ----------------------------------------------------------------------------
 *
 * Genere une question aleatoire pour le quiz du nombre d'orbitales a partir
 * d'une combinaison partielle de nombres quantiques (n, l, ml). Cinq types de
 * question, tires avec la meme probabilite :
 *
 *   - n seul       : combien d'orbitales dans la couche n            -> n^2
 *   - n et l       : combien d'orbitales dans la sous-couche (n, l)  -> 2l+1
 *   - l seul       : combien d'orbitales partagent ce l (tout n)     -> 2l+1
 *   - n et ml      : combien de l valides pour ce (n, ml)            -> n-|ml|
 *   - n, l et ml   : cette combinaison designe-t-elle une orbitale ? -> 0 ou 1
 *
 * Les types n+l, n+ml et n+l+ml peuvent produire un piege (combinaison
 * invalide selon les regles l < n et |ml| <= l), avec une probabilite
 * d'environ 5%. Dans ce cas la reponse attendue est 0.
 *
 * Retourne :
 *
 * [
 *   'affichage'   => array,   // sous-ensemble ordonne de ['n', 'l', 'ml']
 *   'valeur'      => int,     // reponse attendue, >= 0
 *   'explication' => string   // affichee seulement si la reponse est fausse
 * ]
 *
 * ---------------------------------------------------------------------------- */
function orbitales_generer_question(): array
{
    $generateurs = array(
        'orbitales_generer_n',
        'orbitales_generer_n_l',
        'orbitales_generer_l',
        'orbitales_generer_n_ml',
        'orbitales_generer_n_l_ml'
    );

    $fonction = $generateurs[random_int(0, count($generateurs) - 1)];

    return $fonction();
}

/* ----------------------------------------------------------------------------
 *
 * orbitales_piege()
 *
 * ----------------------------------------------------------------------------
 *
 * TRUE environ 1 fois sur 20 (5%). Utilise par les generateurs qui peuvent
 * produire une combinaison invalide (piege, reponse attendue 0).
 *
 * ---------------------------------------------------------------------------- */
function orbitales_piege(): bool
{
    return random_int(1, 100) <= 5;
}

function orbitales_generer_n(): array
{
    $n      = random_int(1, 7);
    $valeur = $n * $n;

    return array(
        'affichage'   => array('n' => $n),
        'valeur'      => $valeur,
        'explication' => "Dans la couche n = $n, l va de 0 a n-1 et chaque sous-couche l contient 2l+1 orbitales. Au total : n² = $valeur orbitales."
    );
}

function orbitales_generer_n_l(): array
{
    $n = random_int(1, 7);

    if (orbitales_piege())
    {
        $l      = $n + random_int(0, 2);
        $valeur = 0;

        $explication = "l doit toujours etre strictement inferieur a n. Ici n = $n, donc l = $l est impossible : 0 orbitale.";
    }
    else
    {
        $l      = random_int(0, $n - 1);
        $valeur = 2 * $l + 1;

        $explication = "Pour l = $l, ml va de -l a +l, ce qui donne 2l+1 = $valeur orbitales.";
    }

    return array(
        'affichage'   => array('n' => $n, 'l' => $l),
        'valeur'      => $valeur,
        'explication' => $explication
    );
}

function orbitales_generer_l(): array
{
    $l      = random_int(0, 6);
    $valeur = 2 * $l + 1;

    return array(
        'affichage'   => array('l' => $l),
        'valeur'      => $valeur,
        'explication' => "Pour l = $l, ml peut prendre 2l+1 = $valeur valeurs differentes (de -l a +l). Ces $valeur orbitales existent quel que soit n, tant que n > l."
    );
}

function orbitales_generer_n_ml(): array
{
    $n = random_int(1, 7);

    if (orbitales_piege())
    {
        $abs_ml = $n + random_int(0, 2);
        $ml     = (random_int(0, 1) === 0) ? $abs_ml : -$abs_ml;
        $valeur = 0;

        $l_max = $n - 1;
        $explication = "Pour n = $n, l va au maximum jusqu'a $l_max, donc |ml| ne peut jamais atteindre $abs_ml : 0 orbitale.";
    }
    else
    {
        $abs_ml = random_int(0, $n - 1);
        $ml     = ($abs_ml === 0) ? 0 : (random_int(0, 1) === 0 ? $abs_ml : -$abs_ml);
        $valeur = $n - $abs_ml;

        $l_max = $n - 1;
        $explication = "l doit etre au moins |ml| = $abs_ml et au plus n-1 = $l_max, ce qui laisse $valeur valeur(s) de l possibles, donc $valeur orbitales.";
    }

    return array(
        'affichage'   => array('n' => $n, 'ml' => $ml),
        'valeur'      => $valeur,
        'explication' => $explication
    );
}

function orbitales_generer_n_l_ml(): array
{
    $n = random_int(1, 7);

    if (orbitales_piege())
    {
        if (random_int(0, 1) === 0)
        {
            $l  = $n + random_int(0, 2);
            $ml = random_int(-$l, $l);

            $raison = "l = $l n'est pas valide pour n = $n (l doit etre strictement inferieur a n)";
        }
        else
        {
            $l      = random_int(0, max(0, $n - 1));
            $abs_ml = $l + random_int(1, 2);
            $ml     = (random_int(0, 1) === 0) ? $abs_ml : -$abs_ml;

            $raison = "ml = $ml n'est pas valide pour l = $l (|ml| doit etre inferieur ou egal a l)";
        }

        $valeur      = 0;
        $explication = "$raison, donc cette combinaison ne correspond a aucune orbitale.";
    }
    else
    {
        $l      = random_int(0, $n - 1);
        $ml     = random_int(-$l, $l);
        $valeur = 1;

        $explication = 'Cette combinaison (n, l, ml) designe une orbitale unique et bien precise : 1 orbitale.';
    }

    return array(
        'affichage'   => array('n' => $n, 'l' => $l, 'ml' => $ml),
        'valeur'      => $valeur,
        'explication' => $explication
    );
}

```

Then modify the existing `quiz_liste_disponibles()` function body to add the `orbitales` entry:

```php
function quiz_liste_disponibles(): array
{
    return array(
        'cs' => array(
            'titre'       => 'Chiffres significatifs',
            'description' => "Déterminez le nombre de chiffres significatifs d'un nombre.",
        ),
        'orbitales' => array(
            'titre'       => 'Nombre d\'orbitales',
            'description' => 'Déterminez le nombre d\'orbitales correspondant à une combinaison de nombres quantiques (n, l, mₗ).',
        ),
    );
}
```

- [ ] **Step 4: Run the verification script and confirm it passes**

Run: `php /tmp/verify_orbitales_helper.php`
Expected: `OK`

- [ ] **Step 5: Lint the modified file and commit**

Run: `php -l application/helpers/chimie_helper.php`
Expected: `No syntax errors detected`

```bash
git add application/helpers/chimie_helper.php
git commit -m "Ajoute les generateurs de questions pour le quiz orbitales"
```

---

### Task 2: Controller wiring in `Quiz.php`

**Files:**
- Modify: `application/controllers/Quiz.php`

**Interfaces:**
- Consumes: `orbitales_generer_question(): array` and `quiz_liste_disponibles()['orbitales']` from Task 1.
- Produces: `GET /quiz/orbitales` renders `quiz/quiz_orbitales_view` with `$this->data['quiz']` set. `GET /quiz/lot/orbitales` (AJAX) returns a JSON array of 20 question objects, same shape `Quiz::lot()` already returns for `cs`.

- [ ] **Step 1: Add the `orbitales()` action**

In `application/controllers/Quiz.php`, add this method right after the existing `cs()` method (currently ending at line 34):

```php
    public function orbitales()
    {
        $this->data['quiz'] = $this->quizzes['orbitales'];

        $this->_display_view('orbitales');
    }
```

- [ ] **Step 2: Add the `orbitales` case to `lot()`**

In the `switch ($slug)` block inside `lot()` (currently only containing `case 'cs':`), add:

```php
            case 'orbitales' :
                for ($i = 0; $i < $taille_lot; $i++)
                {
                    $lot[] = orbitales_generer_question();
                }
                break;
```

- [ ] **Step 3: Lint and manually smoke-test the endpoint**

Run: `php -l application/controllers/Quiz.php`
Expected: `No syntax errors detected`

Since there's no test harness for controllers in this project, defer functional verification of the HTTP endpoints to Task 5 (end-to-end browser check), after the view and JS exist to actually exercise `/quiz/orbitales` and `/quiz/lot/orbitales`.

- [ ] **Step 4: Commit**

```bash
git add application/controllers/Quiz.php
git commit -m "Ajoute la route et l'endpoint AJAX du quiz orbitales"
```

---

### Task 3: View `quiz_orbitales_view.php`

**Files:**
- Create: `application/views/quiz/quiz_orbitales_view.php`

**Interfaces:**
- Consumes: `$quiz['titre']`, `$quiz['description']` (set by `Quiz::orbitales()` in Task 2).
- Produces: DOM elements consumed by `assets/js/quiz_orbitales.js` (Task 4): `#quiz-orb-score`, `#quiz-orb-valeurs`, `#quiz-orb-input`, `#quiz-orb-envoyer`, `#quiz-orb-resultat`, `#quiz-orb-resultat-titre`, `#quiz-orb-explication`, `#quiz-orb-suivant-wrap`, `#quiz-orb-suivant`, `#quiz-orb-remise-zero`.

- [ ] **Step 1: Create the view file**

Create `application/views/quiz/quiz_orbitales_view.php`:

```php
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
```

- [ ] **Step 2: Lint the PHP**

Run: `php -l application/views/quiz/quiz_orbitales_view.php`
Expected: `No syntax errors detected`

- [ ] **Step 3: Commit**

```bash
git add application/views/quiz/quiz_orbitales_view.php
git commit -m "Ajoute la vue du quiz orbitales"
```

---

### Task 4: JS `quiz_orbitales.js`

**Files:**
- Create: `assets/js/quiz_orbitales.js`

**Interfaces:**
- Consumes: DOM ids from Task 3; `base_url` (global, already defined site-wide, same as used by `quiz_cs.js`); AJAX endpoint `quiz/lot/orbitales` returning an array of `{affichage, valeur, explication}` objects (Task 2).
- Produces: none consumed elsewhere — this is the leaf of the dependency chain.

- [ ] **Step 1: Create the script**

Create `assets/js/quiz_orbitales.js`:

```js
/* ====================================================================
 *
 * quiz_orbitales.js
 *
 * Quiz d'entrainement : nombre d'orbitales a partir des nombres
 * quantiques (n, l, ml)
 *
 * ==================================================================== */
$(document).ready(function()
{
    var tampon        = [];   // lot de questions en attente
    var enChargement   = false;
    var courant        = null;
    var essais         = 0;
    var reussis        = 0;
    var repondu         = false;

    var SEUIL_RECHARGE = 5;
    var CLE_SCORE       = 'quiz_orbitales_score';
    var ORDRE_CLES      = ['n', 'l', 'ml'];

    function chargerScore()
    {
        try
        {
            var sauvegarde = JSON.parse(localStorage.getItem(CLE_SCORE));

            if (sauvegarde && typeof sauvegarde.essais === 'number' && typeof sauvegarde.reussis === 'number')
            {
                essais  = sauvegarde.essais;
                reussis = sauvegarde.reussis;
            }
        }
        catch (e) {}
    }

    function sauvegarderScore()
    {
        try
        {
            localStorage.setItem(CLE_SCORE, JSON.stringify({ essais: essais, reussis: reussis }));
        }
        catch (e) {}
    }

    function chargerLot()
    {
        if (enChargement) return;

        enChargement = true;

        $.getJSON(base_url + 'quiz/lot/orbitales', function(data)
        {
            enChargement = false;

            if (data && data.length)
            {
                tampon = tampon.concat(data);
            }

            if (courant === null)
            {
                questionSuivante();
            }
        })
        .fail(function()
        {
            enChargement = false;
        });
    }

    function afficherValeurs(affichage)
    {
        var html = '';

        for (var i = 0; i < ORDRE_CLES.length; i++)
        {
            var cle = ORDRE_CLES[i];

            if (Object.prototype.hasOwnProperty.call(affichage, cle))
            {
                var etiquette = (cle === 'ml') ? 'mₗ' : cle;
                html += '<span class="quiz-orb-valeur">' + etiquette + ' = ' + affichage[cle] + '</span>';
            }
        }

        $('#quiz-orb-valeurs').html(html);
    }

    function questionSuivante()
    {
        if (tampon.length <= SEUIL_RECHARGE)
        {
            chargerLot();
        }

        if (tampon.length === 0)
        {
            $('#quiz-orb-valeurs').text('...');
            return;
        }

        courant = tampon.shift();
        repondu = false;

        afficherValeurs(courant.affichage);

        $('#quiz-orb-input').val('').prop('disabled', false);
        $('#quiz-orb-resultat').addClass('d-none');
        $('#quiz-orb-suivant-wrap').addClass('d-none');
        $('#quiz-orb-envoyer').prop('disabled', true).removeClass('d-none');

        $('#quiz-orb-input').trigger('focus');
    }

    function afficherScore()
    {
        if (essais === 0)
        {
            $('#quiz-orb-score').text("Aucun essai pour l'instant");
            return;
        }

        var moyenne = Math.round((reussis / essais) * 100);

        $('#quiz-orb-score').text(reussis + ' / ' + essais + ' (' + moyenne + ' %)');
    }

    function reponseValide()
    {
        var texte = $('#quiz-orb-input').val();

        if (texte === '' || texte === null) return false;

        var nombre = Number(texte);

        return Number.isInteger(nombre) && nombre >= 0;
    }

    function envoyer()
    {
        if (repondu || ! courant || ! reponseValide()) return;

        repondu = true;

        var reponse = parseInt($('#quiz-orb-input').val(), 10);
        var correct  = (reponse === courant.valeur);

        essais++;
        if (correct) reussis++;

        afficherScore();
        sauvegarderScore();

        $('#quiz-orb-input').prop('disabled', true);
        $('#quiz-orb-envoyer').addClass('d-none');

        var titre = correct ? 'RÉUSSI' : ('ÉCHEC — réponse attendue : ' + courant.valeur);

        $('#quiz-orb-resultat-titre').text(titre);

        if ( ! correct)
        {
            $('#quiz-orb-explication').text(courant.explication).removeClass('d-none');
        }
        else
        {
            $('#quiz-orb-explication').text('').addClass('d-none');
        }

        $('#quiz-orb-resultat')
            .removeClass('d-none reussi echec')
            .addClass(correct ? 'reussi' : 'echec');

        $('#quiz-orb-suivant-wrap').removeClass('d-none');
        $('#quiz-orb-suivant').trigger('focus');
    }

    $('#quiz-orb-input').on('input', function()
    {
        $('#quiz-orb-envoyer').prop('disabled', ! reponseValide());
    });

    $('#quiz-orb-envoyer').on('click', function()
    {
        envoyer();
    });

    $('#quiz-orb-suivant').on('click', function()
    {
        questionSuivante();
    });

    $('#quiz-orb-remise-zero').on('click', function()
    {
        essais  = 0;
        reussis = 0;

        try
        {
            localStorage.removeItem(CLE_SCORE);
        }
        catch (e) {}

        afficherScore();
    });

    // Navigation clavier : Entree = Envoyer (si une reponse valide est saisie) ou Suivant (une fois repondu)
    $(document).on('keydown', function(e)
    {
        if (e.key !== 'Enter') return;

        if (repondu && ! $('#quiz-orb-suivant-wrap').hasClass('d-none'))
        {
            e.preventDefault();
            questionSuivante();
        }
        else if ( ! repondu && reponseValide())
        {
            e.preventDefault();
            envoyer();
        }
    });

    chargerScore();
    afficherScore();
    chargerLot();
});
```

- [ ] **Step 2: Commit**

```bash
git add assets/js/quiz_orbitales.js
git commit -m "Ajoute le script du quiz orbitales"
```

---

### Task 5: End-to-end verification and quiz index entry check

**Files:**
- None modified (verification only). `quiz_liste_disponibles()` from Task 1 already makes the new quiz appear on `application/views/quiz/quiz_index_view.php` automatically, since that view iterates the manifest generically — no changes needed there.

**Interfaces:**
- Consumes: everything from Tasks 1–4.

- [ ] **Step 1: Start the app and open the quiz index**

Use this project's `run` skill (or the existing local dev server setup) to serve the app, then load `/quiz` in a browser. Confirm a new card "Nombre d'orbitales" appears alongside "Chiffres significatifs", with the description text rendering correctly (accents, `mₗ`).

- [ ] **Step 2: Exercise the golden path**

Open `/quiz/orbitales`. Confirm:
- A question renders (one or more of `n =`, `l =`, `ml =`), the "Envoyer" button is disabled until a number is typed.
- Typing a number enables "Envoyer"; clicking it (or pressing Enter) locks the input, shows RÉUSSI or ÉCHEC, and updates the score badge.
- On ÉCHEC, the explanation text appears; on RÉUSSI, it does not.
- "Suivant" loads a new question with a cleared input; "Remise à zéro" resets the score badge and clears `localStorage['quiz_orbitales_score']`.

- [ ] **Step 3: Exercise edge cases**

- Answer several questions until you observe at least one trap-type question (n+l, n+ml, or n+l+ml with expected answer 0) and confirm the explanation correctly states why the combination is invalid.
- Reload the page mid-quiz and confirm the score badge restores from `localStorage`.
- Try submitting a negative number or empty input — confirm "Envoyer" stays disabled / cannot be triggered.

- [ ] **Step 4: Clean up the throwaway verification script**

```bash
rm -f /tmp/verify_orbitales_helper.php
```

- [ ] **Step 5: Final review**

Run `git log --oneline -6` and `git status` to confirm all four commits from Tasks 1–4 are present and the working tree is clean. No commit needed for this task (verification only).

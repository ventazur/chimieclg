# Quiz : nombre d'orbitales à partir des nombres quantiques

## Objectif

Nouveau quiz d'entraînement (slug `orbitales`) demandant à l'étudiant de
déterminer le nombre d'orbitales correspondant à une combinaison partielle de
nombres quantiques (n, l, ml). Réutilise l'architecture existante du quiz
« Chiffres significatifs » (`cs`) : contrôleur `Quiz`, génération des données
côté serveur via un helper, chargement par lots en AJAX, score persistant en
`localStorage`.

## Types de question

Chaque question fournit un sous-ensemble de {n, l, ml} et demande le nombre
d'orbitales correspondant. Cinq types, tirés au hasard avec probabilité égale
(20 % chacun) :

| Type | Donné | Réponse | Piège possible |
|---|---|---|---|
| n seul | n (1 à 7) | n² | non |
| n et l | n (1 à 7), l | 2l + 1 | oui : l ≥ n → réponse 0 |
| l seul | l (0 à 6) | 2l + 1 | non (l seul est toujours valide) |
| n et ml | n (1 à 7), ml | n − \|ml\| | oui : \|ml\| ≥ n → réponse 0 |
| n, l et ml | n, l, ml | 1 si valide, sinon 0 | oui : l ≥ n, ou \|ml\| > l |

Parmi les types pouvant produire un piège (n+l, n+ml, n+l+ml), environ 5 % des
questions générées sont des combinaisons invalides (réponse 0). Le reste
génère des valeurs valides selon les règles :

- l valide : 0 ≤ l ≤ n − 1
- ml valide : −l ≤ ml ≤ l

## Génération des données (helper `chimie_helper.php`)

Nouvelle fonction `orbitales_generer_question(): array`, suivant le même
patron que `cs_generer_nombre()` : elle tire un type de question puis délègue
à une fonction dédiée par type (ex. `orbitales_generer_n_l()`).

Format de retour :

```php
[
    'affichage'   => ['n' => 3, 'l' => 1],  // uniquement les clés fournies pour ce type
    'valeur'      => 3,                      // reponse attendue (entier >= 0)
    'explication' => "...",                  // texte pedagogique
]
```

`quiz_liste_disponibles()` gagne une entrée `orbitales` (titre + description).

## Contrôleur

`Quiz::orbitales()` (vue `quiz_orbitales_view`) suit le même patron que
`Quiz::cs()`. La méthode `Quiz::lot()` gagne un `case 'orbitales'` qui appelle
`orbitales_generer_question()` en boucle (même taille de lot : 20).

## Vue et interface de réponse

Vue `quiz_orbitales_view.php`, structure similaire à `quiz_cs_view.php` mais :

- La zone de mesure affiche dynamiquement les nombres quantiques fournis
  (ex. « n = 3 », « l = 1 ») au lieu d'un nombre fixe — un bloc par clé
  présente dans `affichage`.
- L'interface de réponse est un champ `<input type="number" min="0">` plutôt
  que les boutons ronds du quiz CS (la plage de réponses possibles varie trop
  selon le type de question, de 0 à 49).
- Validation : bouton « Envoyer » activé dès que le champ contient un entier
  ≥ 0 ; Entrée valide aussi (comme CS).
- Score, bouton « Suivant », bouton « Remise à zéro » : identiques au
  patron CS, avec une clé `localStorage` dédiée (`quiz_orbitales_score`).

## Feedback

- Bonne réponse : titre « RÉUSSI », pas d'explication affichée.
- Mauvaise réponse (y compris piège manqué) : titre « ÉCHEC — réponse
  attendue : X », explication affichée pour détailler la règle (ex. « l doit
  être strictement inférieur à n, donc cette combinaison ne correspond à
  aucune orbitale. »).

## Fichiers à créer/modifier

- `application/helpers/chimie_helper.php` : ajout des fonctions génératrices
  et entrée dans `quiz_liste_disponibles()`.
- `application/controllers/Quiz.php` : méthode `orbitales()` + case dans
  `lot()`.
- `application/views/quiz/quiz_orbitales_view.php` : nouvelle vue.
- `assets/js/quiz_orbitales.js` : nouveau script, adapté de `quiz_cs.js`
  (champ numérique au lieu des boutons ronds, sinon même logique de lot/score).

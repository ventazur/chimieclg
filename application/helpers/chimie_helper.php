<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 *
 * Fonctions liees au contenu de chimie (quiz, etc.)
 *
 * ---------------------------------------------------------------------------- */

/* ----------------------------------------------------------------------------
 *
 * cs_analyser()
 *
 * ----------------------------------------------------------------------------
 *
 * Analyse un nombre et determine le (ou les) nombre(s) de chiffres significatifs
 * (CS) qu'il contient.
 *
 * Un entier sans virgule qui se termine par un ou plusieurs zeros (ex. 1500)
 * est ambigu : sans convention supplementaire (notation scientifique, trait
 * de soulignement, etc.), on ne peut pas savoir si les zeros de fin ne font
 * que fixer la position du chiffre ou s'ils sont mesures. On accepte alors
 * toute reponse entre 'min' et 'max'.
 *
 * Retourne :
 *
 * [
 *   'min'         => int,      // borne basse defendable
 *   'max'         => int,      // borne haute defendable
 *   'ambigu'      => bool,     // TRUE si min !== max
 *   'valeurs'     => int[],    // reponses acceptees [min..max]
 *   'explication' => string    // texte affiche apres la reponse
 * ]
 *
 * ---------------------------------------------------------------------------- */
function cs_analyser(string $nombre): array
{
    //
    // Normaliser : remplacer les virgules par des points, supprimer les espaces.
    //

    $s = str_replace(',', '.', trim($nombre));

    //
    // Retirer l'exposant en notation scientifique (ex. 1.00E-8 -> 1.00).
    //

    $s = preg_replace('/[eE][+-]?[0-9]+$/', '', $s);

    //
    // Retirer le signe.
    //

    $s = ltrim($s, '-+');

    //
    // Cas special : nombre nul (ex. 0, 0.0, 0000.00).
    // Chaque 0 compte comme un CS. Ce cas n'est jamais ambigu.
    //
    // Exemples :
    //
    // 0       = 1 CS
    // 0.0     = 2 CS
    // 0.00    = 3 CS
    // 0.000   = 4 CS
    // 000     = 1 CS  (zeros non significatifs avant la virgule)
    //

    if ((float) $s === 0.0)
    {
        $pos_point = strpos($s, '.');

        $n = ($pos_point === FALSE) ? 1 : 1 + strlen(substr($s, $pos_point + 1));

        return array(
            'min'         => $n,
            'max'         => $n,
            'ambigu'      => FALSE,
            'valeurs'     => array($n),
            'explication' => "Nombre nul : chaque zéro est significatif."
        );
    }

    $pos_point = strpos($s, '.');

    //
    // Presence d'une virgule : aucune ambiguite possible (c'est justement
    // ce que la convention scientifique de la virgule decimale garantit).
    // Les zeros non significatifs a gauche sont retires, tous les autres
    // chiffres (incluant les zeros entre deux chiffres et les zeros de fin
    // apres la virgule) sont significatifs.
    //
    // L'explication ne mentionne que les phenomenes reellement presents dans
    // le nombre (zero de tete, zero entre deux chiffres, zero de fin apres
    // la virgule) : elle ne doit jamais parler de zero absent du nombre.
    //

    if ($pos_point !== FALSE)
    {
        list($partie_entiere, $partie_decimale) = explode('.', $s, 2);

        $combine   = $partie_entiere . $partie_decimale;
        $sans_tete = ltrim($combine, '0');

        $n = strlen($sans_tete);

        $zeros_de_tete       = strlen($combine) - strlen($sans_tete);
        $zero_de_fin_decimal = ($partie_decimale !== '' && substr($partie_decimale, -1) === '0');
        $zero_interne        = cs_a_zero_interne($sans_tete);

        $phrases = array();

        if ($zeros_de_tete > 0)
        {
            $phrases[] = "Les zéros avant le premier chiffre non-zéro ne sont pas significatifs.";
        }

        if ($zero_interne)
        {
            $phrases[] = "Un zéro entre deux chiffres est significatif.";
        }

        if ($zero_de_fin_decimal)
        {
            $phrases[] = "Les zéros de la portion décimale, après la virgule, sont toujours significatifs.";
        }

        if (empty($phrases))
        {
            $explication = "Tous les chiffres qui ne sont pas des zéros sont toujours significatifs.";
        }
        else
        {
            $explication = implode(' ', $phrases);
        }

        return array(
            'min'         => $n,
            'max'         => $n,
            'ambigu'      => FALSE,
            'valeurs'     => array($n),
            'explication' => $explication
        );
    }

    //
    // Entier sans virgule : les zeros de fin sont ambigus.
    //

    $s = ltrim($s, '0');

    $longueur = strlen($s);
    $fin      = 0;

    for ($i = $longueur - 1; $i >= 0 && $s[$i] === '0'; $i--)
    {
        $fin++;
    }

    $max = $longueur;
    $min = $longueur - $fin;

    if ($fin === 0)
    {
        if (cs_a_zero_interne($s))
        {
            $explication = "Un zéro entre deux chiffres qui ne sont pas des zéros est significatif.";
        }
        else
        {
            $explication = "Tous les chiffres qui ne sont pas des zéros sont toujours significatifs.";
        }

        return array(
            'min'         => $max,
            'max'         => $max,
            'ambigu'      => FALSE,
            'valeurs'     => array($max),
            'explication' => $explication
        );
    }

    return array(
        'min'         => $min,
        'max'         => $max,
        'ambigu'      => TRUE,
        'valeurs'     => range($min, $max),
        'explication' => "Pour les nombres entiers se terminant par un ou plusieurs zéros (sans virgule décimale), les zéros de fin sont ambigus : de " . $min . " à " . $max . " CS, toute réponse est acceptée."
    );
}

/* ----------------------------------------------------------------------------
 *
 * cs_a_zero_interne()
 *
 * ----------------------------------------------------------------------------
 *
 * Determine si une suite de chiffres (sans zeros de tete) contient un zero
 * place entre deux chiffres non nuls (ex. 506, 1,203). Un zero uniquement
 * en fin de suite (ex. 500) n'est pas considere comme "interne" : c'est le
 * cas des zeros de fin, traite separement.
 *
 * ---------------------------------------------------------------------------- */
function cs_a_zero_interne(string $chiffres): bool
{
    return (bool) preg_match('/0[0-9]*[1-9]/', str_replace('.', '', $chiffres));
}

/* ----------------------------------------------------------------------------
 *
 * cs()
 *
 * ----------------------------------------------------------------------------
 *
 * Version canonique (compat) : retourne un seul entier, soit la borne haute
 * de cs_analyser() (les zeros de fin d'un entier ambigu sont comptes comme
 * significatifs).
 *
 * ---------------------------------------------------------------------------- */
function cs(string $nombre): int
{
    return cs_analyser($nombre)['max'];
}

/* ----------------------------------------------------------------------------
 *
 * cs_generer_entier()
 *
 * ----------------------------------------------------------------------------
 *
 * Genere un entier aleatoire (1 a 6 chiffres), parfois avec des zeros de fin
 * ambigus (ex. 1500).
 *
 * ---------------------------------------------------------------------------- */
function cs_generer_entier(): array
{
    $n_digits = random_int(1, 6);
    $fin      = ($n_digits > 1) ? random_int(0, $n_digits - 1) : 0;

    $chiffres    = array();
    $chiffres[0] = (string) random_int(1, 9);

    for ($i = 1; $i < $n_digits - $fin; $i++)
    {
        $chiffres[$i] = (string) random_int(0, 9);
    }

    if ($fin > 0)
    {
        //
        // Le dernier chiffre avant les zeros de fin doit etre non-nul,
        // sinon le nombre de zeros de fin reel serait plus grand que prevu.
        //

        $pos_dernier_sig = $n_digits - $fin - 1;

        if ($pos_dernier_sig > 0)
        {
            $chiffres[$pos_dernier_sig] = (string) random_int(1, 9);
        }

        for ($i = $n_digits - $fin; $i < $n_digits; $i++)
        {
            $chiffres[$i] = '0';
        }
    }

    ksort($chiffres);

    $nombre  = implode('', $chiffres);
    $analyse = cs_analyser($nombre);

    return array(
        'affichage'   => $nombre,
        'valeurs'     => $analyse['valeurs'],
        'ambigu'      => $analyse['ambigu'],
        'explication' => $analyse['explication']
    );
}

/* ----------------------------------------------------------------------------
 *
 * cs_generer_decimal_sup()
 *
 * ----------------------------------------------------------------------------
 *
 * Genere un decimal superieur a 1 (ex. 12,30), jamais ambigu.
 *
 * ---------------------------------------------------------------------------- */
function cs_generer_decimal_sup(): array
{
    $total    = random_int(2, 6);
    $int_len  = random_int(1, min(3, $total - 1));
    $dec_len  = $total - $int_len;

    $int_part = (string) random_int(1, 9);

    for ($i = 1; $i < $int_len; $i++)
    {
        $int_part .= (string) random_int(0, 9);
    }

    $dec_part = '';

    for ($i = 0; $i < $dec_len; $i++)
    {
        $dec_part .= (string) random_int(0, 9);
    }

    $analyse = cs_analyser($int_part . '.' . $dec_part);

    return array(
        'affichage'   => $int_part . ',' . $dec_part,
        'valeurs'     => $analyse['valeurs'],
        'ambigu'      => $analyse['ambigu'],
        'explication' => $analyse['explication']
    );
}

/* ----------------------------------------------------------------------------
 *
 * cs_generer_decimal_inf()
 *
 * ----------------------------------------------------------------------------
 *
 * Genere un decimal inferieur a 1, avec des zeros de tete (ex. 0,00456),
 * jamais ambigu.
 *
 * ---------------------------------------------------------------------------- */
function cs_generer_decimal_inf(): array
{
    $sig            = random_int(1, 6);
    $zeros_de_tete  = random_int(0, 3);

    $chiffres_sig = (string) random_int(1, 9);

    for ($i = 1; $i < $sig; $i++)
    {
        $chiffres_sig .= (string) random_int(0, 9);
    }

    $dec_part = str_repeat('0', $zeros_de_tete) . $chiffres_sig;

    $analyse = cs_analyser('0.' . $dec_part);

    return array(
        'affichage'   => '0,' . $dec_part,
        'valeurs'     => $analyse['valeurs'],
        'ambigu'      => $analyse['ambigu'],
        'explication' => $analyse['explication']
    );
}

/* ----------------------------------------------------------------------------
 *
 * cs_generer_scientifique()
 *
 * ----------------------------------------------------------------------------
 *
 * Genere un nombre en notation scientifique (ex. 4,50 × 10<sup>3</sup>), jamais
 * ambigu. L'exposant est mis en exposant via <sup> (les chiffres unicode en
 * exposant s'affichent mal, surtout a partir de 2 chiffres, selon la police).
 *
 * ---------------------------------------------------------------------------- */
function cs_generer_scientifique(): array
{
    $sig = random_int(1, 6);
    $exp = random_int(-9, 23);

    if ($sig === 1)
    {
        $premier            = (string) random_int(1, 9);
        $mantisse_machine   = $premier;
        $mantisse_affichage = $premier;
    }
    else
    {
        $premier = (string) random_int(1, 9);
        $reste   = '';

        for ($i = 1; $i < $sig; $i++)
        {
            $reste .= (string) random_int(0, 9);
        }

        $mantisse_machine   = $premier . '.' . $reste;
        $mantisse_affichage = $premier . ',' . $reste;
    }

    $analyse = cs_analyser($mantisse_machine . 'E' . $exp);

    //
    // La puissance de 10 (l'exposant) ne compte jamais pour les CS : seuls
    // les chiffres de la mantisse comptent. On le precise systematiquement,
    // en plus de l'explication propre a la mantisse.
    //

    $explication = $analyse['explication'] . " La puissance de 10 ne compte pas dans les chiffres significatifs.";

    return array(
        'affichage'   => $mantisse_affichage . ' × 10<sup>' . $exp . '</sup>',
        'valeurs'     => $analyse['valeurs'],
        'ambigu'      => $analyse['ambigu'],
        'explication' => $explication
    );
}

/* ----------------------------------------------------------------------------
 *
 * cs_generer_nombre()
 *
 * ----------------------------------------------------------------------------
 *
 * Genere un nombre aleatoire (un des 4 formats) pour le quiz des chiffres
 * significatifs. Toutes les valeurs acceptees tombent entre 1 et 6.
 *
 * Retourne :
 *
 * [
 *   'affichage'   => string,  // HTML, ex. "0,00456", "1500", "4,50 × 10<sup>3</sup>"
 *   'valeurs'     => int[],   // reponse(s) acceptee(s), 1 a 6
 *   'ambigu'      => bool,
 *   'explication' => string
 * ]
 *
 * ---------------------------------------------------------------------------- */
function cs_generer_nombre(): array
{
    $generateurs = array(
        'cs_generer_entier',
        'cs_generer_decimal_sup',
        'cs_generer_decimal_inf',
        'cs_generer_scientifique'
    );

    $fonction = $generateurs[random_int(0, count($generateurs) - 1)];

    return $fonction();
}

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
        'orbitales_generer_n_l_ml',
        'orbitales_generer_n_inf',
        'orbitales_generer_n_inf_l',
        'orbitales_generer_n_inf_ml'
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
    $n      = random_int(1, 9);
    $valeur = $n * $n;

    return array(
        'affichage'   => array('n' => $n),
        'valeur'      => $valeur,
        'explication' => "Dans la couche n = $n, l va de 0 à n-1 et chaque sous-couche l contient 2l+1 orbitales. Au total : n² = $valeur orbitales."
    );
}

function orbitales_generer_n_l(): array
{
    $n = random_int(1, 9);

    if (orbitales_piege())
    {
        $l      = $n + random_int(0, 2);
        $valeur = 0;

        $explication = "l doit toujours être strictement inférieur à n. Ici n = $n, donc l = $l est impossible : 0 orbitale.";
    }
    else
    {
        $l      = random_int(0, $n - 1);
        $valeur = 2 * $l + 1;

        $explication = "Pour l = $l, ml va de -l à +l, ce qui donne 2l+1 = $valeur orbitales.";
    }

    return array(
        'affichage'   => array('n' => $n, 'l' => $l),
        'valeur'      => $valeur,
        'explication' => $explication
    );
}

function orbitales_generer_l(): array
{
    $l      = random_int(0, 8);
    $valeur = 2 * $l + 1;

    return array(
        'affichage'   => array('l' => $l),
        'valeur'      => $valeur,
        'explication' => "Pour l = $l, ml peut prendre 2l+1 = $valeur valeurs différentes (de -l à +l). Ces $valeur orbitales existent quel que soit n, tant que n > l."
    );
}

function orbitales_generer_n_ml(): array
{
    $n = random_int(1, 9);

    if (orbitales_piege())
    {
        $abs_ml = $n + random_int(0, 2);
        $ml     = (random_int(0, 1) === 0) ? $abs_ml : -$abs_ml;
        $valeur = 0;

        $l_max = $n - 1;
        $explication = "Pour n = $n, l va au maximum jusqu'à $l_max, donc |ml| ne peut jamais atteindre $abs_ml : 0 orbitale.";
    }
    else
    {
        $abs_ml = random_int(0, $n - 1);
        $ml     = ($abs_ml === 0) ? 0 : (random_int(0, 1) === 0 ? $abs_ml : -$abs_ml);
        $valeur = $n - $abs_ml;

        $l_max = $n - 1;
        $explication = "l doit être au moins |ml| = $abs_ml et au plus n-1 = $l_max, ce qui laisse $valeur valeur(s) de l possibles, donc $valeur orbitales.";
    }

    return array(
        'affichage'   => array('n' => $n, 'ml' => $ml),
        'valeur'      => $valeur,
        'explication' => $explication
    );
}

function orbitales_generer_n_l_ml(): array
{
    $n = random_int(1, 9);

    if (orbitales_piege())
    {
        if (random_int(0, 1) === 0)
        {
            $l  = $n + random_int(0, 2);
            $ml = random_int(-$l, $l);

            $raison = "l = $l n'est pas valide pour n = $n (l doit être strictement inférieur à n)";
        }
        else
        {
            $l      = random_int(0, max(0, $n - 1));
            $abs_ml = $l + random_int(1, 2);
            $ml     = (random_int(0, 1) === 0) ? $abs_ml : -$abs_ml;

            $raison = "ml = $ml n'est pas valide pour l = $l (|ml| doit être inférieur ou égal à l)";
        }

        $valeur      = 0;
        $explication = "$raison, donc cette combinaison ne correspond à aucune orbitale.";
    }
    else
    {
        $l      = random_int(0, $n - 1);
        $ml     = random_int(-$l, $l);
        $valeur = 1;

        $explication = 'Cette combinaison (n, l, ml) désigne une orbitale unique et bien précise : 1 orbitale.';
    }

    return array(
        'affichage'   => array('n' => $n, 'l' => $l, 'ml' => $ml),
        'valeur'      => $valeur,
        'explication' => $explication
    );
}

function orbitales_generer_n_inf(): array
{
    $k = random_int(2, 9);
    $m = $k - 1;

    $valeur = intdiv($m * ($m + 1) * (2 * $m + 1), 6);

    $termes = array();

    for ($i = 1; $i <= $m; $i++)
    {
        $termes[] = $i * $i;
    }

    $explication = "n < $k signifie n = " . implode(', ', range(1, $m)) . ". Chaque n contribue n² orbitales : " . implode(' + ', $termes) . " = $valeur orbitales.";

    return array(
        'affichage'   => array('n_max' => $k),
        'valeur'      => $valeur,
        'explication' => $explication
    );
}

function orbitales_generer_n_inf_l(): array
{
    $k = random_int(2, 9);
    $m = $k - 1;

    if (orbitales_piege())
    {
        $l      = $m + random_int(0, 2);
        $valeur = 0;

        $explication = "l = $l n'est valide pour aucun n < $k (il faudrait n ≥ l+1 = " . ($l + 1) . ", mais n < $k), donc 0 orbitale.";
    }
    else
    {
        $l      = random_int(0, max(0, $m - 1));
        $nb     = $m - $l;
        $valeur = $nb * (2 * $l + 1);

        $premier_n = $l + 1;

        $explication = "Pour l = $l, seuls les n tels que n > l et n < $k conviennent, soit n = $premier_n à $m ($nb valeur(s)). Chacun contribue 2l+1 = " . (2 * $l + 1) . " orbitales, donc $nb × " . (2 * $l + 1) . " = $valeur orbitales.";
    }

    return array(
        'affichage'   => array('n_max' => $k, 'l' => $l),
        'valeur'      => $valeur,
        'explication' => $explication
    );
}

function orbitales_generer_n_inf_ml(): array
{
    $k = random_int(2, 9);
    $m = $k - 1;

    if (orbitales_piege())
    {
        $abs_ml = $m + random_int(0, 2);
        $ml     = (random_int(0, 1) === 0) ? $abs_ml : -$abs_ml;
        $valeur = 0;

        $explication = "Pour n < $k, n va au maximum jusqu'à $m, donc |ml| ne peut jamais atteindre $abs_ml pour aucun n de cette plage : 0 orbitale.";
    }
    else
    {
        $abs_ml = random_int(0, max(0, $m - 1));
        $ml     = ($abs_ml === 0) ? 0 : (random_int(0, 1) === 0 ? $abs_ml : -$abs_ml);
        $nb     = $m - $abs_ml;
        $valeur = intdiv($nb * ($nb + 1), 2);

        $premier_n = $abs_ml + 1;

        $explication = "Pour ml = $ml, seuls les n tels que n > |ml| et n < $k conviennent, soit n = $premier_n à $m ($nb valeur(s)). Chaque n contribue (n-|ml|) orbitales, et leur somme donne $valeur orbitales.";
    }

    return array(
        'affichage'   => array('n_max' => $k, 'ml' => $ml),
        'valeur'      => $valeur,
        'explication' => $explication
    );
}

/* ----------------------------------------------------------------------------
 *
 * quiz_liste_disponibles()
 *
 * ----------------------------------------------------------------------------
 *
 * Manifeste des quiz disponibles, partage entre le controleur Quiz et
 * d'autres pages (ex. Ressources) qui veulent lister les quiz. Pour ajouter
 * un quiz, ajouter une entree ici ainsi que la methode et la vue du
 * controleur Quiz correspondantes.
 *
 * ---------------------------------------------------------------------------- */
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

/* End of file chimie_helper.php */
/* Location: ./application/helpers/chimie_helper.php */

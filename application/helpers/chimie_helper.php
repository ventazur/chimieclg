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
 * etats_generer_question()
 *
 * ----------------------------------------------------------------------------
 *
 * Genere une question pour le quiz du nombre d'etats quantiques. Reutilise
 * orbitales_generer_question() tel quel (memes 8 types de combinaisons),
 * puis ajoute environ une fois sur deux le nombre quantique de spin ms
 * (+1/2 ou -1/2). Si ms n'est pas fixe, chaque orbitale compte pour 2 etats
 * (un par spin) ; si ms est fixe, le compte d'etats est le meme que le
 * compte d'orbitales.
 *
 * ---------------------------------------------------------------------------- */
function etats_generer_question(): array
{
    $base      = orbitales_generer_question();
    $ms_inclus = (random_int(0, 1) === 0);

    return etats_ajouter_spin($base, $ms_inclus);
}

/* ----------------------------------------------------------------------------
 *
 * etats_ajouter_spin()
 *
 * ----------------------------------------------------------------------------
 *
 * Applique la regle du spin a un resultat de orbitales_generer_question().
 * Isolee de l'aleatoire pour rester testable de facon deterministe.
 *
 * ---------------------------------------------------------------------------- */
function etats_ajouter_spin(array $base, bool $ms_inclus): array
{
    $affichage = $base['affichage'];

    if ($ms_inclus)
    {
        $ms = (random_int(0, 1) === 0) ? '+1/2' : '-1/2';
        $affichage['ms'] = $ms;
    }

    $valeur = $ms_inclus ? $base['valeur'] : $base['valeur'] * 2;

    if ($base['valeur'] === 0)
    {
        $explication = $base['explication'];
    }
    elseif ($ms_inclus)
    {
        $explication = $base['explication'] . " Puisque ms = $ms est fixé, chaque orbitale ne compte que pour un seul état quantique : $valeur état(s) au total.";
    }
    else
    {
        $explication = $base['explication'] . " Chaque orbitale contient 2 états quantiques (ms = +1/2 et ms = -1/2), donc $valeur états au total.";
    }

    return array(
        'affichage'   => $affichage,
        'valeur'      => $valeur,
        'explication' => $explication
    );
}

/* ----------------------------------------------------------------------------
 *
 * cases_subcouches_ordre()
 *
 * ----------------------------------------------------------------------------
 *
 * Ordre de remplissage (Aufbau) des sous-couches pour les elements Z = 1 a
 * 38 (H a Sr), avec leur capacite maximale (2 electrons par orbitale).
 * Utilise a la fois pour construire une configuration neutre et pour ajouter
 * des electrons a un anion.
 *
 * ---------------------------------------------------------------------------- */
function cases_subcouches_ordre(): array
{
    return array(
        array('n' => 1, 'l' => 0, 'capacite' => 2),  // 1s
        array('n' => 2, 'l' => 0, 'capacite' => 2),  // 2s
        array('n' => 2, 'l' => 1, 'capacite' => 6),  // 2p
        array('n' => 3, 'l' => 0, 'capacite' => 2),  // 3s
        array('n' => 3, 'l' => 1, 'capacite' => 6),  // 3p
        array('n' => 4, 'l' => 0, 'capacite' => 2),  // 4s
        array('n' => 3, 'l' => 2, 'capacite' => 10), // 3d
        array('n' => 4, 'l' => 1, 'capacite' => 6),  // 4p
        array('n' => 5, 'l' => 0, 'capacite' => 2),  // 5s
    );
}

/* ----------------------------------------------------------------------------
 *
 * cases_symboles() / cases_noms()
 *
 * ----------------------------------------------------------------------------
 *
 * Symboles et noms francais des elements Z = 1 (H) a Z = 38 (Sr).
 *
 * ---------------------------------------------------------------------------- */
function cases_symboles(): array
{
    return array(
        1 => 'H',  2 => 'He', 3 => 'Li', 4 => 'Be', 5 => 'B',  6 => 'C',
        7 => 'N',  8 => 'O',  9 => 'F',  10 => 'Ne', 11 => 'Na', 12 => 'Mg',
        13 => 'Al', 14 => 'Si', 15 => 'P', 16 => 'S', 17 => 'Cl', 18 => 'Ar',
        19 => 'K', 20 => 'Ca', 21 => 'Sc', 22 => 'Ti', 23 => 'V', 24 => 'Cr',
        25 => 'Mn', 26 => 'Fe', 27 => 'Co', 28 => 'Ni', 29 => 'Cu', 30 => 'Zn',
        31 => 'Ga', 32 => 'Ge', 33 => 'As', 34 => 'Se', 35 => 'Br', 36 => 'Kr',
        37 => 'Rb', 38 => 'Sr',
    );
}

function cases_noms(): array
{
    return array(
        1 => 'Hydrogène',  2 => 'Hélium',    3 => 'Lithium',   4 => 'Béryllium',
        5 => 'Bore',       6 => 'Carbone',   7 => 'Azote',     8 => 'Oxygène',
        9 => 'Fluor',      10 => 'Néon',     11 => 'Sodium',   12 => 'Magnésium',
        13 => 'Aluminium', 14 => 'Silicium', 15 => 'Phosphore', 16 => 'Soufre',
        17 => 'Chlore',    18 => 'Argon',    19 => 'Potassium', 20 => 'Calcium',
        21 => 'Scandium',  22 => 'Titane',   23 => 'Vanadium', 24 => 'Chrome',
        25 => 'Manganèse', 26 => 'Fer',      27 => 'Cobalt',   28 => 'Nickel',
        29 => 'Cuivre',    30 => 'Zinc',     31 => 'Gallium',  32 => 'Germanium',
        33 => 'Arsenic',   34 => 'Sélénium', 35 => 'Brome',    36 => 'Krypton',
        37 => 'Rubidium',  38 => 'Strontium',
    );
}

/* ----------------------------------------------------------------------------
 *
 * cases_charges_reelles()
 *
 * ----------------------------------------------------------------------------
 *
 * Charges d'ions monoatomiques courants (et realistes) par element, Z = 1 a
 * 38. Un element absent (ou associe a un tableau vide) n'a pas d'ion
 * monoatomique usuel : les gaz nobles (He, Ne, Ar, Kr) et quelques elements a
 * caractere surtout covalent (C, Si, Ge, As) en font partie.
 *
 * ---------------------------------------------------------------------------- */
function cases_charges_reelles(): array
{
    return array(
        1  => array(-1),        // H-
        3  => array(1),         // Li+
        4  => array(2),         // Be2+
        5  => array(3),         // B3+
        7  => array(-3),        // N3-
        8  => array(-2),        // O2-
        9  => array(-1),        // F-
        11 => array(1),         // Na+
        12 => array(2),         // Mg2+
        13 => array(3),         // Al3+
        15 => array(-3),        // P3-
        16 => array(-2),        // S2-
        17 => array(-1),        // Cl-
        19 => array(1),         // K+
        20 => array(2),         // Ca2+
        21 => array(3),         // Sc3+
        22 => array(4),         // Ti4+
        23 => array(3),         // V3+
        24 => array(2, 3),      // Cr2+, Cr3+
        25 => array(2),         // Mn2+
        26 => array(2, 3),      // Fe2+, Fe3+
        27 => array(2, 3),      // Co2+, Co3+
        28 => array(2),         // Ni2+
        29 => array(1, 2),      // Cu+, Cu2+
        30 => array(2),         // Zn2+
        31 => array(3),         // Ga3+
        34 => array(-2),        // Se2-
        35 => array(-1),        // Br-
        37 => array(1),         // Rb+
        38 => array(2),         // Sr2+
    );
}

/* ----------------------------------------------------------------------------
 *
 * cases_lettre_l() / cases_exposant_charge()
 *
 * ---------------------------------------------------------------------------- */
function cases_lettre_l(int $l): string
{
    $lettres = array(0 => 's', 1 => 'p', 2 => 'd', 3 => 'f');

    return $lettres[$l] ?? '?';
}

function cases_exposant_charge(int $charge): string
{
    // Balise <sup> (plutot que des caracteres unicode en exposant, dont la
    // taille est figee par la police) pour pouvoir agrandir la charge en CSS.
    $signe = ($charge > 0) ? '+' : '−';
    $valeur_absolue = abs($charge);
    $texte = ($valeur_absolue === 1) ? $signe : ($valeur_absolue . $signe);

    return '<sup class="quiz-cases-charge">' . $texte . '</sup>';
}

/* ----------------------------------------------------------------------------
 *
 * cases_config_neutre()
 *
 * ----------------------------------------------------------------------------
 *
 * Construit la configuration electronique (par sous-couches) de l'atome
 * neutre de numero atomique $z, en remplissant les sous-couches dans l'ordre
 * d'Aufbau. Applique ensuite les deux exceptions d'Aufbau presentes dans
 * cette plage : Cr (Z=24) = [Ar] 3d5 4s1 et Cu (Z=29) = [Ar] 3d10 4s1.
 *
 * Retourne une liste ordonnee de sous-couches array('n', 'l', 'k') (k =
 * nombre d'electrons dans la sous-couche), dans l'ordre de remplissage.
 *
 * ---------------------------------------------------------------------------- */
function cases_config_neutre(int $z): array
{
    $restant = $z;
    $config  = array();

    foreach (cases_subcouches_ordre() as $sous_couche)
    {
        if ($restant <= 0) break;

        $k = min($restant, $sous_couche['capacite']);

        $config[] = array('n' => $sous_couche['n'], 'l' => $sous_couche['l'], 'k' => $k);

        $restant -= $k;
    }

    if ($z === 24 || $z === 29)
    {
        $k_4s = ($z === 24) ? 1 : 1;
        $k_3d = ($z === 24) ? 5 : 10;

        foreach ($config as &$sous_couche)
        {
            if ($sous_couche['n'] === 4 && $sous_couche['l'] === 0) $sous_couche['k'] = $k_4s;
            if ($sous_couche['n'] === 3 && $sous_couche['l'] === 2) $sous_couche['k'] = $k_3d;
        }
        unset($sous_couche);
    }

    return $config;
}

/* ----------------------------------------------------------------------------
 *
 * cases_appliquer_ion()
 *
 * ----------------------------------------------------------------------------
 *
 * Applique une charge a une configuration electronique neutre :
 *
 *   - cation (charge > 0) : retire les electrons un a un en commencant par la
 *     sous-couche de n le plus eleve (puis de l le plus eleve a n egal). Pour
 *     les metaux de transition, cela retire les electrons ns avant les
 *     electrons (n-1)d, conformement a la convention des manuels.
 *   - anion (charge < 0) : ajoute les electrons en suivant l'ordre d'Aufbau,
 *     en completant d'abord la sous-couche la plus externe deja entamee.
 *
 * ---------------------------------------------------------------------------- */
function cases_appliquer_ion(array $config, int $charge): array
{
    $ordre   = cases_subcouches_ordre();
    $comptes = array();

    foreach ($config as $sous_couche)
    {
        $comptes[$sous_couche['n'] . '_' . $sous_couche['l']] = $sous_couche['k'];
    }

    if ($charge > 0)
    {
        for ($i = 0; $i < $charge; $i++)
        {
            $meilleure_cle = null;
            $meilleur_n    = -1;
            $meilleur_l    = -1;

            foreach ($comptes as $cle => $k)
            {
                if ($k <= 0) continue;

                list($n, $l) = array_map('intval', explode('_', $cle));

                if ($n > $meilleur_n || ($n === $meilleur_n && $l > $meilleur_l))
                {
                    $meilleur_n    = $n;
                    $meilleur_l    = $l;
                    $meilleure_cle = $cle;
                }
            }

            if ($meilleure_cle === null) break; // plus d'electron a retirer

            $comptes[$meilleure_cle]--;
        }
    }
    elseif ($charge < 0)
    {
        $restant = -$charge;

        foreach ($ordre as $sous_couche)
        {
            if ($restant <= 0) break;

            $cle     = $sous_couche['n'] . '_' . $sous_couche['l'];
            $present = $comptes[$cle] ?? 0;
            $espace  = $sous_couche['capacite'] - $present;

            if ($espace <= 0) continue;

            $ajout = min($espace, $restant);

            $comptes[$cle] = $present + $ajout;
            $restant -= $ajout;
        }
    }

    $resultat = array();

    foreach ($ordre as $sous_couche)
    {
        $cle = $sous_couche['n'] . '_' . $sous_couche['l'];
        $k   = $comptes[$cle] ?? 0;

        if ($k > 0)
        {
            $resultat[] = array('n' => $sous_couche['n'], 'l' => $sous_couche['l'], 'k' => $k);
        }
    }

    return $resultat;
}

/* ----------------------------------------------------------------------------
 *
 * cases_hund_boxes()
 *
 * ----------------------------------------------------------------------------
 *
 * Repartit k electrons dans les 2l+1 cases quantiques (orbitales) d'une
 * sous-couche selon la regle de Hund : chaque case recoit un premier
 * electron (de gauche a droite), puis les cases sont appariees (toujours de
 * gauche a droite) une fois que toutes en contiennent un.
 *
 * Convention : la case d'indice 0 correspond a mₗ = -l, la derniere a
 * mₗ = +l.
 *
 * Retourne un tableau de $2l+1$ chaines : '' (vide), '↑' (celibataire) ou
 * '↑↓' (appariee).
 *
 * ---------------------------------------------------------------------------- */
function cases_hund_boxes(int $n, int $l, int $k): array
{
    $nb_cases = 2 * $l + 1;
    $cases    = array_fill(0, $nb_cases, 0);
    $restant  = $k;

    for ($i = 0; $i < $nb_cases && $restant > 0; $i++)
    {
        $cases[$i] = 1;
        $restant--;
    }

    for ($i = 0; $i < $nb_cases && $restant > 0; $i++)
    {
        if ($cases[$i] === 1)
        {
            $cases[$i] = 2;
            $restant--;
        }
    }

    return array_map(function ($nb_electrons)
    {
        if ($nb_electrons === 2) return '↑↓';
        if ($nb_electrons === 1) return '↑';
        return '';
    }, $cases);
}

/* ----------------------------------------------------------------------------
 *
 * cases_valence_coeur()
 *
 * ----------------------------------------------------------------------------
 *
 * Compte les electrons de valence et de coeur d'un atome NEUTRE (la notion de
 * valence n'est pas demandee pour un ion, voir cases_generer_question()).
 *
 * Convention : valence = electrons de la couche n la plus elevee (ns, np) ;
 * pour les metaux de transition (Sc a Zn, Z=21 a 30), les electrons (n-1)d
 * sont egalement comptes comme electrons de valence.
 *
 * ---------------------------------------------------------------------------- */
function cases_valence_coeur(array $config, int $z): array
{
    $n_max           = max(array_column($config, 'n'));
    $est_transition  = ($z >= 21 && $z <= 30);
    $valence         = 0;
    $coeur           = 0;

    foreach ($config as $sous_couche)
    {
        $est_valence = ($sous_couche['n'] === $n_max)
            || ($est_transition && $sous_couche['l'] === 2 && $sous_couche['n'] === $n_max - 1);

        if ($est_valence)
        {
            $valence += $sous_couche['k'];
        }
        else
        {
            $coeur += $sous_couche['k'];
        }
    }

    return array('valence' => $valence, 'coeur' => $coeur);
}

/* ----------------------------------------------------------------------------
 *
 * cases_compter_ml() / cases_compter_l() / cases_orbitales_occupees() /
 * cases_non_apparies() / cases_ml_plus_haute_energie()
 *
 * ----------------------------------------------------------------------------
 *
 * Comptes derives d'une configuration electronique (atome neutre ou ion).
 *
 * ---------------------------------------------------------------------------- */
function cases_compter_ml(array $config, int $ml): int
{
    $total = 0;

    foreach ($config as $sous_couche)
    {
        if (abs($ml) > $sous_couche['l']) continue;

        $boites = cases_hund_boxes($sous_couche['n'], $sous_couche['l'], $sous_couche['k']);
        $indice = $ml + $sous_couche['l'];
        $boite  = $boites[$indice];

        $total += ($boite === '↑↓') ? 2 : (($boite === '↑') ? 1 : 0);
    }

    return $total;
}

function cases_compter_l(array $config, int $l): int
{
    $total = 0;

    foreach ($config as $sous_couche)
    {
        if ($sous_couche['l'] === $l) $total += $sous_couche['k'];
    }

    return $total;
}

function cases_orbitales_occupees(array $config): int
{
    $total = 0;

    foreach ($config as $sous_couche)
    {
        $total += min($sous_couche['k'], 2 * $sous_couche['l'] + 1);
    }

    return $total;
}

function cases_non_apparies(array $config): int
{
    $total = 0;

    foreach ($config as $sous_couche)
    {
        $nb_cases = 2 * $sous_couche['l'] + 1;
        $k        = $sous_couche['k'];

        $total += ($k <= $nb_cases) ? $k : (2 * $nb_cases - $k);
    }

    return $total;
}

function cases_ml_plus_haute_energie(array $config): int
{
    $sous_couche = end($config); // derniere sous-couche remplie = la plus haute en energie

    $l = $sous_couche['l'];
    $k = $sous_couche['k'];
    $nb_cases = 2 * $l + 1;

    return ($k <= $nb_cases) ? (-$l + ($k - 1)) : ($k - 3 * $l - 2);
}

/* ----------------------------------------------------------------------------
 *
 * cases_rendre_diagramme()
 *
 * ----------------------------------------------------------------------------
 *
 * Prepare une configuration electronique pour l'affichage sous forme de
 * cases quantiques (une entree par sous-couche, dans l'ordre de remplissage).
 *
 * ---------------------------------------------------------------------------- */
function cases_rendre_diagramme(array $config): array
{
    $diagramme = array();

    foreach ($config as $sous_couche)
    {
        $diagramme[] = array(
            'etiquette' => $sous_couche['n'] . cases_lettre_l($sous_couche['l']),
            'n'         => $sous_couche['n'],
            'l'         => $sous_couche['l'],
            'boites'    => cases_hund_boxes($sous_couche['n'], $sous_couche['l'], $sous_couche['k']),
        );
    }

    return $diagramme;
}

/* ----------------------------------------------------------------------------
 *
 * cases_generer_question()
 *
 * ----------------------------------------------------------------------------
 *
 * Genere une instance complete du quiz "Cases quantiques" : un element tire
 * au hasard (Z = 1 a 38), sa configuration electronique par cases quantiques
 * (atome neutre uniquement - c'est a l'etudiant de deriver l'ion), et 5
 * questions numeriques tirees parmi 6 types possibles, portant sur l'atome
 * neutre et/ou sur un de ses ions (charge realiste, si l'element en a une).
 *
 * Types de question :
 *   'valence_coeur' : electrons de valence OU de coeur (atome neutre seul)
 *   'ml'             : electrons dont mₗ = X
 *   'l'              : electrons dont l = Y (orbitales s/p/d)
 *   'orbitales'      : nombre d'orbitales occupees
 *   'non_apparies'   : nombre d'electrons non-apparies
 *   'ml_max'         : mₗ de l'electron de plus haute energie
 *
 * Retourne :
 *
 * [
 *   'element'   => array('z', 'symbole', 'nom'),
 *   'cases'     => array,              // diagramme de l'atome NEUTRE
 *   'ion'       => array('charge','symbole') | null,
 *   'questions' => [ array('enonce', 'contexte', 'valeur', 'explication') x5 ]
 * ]
 *
 * ---------------------------------------------------------------------------- */
function cases_generer_question(): array
{
    $z        = random_int(1, 38);
    $symboles = cases_symboles();
    $noms     = cases_noms();
    $charges  = cases_charges_reelles();

    $config_neutre = cases_config_neutre($z);

    $ion = null;
    $config_ion = null;

    if ( ! empty($charges[$z]))
    {
        $charge_choisie = $charges[$z][random_int(0, count($charges[$z]) - 1)];

        $config_ion = cases_appliquer_ion($config_neutre, $charge_choisie);
        $ion = array(
            'charge'  => $charge_choisie,
            'symbole' => '<span class="quiz-cases-symbole">' . $symboles[$z] . '</span>' . cases_exposant_charge($charge_choisie),
        );
    }

    $types_disponibles = array('valence_coeur', 'ml', 'l', 'orbitales', 'non_apparies', 'ml_max');
    shuffle($types_disponibles);
    $types_choisis = array_slice($types_disponibles, 0, 5);

    // Contexte ('neutre' ou 'ion') choisi pour chaque type retenu. Suivi a
    // part (plutot que relu depuis le texte affiche) pour rester fiable.
    $contextes_choisis = array();

    foreach ($types_choisis as $type)
    {
        if ($type === 'valence_coeur')
        {
            $contextes_choisis[] = 'neutre';
        }
        else
        {
            $contextes_choisis[] = ($ion !== null && random_int(0, 1) === 0) ? 'ion' : 'neutre';
        }
    }

    // Garantit au moins une question sur l'atome neutre et, si un ion existe,
    // au moins une question sur cet ion. Les deux forcages ne doivent jamais
    // se marcher dessus : chacun evite l'indice deja fixe par l'autre.
    if ($ion !== null)
    {
        $indice_force_ion = null;

        if ( ! in_array('ion', $contextes_choisis, true))
        {
            foreach ($types_choisis as $i => $type)
            {
                if ($type !== 'valence_coeur')
                {
                    $contextes_choisis[$i] = 'ion';
                    $indice_force_ion = $i;
                    break;
                }
            }
        }

        if ( ! in_array('neutre', $contextes_choisis, true))
        {
            foreach ($types_choisis as $i => $type)
            {
                if ($type !== 'valence_coeur' && $i !== $indice_force_ion)
                {
                    $contextes_choisis[$i] = 'neutre';
                    break;
                }
            }
        }
    }

    $questions = array();

    foreach ($types_choisis as $i => $type)
    {
        $questions[] = cases_construire_question($type, $contextes_choisis[$i], $config_neutre, $config_ion, $z, $ion);
    }

    return array(
        'element'   => array('z' => $z, 'symbole' => $symboles[$z], 'nom' => $noms[$z]),
        'cases'     => cases_rendre_diagramme($config_neutre),
        'ion'       => $ion,
        'questions' => $questions,
    );
}

/* ----------------------------------------------------------------------------
 *
 * cases_construire_question()
 *
 * ----------------------------------------------------------------------------
 *
 * Construit une question d'un type donne, dans un contexte donne ('neutre'
 * ou 'ion'). Isolee de cases_generer_question() pour rester testable de
 * facon deterministe.
 *
 * ---------------------------------------------------------------------------- */
function cases_construire_question(string $type, string $contexte, array $config_neutre, ?array $config_ion, int $z, ?array $ion): array
{
    $config          = ($contexte === 'ion') ? $config_ion : $config_neutre;
    $contexte_libelle = ($contexte === 'ion') ? ("l'ion " . $ion['symbole']) : "l'atome neutre";

    switch ($type)
    {
        case 'valence_coeur':
            $compte = cases_valence_coeur($config_neutre, $z);
            $demander_valence = (random_int(0, 1) === 0);

            if ($demander_valence)
            {
                return array(
                    'enonce'      => "Combien d'électrons de valence l'atome neutre possède-t-il ?",
                    'contexte'    => $contexte_libelle,
                    'valeur'      => $compte['valence'],
                    'explication' => "L'atome neutre possède {$compte['valence']} électron(s) de valence et {$compte['coeur']} électron(s) de cœur.",
                );
            }

            return array(
                'enonce'      => "Combien d'électrons de cœur l'atome neutre possède-t-il ?",
                'contexte'    => $contexte_libelle,
                'valeur'      => $compte['coeur'],
                'explication' => "L'atome neutre possède {$compte['coeur']} électron(s) de cœur et {$compte['valence']} électron(s) de valence.",
            );

        case 'ml':
            $ml     = random_int(-2, 2);
            $valeur = cases_compter_ml($config, $ml);

            return array(
                'enonce'      => "Combien d'électrons ont mₗ = $ml ?",
                'contexte'    => $contexte_libelle,
                'valeur'      => $valeur,
                'explication' => "Pour $contexte_libelle, $valeur électron(s) occupent une case mₗ = $ml (toutes sous-couches confondues).",
            );

        case 'l':
            $l      = random_int(0, 2);
            $lettre = cases_lettre_l($l);
            $valeur = cases_compter_l($config, $l);

            return array(
                'enonce'      => "Combien d'électrons ont l = $l ?",
                'contexte'    => $contexte_libelle,
                'valeur'      => $valeur,
                'explication' => "Pour $contexte_libelle, $valeur électron(s) se trouvent dans une orbitale $lettre (l = $l).",
            );

        case 'orbitales':
            $valeur = cases_orbitales_occupees($config);

            return array(
                'enonce'      => "Combien d'orbitales sont occupées ?",
                'contexte'    => $contexte_libelle,
                'valeur'      => $valeur,
                'explication' => "Pour $contexte_libelle, $valeur orbitale(s) contiennent au moins un électron.",
            );

        case 'non_apparies':
            $valeur = cases_non_apparies($config);
            $terme  = (random_int(0, 1) === 0) ? 'non-appariés' : 'célibataires';

            return array(
                'enonce'      => "Combien d'électrons sont $terme ?",
                'contexte'    => $contexte_libelle,
                'valeur'      => $valeur,
                'explication' => "Pour $contexte_libelle, $valeur électron(s) occupent seuls leur case quantique.",
            );

        case 'ml_max':
        default:
            $valeur = cases_ml_plus_haute_energie($config);

            return array(
                'enonce'      => "Quel est le nombre quantique mₗ de l'électron de plus haute énergie ?",
                'contexte'    => $contexte_libelle,
                'valeur'      => $valeur,
                'explication' => "Pour $contexte_libelle, la sous-couche de plus haute énergie occupée place son dernier électron dans la case mₗ = $valeur.",
            );
    }
}

/* ----------------------------------------------------------------------------
 *
 * nomen_banque_paires() / nomen_generer_manche()
 *
 * ----------------------------------------------------------------------------
 *
 * Banque de formules (acides, molecules, ions polyatomiques) associees a
 * leur nom, utilisee par le quiz d'appariement 'nomen'.
 *
 * ---------------------------------------------------------------------------- */
function nomen_banque_paires(): array
{
    return array(
        array('struct' => 'HCl',                                              'nom' => 'acide chlorhydrique'),
        array('struct' => 'HBr',                                              'nom' => 'acide bromhydrique'),
        array('struct' => 'HI',                                               'nom' => 'acide iodhydrique'),
        array('struct' => 'HF',                                               'nom' => 'acide fluorhydrique'),
        array('struct' => 'HNO<sub>3</sub>',                                  'nom' => 'acide nitrique'),
        array('struct' => 'H<sub>2</sub>SO<sub>4</sub>',                      'nom' => 'acide sulfurique'),
        array('struct' => 'H<sub>2</sub>CO<sub>3</sub>',                      'nom' => 'acide carbonique'),
        array('struct' => 'H<sub>3</sub>PO<sub>4</sub>',                      'nom' => 'acide phosphorique'),
        array('struct' => 'HClO<sub>4</sub>',                                 'nom' => 'acide perchlorique'),
        array('struct' => 'HClO<sub>3</sub>',                                 'nom' => 'acide chlorique'),
        array('struct' => 'HCOOH',                                            'nom' => 'acide formique (méthanoïque)'),
        array('struct' => 'CH<sub>3</sub>COOH',                               'nom' => 'acide acétique (éthanoïque)'),
        array('struct' => 'HCN',                                              'nom' => 'acide cyanhydrique'),
        array('struct' => 'NH<sub>3</sub>',                                   'nom' => 'ammoniac'),
        array('struct' => 'NaOH',                                             'nom' => 'hydroxyde de sodium'),
        array('struct' => 'KOH',                                              'nom' => 'hydroxyde de potassium'),
        array('struct' => 'NH<sub>4</sub><sup>+</sup>',                       'nom' => 'ion ammonium'),
        array('struct' => 'H<sub>3</sub>O<sup>+</sup>',                       'nom' => 'ion hydronium'),
        array('struct' => 'OH<sup>-</sup>',                                   'nom' => 'ion hydroxyde'),
        array('struct' => 'NO<sub>3</sub><sup>-</sup>',                       'nom' => 'ion nitrate'),
        array('struct' => 'NO<sub>2</sub><sup>-</sup>',                       'nom' => 'ion nitrite'),
        array('struct' => 'PO<sub>4</sub><sup>3-</sup>',                      'nom' => 'ion phosphate'),
        array('struct' => 'H<sub>2</sub>PO<sub>4</sub><sup>-</sup>',          'nom' => 'ion dihydrogénophosphate'),
        array('struct' => 'CO<sub>3</sub><sup>2-</sup>',                      'nom' => 'ion carbonate'),
        array('struct' => 'HCO<sub>3</sub><sup>-</sup>',                      'nom' => 'ion hydrogénocarbonate'),
        array('struct' => 'SO<sub>3</sub><sup>2-</sup>',                      'nom' => 'ion sulfite'),
        array('struct' => 'HSO<sub>4</sub><sup>-</sup>',                      'nom' => 'ion hydrogénosulfate'),
        array('struct' => 'CH<sub>3</sub>COO<sup>-</sup>',                    'nom' => 'ion acétate'),
        array('struct' => 'C<sub>2</sub>O<sub>4</sub><sup>2-</sup>',          'nom' => 'ion oxalate'),
        array('struct' => 'CN<sup>-</sup>',                                   'nom' => 'ion cyanure'),
        array('struct' => 'SCN<sup>-</sup>',                                  'nom' => 'ion thiocyanate'),
        array('struct' => 'ClO<sup>-</sup>',                                  'nom' => 'ion hypochlorite'),
        array('struct' => 'ClO<sub>2</sub><sup>-</sup>',                      'nom' => 'ion chlorite'),
        array('struct' => 'ClO<sub>3</sub><sup>-</sup>',                      'nom' => 'ion chlorate'),
        array('struct' => 'ClO<sub>4</sub><sup>-</sup>',                      'nom' => 'ion perchlorate'),
        array('struct' => 'CrO<sub>4</sub><sup>2-</sup>',                     'nom' => 'ion chromate'),
        array('struct' => 'Cr<sub>2</sub>O<sub>7</sub><sup>2-</sup>',         'nom' => 'ion dichromate'),
        array('struct' => 'MnO<sub>4</sub><sup>-</sup>',                      'nom' => 'ion permanganate'),
    );
}

function nomen_generer_manche(): array
{
    $banque = nomen_banque_paires();

    shuffle($banque);

    return array(
        'paires' => array_slice($banque, 0, 5),
    );
}

/* ----------------------------------------------------------------------------
 *
 * fonctions_banque() / fonctions_generer_manche()
 *
 * ----------------------------------------------------------------------------
 *
 * Banque de fonctions chimiques organiques (un representant canonique par
 * fonction), utilisee par le quiz d'appariement 'fonctions'. Le cycle
 * aromatique (fonctions 'aromatique' et 'phenol') est abrege 'Ph' (phenyle),
 * comme en notation semi-developpee usuelle.
 *
 * ---------------------------------------------------------------------------- */
function fonctions_banque(): array
{
    return array(
        array('cle' => 'alcool',      'struct' => 'CH<sub>3</sub>–CH<sub>2</sub>–OH',                        'nom' => 'alcool'),
        array('cle' => 'ester',       'struct' => 'CH<sub>3</sub>–COO–CH<sub>3</sub>',                        'nom' => 'ester'),
        array('cle' => 'ether',       'struct' => 'CH<sub>3</sub>–O–CH<sub>3</sub>',                          'nom' => 'éther'),
        array('cle' => 'acide',       'struct' => 'CH<sub>3</sub>–COOH',                                      'nom' => 'acide carboxylique'),
        array('cle' => 'amine',       'struct' => 'CH<sub>3</sub>–CH<sub>2</sub>–NH<sub>2</sub>',              'nom' => 'amine'),
        array('cle' => 'amide',       'struct' => 'CH<sub>3</sub>–CO–NH<sub>2</sub>',                         'nom' => 'amide'),
        array('cle' => 'phenol',      'struct' => 'Ph–OH',                                                    'nom' => 'phénol'),
        array('cle' => 'aldehyde',    'struct' => 'CH<sub>3</sub>–CHO',                                       'nom' => 'aldéhyde'),
        array('cle' => 'thiol',       'struct' => 'CH<sub>3</sub>–CH<sub>2</sub>–SH',                         'nom' => 'thiol'),
        array('cle' => 'alcyne',      'struct' => 'CH≡CH',                                                    'nom' => 'alcyne'),
        array('cle' => 'alcene',      'struct' => 'CH<sub>2</sub>=CH<sub>2</sub>',                            'nom' => 'alcène'),
        array('cle' => 'aromatique',  'struct' => 'ArH',                                                     'nom' => 'aromatique'),
        array('cle' => 'halogenure',  'struct' => 'CH<sub>3</sub>–CH<sub>2</sub>–Cl',                         'nom' => 'halogénure'),
        array('cle' => 'cetone',      'struct' => 'CH<sub>3</sub>–CO–CH<sub>3</sub>',                        'nom' => 'cétone'),
        array('cle' => 'nitrile',     'struct' => 'CH<sub>3</sub>–C≡N',                                       'nom' => 'nitrile'),
    );
}

function fonctions_generer_manche(): array
{
    $conflits = array(
        'phenol'     => array('aromatique', 'alcool'),
        'aromatique' => array('phenol'),
        'alcool'     => array('phenol'),
    );

    $banque = fonctions_banque();
    shuffle($banque);

    $retenues = array();
    $cles     = array();

    foreach ($banque as $entree)
    {
        if (count($retenues) >= 5) break;

        $en_conflit = false;

        if ( ! empty($conflits[$entree['cle']]))
        {
            foreach ($conflits[$entree['cle']] as $autre)
            {
                if (in_array($autre, $cles, true))
                {
                    $en_conflit = true;
                    break;
                }
            }
        }

        if ($en_conflit) continue;

        $retenues[] = array('struct' => $entree['struct'], 'nom' => $entree['nom']);
        $cles[]     = $entree['cle'];
    }

    return array(
        'paires' => $retenues,
    );
}

/* ----------------------------------------------------------------------------
 *
 * conversions_table_prefixes()
 *
 * ----------------------------------------------------------------------------
 *
 * Table des prefixes SI utilisee par les generateurs du quiz de conversion,
 * avec leur puissance de 10.
 *
 * ---------------------------------------------------------------------------- */
function conversions_table_prefixes(): array
{
    return array(
        'T'  => 12,
        'G'  => 9,
        'M'  => 6,
        'k'  => 3,
        'h'  => 2,
        'da' => 1,
        ''   => 0,
        'd'  => -1,
        'c'  => -2,
        'm'  => -3,
        'µ'  => -6,
        'n'  => -9,
        'p'  => -12,
    );
}

/* ----------------------------------------------------------------------------
 *
 * conversions_generer_chiffres_significatifs()
 *
 * ----------------------------------------------------------------------------
 *
 * Genere 2 ou 3 chiffres significatifs, sans zero terminal ambigu (le premier
 * et le dernier chiffre sont non nuls).
 *
 * Retourne [ $s, $mantisse_affichage, $mantisse_valeur, $n ].
 *
 * ---------------------------------------------------------------------------- */
function conversions_generer_chiffres_significatifs(): array
{
    $n = random_int(2, 3);
    $s = (string) random_int(1, 9);

    for ($i = 0; $i < $n - 2; $i++)
    {
        $s .= (string) random_int(0, 9);
    }

    $s .= (string) random_int(1, 9);

    $mantisse_affichage = substr($s, 0, 1) . ',' . substr($s, 1);
    $mantisse_valeur    = (float) (substr($s, 0, 1) . '.' . substr($s, 1));

    return array($s, $mantisse_affichage, $mantisse_valeur, $n);
}

/* ----------------------------------------------------------------------------
 *
 * conversions_formater_nombre()
 *
 * ----------------------------------------------------------------------------
 *
 * Formate les chiffres significatifs $s (longueur $n) en un nombre affiche
 * sans notation scientifique, decale de $k crans (k <= 0 pour ne jamais
 * ajouter de zero terminal a un entier, ce qui rendrait le nombre de CS
 * ambigu).
 *
 * ---------------------------------------------------------------------------- */
function conversions_formater_nombre(string $s, int $n, int $k): string
{
    if ($k >= 0)
    {
        return $s . str_repeat('0', $k);
    }

    $decalage = -$k;

    if ($decalage < $n)
    {
        $partie_entiere  = substr($s, 0, $n - $decalage);
        $partie_decimale = substr($s, $n - $decalage);
    }
    else
    {
        $partie_entiere  = '0';
        $partie_decimale = str_repeat('0', $decalage - $n) . $s;
    }

    return $partie_entiere . ',' . $partie_decimale;
}

/* ----------------------------------------------------------------------------
 *
 * conversions_generer_question_lineaire()
 *
 * ----------------------------------------------------------------------------
 *
 * Genere une conversion d'unite entre prefixes SI (T, G, M, k, h, da, [aucun],
 * d, c, m, µ, n, p) sur une unite de base (s, g, m, L). La valeur de depart
 * est affichee sans notation scientifique ; l'etudiant doit fournir la
 * reponse en notation scientifique normalisee (1 <= mantisse < 10), sous
 * forme de deux valeurs : la mantisse et l'exposant.
 *
 * Comme un changement de prefixe ne fait que decaler la puissance de 10, les
 * chiffres significatifs (2 ou 3, jamais de zero terminal ambigu) sont
 * conserves tels quels dans la mantisse de la reponse ; seul l'exposant
 * change.
 *
 * Retourne :
 *
 * [
 *   'nombre'             => string,  // valeur affichee (virgule), ex. "0,0134"
 *   'source'             => string,  // unite de depart, ex. "nm"
 *   'cible'               => string,  // unite demandee, ex. "km"
 *   'mantisse_valeur'    => float,   // ex. 1.34
 *   'mantisse_affichage' => string,  // ex. "1,34"
 *   'exposant'           => int,     // ex. -14
 *   'explication'        => string
 * ]
 *
 * ---------------------------------------------------------------------------- */
function conversions_generer_question_lineaire(): array
{
    $prefixes = conversions_table_prefixes();
    $bases    = array('s', 'g', 'm', 'L');

    list($s, $mantisse_affichage, $mantisse_valeur, $n) = conversions_generer_chiffres_significatifs();

    $k              = random_int(-5, 0);
    $e_disp         = $k + ($n - 1);
    $nombre_affiche = conversions_formater_nombre($s, $n, $k);

    //
    // Unite de depart (toujours prefixee) et unite demandee (n'importe
    // quel prefixe, incluant l'unite de base, mais different de la source).
    //

    $base = $bases[array_rand($bases)];

    $prefixes_source_possibles = array_values(array_filter(array_keys($prefixes), function($p) { return $p !== ''; }));
    $prefixe_source            = $prefixes_source_possibles[array_rand($prefixes_source_possibles)];

    $prefixes_cible_possibles = array_values(array_filter(array_keys($prefixes), function($p) use ($prefixe_source) { return $p !== $prefixe_source; }));
    $prefixe_cible            = $prefixes_cible_possibles[array_rand($prefixes_cible_possibles)];

    $unite_source = $prefixe_source . $base;
    $unite_cible  = $prefixe_cible . $base;

    $puissance_source = $prefixes[$prefixe_source];
    $puissance_cible   = $prefixes[$prefixe_cible];

    $exposant = $e_disp + $puissance_source - $puissance_cible;
    $delta     = $puissance_source - $puissance_cible;

    $explication = $nombre_affiche . ' ' . $unite_source . ' = ' . $mantisse_affichage
        . ' × 10<sup>' . $e_disp . '</sup> ' . $unite_source . '. Passage de ' . $unite_source
        . ' à ' . $unite_cible . ' : l\'exposant se décale de ' . ($delta >= 0 ? '+' : '') . $delta
        . ' (10<sup>' . $puissance_source . '</sup> ' . $base . ' pour ' . $unite_source . ', 10<sup>'
        . $puissance_cible . '</sup> ' . $base . ' pour ' . $unite_cible . '). Donc '
        . $mantisse_affichage . ' × 10<sup>' . $exposant . '</sup> ' . $unite_cible . '.';

    return array(
        'nombre'             => $nombre_affiche,
        'source'             => $unite_source,
        'cible'              => $unite_cible,
        'mantisse_valeur'    => $mantisse_valeur,
        'mantisse_affichage' => $mantisse_affichage,
        'exposant'           => $exposant,
        'explication'        => $explication,
        'indice'             => '',
    );
}

/* ----------------------------------------------------------------------------
 *
 * conversions_generer_question_aire()
 *
 * ----------------------------------------------------------------------------
 *
 * Genere une conversion d'unite de surface (m² avec prefixe SI). Un
 * changement de prefixe lineaire de delta decale l'exposant d'une aire de
 * 2 * delta (le facteur d'echelle est mis au carre).
 *
 * ---------------------------------------------------------------------------- */
function conversions_generer_question_aire(): array
{
    $prefixes = conversions_table_prefixes();

    list($s, $mantisse_affichage, $mantisse_valeur, $n) = conversions_generer_chiffres_significatifs();

    $k              = random_int(-5, 0);
    $e_disp         = $k + ($n - 1);
    $nombre_affiche = conversions_formater_nombre($s, $n, $k);

    $prefixes_source_possibles = array_values(array_filter(array_keys($prefixes), function($p) { return $p !== ''; }));
    $prefixe_source            = $prefixes_source_possibles[array_rand($prefixes_source_possibles)];

    $prefixes_cible_possibles = array_values(array_filter(array_keys($prefixes), function($p) use ($prefixe_source) { return $p !== $prefixe_source; }));
    $prefixe_cible            = $prefixes_cible_possibles[array_rand($prefixes_cible_possibles)];

    $unite_source = $prefixe_source . 'm²';
    $unite_cible  = $prefixe_cible . 'm²';

    $puissance_source = $prefixes[$prefixe_source];
    $puissance_cible   = $prefixes[$prefixe_cible];

    $delta_lineaire = $puissance_source - $puissance_cible;
    $delta          = 2 * $delta_lineaire;
    $exposant       = $e_disp + $delta;

    $explication = $nombre_affiche . ' ' . $unite_source . ' = ' . $mantisse_affichage
        . ' × 10<sup>' . $e_disp . '</sup> ' . $unite_source . '. Passage de ' . $prefixe_source . 'm à '
        . $prefixe_cible . 'm : l\'exposant linéaire se décale de ' . ($delta_lineaire >= 0 ? '+' : '') . $delta_lineaire
        . ', donc ×2 pour une aire (m²) : ' . ($delta >= 0 ? '+' : '') . $delta . '. Donc '
        . $mantisse_affichage . ' × 10<sup>' . $exposant . '</sup> ' . $unite_cible . '.';

    return array(
        'nombre'             => $nombre_affiche,
        'source'             => $unite_source,
        'cible'              => $unite_cible,
        'mantisse_valeur'    => $mantisse_valeur,
        'mantisse_affichage' => $mantisse_affichage,
        'exposant'           => $exposant,
        'explication'        => $explication,
        'indice'             => '',
    );
}

/* ----------------------------------------------------------------------------
 *
 * conversions_generer_question_cube()
 *
 * ----------------------------------------------------------------------------
 *
 * Genere une conversion d'unite de volume : cube de m (avec prefixe SI) et/ou
 * litre (avec prefixe SI), les deux familles etant reliees par
 * 1 L = 1 dm³ = 10⁻³ m³ (donc aussi 1 cm³ = 1 mL). Un changement de prefixe
 * lineaire de delta decale l'exposant d'un volume en m³ de 3 * delta (le
 * facteur d'echelle est mis au cube) ; un prefixe de litre decale l'exposant
 * de son propre delta, avec un decalage fixe de -3 pour l'ancrer au m³.
 *
 * ---------------------------------------------------------------------------- */
function conversions_generer_question_cube(): array
{
    $prefixes = conversions_table_prefixes();

    list($s, $mantisse_affichage, $mantisse_valeur, $n) = conversions_generer_chiffres_significatifs();

    $k              = random_int(-5, 0);
    $e_disp         = $k + ($n - 1);
    $nombre_affiche = conversions_formater_nombre($s, $n, $k);

    //
    // Univers des unites de volume : cube de m (prefixe quelconque) ou
    // litres (prefixe quelconque), ramenees a une reference commune : la
    // puissance de 10 par rapport au m³.
    //

    $univers = array();

    foreach ($prefixes as $prefixe => $puissance)
    {
        $univers[] = array('unite' => $prefixe . 'm³', 'prefixe' => $prefixe, 'puissance_m3' => 3 * $puissance);
        $univers[] = array('unite' => $prefixe . 'L',  'prefixe' => $prefixe, 'puissance_m3' => $puissance - 3);
    }

    //
    // La source est toujours prefixee (jamais "m³" ou "L" tout court).
    //

    $univers_source = array_values(array_filter($univers, function($u) { return $u['prefixe'] !== ''; }));
    $source         = $univers_source[array_rand($univers_source)];

    $univers_cible = array_values(array_filter($univers, function($u) use ($source) { return $u['unite'] !== $source['unite']; }));
    $cible         = $univers_cible[array_rand($univers_cible)];

    $unite_source = $source['unite'];
    $unite_cible  = $cible['unite'];

    $delta    = $source['puissance_m3'] - $cible['puissance_m3'];
    $exposant = $e_disp + $delta;

    //
    // Quand la conversion croise les familles m³ / L, un indice est propose
    // AVANT que l'etudiant ne reponde (affiche par la vue), en plus d'etre
    // rappele dans l'explication en cas d'erreur.
    //

    $indice = '';

    if (substr($unite_source, -1) !== substr($unite_cible, -1))
    {
        $indice = '1 cm³ = 1 mL';
    }

    $explication = $nombre_affiche . ' ' . $unite_source . ' = ' . $mantisse_affichage
        . ' × 10<sup>' . $e_disp . '</sup> ' . $unite_source . '. En m³ : 1 ' . $unite_source . ' = 10<sup>'
        . $source['puissance_m3'] . '</sup> m³, et 1 ' . $unite_cible . ' = 10<sup>' . $cible['puissance_m3']
        . '</sup> m³.' . ($indice !== '' ? ' Indice : ' . $indice . '.' : '') . ' L\'exposant se décale donc de '
        . ($delta >= 0 ? '+' : '') . $delta . '. Donc ' . $mantisse_affichage . ' × 10<sup>' . $exposant
        . '</sup> ' . $unite_cible . '.';

    return array(
        'nombre'             => $nombre_affiche,
        'source'             => $unite_source,
        'cible'              => $unite_cible,
        'mantisse_valeur'    => $mantisse_valeur,
        'mantisse_affichage' => $mantisse_affichage,
        'exposant'           => $exposant,
        'explication'        => $explication,
        'indice'             => $indice,
    );
}

/* ----------------------------------------------------------------------------
 *
 * conversions_generer_question()
 *
 * ----------------------------------------------------------------------------
 *
 * Choisit au hasard entre une conversion lineaire (s, g, m, L), une
 * conversion de surface (m²) ou une conversion de volume (m³ et/ou L).
 *
 * ---------------------------------------------------------------------------- */
function conversions_generer_question(): array
{
    $generateurs = array(
        'conversions_generer_question_lineaire',
        'conversions_generer_question_aire',
        'conversions_generer_question_cube',
    );

    $fn = $generateurs[array_rand($generateurs)];

    return $fn();
}

/* ----------------------------------------------------------------------------
 *
 * quiz_liste_disponibles()
 *
 * ----------------------------------------------------------------------------
 *
 * Manifeste des quiz disponibles, utilise par le controleur Quiz. Pour
 * ajouter un quiz, ajouter une entree ici ainsi que la methode et la vue du
 * controleur Quiz correspondantes.
 *
 * ---------------------------------------------------------------------------- */
function quiz_liste_disponibles(): array
{
    return array(
		'cs' => array(
			'cours'		  => 'SN1',
            'titre'       => 'Chiffres significatifs',
            'description' => "Déterminez le nombre de chiffres significatifs d'un nombre.",
        ),
		'cscalcul' => array(
			'cours'		  => 'SN1',
            'titre'       => 'Chiffres significatifs d\'un calcul',
            'description' => "Déterminez le nombre de chiffres significatifs de la réponse d'un calcul combinant additions/soustractions et multiplications/divisions.",
        ),
		'conversions' => array(
			'cours'		  => 'SN1',
            'titre'       => "Conversion d'unités",
            'description' => "Convertissez une valeur aux unités demandées, en notation scientifique.",
        ),
		'orbitales' => array(
			'cours'		  => 'SN1',
            'titre'       => 'Nombre d\'orbitales',
            'description' => 'Déterminez le nombre d\'orbitales correspondant à une combinaison de nombres quantiques (n, l, mₗ).',
        ),
		'eq' => array(
			'cours'		  => 'SN1',
            'titre'       => 'Nombre d\'états quantiques',
            'description' => 'Déterminez le nombre d\'états quantiques correspondant à une combinaison de nombres quantiques (n, l, mₗ, ms).',
        ),
		'cases' => array(
			'cours'		  => 'SN1',
            'titre'       => 'Cases quantiques',
            'description' => "À partir des cases quantiques d'un élément (Z = 1 à 38), répondez à 5 questions sur l'atome neutre et sur un de ses ions.",
        ),
		'nomen' => array(
			'cours'		  => 'SN1',
            'titre'       => 'Nom des ions et molécules',
            'description' => "Associez les formules des acides, molécules et ions polyatomiques à leur nom.",
        ),
		'fonctions' => array(
			'cours'		  => 'SNU',
            'titre'       => 'Fonctions chimiques',
            'description' => "Associez la structure d'une molécule à la fonction chimique qu'elle contient.",
        ),
		'extremes' => array(
			'cours'		  => 'SN1',
            'titre'       => "Moyenne et incertitude par la méthode des extrêmes",
            'description' => "À partir de 3 mesures avec leur incertitude, calculez la moyenne et l'incertitude par la méthode des extrêmes.",
        ),
    );
}

/* ----------------------------------------------------------------------------
 *
 * CS AJUSTEMENT (adapte de kovao.dev/application/helpers/general_helper.php)
 *
 * ----------------------------------------------------------------------------
 *
 * Arrondit $nombre au nombre de chiffres significatifs $cs demande, en
 * bcmath pour eviter les problemes de precision virgule flottante, et
 * retourne le resultat formate (notation decimale ou scientifique selon le
 * cas).
 *
 * ---------------------------------------------------------------------------- */
function cs_ajustement($nombre, $cs)
{
    if ($cs == 0)
    {
        return $nombre;
    }

    $nombre_orig = $nombre;
    $nombre      = str_replace(',', '.', (string) $nombre);

    $negatif = FALSE;

    if ($nombre !== '' && $nombre[0] === '-')
    {
        $negatif = TRUE;
        $nombre  = substr($nombre, 1);
    }

    if ((float) $nombre == 0)
    {
        if ($cs == 1) return '0';

        return '0.' . str_repeat('0', $cs - 1);
    }

    $ns_exposant = NULL;

    $nombre_upper = strtoupper($nombre);

    if (strpos($nombre_upper, 'E') !== FALSE)
    {
        $pos_e       = strpos($nombre_upper, 'E');
        $ns_exposant = (int) substr($nombre, $pos_e + 1);
        $nombre      = substr($nombre, 0, $pos_e);
    }

    $precision_travail = 50;

    $bc_nombre = $nombre;

    if ($ns_exposant !== NULL)
    {
        if ($ns_exposant >= 0)
        {
            $bc_nombre = bcmul($bc_nombre, bcpow('10', (string) $ns_exposant, 0), $precision_travail);
        }
        else
        {
            $bc_nombre = bcdiv($bc_nombre, bcpow('10', (string) (-$ns_exposant), 0), $precision_travail);
        }
    }

    if (strpos($bc_nombre, '.') !== FALSE)
    {
        $bc_nombre = rtrim($bc_nombre, '0');
        $bc_nombre = rtrim($bc_nombre, '.');
    }

    $exposant_1cs = (int) floor(log10((float) $bc_nombre));

    $decimales_arrondi = $cs - 1 - $exposant_1cs;

    if ($decimales_arrondi <= 0)
    {
        $diviseur   = bcpow('10', (string) (-$decimales_arrondi), 0);
        $bc_arrondi = bcdiv($bc_nombre, $diviseur, $precision_travail);
        $bc_arrondi = cs_arrondi_bcmath($bc_arrondi, 0);
        $bc_arrondi = bcmul($bc_arrondi, $diviseur, 0);
    }
    else
    {
        $bc_arrondi = cs_arrondi_bcmath($bc_nombre, $decimales_arrondi);
    }

    if (strpos($bc_arrondi, '.') !== FALSE)
    {
        $bc_arrondi = rtrim($bc_arrondi, '0');
        $bc_arrondi = rtrim($bc_arrondi, '.');
    }

    if ((float) $bc_arrondi === 0.0)
    {
        return '0';
    }

    $exposant_1cs = (int) floor(log10((float) $bc_arrondi));

    $utiliser_ns = FALSE;

    if ($ns_exposant !== NULL)
    {
        $utiliser_ns = TRUE;
    }

    if ( ! $utiliser_ns)
    {
        if (strpos($bc_arrondi, '.') === FALSE && strlen($bc_arrondi) > 1 && substr($bc_arrondi, -1) === '0')
        {
            $utiliser_ns = TRUE;
        }
    }

    if ( ! $utiliser_ns && $exposant_1cs <= -5)
    {
        $utiliser_ns = TRUE;
    }

    if ($utiliser_ns)
    {
        $sortie = cs_construire_notation_scientifique($bc_arrondi, $cs, $exposant_1cs);
    }
    else
    {
        $sortie = cs_construire_notation_decimale($bc_arrondi, $cs, $exposant_1cs);
    }

    if ($negatif && (float) $sortie != 0)
    {
        return '-' . $sortie;
    }

    return $sortie;
}

// Arrondi scolaire (half-up) en arithmetique exacte, sans jamais passer par un float.
function cs_arrondi_bcmath($nombre, $decimales)
{
    $nombre  = (string) $nombre;
    $negatif = FALSE;

    if ($nombre !== '' && $nombre[0] === '-')
    {
        $negatif = TRUE;
        $nombre  = substr($nombre, 1);
    }

    if (strpos($nombre, '.') === FALSE)
    {
        $nombre .= '.';
    }

    list($entier, $frac) = explode('.', $nombre, 2);

    $frac             = str_pad($frac, $decimales + 1, '0');
    $chiffre_decision = (int) $frac[$decimales];
    $frac_tronque     = substr($frac, 0, $decimales);

    $resultat = ($decimales > 0) ? $entier . '.' . $frac_tronque : $entier;

    if ($chiffre_decision >= 5)
    {
        $increment = ($decimales == 0) ? '1' : '0.' . str_repeat('0', $decimales - 1) . '1';
        $resultat  = bcadd($resultat, $increment, $decimales);
    }

    $resultat = ltrim($resultat, '0') ?: '0';

    if ($resultat !== '' && $resultat[0] === '.')
    {
        $resultat = '0' . $resultat;
    }

    if ($negatif)
    {
        $resultat = '-' . $resultat;
    }

    return $resultat;
}

// Formate la sortie en decimal avec exactement $cs CS (complete les zeros de fin).
function cs_construire_notation_decimale($bc_arrondi, $cs, $exposant_1cs)
{
    $decimales_voulues = $cs - 1 - $exposant_1cs;

    if ($decimales_voulues <= 0)
    {
        return $bc_arrondi;
    }

    if (strpos($bc_arrondi, '.') === FALSE)
    {
        $bc_arrondi .= '.';
    }

    list($entier, $frac) = explode('.', $bc_arrondi, 2);

    $frac = str_pad($frac, $decimales_voulues, '0');

    return $entier . '.' . $frac;
}

// Formate la mantisse et l'exposant d'une notation scientifique avec exactement $cs CS.
function cs_construire_notation_scientifique($bc_arrondi, $cs, $exposant_1cs)
{
    if ($exposant_1cs >= 0)
    {
        $diviseur = bcpow('10', (string) $exposant_1cs, 0);
        $mantisse = bcdiv($bc_arrondi, $diviseur, 30);
    }
    else
    {
        $multiplicateur = bcpow('10', (string) (-$exposant_1cs), 0);
        $mantisse       = bcmul($bc_arrondi, $multiplicateur, 30);
    }

    if (strpos($mantisse, '.') !== FALSE)
    {
        $mantisse = rtrim($mantisse, '0');
        $mantisse = rtrim($mantisse, '.');
    }

    $decimales_mantisse = $cs - 1;

    if ($decimales_mantisse == 0)
    {
        $mantisse_formatee = (string) (int) round((float) $mantisse);
    }
    else
    {
        if (strpos($mantisse, '.') === FALSE)
        {
            $mantisse .= '.';
        }

        list($ent, $frac) = explode('.', $mantisse, 2);

        $frac              = str_pad($frac, $decimales_mantisse, '0');
        $mantisse_formatee = $ent . '.' . $frac;
    }

    if ($exposant_1cs == 0)
    {
        return $mantisse_formatee;
    }

    return $mantisse_formatee . 'E' . $exposant_1cs;
}

/* ----------------------------------------------------------------------------
 *
 * cs_calcul_generer_equation()
 *
 * ----------------------------------------------------------------------------
 *
 * Quiz "Chiffres significatifs d'un calcul" : genere une equation a 3
 * operandes combinant obligatoirement une addition/soustraction et une
 * multiplication/division (une des deux operations est prioritaire selon
 * l'ordre choisi au hasard), et propage les CS selon la regle appropriee a
 * chaque etape :
 *
 *   - multiplication / division : le CS du resultat = le CS minimal des
 *     operandes
 *   - addition / soustraction   : le resultat est limite au nombre de
 *     decimales du terme le moins precis
 *
 * Retourne :
 *
 * [
 *   'affichage'       => string,  // l'equation, ex. "(12,5 + 3,40) × 2,1"
 *   'reponse_brute'   => string,  // resultat non arrondi, ex. "33,39"
 *   'reponse_ajustee' => string,  // resultat arrondi au bon nombre de CS
 *   'valeur'          => int,     // reponse attendue (nombre de CS)
 *   'explication'     => string
 * ]
 *
 * ---------------------------------------------------------------------------- */
function cs_calcul_generer_equation(): array
{
    $a = cs_calcul_generer_operande();
    $b = cs_calcul_generer_operande();
    $c = cs_calcul_generer_operande();

    $op_addsub = (random_int(0, 1) === 0) ? '+' : '-';
    $op_muldiv = (random_int(0, 1) === 0) ? '*' : '/';

    $addsub_en_premier = (random_int(0, 1) === 0);

    if ($addsub_en_premier)
    {
        $inter = cs_calcul_combiner_addition($a, $b, $op_addsub);
        $final = cs_calcul_combiner_multiplication($inter, $c, $op_muldiv);

        $etapes = array(
            array('type' => 'add', 'gauche' => $a, 'droite' => $b, 'op' => $op_addsub, 'resultat' => $inter),
            array('type' => 'mul', 'gauche' => $inter, 'droite' => $c, 'op' => $op_muldiv, 'resultat' => $final),
        );

        $affichage = '(' . cs_calcul_afficher_noeud($a) . ' ' . cs_calcul_afficher_operateur($op_addsub) . ' ' . cs_calcul_afficher_noeud($b) . ') '
            . cs_calcul_afficher_operateur($op_muldiv) . ' ' . cs_calcul_afficher_noeud($c);
    }
    else
    {
        $inter = cs_calcul_combiner_multiplication($a, $b, $op_muldiv);
        $final = cs_calcul_combiner_addition($inter, $c, $op_addsub);

        $etapes = array(
            array('type' => 'mul', 'gauche' => $a, 'droite' => $b, 'op' => $op_muldiv, 'resultat' => $inter),
            array('type' => 'add', 'gauche' => $inter, 'droite' => $c, 'op' => $op_addsub, 'resultat' => $final),
        );

        $affichage = cs_calcul_afficher_noeud($a) . ' ' . cs_calcul_afficher_operateur($op_muldiv) . ' ' . cs_calcul_afficher_noeud($b) . ' '
            . cs_calcul_afficher_operateur($op_addsub) . ' ' . cs_calcul_afficher_noeud($c);
    }

    $reponse_ajustee = cs_ajustement($final['valeur'], $final['cs']);

    $explication = cs_calcul_decrire_etape($etapes[0], 1) . ' ' . cs_calcul_decrire_etape($etapes[1], 2);

    return array(
        'affichage'       => $affichage,
        'reponse_brute'   => cs_calcul_formater_brut($final['valeur']),
        'reponse_ajustee' => cs_calcul_formater_ajustee($reponse_ajustee),
        'valeur'          => $final['cs'],
        'explication'     => $explication,
    );
}

// Genere un operande decimal aleatoire (avec point decimal, jamais un entier
// nu) pour eviter le cas ambigu des zeros de fin d'un entier.
function cs_calcul_generer_operande(): array
{
    if (random_int(0, 1) === 0)
    {
        // Valeur >= 1, ex. "12.5", "3.40", "150.25".
        $int_len = random_int(1, 2);
        $dec_len = random_int(1, 2);

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

        $texte = $int_part . '.' . $dec_part;
    }
    else
    {
        // Valeur < 1, ex. "0.45", "0.032".
        $zeros_de_tete = random_int(0, 2);
        $sig_len       = random_int(1, 2);

        $sig = (string) random_int(1, 9);

        for ($i = 1; $i < $sig_len; $i++)
        {
            $sig .= (string) random_int(0, 9);
        }

        $texte = '0.' . str_repeat('0', $zeros_de_tete) . $sig;
    }

    return array(
        'texte'  => $texte,
        'valeur' => (float) $texte,
        'cs'     => cs($texte),
    );
}

// Position (exposant de base 10) du dernier chiffre significatif d'une
// valeur, deduite de son CS.
function cs_calcul_position_dernier_chiffre(float $valeur, int $cs): int
{
    if ($valeur == 0.0)
    {
        return -($cs - 1);
    }

    $exposant = (int) floor(log10(abs($valeur)));

    return $exposant - ($cs - 1);
}

// Operation inverse : deduit le CS d'une valeur dont la position du dernier
// chiffre significatif est deja connue.
function cs_calcul_cs_depuis_position(float $valeur, int $position): int
{
    if ($valeur == 0.0)
    {
        return max(1, -$position + 1);
    }

    $exposant = (int) floor(log10(abs($valeur)));

    return max(1, $exposant - $position + 1);
}

function cs_calcul_combiner_addition(array $gauche, array $droite, string $op): array
{
    $valeur = ($op === '+') ? ($gauche['valeur'] + $droite['valeur']) : ($gauche['valeur'] - $droite['valeur']);

    $p_gauche   = cs_calcul_position_dernier_chiffre($gauche['valeur'], $gauche['cs']);
    $p_droite   = cs_calcul_position_dernier_chiffre($droite['valeur'], $droite['cs']);
    $p_resultat = max($p_gauche, $p_droite);

    $cs = cs_calcul_cs_depuis_position($valeur, $p_resultat);

    return array('valeur' => $valeur, 'cs' => $cs);
}

function cs_calcul_combiner_multiplication(array $gauche, array $droite, string $op): array
{
    $valeur = ($op === '*') ? ($gauche['valeur'] * $droite['valeur']) : ($gauche['valeur'] / $droite['valeur']);
    $cs     = min($gauche['cs'], $droite['cs']);

    return array('valeur' => $valeur, 'cs' => $cs);
}

// Affiche un operande litteral (son texte, virgule) ou un noeud intermediaire
// (sa valeur calculee, arrondie pour l'affichage seulement).
function cs_calcul_afficher_noeud(array $noeud): string
{
    if (array_key_exists('texte', $noeud))
    {
        return str_replace('.', ',', $noeud['texte']);
    }

    return cs_calcul_formater_brut($noeud['valeur']);
}

function cs_calcul_afficher_operateur(string $op): string
{
    switch ($op)
    {
        case '+' : return '+';
        case '-' : return '−';
        case '*' : return '×';
        case '/' : return '÷';
    }

    return $op;
}

// Formate une valeur brute (non arrondie aux CS) pour affichage, virgule
// decimale, sans zeros de fin parasites.
function cs_calcul_formater_brut(float $valeur): string
{
    $texte = sprintf('%.6f', $valeur);

    if (strpos($texte, '.') !== FALSE)
    {
        $texte = rtrim($texte, '0');
        $texte = rtrim($texte, '.');
    }

    if ($texte === '' || $texte === '-')
    {
        $texte = '0';
    }

    return str_replace('.', ',', $texte);
}

// Formate la sortie de cs_ajustement() (point decimal, notation E possible)
// pour affichage (virgule decimale, notation ×10^n avec <sup>).
function cs_calcul_formater_ajustee(string $texte): string
{
    $pos_e = stripos($texte, 'E');

    if ($pos_e === FALSE)
    {
        return str_replace('.', ',', $texte);
    }

    $mantisse = str_replace('.', ',', substr($texte, 0, $pos_e));
    $exposant = substr($texte, $pos_e + 1);

    return $mantisse . ' × 10<sup>' . $exposant . '</sup>';
}

function cs_calcul_decrire_etape(array $etape, int $numero): string
{
    $gauche        = $etape['gauche'];
    $droite        = $etape['droite'];
    $resultat      = $etape['resultat'];
    $op_disp       = cs_calcul_afficher_operateur($etape['op']);
    $gauche_disp   = cs_calcul_afficher_noeud($gauche);
    $droite_disp   = cs_calcul_afficher_noeud($droite);
    $resultat_brut = cs_calcul_formater_brut($resultat['valeur']);

    if ($etape['type'] === 'add')
    {
        $p_gauche  = cs_calcul_position_dernier_chiffre($gauche['valeur'], $gauche['cs']);
        $p_droite  = cs_calcul_position_dernier_chiffre($droite['valeur'], $droite['cs']);
        $decimales = max(0, -max($p_gauche, $p_droite));

        return "Étape {$numero} (addition/soustraction) : {$gauche_disp} {$op_disp} {$droite_disp} = {$resultat_brut}. "
            . "Le résultat d'une addition ou d'une soustraction ne peut pas être plus précis que le terme le moins précis : on arrondit à {$decimales} décimale(s), ce qui donne {$resultat['cs']} chiffre(s) significatif(s).";
    }

    return "Étape {$numero} (multiplication/division) : {$gauche_disp} {$op_disp} {$droite_disp} = {$resultat_brut}. "
        . "Le résultat d'une multiplication ou d'une division conserve le plus petit nombre de chiffres significatifs parmi les opérandes : min({$gauche['cs']}, {$droite['cs']}) = {$resultat['cs']} chiffre(s) significatif(s).";
}

/* ----------------------------------------------------------------------------
 *
 * extremes_generer_question()
 *
 * ----------------------------------------------------------------------------
 *
 * Quiz "Moyenne et incertitude par la methode des extremes" : genere 3
 * mesures (xi ± Δxi) d'une meme grandeur, groupees autour d'une valeur
 * centrale commune, avec soit la meme precision (meme instrument) soit des
 * precisions variables selon les mesures. L'incertitude de chaque mesure
 * correspond a une amplitude entre 1 et 9 unites de son dernier chiffre
 * (incertitude de lecture), tiree au hasard independamment pour chaque
 * mesure.
 *
 * Methode des extremes :
 *   max = le plus grand parmi (xi + Δxi)
 *   min = le plus petit parmi (xi − Δxi)
 *   moyenne     = (max + min) / 2
 *   incertitude = (max − min) / 2, arrondie a 1 CS ; la moyenne est arrondie
 *   a la meme decimale que l'incertitude.
 *
 * Tout le calcul se fait en arithmetique entiere/bcmath (aucun float) pour
 * eviter les erreurs de precision.
 *
 * ---------------------------------------------------------------------------- */
function extremes_generer_question(): array
{
    $unites = extremes_config_unites();
    $unite  = array_rand($unites);
    $cfg    = $unites[$unite];

    $meme_instrument  = (random_int(0, 1) === 0);
    $decimale_commune = $cfg['decimales'][array_rand($cfg['decimales'])];

    // Valeur centrale commune, ancree a l'echelle la plus fine possible pour cette unite.
    $decimale_ref = max($cfg['decimales']);
    $echelle_ref  = (int) round(pow(10, $decimale_ref));
    $central_int  = random_int((int) round($cfg['min'] * $echelle_ref), (int) round($cfg['max'] * $echelle_ref));

    // Environ 25 % du temps, les 3 mesures partagent la meme incertitude
    // (meme instrument pour la lecture) ; sinon chaque mesure a la sienne.
    $incert_commun = (random_int(1, 4) === 1) ? random_int(1, 9) : NULL;

    $mesures = array();

    for ($i = 0; $i < 3; $i++)
    {
        $decimales = $meme_instrument ? $decimale_commune : $cfg['decimales'][array_rand($cfg['decimales'])];
        $echelle   = (int) round(pow(10, $decimales));
        $incert    = $incert_commun ?? random_int(1, 9);

        $valeur_ramenee = (int) round($central_int / ($echelle_ref / $echelle));
        $valeur_int     = max(1 + $incert, $valeur_ramenee + random_int(-2, 2));

        $mesures[] = array(
            'valeur_int' => $valeur_int,
            'decimales'  => $decimales,
            'echelle'    => $echelle,
            'incert'     => $incert,
        );
    }

    // Echelle commune (la plus fine des 3 mesures) pour comparer leurs bornes.
    $decimale_commune_max = max(array_column($mesures, 'decimales'));
    $echelle_commune      = (int) round(pow(10, $decimale_commune_max));

    $bornes_sup = array();
    $bornes_inf = array();

    foreach ($mesures as $m)
    {
        $facteur = (int) round($echelle_commune / $m['echelle']);

        $bornes_sup[] = ($m['valeur_int'] + $m['incert']) * $facteur;
        $bornes_inf[] = ($m['valeur_int'] - $m['incert']) * $facteur;
    }

    $max_commun = max($bornes_sup);
    $min_commun = min($bornes_inf);

    $diviseur = (string) (2 * $echelle_commune);

    $moyenne_bc     = bcdiv((string) ($max_commun + $min_commun), $diviseur, 20);
    $incertitude_bc = bcdiv((string) ($max_commun - $min_commun), $diviseur, 20);

    // Arrondi a 1 CS en notation decimale simple (jamais scientifique : cette
    // ambiguite des zeros de fin n'est pas le propos de ce quiz, deja couvert
    // par le quiz "cscalcul").
    $incertitude_ajustee = extremes_ajustement_1cs($incertitude_bc);

    // Position (puissance de 10) du chiffre significatif de l'incertitude : la
    // moyenne doit etre arrondie a cette meme position, qu'elle soit une
    // decimale (position < 0) ou une dizaine/centaine (position >= 0).
    $position_incertitude = (int) floor(log10(abs((float) $incertitude_ajustee)));

    if ($position_incertitude >= 0)
    {
        $echelle_position = bcpow('10', (string) $position_incertitude, 0);
        $moyenne_ajustee  = bcmul(cs_arrondi_bcmath(bcdiv($moyenne_bc, $echelle_position, 20), 0), $echelle_position, 0);
    }
    else
    {
        $moyenne_ajustee = cs_arrondi_bcmath($moyenne_bc, -$position_incertitude);
    }

    $mesures_affichage = array();

    foreach ($mesures as $m)
    {
        $mesures_affichage[] = array(
            'valeur'      => extremes_formater_valeur($m['valeur_int'], $m['decimales']),
            'incertitude' => extremes_formater_valeur($m['incert'], $m['decimales']),
        );
    }

    $explication = extremes_decrire_calcul($mesures, $decimale_commune_max, $max_commun, $min_commun, $moyenne_ajustee, $incertitude_ajustee);

    return array(
        'unite'              => $unite,
        'mesures'            => $mesures_affichage,
        'moyenne'            => str_replace('.', ',', $moyenne_ajustee),
        'incertitude'        => str_replace('.', ',', $incertitude_ajustee),
        'moyenne_valeur'     => $moyenne_ajustee,
        'incertitude_valeur' => $incertitude_ajustee,
        'explication'        => $explication,
    );
}

// Plages de generation et decimales possibles pour chaque grandeur du quiz "extremes".
function extremes_config_unites(): array
{
    return array(
        'g'  => array('min' => 1,   'max' => 200, 'decimales' => array(1, 2)),
        'mL' => array('min' => 1,   'max' => 100, 'decimales' => array(1, 2)),
        'cm' => array('min' => 1,   'max' => 50,  'decimales' => array(1, 2)),
        'mg' => array('min' => 10,  'max' => 500, 'decimales' => array(0, 1)),
        'L'  => array('min' => 0.1, 'max' => 5,   'decimales' => array(2, 3)),
    );
}

// Arrondit une valeur bcmath (positive) a 1 chiffre significatif, toujours en
// notation decimale simple (jamais scientifique), ex. "0.0347" -> "0.03",
// "13.4" -> "10".
function extremes_ajustement_1cs(string $bc_valeur): string
{
    $exposant = (int) floor(log10(abs((float) $bc_valeur)));

    if ($exposant >= 0)
    {
        $echelle = bcpow('10', (string) $exposant, 0);

        return bcmul(cs_arrondi_bcmath(bcdiv($bc_valeur, $echelle, 20), 0), $echelle, 0);
    }

    return cs_arrondi_bcmath($bc_valeur, -$exposant);
}

// Formate un entier a l'echelle 10^$decimales en texte decimal (virgule), ex. (125, 1) -> "12,5".
function extremes_formater_valeur(int $valeur_int, int $decimales): string
{
    if ($decimales === 0)
    {
        return (string) $valeur_int;
    }

    $abs = str_pad((string) abs($valeur_int), $decimales + 1, '0', STR_PAD_LEFT);

    $entier = substr($abs, 0, strlen($abs) - $decimales);
    $frac   = substr($abs, -$decimales);

    return ($valeur_int < 0 ? '-' : '') . $entier . ',' . $frac;
}

// Construit le texte d'explication (bornes de chaque mesure, max/min, moyenne, incertitude).
function extremes_decrire_calcul(array $mesures, int $decimale_commune_max, int $max_commun, int $min_commun, string $moyenne_ajustee, string $incertitude_ajustee): string
{
    $bornes = array();

    foreach ($mesures as $m)
    {
        $valeur_disp = extremes_formater_valeur($m['valeur_int'], $m['decimales']);
        $sup_disp    = extremes_formater_valeur($m['valeur_int'] + $m['incert'], $m['decimales']);
        $inf_disp    = extremes_formater_valeur($m['valeur_int'] - $m['incert'], $m['decimales']);

        $bornes[] = "{$valeur_disp} → [{$inf_disp} ; {$sup_disp}]";
    }

    $max_disp       = extremes_formater_valeur($max_commun, $decimale_commune_max);
    $min_disp       = extremes_formater_valeur($min_commun, $decimale_commune_max);
    $moyenne_disp   = str_replace('.', ',', $moyenne_ajustee);
    $incertitude_disp = str_replace('.', ',', $incertitude_ajustee);

    return "Bornes de chaque mesure (valeur ± incertitude) : " . implode(' ; ', $bornes) . '. '
        . "Maximum = {$max_disp}, minimum = {$min_disp}. "
        . "Moyenne = (max + min) / 2 = {$moyenne_disp}. "
        . "Incertitude = (max − min) / 2 = {$incertitude_disp}, arrondie à 1 chiffre significatif, et la moyenne est arrondie à la même décimale.";
}

/* End of file chimie_helper.php */
/* Location: ./application/helpers/chimie_helper.php */

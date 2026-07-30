/* ====================================================================
 *
 * quiz_concentrations.js
 *
 * Quiz d'entrainement : conversion d'une concentration de solution entre
 * mol/L, mol/kg, % m/m, % m/V, ppm et ppb. Reponse en notation scientifique
 * normalisee (mantisse + exposant), avec les chiffres significatifs de
 * l'enonce a respecter dans la mantisse.
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
    var CLE_SCORE       = 'quiz_concentrations_score';

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

        $.getJSON(base_url + 'quiz/lot/concentrations', function(data)
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

    function afficherQuestion(question)
    {
        $('#quiz-concentrations-enonce').html(question.enonce);
        $('#quiz-concentrations-unite-reponse').text(question.unite_cible);

        var $donnees = $('#quiz-concentrations-donnees').empty();

        $.each(question.donnees, function(i, d)
        {
            $('<span class="quiz-concentrations-donnee"></span>')
                .text(d.label + ' : ' + d.valeur)
                .appendTo($donnees);
        });
    }

    function questionSuivante()
    {
        if (tampon.length <= SEUIL_RECHARGE)
        {
            chargerLot();
        }

        if (tampon.length === 0)
        {
            $('#quiz-concentrations-enonce').text('...');
            $('#quiz-concentrations-unite-reponse').text('');
            $('#quiz-concentrations-donnees').empty();
            return;
        }

        courant = tampon.shift();
        repondu = false;

        afficherQuestion(courant);

        $('#quiz-concentrations-input-mantisse').val('').prop('disabled', false);
        $('#quiz-concentrations-input-exposant').val('').prop('disabled', false);
        $('#quiz-concentrations-resultat').addClass('d-none');
        $('#quiz-concentrations-suivant-wrap').addClass('d-none');
        $('#quiz-concentrations-envoyer').prop('disabled', true).removeClass('d-none');

        $('#quiz-concentrations-input-mantisse').trigger('focus');
    }

    function afficherScore()
    {
        if (essais === 0)
        {
            $('#quiz-concentrations-score').text("Aucun essai pour l'instant");
            return;
        }

        var moyenne = Math.round((reussis / essais) * 100);

        $('#quiz-concentrations-score').text(reussis + ' / ' + essais + ' (' + moyenne + ' %)');
    }

    function mantisseSaisie()
    {
        var texte = $('#quiz-concentrations-input-mantisse').val();

        if (texte === null) return '';

        return texte.trim().replace(',', '.');
    }

    function reponseValide()
    {
        var mantisse = mantisseSaisie();
        var exposant = $('#quiz-concentrations-input-exposant').val();

        if (mantisse === '' || exposant === null || exposant.trim() === '') return false;

        if ( ! isFinite(Number(mantisse))) return false;

        return /^-?\d+$/.test(exposant.trim());
    }

    // Nombre de chiffres dans une mantisse (ignore le signe et le separateur decimal) :
    // sert a distinguer une reponse dont la VALEUR est juste mais dont le nombre de
    // chiffres significatifs ne correspond pas a celui de l'enonce.
    function chiffresDansMantisse(s)
    {
        return s.replace(/[^0-9]/g, '').length;
    }

    function envoyer()
    {
        if (repondu || ! courant || ! reponseValide()) return;

        repondu = true;

        var mantisseUtilisateur = mantisseSaisie();
        var exposantUtilisateur = parseInt($('#quiz-concentrations-input-exposant').val().trim(), 10);

        var exposantOk  = (exposantUtilisateur === courant.exposant);
        var valeurExacte = (mantisseUtilisateur === courant.mantisse_valeur);
        var correct      = valeurExacte && exposantOk;

        var csAttendu = chiffresDansMantisse(courant.mantisse_valeur);

        // Valeur numeriquement identique (ex. "4.350" == "4.35") mais chaine differente : la
        // difference porte uniquement sur des zeros de fin, donc sur le nombre de CS, pas sur
        // la valeur elle-meme.
        var memeValeurNumerique = isFinite(Number(mantisseUtilisateur))
            && Number(mantisseUtilisateur) === Number(courant.mantisse_valeur);

        var csSeulsEnCause = ( ! correct) && exposantOk && memeValeurNumerique;

        essais++;
        if (correct) reussis++;

        afficherScore();
        sauvegarderScore();

        $('#quiz-concentrations-input-mantisse').prop('disabled', true);
        $('#quiz-concentrations-input-exposant').prop('disabled', true);
        $('#quiz-concentrations-envoyer').addClass('d-none');

        // Span dedie : le titre est en majuscules (text-transform CSS), mais l'unite de la
        // reponse (mol/L, % m/m, etc.) doit garder sa casse d'origine.
        var reponseAttendue = '<span class="quiz-concentrations-no-uppercase">'
            + courant.mantisse_affichage + ' × 10<sup>' + courant.exposant + '</sup> ' + courant.unite_cible
            + '</span>';
        var titre;

        if (correct)
        {
            titre = 'RÉUSSI';
        }
        else if (csSeulsEnCause)
        {
            titre = 'ÉCHEC — valeur juste, mais ' + csAttendu + ' chiffres significatifs attendus (réponse : ' + reponseAttendue + ')';
        }
        else
        {
            titre = 'ÉCHEC — réponse attendue : ' + reponseAttendue;
        }

        $('#quiz-concentrations-resultat-titre').html(titre);

        if ( ! correct)
        {
            $('#quiz-concentrations-explication').html(courant.explication).removeClass('d-none');
        }
        else
        {
            $('#quiz-concentrations-explication').html('').addClass('d-none');
        }

        $('#quiz-concentrations-resultat')
            .removeClass('d-none reussi echec')
            .addClass(correct ? 'reussi' : 'echec');

        $('#quiz-concentrations-suivant-wrap').removeClass('d-none');
        $('#quiz-concentrations-suivant').trigger('focus');
    }

    $('#quiz-concentrations-input-mantisse, #quiz-concentrations-input-exposant').on('input', function()
    {
        $('#quiz-concentrations-envoyer').prop('disabled', ! reponseValide());
    });

    $('#quiz-concentrations-envoyer').on('click', function()
    {
        envoyer();
    });

    $('#quiz-concentrations-suivant').on('click', function()
    {
        questionSuivante();
    });

    $('#quiz-concentrations-remise-zero').on('click', function()
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

        if (repondu && ! $('#quiz-concentrations-suivant-wrap').hasClass('d-none'))
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

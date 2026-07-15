/* ====================================================================
 *
 * quiz_conversions.js
 *
 * Quiz d'entrainement : conversion d'une valeur entre prefixes SI,
 * reponse en notation scientifique normalisee (mantisse + exposant).
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
    var CLE_SCORE       = 'quiz_conversions_score';
    var TOLERANCE       = 1e-9;

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

        $.getJSON(base_url + 'quiz/lot/conversions', function(data)
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

    function afficherValeur(question)
    {
        $('#quiz-conversions-depart-nombre').text(question.nombre);
        $('#quiz-conversions-depart-unite').text(question.source);
        $('#quiz-conversions-arrivee').text(question.cible);
        $('#quiz-conversions-unite-reponse').text(question.cible);
    }

    function questionSuivante()
    {
        if (tampon.length <= SEUIL_RECHARGE)
        {
            chargerLot();
        }

        if (tampon.length === 0)
        {
            $('#quiz-conversions-depart-nombre').text('...');
            $('#quiz-conversions-depart-unite').text('');
            $('#quiz-conversions-arrivee').text('');
            $('#quiz-conversions-unite-reponse').text('');
            $('#quiz-conversions-indice').addClass('d-none');
            return;
        }

        courant = tampon.shift();
        repondu = false;

        afficherValeur(courant);

        if (courant.indice)
        {
            $('#quiz-conversions-indice').text('Indice : ' + courant.indice).removeClass('d-none');
        }
        else
        {
            $('#quiz-conversions-indice').addClass('d-none');
        }

        $('#quiz-conversions-input-mantisse').val('').prop('disabled', false);
        $('#quiz-conversions-input-exposant').val('').prop('disabled', false);
        $('#quiz-conversions-resultat').addClass('d-none');
        $('#quiz-conversions-suivant-wrap').addClass('d-none');
        $('#quiz-conversions-envoyer').prop('disabled', true).removeClass('d-none');

        $('#quiz-conversions-input-mantisse').trigger('focus');
    }

    function afficherScore()
    {
        if (essais === 0)
        {
            $('#quiz-conversions-score').text("Aucun essai pour l'instant");
            return;
        }

        var moyenne = Math.round((reussis / essais) * 100);

        $('#quiz-conversions-score').text(reussis + ' / ' + essais + ' (' + moyenne + ' %)');
    }

    function mantisseSaisie()
    {
        var texte = $('#quiz-conversions-input-mantisse').val();

        if (texte === null) return '';

        return texte.trim().replace(',', '.');
    }

    function reponseValide()
    {
        var mantisse = mantisseSaisie();
        var exposant = $('#quiz-conversions-input-exposant').val();

        if (mantisse === '' || exposant === null || exposant.trim() === '') return false;

        var nombreMantisse = Number(mantisse);

        if ( ! isFinite(nombreMantisse)) return false;

        return /^-?\d+$/.test(exposant.trim());
    }

    function envoyer()
    {
        if (repondu || ! courant || ! reponseValide()) return;

        repondu = true;

        var mantisseUtilisateur = Number(mantisseSaisie());
        var exposantUtilisateur = parseInt($('#quiz-conversions-input-exposant').val().trim(), 10);

        var correct = (Math.abs(mantisseUtilisateur - courant.mantisse_valeur) < TOLERANCE)
                    && (exposantUtilisateur === courant.exposant);

        essais++;
        if (correct) reussis++;

        afficherScore();
        sauvegarderScore();

        $('#quiz-conversions-input-mantisse').prop('disabled', true);
        $('#quiz-conversions-input-exposant').prop('disabled', true);
        $('#quiz-conversions-envoyer').addClass('d-none');

        var reponseAttendue = courant.mantisse_affichage + ' × 10' + courant.exposant + ' ' + courant.cible;
        var titre           = correct ? 'RÉUSSI' : ('ÉCHEC — réponse attendue : ' + reponseAttendue);

        $('#quiz-conversions-resultat-titre').text(titre);

        if ( ! correct)
        {
            $('#quiz-conversions-explication').html(courant.explication).removeClass('d-none');
        }
        else
        {
            $('#quiz-conversions-explication').html('').addClass('d-none');
        }

        $('#quiz-conversions-resultat')
            .removeClass('d-none reussi echec')
            .addClass(correct ? 'reussi' : 'echec');

        $('#quiz-conversions-suivant-wrap').removeClass('d-none');
        $('#quiz-conversions-suivant').trigger('focus');
    }

    $('#quiz-conversions-input-mantisse, #quiz-conversions-input-exposant').on('input', function()
    {
        $('#quiz-conversions-envoyer').prop('disabled', ! reponseValide());
    });

    $('#quiz-conversions-envoyer').on('click', function()
    {
        envoyer();
    });

    $('#quiz-conversions-suivant').on('click', function()
    {
        questionSuivante();
    });

    $('#quiz-conversions-remise-zero').on('click', function()
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

        if (repondu && ! $('#quiz-conversions-suivant-wrap').hasClass('d-none'))
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

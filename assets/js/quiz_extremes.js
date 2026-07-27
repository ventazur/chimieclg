/* ====================================================================
 *
 * quiz_extremes.js
 *
 * Quiz d'entrainement : moyenne et incertitude de 3 mesures (chacune avec
 * sa propre incertitude) calculees par la methode des extremes.
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
    var CLE_SCORE       = 'quiz_extremes_score';

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

        $.getJSON(base_url + 'quiz/lot/extremes', function(data)
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

    function afficherMesures(question)
    {
        var $mesures = $('#quiz-extremes-mesures').empty();

        $.each(question.mesures, function(i, m)
        {
            $('<div class="quiz-extremes-mesure-item"></div>')
                .text(m.valeur + ' ± ' + m.incertitude + ' ' + question.unite)
                .appendTo($mesures);
        });

        $('#quiz-extremes-unite-reponse').text(question.unite);
    }

    function questionSuivante()
    {
        if (tampon.length <= SEUIL_RECHARGE)
        {
            chargerLot();
        }

        if (tampon.length === 0)
        {
            $('#quiz-extremes-mesures').text('...');
            $('#quiz-extremes-unite-reponse').text('');
            return;
        }

        courant = tampon.shift();
        repondu = false;

        afficherMesures(courant);

        $('#quiz-extremes-input-moyenne').val('').prop('disabled', false);
        $('#quiz-extremes-input-incertitude').val('').prop('disabled', false);
        $('#quiz-extremes-resultat').addClass('d-none');
        $('#quiz-extremes-suivant-wrap').addClass('d-none');
        $('#quiz-extremes-envoyer').prop('disabled', true).removeClass('d-none');

        $('#quiz-extremes-input-moyenne').trigger('focus');
    }

    function afficherScore()
    {
        if (essais === 0)
        {
            $('#quiz-extremes-score').text("Aucun essai pour l'instant");
            return;
        }

        var moyenne = Math.round((reussis / essais) * 100);

        $('#quiz-extremes-score').text(reussis + ' / ' + essais + ' (' + moyenne + ' %)');
    }

    function texteSaisi(id)
    {
        var texte = $(id).val();

        if (texte === null) return '';

        return texte.trim().replace(',', '.');
    }

    function reponseValide()
    {
        var moyenne     = texteSaisi('#quiz-extremes-input-moyenne');
        var incertitude = texteSaisi('#quiz-extremes-input-incertitude');

        if (moyenne === '' || incertitude === '') return false;

        return isFinite(Number(moyenne)) && isFinite(Number(incertitude));
    }

    function envoyer()
    {
        if (repondu || ! courant || ! reponseValide()) return;

        repondu = true;

        var moyenneSaisie     = texteSaisi('#quiz-extremes-input-moyenne');
        var incertitudeSaisie = texteSaisi('#quiz-extremes-input-incertitude');

        var correct = (moyenneSaisie === courant.moyenne_valeur) && (incertitudeSaisie === courant.incertitude_valeur);

        essais++;
        if (correct) reussis++;

        afficherScore();
        sauvegarderScore();

        $('#quiz-extremes-input-moyenne').prop('disabled', true);
        $('#quiz-extremes-input-incertitude').prop('disabled', true);
        $('#quiz-extremes-envoyer').addClass('d-none');

        var reponseAttendue = courant.moyenne + ' ± ' + courant.incertitude + ' ' + courant.unite;
        var titre           = correct ? 'RÉUSSI' : ('ÉCHEC — réponse attendue : ' + reponseAttendue);

        $('#quiz-extremes-resultat-titre').text(titre);

        if ( ! correct)
        {
            $('#quiz-extremes-explication').html(courant.explication).removeClass('d-none');
        }
        else
        {
            $('#quiz-extremes-explication').html('').addClass('d-none');
        }

        $('#quiz-extremes-resultat')
            .removeClass('d-none reussi echec')
            .addClass(correct ? 'reussi' : 'echec');

        $('#quiz-extremes-suivant-wrap').removeClass('d-none');
        $('#quiz-extremes-suivant').trigger('focus');
    }

    $('#quiz-extremes-input-moyenne, #quiz-extremes-input-incertitude').on('input', function()
    {
        $('#quiz-extremes-envoyer').prop('disabled', ! reponseValide());
    });

    $('#quiz-extremes-envoyer').on('click', function()
    {
        envoyer();
    });

    $('#quiz-extremes-suivant').on('click', function()
    {
        questionSuivante();
    });

    $('#quiz-extremes-remise-zero').on('click', function()
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

        if (repondu && ! $('#quiz-extremes-suivant-wrap').hasClass('d-none'))
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

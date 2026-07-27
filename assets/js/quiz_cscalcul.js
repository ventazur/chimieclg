/* ====================================================================
 *
 * quiz_cscalcul.js
 *
 * Quiz d'entrainement : chiffres significatifs d'un calcul (equation
 * combinant addition/soustraction et multiplication/division)
 *
 * ==================================================================== */
$(document).ready(function()
{
    var tampon        = [];   // lot d'equations en attente
    var enChargement   = false;
    var courant        = null;
    var essais         = 0;
    var reussis        = 0;
    var repondu         = false;
    var selection       = null;  // valeur choisie avant l'envoi

    var SEUIL_RECHARGE = 5;
    var CLE_SCORE       = 'quiz_cscalcul_score';

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

        $.getJSON(base_url + 'quiz/lot/cscalcul', function(data)
        {
            enChargement = false;

            if (data && data.length)
            {
                tampon = tampon.concat(data);
            }

            if (courant === null)
            {
                equationSuivante();
            }
        })
        .fail(function()
        {
            enChargement = false;
        });
    }

    function equationSuivante()
    {
        if (tampon.length <= SEUIL_RECHARGE)
        {
            chargerLot();
        }

        if (tampon.length === 0)
        {
            $('#quiz-cscalcul-equation').text('...');
            return;
        }

        courant   = tampon.shift();
        repondu   = false;
        selection = null;

        $('#quiz-cscalcul-equation').html(courant.affichage);
        $('#quiz-cscalcul-resultat').addClass('d-none');
        $('#quiz-cscalcul-suivant-wrap').addClass('d-none');
        $('.quiz-cscalcul-choix').prop('disabled', false).removeClass('btn-dark btn-success btn-danger').addClass('btn-outline-dark');
        $('#quiz-cscalcul-envoyer').prop('disabled', true).removeClass('d-none');
    }

    function afficherScore()
    {
        if (essais === 0)
        {
            $('#quiz-cscalcul-score').text("Aucun essai pour l'instant");
            return;
        }

        var moyenne = Math.round((reussis / essais) * 100);

        $('#quiz-cscalcul-score').text(reussis + ' / ' + essais + ' (' + moyenne + ' %)');
    }

    function choisir(valeur, el)
    {
        if (repondu) return;

        selection = valeur;

        $('.quiz-cscalcul-choix').removeClass('btn-dark').addClass('btn-outline-dark');
        $(el).removeClass('btn-outline-dark').addClass('btn-dark');

        $('#quiz-cscalcul-envoyer').prop('disabled', false);
    }

    function envoyer()
    {
        if (repondu || ! courant || selection === null) return;

        repondu = true;

        var correct = (selection === courant.valeur);

        essais++;
        if (correct) reussis++;

        afficherScore();
        sauvegarderScore();

        $('.quiz-cscalcul-choix').prop('disabled', true);
        $('#quiz-cscalcul-envoyer').addClass('d-none');

        $('.quiz-cscalcul-choix').each(function()
        {
            var valeur  = parseInt($(this).attr('data-valeur'), 10);
            var choisi  = (selection === valeur);
            var attendu = (courant.valeur === valeur);

            $(this).removeClass('btn-outline-dark btn-dark');

            if (attendu)
            {
                $(this).addClass('btn-success');
            }
            else if (choisi)
            {
                $(this).addClass('btn-danger');
            }
            else
            {
                $(this).addClass('btn-outline-dark');
            }
        });

        var titre = correct ? 'RÉUSSI' : ('ÉCHEC — réponse attendue : ' + courant.valeur);

        $('#quiz-cscalcul-resultat-titre').text(titre);

        if ( ! correct)
        {
            $('#quiz-cscalcul-reponses')
                .html('Réponse brute : <strong>' + courant.reponse_brute + '</strong><br>Réponse ajustée au bon nombre de CS : <strong>' + courant.reponse_ajustee + '</strong>')
                .removeClass('d-none');

            $('#quiz-cscalcul-explication').text(courant.explication).removeClass('d-none');
        }
        else
        {
            $('#quiz-cscalcul-reponses').html('').addClass('d-none');
            $('#quiz-cscalcul-explication').text('').addClass('d-none');
        }

        $('#quiz-cscalcul-resultat')
            .removeClass('d-none reussi echec')
            .addClass(correct ? 'reussi' : 'echec');

        $('#quiz-cscalcul-suivant-wrap').removeClass('d-none');
        $('#quiz-cscalcul-suivant').trigger('focus');
    }

    $('.quiz-cscalcul-choix').on('click', function()
    {
        choisir(parseInt($(this).attr('data-valeur'), 10), this);
    });

    $('#quiz-cscalcul-envoyer').on('click', function()
    {
        envoyer();
    });

    $('#quiz-cscalcul-suivant').on('click', function()
    {
        equationSuivante();
    });

    $('#quiz-cscalcul-remise-zero').on('click', function()
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

    // Navigation clavier : Entree = Envoyer (si une selection est faite) ou Suivant (une fois repondu)
    $(document).on('keydown', function(e)
    {
        if (e.key !== 'Enter') return;

        if (repondu && ! $('#quiz-cscalcul-suivant-wrap').hasClass('d-none'))
        {
            e.preventDefault();
            equationSuivante();
        }
        else if ( ! repondu && selection !== null)
        {
            e.preventDefault();
            envoyer();
        }
    });

    chargerScore();
    afficherScore();
    chargerLot();
});

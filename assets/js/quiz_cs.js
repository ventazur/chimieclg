/* ====================================================================
 *
 * quiz_cs.js
 *
 * Quiz d'entrainement : chiffres significatifs
 *
 * ==================================================================== */
$(document).ready(function()
{
    var tampon        = [];   // lot de nombres en attente
    var enChargement   = false;
    var courant        = null;
    var essais         = 0;
    var reussis        = 0;
    var repondu         = false;
    var selection       = [];  // valeurs choisies avant l'envoi

    var SEUIL_RECHARGE = 5;
    var CLE_SCORE       = 'quiz_cs_score';

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

        $.getJSON(base_url + 'quiz/lot/cs', function(data)
        {
            enChargement = false;

            if (data && data.length)
            {
                tampon = tampon.concat(data);
            }

            if (courant === null)
            {
                nombreSuivant();
            }
        })
        .fail(function()
        {
            enChargement = false;
        });
    }

    function nombreSuivant()
    {
        if (tampon.length <= SEUIL_RECHARGE)
        {
            chargerLot();
        }

        if (tampon.length === 0)
        {
            $('#quiz-cs-nombre').text('...');
            return;
        }

        courant   = tampon.shift();
        repondu   = false;
        selection = [];

        $('#quiz-cs-nombre').html(courant.affichage);
        $('#quiz-cs-resultat').addClass('d-none');
        $('#quiz-cs-suivant-wrap').addClass('d-none');
        $('.quiz-cs-choix').prop('disabled', false).removeClass('btn-dark btn-success btn-danger').addClass('btn-outline-dark');
        $('#quiz-cs-envoyer').prop('disabled', true).removeClass('d-none');
    }

    function afficherScore()
    {
        if (essais === 0)
        {
            $('#quiz-cs-score').text("Aucun essai pour l'instant");
            return;
        }

        var moyenne = Math.round((reussis / essais) * 100);

        $('#quiz-cs-score').text(reussis + ' / ' + essais + ' (' + moyenne + ' %)');
    }

    function basculerChoix(valeur, el)
    {
        if (repondu) return;

        var index = selection.indexOf(valeur);

        if (index === -1)
        {
            selection.push(valeur);
            $(el).removeClass('btn-outline-dark').addClass('btn-dark');
        }
        else
        {
            selection.splice(index, 1);
            $(el).removeClass('btn-dark').addClass('btn-outline-dark');
        }

        $('#quiz-cs-envoyer').prop('disabled', selection.length === 0);
    }

    function memeEnsemble(a, b)
    {
        if (a.length !== b.length) return false;

        var aTrie = a.slice().sort();
        var bTrie = b.slice().sort();

        for (var i = 0; i < aTrie.length; i++)
        {
            if (aTrie[i] !== bTrie[i]) return false;
        }

        return true;
    }

    function envoyer()
    {
        if (repondu || ! courant || selection.length === 0) return;

        repondu = true;

        var correct = memeEnsemble(selection, courant.valeurs);

        essais++;
        if (correct) reussis++;

        afficherScore();
        sauvegarderScore();

        $('.quiz-cs-choix').prop('disabled', true);
        $('#quiz-cs-envoyer').addClass('d-none');

        $('.quiz-cs-choix').each(function()
        {
            var valeur    = parseInt($(this).attr('data-valeur'), 10);
            var choisi    = selection.indexOf(valeur) !== -1;
            var attendu   = courant.valeurs.indexOf(valeur) !== -1;

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

        var titre = correct ? 'RÉUSSI' : 'ÉCHEC';

        if ( ! correct)
        {
            var reponses = courant.valeurs.join(' ou ');
            titre += ' — réponse' + (courant.valeurs.length > 1 ? 's' : '') + ' attendue' + (courant.valeurs.length > 1 ? 's' : '') + ' : ' + reponses;
        }

        $('#quiz-cs-resultat-titre').text(titre);

        if ( ! correct || courant.ambigu)
        {
            $('#quiz-cs-explication').text(courant.explication).removeClass('d-none');
        }
        else
        {
            $('#quiz-cs-explication').text('').addClass('d-none');
        }

        $('#quiz-cs-resultat')
            .removeClass('d-none reussi echec')
            .addClass(correct ? 'reussi' : 'echec');

        $('#quiz-cs-suivant-wrap').removeClass('d-none');
        $('#quiz-cs-suivant').trigger('focus');
    }

    $('.quiz-cs-choix').on('click', function()
    {
        basculerChoix(parseInt($(this).attr('data-valeur'), 10), this);
    });

    $('#quiz-cs-envoyer').on('click', function()
    {
        envoyer();
    });

    $('#quiz-cs-suivant').on('click', function()
    {
        nombreSuivant();
    });

    $('#quiz-cs-remise-zero').on('click', function()
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

        if (repondu && ! $('#quiz-cs-suivant-wrap').hasClass('d-none'))
        {
            e.preventDefault();
            nombreSuivant();
        }
        else if ( ! repondu && selection.length > 0)
        {
            e.preventDefault();
            envoyer();
        }
    });

    chargerScore();
    afficherScore();
    chargerLot();
});

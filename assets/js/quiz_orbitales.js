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

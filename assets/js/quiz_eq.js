/* ====================================================================
 *
 * quiz_eq.js
 *
 * Quiz d'entrainement : nombre d'etats quantiques a partir des nombres
 * quantiques (n, l, ml, ms)
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
    var CLE_SCORE       = 'quiz_eq_score';
    var ORDRE_CLES      = ['n', 'n_max', 'l', 'ml', 'ms'];

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

        $.getJSON(base_url + 'quiz/lot/eq', function(data)
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
                if (cle === 'n_max')
                {
                    html += '<span class="quiz-eq-valeur">n &lt; ' + affichage[cle] + '</span>';
                }
                else
                {
                    var etiquette = (cle === 'ml') ? 'mₗ' : (cle === 'ms') ? 'mₛ' : cle;
                    html += '<span class="quiz-eq-valeur">' + etiquette + ' = ' + affichage[cle] + '</span>';
                }
            }
        }

        $('#quiz-eq-valeurs').html(html);
    }

    function questionSuivante()
    {
        if (tampon.length <= SEUIL_RECHARGE)
        {
            chargerLot();
        }

        if (tampon.length === 0)
        {
            $('#quiz-eq-valeurs').text('...');
            return;
        }

        courant = tampon.shift();
        repondu = false;

        afficherValeurs(courant.affichage);

        $('#quiz-eq-input').val('').prop('disabled', false);
        $('#quiz-eq-resultat').addClass('d-none');
        $('#quiz-eq-suivant-wrap').addClass('d-none');
        $('#quiz-eq-envoyer').prop('disabled', true).removeClass('d-none');

        $('#quiz-eq-input').trigger('focus');
    }

    function afficherScore()
    {
        if (essais === 0)
        {
            $('#quiz-eq-score').text("Aucun essai pour l'instant");
            return;
        }

        var moyenne = Math.round((reussis / essais) * 100);

        $('#quiz-eq-score').text(reussis + ' / ' + essais + ' (' + moyenne + ' %)');
    }

    function reponseValide()
    {
        var texte = $('#quiz-eq-input').val();

        if (texte === '' || texte === null) return false;

        var nombre = Number(texte);

        return Number.isInteger(nombre) && nombre >= 0;
    }

    function envoyer()
    {
        if (repondu || ! courant || ! reponseValide()) return;

        repondu = true;

        var reponse = parseInt($('#quiz-eq-input').val(), 10);
        var correct  = (reponse === courant.valeur);

        essais++;
        if (correct) reussis++;

        afficherScore();
        sauvegarderScore();

        $('#quiz-eq-input').prop('disabled', true);
        $('#quiz-eq-envoyer').addClass('d-none');

        var titre = correct ? 'RÉUSSI' : ('ÉCHEC — réponse attendue : ' + courant.valeur);

        $('#quiz-eq-resultat-titre').text(titre);

        if ( ! correct)
        {
            $('#quiz-eq-explication').text(courant.explication).removeClass('d-none');
        }
        else
        {
            $('#quiz-eq-explication').text('').addClass('d-none');
        }

        $('#quiz-eq-resultat')
            .removeClass('d-none reussi echec')
            .addClass(correct ? 'reussi' : 'echec');

        $('#quiz-eq-suivant-wrap').removeClass('d-none');
        $('#quiz-eq-suivant').trigger('focus');
    }

    $('#quiz-eq-input').on('input', function()
    {
        $('#quiz-eq-envoyer').prop('disabled', ! reponseValide());
    });

    $('#quiz-eq-envoyer').on('click', function()
    {
        envoyer();
    });

    $('#quiz-eq-suivant').on('click', function()
    {
        questionSuivante();
    });

    $('#quiz-eq-remise-zero').on('click', function()
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

        if (repondu && ! $('#quiz-eq-suivant-wrap').hasClass('d-none'))
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

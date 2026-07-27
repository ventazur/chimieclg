/* ====================================================================
 *
 * quiz_cases.js
 *
 * Quiz d'entrainement : cases quantiques d'un element (Z = 1 a 38) et
 * 5 questions numeriques sur l'atome neutre et/ou un de ses ions.
 *
 * ==================================================================== */
$(document).ready(function()
{
    var tampon        = [];   // lot d'instances (element + cases + 5 questions) en attente
    var enChargement   = false;
    var courant        = null;
    var repondu         = false;

    var NB_QUESTIONS   = 5;
    var SEUIL_RECHARGE = 3;
    var CLE_SCORE       = 'quiz_cases_score';

    var essais  = 0;
    var reussis = 0;

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

    function afficherScore()
    {
        if (essais === 0)
        {
            $('#quiz-cases-score').text("Aucun essai pour l'instant");
            return;
        }

        var moyenne = Math.round((reussis / essais) * 100);

        $('#quiz-cases-score').text(reussis + ' / ' + essais + ' (' + moyenne + ' %)');
    }

    function chargerLot()
    {
        if (enChargement) return;

        enChargement = true;

        $.getJSON(base_url + 'quiz/lot/cases', function(data)
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

    function afficherElement(element)
    {
        var html = '<div id="quiz-cases-element-symbole">' + element.symbole + '</div>'
            + '<div id="quiz-cases-element-details">' + element.nom + ' — Z = ' + element.z + '</div>';

        $('#quiz-cases-element').html(html);
    }

    function afficherDiagramme(cases)
    {
        var html = '';

        for (var i = 0; i < cases.length; i++)
        {
            var sousCouche = cases[i];

            html += '<div class="quiz-cases-sous-couche">';
            html += '<div class="quiz-cases-boites">';

            for (var j = 0; j < sousCouche.boites.length; j++)
            {
                html += '<div class="quiz-cases-boite">' + sousCouche.boites[j] + '</div>';
            }

            html += '</div>';
            html += '<div class="quiz-cases-etiquette">' + sousCouche.etiquette + '</div>';
            html += '</div>';
        }

        $('#quiz-cases-diagramme').html(html).addClass('d-none');
        $('#quiz-cases-diagramme-afficher-wrap').removeClass('d-none');
    }

    function afficherQuestions(questions)
    {
        var html = '';

        for (var i = 0; i < questions.length; i++)
        {
            var question = questions[i];

            html += '<div class="quiz-cases-question" data-index="' + i + '">';
            html += '<div class="quiz-cases-contexte">' + question.contexte + '</div>';
            html += '<div class="quiz-cases-enonce">' + question.enonce + '</div>';
            html += '<div class="quiz-cases-reponse">';
            html += '<input type="number" step="1" inputmode="numeric" autocomplete="off" class="form-control quiz-cases-input" id="quiz-cases-input-' + i + '" aria-label="Réponse">';
            html += '</div>';
            html += '<div class="quiz-cases-question-resultat d-none"></div>';
            html += '</div>';
        }

        $('#quiz-cases-questions').html(html);

        $('.quiz-cases-input').on('input', function()
        {
            $('#quiz-cases-envoyer').prop('disabled', ! toutesReponsesValides());
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
            $('#quiz-cases-element').text('...');
            $('#quiz-cases-diagramme').empty();
            $('#quiz-cases-questions').empty();
            return;
        }

        courant = tampon.shift();
        repondu = false;

        afficherElement(courant.element);
        afficherDiagramme(courant.cases);
        afficherQuestions(courant.questions);

        $('#quiz-cases-resultat-global').addClass('d-none').text('');
        $('#quiz-cases-suivant-wrap').addClass('d-none');
        $('#quiz-cases-envoyer').prop('disabled', true).removeClass('d-none');

        $('#quiz-cases-input-0').trigger('focus');
    }

    function reponseValide(index)
    {
        var texte = $('#quiz-cases-input-' + index).val();

        if (texte === '' || texte === null) return false;

        var nombre = Number(texte);

        return Number.isInteger(nombre);
    }

    function toutesReponsesValides()
    {
        for (var i = 0; i < NB_QUESTIONS; i++)
        {
            if ( ! reponseValide(i)) return false;
        }

        return true;
    }

    function envoyer()
    {
        if (repondu || ! courant || ! toutesReponsesValides()) return;

        repondu = true;

        $('#quiz-cases-diagramme').removeClass('d-none');
        $('#quiz-cases-diagramme-afficher-wrap').addClass('d-none');

        var nbReussies = 0;

        for (var i = 0; i < courant.questions.length; i++)
        {
            var question = courant.questions[i];
            var reponse  = parseInt($('#quiz-cases-input-' + i).val(), 10);
            var correct  = (reponse === question.valeur);

            essais++;
            if (correct) { reussis++; nbReussies++; }

            var $ligne = $('.quiz-cases-question[data-index="' + i + '"]');

            $ligne.find('.quiz-cases-input').prop('disabled', true);

            var texte = correct ? 'RÉUSSI' : ('ÉCHEC — réponse attendue : ' + question.valeur);
            var html  = '<div>' + texte + '</div>';

            if ( ! correct)
            {
                html += '<div class="quiz-cases-question-explication">' + question.explication + '</div>';
            }

            $ligne.find('.quiz-cases-question-resultat')
                .removeClass('d-none reussi echec')
                .addClass(correct ? 'reussi' : 'echec')
                .html(html);
        }

        afficherScore();
        sauvegarderScore();

        $('#quiz-cases-envoyer').addClass('d-none');

        var libelleReponses = (nbReussies <= 1) ? 'bonne réponse' : 'bonnes réponses';

        $('#quiz-cases-resultat-global')
            .removeClass('d-none')
            .text(nbReussies + ' / ' + courant.questions.length + ' ' + libelleReponses);

        $('#quiz-cases-suivant-wrap').removeClass('d-none');
        $('#quiz-cases-suivant').trigger('focus');
    }

    $('#quiz-cases-diagramme-afficher').on('click', function()
    {
        $('#quiz-cases-diagramme').removeClass('d-none');
        $('#quiz-cases-diagramme-afficher-wrap').addClass('d-none');
    });

    $('#quiz-cases-envoyer').on('click', function()
    {
        envoyer();
    });

    $('#quiz-cases-suivant').on('click', function()
    {
        questionSuivante();
    });

    $('#quiz-cases-remise-zero').on('click', function()
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

    // Navigation clavier : Entree = Envoyer (si toutes les reponses sont valides) ou Suivant (une fois repondu)
    $(document).on('keydown', function(e)
    {
        if (e.key !== 'Enter') return;

        if (repondu && ! $('#quiz-cases-suivant-wrap').hasClass('d-none'))
        {
            e.preventDefault();
            questionSuivante();
        }
        else if ( ! repondu && toutesReponsesValides())
        {
            e.preventDefault();
            envoyer();
        }
    });

    chargerScore();
    afficherScore();
    chargerLot();
});

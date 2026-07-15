/* ====================================================================
 *
 * quiz_nomen.js
 *
 * Quiz d'entrainement : nom des ions polyatomiques et molecules
 * (appariement formule <-> nom par glisser-depose ou tap)
 *
 * ==================================================================== */
$(document).ready(function()
{
    var tampon       = [];   // lot de manches en attente
    var enChargement = false;
    var manche       = null; // { paires: [{struct, nom}, ...] }
    var numManche    = 0;
    var essais       = 0;
    var reussis      = 0;
    var placements   = {};   // cibleId -> { sourceId, correct }
    var selection    = null; // id de la source selectionnee (mode tap)

    var SEUIL_RECHARGE = 5;
    var CLE_SCORE       = 'quiz_nomen_score';

    // Sens pair (0, 2, 4, ...) : source = formule, cible = nom.
    // Sens impair : source = nom, cible = formule.
    function sensFormuleVersNom()
    {
        return numManche % 2 === 0;
    }

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
            $('#quiz-nomen-score').text("Aucun essai pour l'instant");
            return;
        }

        var moyenne = Math.round((reussis / essais) * 100);

        $('#quiz-nomen-score').text(reussis + ' / ' + essais + ' (' + moyenne + ' %)');
    }

    function chargerLot()
    {
        if (enChargement) return;

        enChargement = true;

        $.getJSON(base_url + 'quiz/lot/nomen', function(data)
        {
            enChargement = false;

            if (data && data.length)
            {
                tampon = tampon.concat(data);
            }

            if (manche === null)
            {
                mancheSuivante();
            }
        })
        .fail(function()
        {
            enChargement = false;
        });
    }

    function melanger(tableau)
    {
        var copie = tableau.slice();

        for (var i = copie.length - 1; i > 0; i--)
        {
            var j = Math.floor(Math.random() * (i + 1));
            var tmp = copie[i];
            copie[i] = copie[j];
            copie[j] = tmp;
        }

        return copie;
    }

    function creerJeton(id, contenu, role)
    {
        var $jeton = $('<div>')
            .addClass('quiz-nomen-jeton')
            .attr('data-id', id);

        if (role === 'source')
        {
            $jeton.addClass('quiz-nomen-source').attr('draggable', 'true');
            $jeton.append($('<span>').addClass('jeton-texte').html(contenu));
        }
        else
        {
            $jeton.addClass('quiz-nomen-cible');

            var $ligne = $('<div>').addClass('cible-ligne');
            $ligne.append($('<div>').addClass('cible-label').html(contenu));
            $ligne.append($('<span>').addClass('cible-marque d-none'));

            $jeton.append($ligne);
        }

        return $jeton;
    }

    function mancheSuivante()
    {
        if (tampon.length <= SEUIL_RECHARGE)
        {
            chargerLot();
        }

        if (tampon.length === 0)
        {
            $('#quiz-nomen-sources, #quiz-nomen-cibles').empty();
            return;
        }

        manche     = tampon.shift();
        placements = {};
        selection  = null;

        var formuleVersNom = sensFormuleVersNom();

        $('#quiz-nomen-consigne-source').text(formuleVersNom ? 'formule' : 'nom');
        $('#quiz-nomen-consigne-cible').text(formuleVersNom ? 'nom' : 'formule');

        var indices       = manche.paires.map(function(p, i) { return i; });
        var indicesSource = melanger(indices);
        var indicesCible  = melanger(indices);

        var $sources = $('#quiz-nomen-sources').empty();
        var $cibles  = $('#quiz-nomen-cibles').empty();

        indicesSource.forEach(function(i)
        {
            var contenu = formuleVersNom ? manche.paires[i].struct : manche.paires[i].nom;
            $sources.append(creerJeton(i, contenu, 'source'));
        });

        indicesCible.forEach(function(i)
        {
            var contenu = formuleVersNom ? manche.paires[i].nom : manche.paires[i].struct;
            $cibles.append(creerJeton(i, contenu, 'cible'));
        });

        $('#quiz-nomen-continuer-wrap').addClass('d-none');
    }

    function toutesLesCiblesRemplies()
    {
        return Object.keys(placements).length >= manche.paires.length;
    }

    function revelerCorrections()
    {
        var formuleVersNom = sensFormuleVersNom();

        $('.quiz-nomen-cible').each(function()
        {
            var cibleId = parseInt($(this).attr('data-id'), 10);
            var p       = placements[cibleId];

            if (p && ! p.correct)
            {
                var bonneReponse = formuleVersNom ? manche.paires[cibleId].struct : manche.paires[cibleId].nom;

                $(this).append(
                    $('<span>').addClass('quiz-nomen-correction').html(bonneReponse)
                );
            }
        });

        $('#quiz-nomen-continuer-wrap').removeClass('d-none');
    }

    function placer(sourceId, cibleId)
    {
        if (placements.hasOwnProperty(cibleId)) return;
        if (! manche) return;

        var $source = $('.quiz-nomen-source[data-id="' + sourceId + '"]');
        var $cible  = $('.quiz-nomen-cible[data-id="' + cibleId + '"]');

        if ($source.length === 0 || $cible.length === 0) return;

        var correct = sourceId === cibleId;

        placements[cibleId] = { sourceId: sourceId, correct: correct };

        essais++;
        if (correct) reussis++;

        afficherScore();
        sauvegarderScore();

        $source.addClass('placee').removeClass('selectionne');
        selection = null;

        $cible
            .addClass('occupee')
            .addClass(correct ? 'correct' : 'incorrect')
            .find('.cible-marque')
            .text(correct ? '✓' : '✗')
            .removeClass('d-none');

        if (toutesLesCiblesRemplies())
        {
            revelerCorrections();
        }
    }

    /* ------------------------------------------------------------
     * Glisser-deposer (ordinateur)
     * ------------------------------------------------------------ */

    $('#quiz-nomen-sources').on('dragstart', '.quiz-nomen-source', function(e)
    {
        if ($(this).hasClass('placee')) { e.preventDefault(); return; }

        e.originalEvent.dataTransfer.setData('text/plain', $(this).attr('data-id'));
        e.originalEvent.dataTransfer.effectAllowed = 'move';
    });

    $('#quiz-nomen-cibles').on('dragover', '.quiz-nomen-cible', function(e)
    {
        if ($(this).hasClass('occupee')) return;

        e.preventDefault();
        $(this).addClass('survol');
    });

    $('#quiz-nomen-cibles').on('dragleave', '.quiz-nomen-cible', function()
    {
        $(this).removeClass('survol');
    });

    $('#quiz-nomen-cibles').on('drop', '.quiz-nomen-cible', function(e)
    {
        e.preventDefault();
        $(this).removeClass('survol');

        if ($(this).hasClass('occupee')) return;

        var sourceId = parseInt(e.originalEvent.dataTransfer.getData('text/plain'), 10);
        var cibleId  = parseInt($(this).attr('data-id'), 10);

        placer(sourceId, cibleId);
    });

    /* ------------------------------------------------------------
     * Tap-pour-associer (tactile / souris)
     * ------------------------------------------------------------ */

    $('#quiz-nomen-sources').on('click', '.quiz-nomen-source', function()
    {
        if ($(this).hasClass('placee')) return;

        var id = parseInt($(this).attr('data-id'), 10);

        if (selection === id)
        {
            selection = null;
            $(this).removeClass('selectionne');
        }
        else
        {
            $('.quiz-nomen-source').removeClass('selectionne');
            selection = id;
            $(this).addClass('selectionne');
        }
    });

    $('#quiz-nomen-cibles').on('click', '.quiz-nomen-cible', function()
    {
        if ($(this).hasClass('occupee')) return;
        if (selection === null) return;

        var cibleId = parseInt($(this).attr('data-id'), 10);

        placer(selection, cibleId);
    });

    $('#quiz-nomen-continuer').on('click', function()
    {
        numManche++;
        mancheSuivante();
    });

    $('#quiz-nomen-remise-zero').on('click', function()
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

    chargerScore();
    afficherScore();
    chargerLot();
});

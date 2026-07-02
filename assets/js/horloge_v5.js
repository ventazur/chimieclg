/* ====================================================================
 *
 * chimieclg.ca > horloge_v5.js — Compte à rebours examen
 *
 * ==================================================================== */

$(document).ready(function ()
{
    var CONFIG = {
        pingInterval: 30000,
        cookieExpire: 60 * 60 * 6,
        seuilJauneMs: 15 * 60 * 1000,
        seuilRougeMs: 5 * 60 * 1000
    };

    var decalageServeurLocal = 0;
    var decalageCible        = 0;
    var heureLimite = docCookies.getItem('heure_limite');
    if (!heureLimite || !/^\d{1,2}:\d{2}$/.test(heureLimite)) heureLimite = '18:00';

    var epochMsInitial = Number($('#maintenant-epoch').attr('data-epoch-ms'));
    if (epochMsInitial) {
        decalageServeurLocal = epochMsInitial - Date.now();
        decalageCible        = decalageServeurLocal;
    }

    $('#parametres-heure').val(heureLimite);
    rendreChiffresFixes($('#horloge-heure-fin-exact'), heureLimite);

    // --- Temps serveur ---

    function obtenirTempsServeurActuel()
    {
        return Date.now() + decalageServeurLocal;
    }

    function appliquerNouveauDecalage(nouveau)
    {
        if (Math.abs(nouveau - decalageServeurLocal) > 1500) {
            decalageServeurLocal = decalageCible = nouveau;
        } else {
            decalageCible = nouveau;
        }
    }

    function deuxChiffres(n)
    {
        return (n < 10 ? '0' : '') + n;
    }

    // Rend chaque caractère dans une case de largeur fixe, pour que les
    // chiffres proportionnels (ex. Fraunces) ne fassent pas bouger le texte.
    function rendreChiffresFixes(el, texte)
    {
        if (el.data('texte-actuel') === texte) return;
        el.data('texte-actuel', texte);

        el.empty();

        texte.split('').forEach(function (car) {
            var span = $('<span>').text(car);
            span.addClass(car === ':' ? 'horloge-colon' : 'horloge-digit');
            el.append(span);
        });
    }

    // --- Countdown ---

    function appliquerEtatAlerte(diffMs)
    {
        var body = document.body;

        if (diffMs <= 0) {
            body.classList.remove('alerte-jaune', 'alerte-rouge');
            body.classList.add('alerte-terminee');
        } else if (diffMs < CONFIG.seuilRougeMs) {
            body.classList.remove('alerte-jaune', 'alerte-terminee');
            body.classList.add('alerte-rouge');
        } else if (diffMs < CONFIG.seuilJauneMs) {
            body.classList.remove('alerte-rouge', 'alerte-terminee');
            body.classList.add('alerte-jaune');
        } else {
            body.classList.remove('alerte-jaune', 'alerte-rouge', 'alerte-terminee');
        }
    }

    function calculerDureeRestante()
    {
        var parts = heureLimite.split(':');
        var dateLimite = new Date();
        dateLimite.setHours(parseInt(parts[0]), parseInt(parts[1]), 0, 0);

        var diffMs = dateLimite.getTime() - obtenirTempsServeurActuel();
        if (diffMs < -60000) diffMs += 24 * 3600 * 1000;
        if (diffMs < 0) diffMs = 0;

        var totalSecondes = Math.round(diffMs / 1000);
        var minutes = Math.floor(totalSecondes / 60);
        var secondes = totalSecondes % 60;

        rendreChiffresFixes($('#horloge-temps-minutes'), deuxChiffres(minutes) + ':' + deuxChiffres(secondes));

        appliquerEtatAlerte(diffMs);
    }

    function rafraichirTemps()
    {
        var ecart = decalageCible - decalageServeurLocal;
        if (ecart !== 0) {
            decalageServeurLocal += Math.max(-100, Math.min(100, ecart));
        }

        var maintenantMs = obtenirTempsServeurActuel();
        var timeStr = new Date(maintenantMs + 50).toTimeString().split(' ')[0];

        rendreChiffresFixes($('#horloge-heure'), timeStr);
        calculerDureeRestante();

        setTimeout(rafraichirTemps, 1000 - (maintenantMs % 1000));
    }

    function pingServeur()
    {
        var tempsDepart = Date.now();

        $.post(base_url + 'horloge/ping', { ci_csrf_token: cct }, function (data) {
            if (data && data.epoch_ms) {
                var latenceMs          = (Date.now() - tempsDepart) / 2;
                var tempsServeurEstime = Number(data.epoch_ms) + latenceMs;
                appliquerNouveauDecalage(tempsServeurEstime - Date.now());
            }
        }, 'json').always(function () {
            setTimeout(pingServeur, CONFIG.pingInterval);
        });
    }

    // --- UI ---

    $('#parametres').on('click', function () {
        $('#horloge-modal').modal('show');
    });

    $('#parametres-sauvegarder').on('click', function ()
    {
        var nouvelleHeure = $('#parametres-heure').val();
        if (nouvelleHeure) {
            heureLimite = nouvelleHeure;
            rendreChiffresFixes($('#horloge-heure-fin-exact'), heureLimite);
            docCookies.setItem('heure_limite', heureLimite, CONFIG.cookieExpire);
            calculerDureeRestante();
        }
        $('#horloge-modal').modal('hide');
    });

    $('#fullscreen-btn').on('click', function ()
    {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(function (err) {
                console.error('Erreur plein écran : ' + err.message);
            });
        } else {
            document.exitFullscreen();
        }
    });

    document.addEventListener('fullscreenchange', function () {
        var isFullscreen = !!document.fullscreenElement;
        $('#fullscreen-btn').toggleClass('is-active', isFullscreen).css('opacity', isFullscreen ? '0.2' : '1');
    });

    // --- Lancement ---

    rafraichirTemps();
    pingServeur();
});

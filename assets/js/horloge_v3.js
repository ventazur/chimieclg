/* ====================================================================
 *
 * chimieclg.ca > horloge_v3.js — Split-flap (tableau d'aéroport)
 *
 * ==================================================================== */

$(document).ready(function ()
{
    const CONFIG = {
        pingInterval: 30000,
        cookieExpire: 60 * 60 * 6,
        flipDuration: 500
    };

    let decalageServeurLocal = 0;
    let decalageCible        = 0;
    let heureLimite   = docCookies.getItem('heure_limite') || '18:00';
    let dernierTemps  = '';
    let dernierNbCartes = -1;

    const epochMsInitial = Number($('#maintenant-epoch').attr('data-epoch-ms'));
    if (epochMsInitial) {
        decalageServeurLocal = epochMsInitial - Date.now();
        decalageCible        = decalageServeurLocal;
    }

    $('#parametres-heure').val(heureLimite);

    function buildHeureFin()
    {
        var $el = $('#horloge-heure-fin-exact').empty();
        var parts = heureLimite.split(':');
        var chars = parts[0].split('').concat([':']).concat(parts[1].split(''));

        chars.forEach(function (c) {
            if (c === ':') {
                var $colon = $('<div class="mini-flap-colon">');
                $colon.append(
                    $('<div class="flap-half flap-top">').append('<span>:</span>'),
                    $('<div class="flap-half flap-bottom">').append('<span>:</span>')
                );
                $el.append($colon);
            } else {
                $el.append(buildMiniCard().each(function () {
                    $(this).attr('data-value', c).find('span').text(c);
                }));
            }
        });
    }

    buildHeureFin();

    // --- Construction des 6 cartes split-flap + 2 séparateurs ---

    function buildClock()
    {
        const $el = $('#horloge-heure').empty();
        const positions = ['h1', 'h2', 'colon', 'm1', 'm2', 'colon', 's1', 's2'];

        positions.forEach(function (p) {
            if (p === 'colon') {
                var $colon = $('<div class="flap-colon">');
                $colon.append(
                    $('<div class="flap-half flap-top">').append('<span>:</span>'),
                    $('<div class="flap-half flap-bottom">').append('<span>:</span>')
                );
                $el.append($colon);
                return;
            }

            const $card = $('<div class="flap-card" data-value="">');

            $card.append(
                $('<div class="flap-half flap-top">').append('<span>0</span>'),
                $('<div class="flap-half flap-bottom">').append('<span>0</span>'),
                $('<div class="flap-flip flap-flip-top">').append('<span>0</span>'),
                $('<div class="flap-flip flap-flip-bottom">').append('<span>0</span>')
            );

            $el.append($card);
        });
    }

    function setDigit($card, newVal)
    {
        var oldVal = $card.attr('data-value');
        if (oldVal === newVal) return;

        $card.attr('data-value', newVal);

        if (oldVal === '') {
            $card.find('span').text(newVal);
            return;
        }

        // Couche statique haute : nouveau chiffre, révélé quand le volet du haut bascule
        $card.find('.flap-top span').text(newVal);
        // Couche statique basse : garde l'ancien chiffre (visible tant que le volet du bas ne la recouvre pas)

        // Volet du haut : ancien chiffre, bascule vers le bas pour disparaître
        $card.find('.flap-flip-top span').text(oldVal);
        $card.find('.flap-flip-top').css('transform', 'rotateX(0deg)');

        // Volet du bas : nouveau chiffre, caché à 90deg puis bascule pour apparaître
        $card.find('.flap-flip-bottom span').text(newVal);
        $card.find('.flap-flip-bottom').css('transform', 'rotateX(90deg)');

        $card.removeClass('flipping');
        void $card[0].offsetWidth;
        $card.addClass('flipping');

        setTimeout(function () {
            $card.removeClass('flipping');
            $card.find('span').text(newVal);
            $card.find('.flap-flip-top').css('transform', 'rotateX(0deg)');
            $card.find('.flap-flip-bottom').css('transform', 'rotateX(0deg)');
        }, CONFIG.flipDuration);
    }

    function setClock(timeStr)
    {
        if (timeStr === dernierTemps) return;
        dernierTemps = timeStr;

        var digits = timeStr.replace(/:/g, '').split('');
        $('#horloge-heure .flap-card').each(function (i) {
            setDigit($(this), digits[i]);
        });
    }

    // --- Temps serveur ---

    function obtenirTempsServeurActuel()
    {
        return Date.now() + decalageServeurLocal;
    }

    // Applique un nouveau décalage : snap si la désynchro est forte (onglet en
    // veille, etc.), sinon vise une cible que rafraichirTemps() rejoint en douceur.
    function appliquerNouveauDecalage(nouveau)
    {
        if (Math.abs(nouveau - decalageServeurLocal) > 1500) {
            decalageServeurLocal = decalageCible = nouveau;
        } else {
            decalageCible = nouveau;
        }
    }

    function buildMiniCard()
    {
        var $card = $('<div class="mini-flap" data-value="">');
        $card.append(
            $('<div class="flap-half flap-top">').append('<span> </span>'),
            $('<div class="flap-half flap-bottom">').append('<span> </span>'),
            $('<div class="flap-flip flap-flip-top">').append('<span> </span>'),
            $('<div class="flap-flip flap-flip-bottom">').append('<span> </span>')
        );
        return $card;
    }

    function setMiniDigit($card, newVal)
    {
        var oldVal = $card.attr('data-value');
        if (oldVal === newVal) return;

        $card.attr('data-value', newVal);

        var display = newVal === '' ? ' ' : newVal;

        if (oldVal === '') {
            $card.find('span').text(display);
            return;
        }

        var oldDisplay = oldVal === '' ? ' ' : oldVal;

        $card.find('.flap-top span').text(display);
        $card.find('.flap-flip-top span').text(oldDisplay);
        $card.find('.flap-flip-top').css('transform', 'rotateX(0deg)');
        $card.find('.flap-flip-bottom span').text(display);
        $card.find('.flap-flip-bottom').css('transform', 'rotateX(90deg)');

        $card.removeClass('flipping');
        void $card[0].offsetWidth;
        $card.addClass('flipping');

        setTimeout(function () {
            $card.removeClass('flipping');
            $card.find('span').text(display);
            $card.find('.flap-flip-top').css('transform', 'rotateX(0deg)');
            $card.find('.flap-flip-bottom').css('transform', 'rotateX(0deg)');
        }, CONFIG.flipDuration);
    }

    function calculerDureeRestante()
    {
        var parts = heureLimite.split(':');
        var dateLimite = new Date();
        dateLimite.setHours(parseInt(parts[0]), parseInt(parts[1]), 0, 0);

        var diffMs = dateLimite.getTime() - obtenirTempsServeurActuel();
        if (diffMs < -60000) diffMs += 24 * 3600 * 1000;

        var minutes = Math.max(0, Math.floor((diffMs + 59999) / 60000));
        var padded = String(minutes).padStart(4, ' ').split('');
        var $container = $('#horloge-temps-minutes');

        if (dernierNbCartes !== 4) {
            $container.empty();
            for (var i = 0; i < 4; i++) {
                $container.append(buildMiniCard());
            }
            dernierNbCartes = 4;
        }

        $container.find('.mini-flap').each(function (i) {
            setMiniDigit($(this), padded[i] === ' ' ? '' : padded[i]);
        });

        $('.horloge-temps-minutes-pluriel').text(minutes > 1 ? 's' : '');
    }

    function rafraichirTemps()
    {
        // Lissage : on rapproche le décalage de sa cible d'au plus 100 ms par tick,
        // pour résorber une correction sans saut brutal du split-flap.
        var ecart = decalageCible - decalageServeurLocal;
        if (ecart !== 0) {
            decalageServeurLocal += Math.max(-100, Math.min(100, ecart));
        }

        var maintenantMs   = obtenirTempsServeurActuel();
        var tempsAfficheMs = maintenantMs + 50;
        var timeStr        = new Date(tempsAfficheMs).toTimeString().split(' ')[0];

        setClock(timeStr);
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
            buildHeureFin();
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

    buildClock();
    rafraichirTemps();
    pingServeur();
});

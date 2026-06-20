/* ====================================================================
 *
 * chimieclg.ca > horloge_v4.js — Tableau vert / craie
 *
 * ==================================================================== */

$(document).ready(function ()
{
    var CONFIG = {
        pingInterval: 30000,
        cookieExpire: 60 * 60 * 6,
        writeDuration: 650
    };

    var NS = 'http://www.w3.org/2000/svg';

    // Tracés SVG pour chaque chiffre (viewBox 0 0 40 70)
    // Chaque tracé suit l'ordre naturel d'écriture manuscrite.
    var PATHS = {
        '0': ['M20,5 C8,5 2,20 2,35 C2,50 8,65 20,65 C32,65 38,50 38,35 C38,20 32,5 20,5'],
        '1': ['M14,14 L23,5 L23,65'],
        '2': ['M8,18 C8,6 18,2 26,5 C34,8 37,17 34,26 C30,38 16,52 5,64 L37,64'],
        '3': ['M10,10 C16,3 30,2 34,12 C37,20 30,28 22,33 C30,36 38,44 34,56 C29,66 15,68 7,60'],
        '4': ['M30,5 L4,42 L36,42', 'M30,5 L30,65'],
        '5': ['M35,5 L11,5 L9,32 C15,25 24,23 31,28 C39,35 39,50 34,58 C28,65 15,67 7,58'],
        '6': ['M33,9 C26,2 14,3 9,16 C4,30 2,44 6,56 C10,65 22,69 31,63 C38,56 39,42 33,35 C27,28 15,29 8,38'],
        '7': ['M5,5 L37,5 L19,65'],
        '8': ['M20,4 C30,4 38,10 36,20 C34,28 26,32 20,35 C14,38 4,44 4,55 C4,66 14,70 22,70 C32,70 40,64 38,52 C36,44 26,38 20,35 C14,32 4,26 4,16 C4,6 12,2 20,4'],
        '9': ['M33,28 C36,14 28,3 18,3 C8,3 2,12 2,24 C2,34 8,40 18,40 C28,38 35,30 33,28 L25,65']
    };

    var decalageServeurLocal = 0;
    var decalageCible        = 0;
    var heureLimite   = docCookies.getItem('heure_limite');
    if (!heureLimite || !/^\d{1,2}:\d{2}$/.test(heureLimite)) heureLimite = '18:00';
    var dernierTemps  = '';

    var epochMsInitial = Number($('#maintenant-epoch').attr('data-epoch-ms'));
    if (epochMsInitial) {
        decalageServeurLocal = epochMsInitial - Date.now();
        decalageCible        = decalageServeurLocal;
    }

    $('#parametres-heure').val(heureLimite);

    // --- Création des éléments SVG ---

    var MAX_STROKES = 2;

    function createDigitSVG(cls)
    {
        var svg = document.createElementNS(NS, 'svg');
        svg.setAttribute('viewBox', '0 0 40 70');
        svg.setAttribute('class', cls);
        svg.setAttribute('data-value', '');

        for (var i = 0; i < MAX_STROKES; i++) {
            var path = document.createElementNS(NS, 'path');
            path.setAttribute('fill', 'none');
            path.setAttribute('stroke', 'currentColor');
            path.setAttribute('stroke-width', '3.5');
            path.setAttribute('stroke-linecap', 'round');
            path.setAttribute('stroke-linejoin', 'round');
            svg.appendChild(path);
        }

        return svg;
    }

    function createColonSVG(cls)
    {
        var svg = document.createElementNS(NS, 'svg');
        svg.setAttribute('viewBox', '0 0 20 70');
        svg.setAttribute('class', cls);

        var c1 = document.createElementNS(NS, 'circle');
        c1.setAttribute('cx', '10');
        c1.setAttribute('cy', '22');
        c1.setAttribute('r', '3.5');
        c1.setAttribute('fill', 'currentColor');
        svg.appendChild(c1);

        var c2 = document.createElementNS(NS, 'circle');
        c2.setAttribute('cx', '10');
        c2.setAttribute('cy', '48');
        c2.setAttribute('r', '3.5');
        c2.setAttribute('fill', 'currentColor');
        svg.appendChild(c2);

        return svg;
    }

    // --- Écriture et effacement ---

    function clearDigit(svg)
    {
        var paths = svg.querySelectorAll('path');
        for (var i = 0; i < paths.length; i++) {
            paths[i].removeAttribute('d');
            paths[i].style.transition = 'none';
            paths[i].style.strokeDasharray = 'none';
            paths[i].style.strokeDashoffset = '0';
        }
    }

    function writeDigit(svg, val, duration)
    {
        var strokes = PATHS[val];
        if (!strokes) { clearDigit(svg); return; }

        var paths = svg.querySelectorAll('path');
        var durationPerStroke = duration ? duration / strokes.length : 0;

        for (var i = 0; i < paths.length; i++) {
            if (i < strokes.length) {
                (function (path, d, delay, dur) {
                    setTimeout(function () {
                        path.setAttribute('d', d);
                        var length = path.getTotalLength();

                        if (!dur) {
                            path.style.transition = 'none';
                            path.style.strokeDasharray = 'none';
                            path.style.strokeDashoffset = '0';
                            return;
                        }

                        path.style.transition = 'none';
                        path.style.strokeDasharray = length;
                        path.style.strokeDashoffset = length;
                        path.getBoundingClientRect();

                        path.style.transition = 'stroke-dashoffset ' + dur + 'ms ease';
                        path.style.strokeDashoffset = '0';
                    }, delay);
                })(paths[i], strokes[i], i * durationPerStroke, durationPerStroke);
            } else {
                paths[i].removeAttribute('d');
            }
        }
    }

    function setDigit(svg, newVal)
    {
        var oldVal = svg.getAttribute('data-value');
        if (oldVal === newVal) return;

        svg.setAttribute('data-value', newVal);

        if (oldVal === '') {
            writeDigit(svg, newVal, 0);
            return;
        }

        clearDigit(svg);
        writeDigit(svg, newVal, CONFIG.writeDuration);
    }


    // --- Construction de l'horloge ---

    function buildClock()
    {
        var el = document.getElementById('horloge-heure');
        el.innerHTML = '';
        var positions = ['h1', 'h2', 'colon', 'm1', 'm2', 'colon', 's1', 's2'];

        positions.forEach(function (p) {
            if (p === 'colon') {
                el.appendChild(createColonSVG('chalk-colon'));
            } else {
                el.appendChild(createDigitSVG('chalk-digit'));
            }
        });
    }

    function setClock(timeStr)
    {
        if (timeStr === dernierTemps) return;
        dernierTemps = timeStr;

        var digits = timeStr.replace(/:/g, '').split('');
        var svgs = document.querySelectorAll('#horloge-heure .chalk-digit');
        for (var i = 0; i < svgs.length; i++) {
            setDigit(svgs[i], digits[i]);
        }
    }

    // --- Heure limite (SVG) ---

    function buildHeureFin()
    {
        var el = document.getElementById('horloge-heure-fin-exact');
        el.innerHTML = '';

        var chars = heureLimite.split('');
        chars.forEach(function (c) {
            if (c === ':') {
                el.appendChild(createColonSVG('chalk-colon-mini'));
            } else {
                var svg = createDigitSVG('chalk-mini');
                writeDigit(svg, c, 0);
                svg.setAttribute('data-value', c);
                el.appendChild(svg);
            }
        });
    }

    var dernierMinutes = '';

    function buildMinutes(minutesStr)
    {
        if (minutesStr === dernierMinutes) return;
        dernierMinutes = minutesStr;

        var el = document.getElementById('horloge-temps-minutes');
        var oldSvgs = el.querySelectorAll('.chalk-mini');
        var oldDigits = [];
        for (var i = 0; i < oldSvgs.length; i++) {
            oldDigits.push(oldSvgs[i].getAttribute('data-value') || '');
        }

        var newDigits = minutesStr.split('');

        if (oldDigits.length !== newDigits.length) {
            el.innerHTML = '';
            newDigits.forEach(function (d) {
                var svg = createDigitSVG('chalk-mini');
                writeDigit(svg, d, oldDigits.length === 0 ? 0 : CONFIG.writeDuration);
                svg.setAttribute('data-value', d);
                el.appendChild(svg);
            });
            return;
        }

        var svgs = el.querySelectorAll('.chalk-mini');
        for (var i = 0; i < svgs.length; i++) {
            setDigit(svgs[i], newDigits[i]);
        }
    }

    buildHeureFin();

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

    // --- Countdown ---

    function calculerDureeRestante()
    {
        var parts = heureLimite.split(':');
        var dateLimite = new Date();
        dateLimite.setHours(parseInt(parts[0]), parseInt(parts[1]), 0, 0);

        var diffMs = dateLimite.getTime() - obtenirTempsServeurActuel();
        if (diffMs < -60000) diffMs += 24 * 3600 * 1000;

        var minutes = Math.max(0, Math.floor((diffMs + 59999) / 60000));

        buildMinutes(String(minutes));
        $('.horloge-temps-minutes-pluriel').text(minutes > 1 ? 's' : '');
    }

    function rafraichirTemps()
    {
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

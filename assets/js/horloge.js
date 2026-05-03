/* ====================================================================
 *
 * chimieclg.ca > horloge.js  
 *
 * --------------------------------------------------------------------
 *
 * v2	avec effet "morph" sur HH:MM:SS)
 * v2.1	ameliorer la robustesse et la rapidite
 * v2.2 pecision accrue
 *
 * ==================================================================== */

$(document).ready(function () 
{
    const regex = /:/g;

    // Configuration
    const CONFIG = {
        pingInterval: 30000,
        secondeInterval: 1000,
        policeDefaut: 'Rubik',
        cookieExpire: 60 * 60 * 6 // 6 heures
    };

    // Initialisation
    let heureLimite = docCookies.getItem('heure_limite') || '18:00';
    let policeActuelle = docCookies.getItem('police_actuelle') || CONFIG.policeDefaut;

    // Mise à jour de l'interface initiale
    $('#parametres-heure').val(heureLimite);
    $('#horloge-heure-fin-exact').text(heureLimite.replace(':', 'h'));
    $('#horloge-heure').css('font-family', policeActuelle);
    $('#select-police').val(policeActuelle);

    // --- Morph Logic ---

    function buildClock($el, initialStr) 
    {
        $el.addClass('morph');
        const parts = ['h1', 'h2', 'colon', 'm1', 'm2', 'colon', 's1', 's2'];
        const $fragment = $(document.createDocumentFragment());

        parts.forEach(p => {
            if (p === 'colon') {
                $fragment.append($('<div class="colon">:</div>'));
            } else {
                const $d = $('<div class="digit"><div class="stack"></div></div>');
                const $stack = $d.find('.stack');
                for (let i = 0; i <= 9; i++) $stack.append($('<span>').text(i));
                $fragment.append($d);
            }
        });

        $el.empty().append($fragment);
        if (initialStr) setClock(initialStr);
    }

    function setClock(timeStr) 
    {
        const digits = timeStr.replace(regex, '').split('').map(Number);
        const $digits = $('#horloge-heure .digit');

        $digits.each(function(i) {
            const $digit = $(this);
            const val = digits[i];
            const h = $digit.height(); 
            // translate3d force l'accélération matérielle (GPU)
            $(this).find('.stack').css('transform', `translate3d(0, ${-(val * h)}px, 0)`);
        });
    }

    // --- Calculs de temps ---

    function calculerDureeRestante() 
    {
        const [h, m] = heureLimite.split(':');
        const dateLimite = new Date();
        dateLimite.setHours(parseInt(h), parseInt(m), 0, 0);

        const limiteEpoch = dateLimite.getTime() / 1000;
        let maintenantEpoch = Number($('#maintenant-epoch').text());
        let diff = limiteEpoch - maintenantEpoch;

        if (diff < -60) { 
            diff += 24 * 3600; 
        }

        let minutes = Math.max(0, Math.ceil(diff / 60));

        $('#horloge-temps-minutes').text(minutes);
        $('#horloge-temps-minutes-pluriel').text(minutes > 1 ? 's' : '');
    }

    function rafraichirTemps() 
    {
        const maintenantEpoch = Number($('#maintenant-epoch').text());
        const d = new Date(maintenantEpoch * 1000);

        // Formatage HH:MM:SS
        const timeStr = d.toTimeString().split(' ')[0];

        setClock(timeStr);
        $('#maintenant-epoch').text(maintenantEpoch + 1);

        calculerDureeRestante();

        // Optimisation : On recalcule le délai pour tomber pile sur la milliseconde 0 de la prochaine seconde
        // Cela évite que l'horloge ne décale progressivement par rapport au système.
        const delaiCorrection = 1000 - (Date.now() % 1000);
        setTimeout(rafraichirTemps, delaiCorrection);
    }

    function pingServeur() 
    {
        const tempsDepart = Date.now(); // Début de la requête

        $.post(`${base_url}horloge/ping`, { ci_csrf_token: cct }, 
        function (data)
        {
            if (data?.epoch) 
            {
                // Calcul de la latence aller-retour (RTT) convertie en secondes / 2
                // On estime que le serveur est à mi-chemin du temps total de la requête.
                const latence = (Date.now() - tempsDepart) / 2000;
                $('#maintenant-epoch').text(Number(data.epoch) + latence);
            }
        }, 'json').always(() => {
            setTimeout(pingServeur, CONFIG.pingInterval);
        });
    }

    // --- Événements UI ---

    $('#parametres').on('click', () => $('#horloge-modal').modal('show'));

    $('#select-police').on('change', function() 
    {
        const police = $(this).val();
        $('#horloge-heure').css('font-family', police);
        docCookies.setItem('police_actuelle', police, CONFIG.cookieExpire);
    });

    $('#parametres-sauvegarder').on('click', function () 
    {
        const nouvelleHeure = $('#parametres-heure').val();
        if (nouvelleHeure) {
            heureLimite = nouvelleHeure;
            $('#horloge-heure-fin-exact').text(heureLimite.replace(':', 'h'));
            docCookies.setItem('heure_limite', heureLimite, CONFIG.cookieExpire);
            calculerDureeRestante();
        }
        $('#horloge-modal').modal('hide');
    });

    // Plein écran
    $('#fullscreen-btn').on('click', function() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(err => {
                console.error(`Erreur: ${err.message}`);
            });
        } else {
            document.exitFullscreen();
        }
    });

    document.addEventListener('fullscreenchange', () => {
        if (document.fullscreenElement) {
            $('#fullscreen-btn').addClass('is-active').css('opacity', '0.2');
        } else {
            $('#fullscreen-btn').removeClass('is-active').css('opacity', '1');
        }
    });

    // --- Lancement ---

    buildClock($('#horloge-heure'), $('#horloge-heure').text().trim());
    rafraichirTemps();
    pingServeur();
});

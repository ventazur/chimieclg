/* ====================================================================
 *
 * chimieclg.ca > horloge.js  
 *
 * --------------------------------------------------------------------
 *
 * v2	avec effet "morph" sur HH:MM:SS)
 * v2.1	ameliorer la robustesse et la rapidite
 * v2.2 precision accrue
 * v2.3 precision accrue+
 * v2.4 ajout de polices dans une liste dynamique
 * v2.5 ajuster la taille de la police de l'horloge
 *
 * ==================================================================== */

$(document).ready(function () 
{
    // -- Gestion des polices ---
    const POLICES_DISPONIBLES = [
        { nom: "Gugi",            googleQuery: "Gugi",                        label: "Futuriste"  },
        { nom: "Rubik",           googleQuery: "Rubik:wght@300..900",         label: "Moderne"    },
        { nom: "Manrope",         googleQuery: "Manrope:wght@200;400;600",    label: "Minimaliste"},
        { nom: "Montserrat",      googleQuery: "Montserrat:wght@300;600",     label: "Classique"  },
        { nom: "Space Mono",      googleQuery: "Space+Mono:wght@400;700",     label: "Rétro-code" },
        { nom: "Oswald",          googleQuery: "Oswald:wght@400;600",         label: "Condensée"  },
        { nom: "Fira Code",       googleQuery: "Fira+Code:wght@300..700",     label: "Technique"  },
        { nom: "Bebas Neue",      googleQuery: "Bebas+Neue",                  label: "Impact"     },
        { nom: "Share Tech Mono", googleQuery: "Share+Tech+Mono",             label: "Terminal"   },
        { nom: "Oxanium",         googleQuery: "Oxanium:wght@300..800",       label: "Sci-Fi"     },
        { nom: "Nunito",          googleQuery: "Nunito:wght@300;600;800",     label: "Arrondie"   },
        { nom: "Quicksand",       googleQuery: "Quicksand:wght@400;600",      label: "Douce"      },
        { nom: "Comfortaa",       googleQuery: "Comfortaa:wght@400;700",      label: "Friendly"   },
    ];

    // Chargement dynamique depuis Google Fonts
    const fontFamilies = POLICES_DISPONIBLES.map(p => `family=${p.googleQuery}`).join('&');
    $('head').append(`<link href="https://fonts.googleapis.com/css2?${fontFamilies}&display=swap" rel="stylesheet">`);

    // --- Configuration ---
    const CONFIG = {
        pingInterval:  30000,
        policeDefaut:  'Rubik',
        tailleDefaut:  12,
        cookieExpire:  60 * 60 * 6
    };

    const regex = /:/g;
    let decalageServeurLocal = 0;

    // --- État initial ---
    let heureLimite   = docCookies.getItem('heure_limite');
    if (!heureLimite || !/^\d{1,2}:\d{2}$/.test(heureLimite)) heureLimite = '18:00';
    let policeActuelle = docCookies.getItem('police_actuelle') || CONFIG.policeDefaut;
    let tailleActuelle = Number(docCookies.getItem('taille_actuelle')) || CONFIG.tailleDefaut;

    // Compensation du décalage serveur/local
    const epochInitial = Number($('#maintenant-epoch').text());
    if (epochInitial) {
        decalageServeurLocal = ((epochInitial * 1000) + 500) - Date.now();
    }

    // --- Construction du menu des polices ---
    const $selectPolice = $('#select-police').empty();
    POLICES_DISPONIBLES.forEach(police => {
        $selectPolice.append(
            $('<option>', {
                value:    police.nom,
                text:     `${police.nom} (${police.label})`,
                selected: police.nom === policeActuelle
            })
        );
    });

    // --- Initialisation de l'interface ---
    $('#parametres-heure').val(heureLimite);
    $('#horloge-heure-fin-exact').text(heureLimite.replace(':', 'h'));
    $('#horloge-heure').css({
        'font-family': policeActuelle,
        'font-size':   `${tailleActuelle}rem`
    });
    $('#range-taille').val(tailleActuelle);
    $('#label-taille').text(tailleActuelle);

    // --- Morph Logic ---
    function buildClock($el, initialStr)
    {
        $el.addClass('morph').empty();

        ['h1', 'h2', 'colon', 'm1', 'm2', 'colon', 's1', 's2'].forEach(p => {
            if (p === 'colon') {
                $el.append('<div class="colon">:</div>');
            } else {
                const $stack = $('<div class="stack">');
                for (let i = 0; i <= 9; i++) $stack.append($('<span>').text(i));
                $el.append($('<div class="digit">').append($stack));
            }
        });

        // Attend le prochain frame pour que le navigateur calcule les dimensions
        requestAnimationFrame(() => {
            if (initialStr) setClock(initialStr);
        });
    }

    function setClock(timeStr)
    {
        const digits = timeStr.replace(regex, '').split('').map(Number);

        $('#horloge-heure .digit').each(function (i) {
            const h = this.getBoundingClientRect().height;
            $(this).find('.stack').css('transform', `translate3d(0, ${-(digits[i] * h)}px, 0)`);
        });
    }

    // --- Calculs de temps ---
    function obtenirTempsServeurActuel()
    {
        return Date.now() + decalageServeurLocal;
    }

    function calculerDureeRestante()
    {
        const [h, m]    = heureLimite.split(':');
        const dateLimite = new Date();
        dateLimite.setHours(parseInt(h), parseInt(m), 0, 0);

        let diffMs = dateLimite.getTime() - obtenirTempsServeurActuel();
        if (diffMs < -60000) diffMs += 24 * 3600 * 1000;

        const minutes = Math.max(0, Math.floor((diffMs + 59999) / 60000));
        $('#horloge-temps-minutes').text(minutes);
        $('.horloge-temps-minutes-pluriel').text(minutes > 1 ? 's' : '');
    }

    function rafraichirTemps()
    {
        const maintenantMs  = obtenirTempsServeurActuel();
        const tempsAfficheMs = maintenantMs + 50;
        const timeStr        = new Date(tempsAfficheMs).toTimeString().split(' ')[0];

        setClock(timeStr);
        $('#maintenant-epoch').text(Math.floor(maintenantMs / 1000));
        calculerDureeRestante();

        // Aligne le prochain tick sur la milliseconde 0 du temps serveur
        setTimeout(rafraichirTemps, 1000 - (maintenantMs % 1000));
    }

    function pingServeur()
    {
        const tempsDepart = Date.now();

        $.post(`${base_url}horloge/ping`, { ci_csrf_token: cct }, function (data) {
            if (data?.epoch) {
                const latenceMs          = (Date.now() - tempsDepart) / 2;
                const tempsServeurEstime = (Number(data.epoch) * 1000 + 500) + latenceMs;
                decalageServeurLocal     = tempsServeurEstime - Date.now();
            }
        }, 'json').always(() => setTimeout(pingServeur, CONFIG.pingInterval));
    }

    // --- Événements UI ---
    $('#parametres').on('click', () => $('#horloge-modal').modal('show'));

    $('#select-police').on('change', function ()
    {
        policeActuelle = $(this).val();
        $('#horloge-heure').css('font-family', policeActuelle);
        docCookies.setItem('police_actuelle', policeActuelle, CONFIG.cookieExpire);
    });

    $('#range-taille').on('input', function ()
    {
        tailleActuelle = Number($(this).val());
        $('#label-taille').text(tailleActuelle);
        $('#horloge-heure').css('font-size', `${tailleActuelle}rem`);
        docCookies.setItem('taille_actuelle', tailleActuelle, CONFIG.cookieExpire);

        // Reconstruction pour recalibrer la hauteur des .digit
        buildClock($('#horloge-heure'), '');
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

    $('#fullscreen-btn').on('click', function ()
    {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(err => {
                console.error(`Erreur plein écran : ${err.message}`);
            });
        } else {
            document.exitFullscreen();
        }
    });

    document.addEventListener('fullscreenchange', () => {
        const isFullscreen = !!document.fullscreenElement;
        $('#fullscreen-btn').toggleClass('is-active', isFullscreen).css('opacity', isFullscreen ? '0.2' : '1');
    });

    // --- Lancement ---
    buildClock($('#horloge-heure'), new Date(obtenirTempsServeurActuel() + 50).toTimeString().split(' ')[0]);
    rafraichirTemps();
    pingServeur();
});

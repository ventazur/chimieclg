
<?
/* --------------------------------------------------------------------
 *
 * Horloge — version 3 (split-flap / tableau d'aéroport)
 *
 * -------------------------------------------------------------------- */ ?>

<body>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400&family=Oswald:wght@600;700&family=Share+Tech+Mono&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <style>
        :root {
            --bg-color: #111;
            --text-color: #fff;
            --accent-color: crimson;
            --card-radius: 10px;
            --card-gap: 14px;
            --digit-width: 10vw;
            --card-ratio: 1.40;
            --digit-height: calc(var(--digit-width) * var(--card-ratio));
            --font-size: calc(var(--digit-width) * 1);
            --mini-width: 4.5vw;
            --mini-height: calc(var(--mini-width) * var(--card-ratio));
            --mini-font: calc(var(--mini-width) * 1);
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: 'Oswald', sans-serif;
            margin: 0;
            overflow: hidden;
        }

        #horloge {
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        #horloge-settings {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 100;
        }

        #horloge-contenu {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        #horloge-clock-wrapper {
            display: flex;
            flex-direction: column;
            align-items: stretch;
        }

        /* --- Split-Flap Display --- */

        #horloge-heure {
            display: flex;
            align-items: center;
            gap: var(--card-gap);
            margin-bottom: 50px;
        }

        .flap-colon {
            position: relative;
            width: var(--digit-width);
            height: var(--digit-height);
            font-size: calc(var(--font-size) * 0.7);
            text-align: center;
            line-height: var(--digit-height);
            color: var(--text-color);
            user-select: none;
        }

        .flap-colon .flap-half {
            position: absolute;
            left: 0;
            width: 100%;
            height: 50%;
            overflow: hidden;
        }

        .flap-colon .flap-top {
            top: 0;
            border-radius: var(--card-radius) var(--card-radius) 0 0;
            background: linear-gradient(to bottom, #282828, #1c1c1c);
        }

        .flap-colon .flap-bottom {
            bottom: 0;
            border-radius: 0 0 var(--card-radius) var(--card-radius);
            background: linear-gradient(to bottom, #161616, #1a1a1a);
        }

        .flap-colon .flap-half span {
            display: block;
            position: absolute;
            left: 0;
            width: 100%;
            height: var(--digit-height);
            line-height: var(--digit-height);
        }

        .flap-colon .flap-top span { top: 0; }
        .flap-colon .flap-bottom span { bottom: 0; }

        .flap-colon::after {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: rgba(0,0,0,0.7);
            z-index: 10;
            pointer-events: none;
        }

        /* Chaque carte de chiffre */
        .flap-card {
            position: relative;
            width: var(--digit-width);
            height: var(--digit-height);
            perspective: 400px;
            font-size: var(--font-size);
            text-align: center;
            font-weight: 900;
            color: var(--text-color);
        }

        /* Demi-panneau générique */
        .flap-half {
            position: absolute;
            left: 0;
            width: 100%;
            height: 50%;
            overflow: hidden;
        }

        /* Le texte occupe toute la hauteur de la carte, centré verticalement.
           Chaque demi-panneau ne montre que sa moitié grâce à overflow:hidden. */
        .flap-half span {
            display: block;
            position: absolute;
            left: 0;
            width: 100%;
            height: calc(var(--digit-height));
            line-height: var(--digit-height);
        }

        /* Moitié haute : le texte part du haut */
        .flap-top {
            top: 0;
            border-radius: var(--card-radius) var(--card-radius) 0 0;
            background: linear-gradient(to bottom, #282828, #1c1c1c);
        }
        .flap-top span { top: 0; }

        /* Moitié basse : le texte est décalé vers le haut pour montrer le bas du chiffre */
        .flap-bottom {
            bottom: 0;
            border-radius: 0 0 var(--card-radius) var(--card-radius);
            background: linear-gradient(to bottom, #161616, #1a1a1a);
        }
        .flap-bottom span { bottom: 0; }

        /* --- Volets animés --- */

        .flap-flip {
            position: absolute;
            left: 0;
            width: 100%;
            height: 50%;
            overflow: hidden;
            z-index: 3;
            backface-visibility: hidden;
        }

        .flap-flip span {
            display: block;
            position: absolute;
            left: 0;
            width: 100%;
            height: var(--digit-height);
            line-height: var(--digit-height);
        }

        .flap-flip-top {
            top: 0;
            border-radius: var(--card-radius) var(--card-radius) 0 0;
            background: linear-gradient(to bottom, #282828, #1c1c1c);
            transform-origin: bottom center;
        }
        .flap-flip-top span { top: 0; }

        .flap-flip-bottom {
            bottom: 0;
            border-radius: 0 0 var(--card-radius) var(--card-radius);
            background: linear-gradient(to bottom, #161616, #1a1a1a);
            transform-origin: top center;
            transform: rotateX(90deg);
        }
        .flap-flip-bottom span { bottom: 0; }

        /* Animations */
        .flap-card.flipping .flap-flip-top {
            animation: flipDown 250ms ease-in forwards;
        }

        .flap-card.flipping .flap-flip-bottom {
            animation: flipUp 250ms 250ms ease-out forwards;
        }

        @keyframes flipDown {
            0%   { transform: rotateX(0deg); }
            100% { transform: rotateX(-90deg); }
        }

        @keyframes flipUp {
            0%   { transform: rotateX(90deg); }
            100% { transform: rotateX(0deg); }
        }

        /* Ligne de jointure horizontale */
        .flap-card::after {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: rgba(0,0,0,0.7);
            z-index: 10;
            pointer-events: none;
        }

        /* --- Countdown --- */

        #horloge-temps-restant {
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
        }

        .countdown-group {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .countdown-label {
            font-family: 'Share Tech Mono', monospace;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: #fff;
            margin-bottom: 12px;
        }

        #horloge-temps-minutes,
        #horloge-heure-fin-exact {
            display: inline-flex;
            gap: 4px;
        }

        .mini-flap {
            position: relative;
            width: var(--mini-width);
            height: var(--mini-height);
            perspective: 200px;
            font-size: var(--mini-font);
            text-align: center;
            font-weight: 900;
            color: var(--accent-color);
            display: inline-block;
        }

        .mini-flap .flap-half,
        .mini-flap .flap-flip {
            position: absolute;
            left: 0;
            width: 100%;
            height: 50%;
            overflow: hidden;
        }

        .mini-flap .flap-half span,
        .mini-flap .flap-flip span {
            display: block;
            position: absolute;
            left: 0;
            width: 100%;
            height: var(--mini-height);
            line-height: var(--mini-height);
        }

        .mini-flap .flap-top {
            top: 0;
            border-radius: 4px 4px 0 0;
            background: linear-gradient(to bottom, #282828, #1c1c1c);
        }
        .mini-flap .flap-top span { top: 0; }

        .mini-flap .flap-bottom {
            bottom: 0;
            border-radius: 0 0 4px 4px;
            background: linear-gradient(to bottom, #161616, #1a1a1a);
        }
        .mini-flap .flap-bottom span { bottom: 0; }

        .mini-flap .flap-flip { z-index: 3; backface-visibility: hidden; }

        .mini-flap .flap-flip-top {
            top: 0;
            border-radius: 4px 4px 0 0;
            background: linear-gradient(to bottom, #282828, #1c1c1c);
            transform-origin: bottom center;
        }
        .mini-flap .flap-flip-top span { top: 0; }

        .mini-flap .flap-flip-bottom {
            bottom: 0;
            border-radius: 0 0 4px 4px;
            background: linear-gradient(to bottom, #161616, #1a1a1a);
            transform-origin: top center;
            transform: rotateX(90deg);
        }
        .mini-flap .flap-flip-bottom span { bottom: 0; }

        .mini-flap.flipping .flap-flip-top {
            animation: flipDown 250ms ease-in forwards;
        }
        .mini-flap.flipping .flap-flip-bottom {
            animation: flipUp 250ms 250ms ease-out forwards;
        }

        .mini-flap::after {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: rgba(0,0,0,0.7);
            z-index: 10;
            pointer-events: none;
        }

        #horloge-heure-fin-exact {
            display: inline-flex;
            gap: 4px;
        }

        #horloge-heure-fin-exact .mini-flap,
        #horloge-heure-fin-exact .mini-flap-colon {
            color: #ff9f00;
        }

        .mini-flap-colon {
            position: relative;
            width: var(--mini-width);
            height: var(--mini-height);
            font-size: var(--mini-font);
            text-align: center;
            color: var(--accent-color);
            font-weight: 900;
        }

        .mini-flap-colon .flap-half {
            position: absolute;
            left: 0;
            width: 100%;
            height: 50%;
            overflow: hidden;
        }

        .mini-flap-colon .flap-half span {
            display: block;
            position: absolute;
            left: 0;
            width: 100%;
            height: var(--mini-height);
            line-height: var(--mini-height);
        }

        .mini-flap-colon .flap-top {
            top: 0;
            border-radius: 4px 4px 0 0;
            background: linear-gradient(to bottom, #282828, #1c1c1c);
        }
        .mini-flap-colon .flap-top span { top: 0; }

        .mini-flap-colon .flap-bottom {
            bottom: 0;
            border-radius: 0 0 4px 4px;
            background: linear-gradient(to bottom, #161616, #1a1a1a);
        }
        .mini-flap-colon .flap-bottom span { bottom: 0; }

        .mini-flap-colon::after {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: rgba(0,0,0,0.7);
            z-index: 10;
            pointer-events: none;
        }

        #clg-logo {
            margin-top: 65px;
            width: 200px;
            max-width: 200px;
            filter: brightness(0) invert(1) opacity(0.4);
        }

        .btn-ui {
            background: none;
            border: none;
            color: var(--text-color);
            cursor: pointer;
            opacity: 0.5;
            transition: opacity 0.3s;
        }

        .btn-ui:hover { opacity: 1; }

        :fullscreen { background-color: var(--bg-color); }

        @media (prefers-reduced-motion: reduce) {
            .flap-card.flipping .flap-flip-top,
            .flap-card.flipping .flap-flip-bottom {
                animation: none;
            }
        }

        @media (max-width: 768px) {
            #horloge-temps-restant { font-size: 1.2rem; }
        }
    </style>

    <script src="<?= base_url('assets/js/horloge_v3.js?v=' . date('U')); ?>"></script>

    <div id="maintenant-epoch" class="d-none" data-epoch-ms="<?= round(microtime(true) * 1000); ?>"><?= date('U'); ?></div>

    <main id="horloge" class="container-fluid">

        <!-- Controls -->
        <div id="horloge-settings">
            <button id="fullscreen-btn" class="btn-ui" title="Plein écran">
                <i class="bi bi-fullscreen" style="font-size: 1.5rem;"></i>
            </button>

            <button id="parametres" class="btn-ui" title="Paramètres">
                <i class="bi bi-sliders2" style="font-size: 1.8rem;"></i>
            </button>
        </div>

        <!-- Main Display -->
        <div id="horloge-contenu">
            <div id="horloge-clock-wrapper">
            <div id="horloge-heure"></div>

            <div id="horloge-temps-restant">
                <div class="countdown-group">
                    <span class="countdown-label">minute<span class="horloge-temps-minutes-pluriel"></span> restante<span class="horloge-temps-minutes-pluriel"></span></span>
                    <span id="horloge-temps-minutes"></span>
                </div>
                <div class="countdown-group">
                    <span class="countdown-label">heure limite</span>
                    <span id="horloge-heure-fin-exact"></span>
                </div>
            </div>
            </div>

            <img id="clg-logo" src="<?= base_url('assets/img/logoCLG_2019.svg'); ?>" alt="Logo Collège">
        </div>

    </main>

    <!-- Modal Paramètres -->
    <style>
        #horloge-modal .modal-content {
            background: #1e1e1e;
            border: 1px solid #333;
            border-radius: 12px;
            color: #fff;
            font-family: 'Manrope', sans-serif;
            box-shadow: 0 20px 60px rgba(0,0,0,0.6);
        }

        #horloge-modal .modal-header {
            border-bottom: 1px solid #2a2a2a;
            padding: 20px 28px;
        }

        #horloge-modal .modal-title {
            font-family: 'Oswald', sans-serif;
            font-weight: 600;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #aaa;
        }

        #horloge-modal .modal-body {
            padding: 28px;
        }

        #horloge-modal .form-label {
            font-weight: 300;
            font-size: 0.95rem;
            color: #999;
            margin-bottom: 10px;
        }

        #horloge-modal .form-control {
            background: #151515;
            border: 1px solid #333;
            border-radius: 8px;
            color: #fff;
            font-family: 'Oswald', sans-serif;
            font-size: 1.6rem;
            letter-spacing: 2px;
            padding: 12px 16px;
            text-align: center;
        }

        #horloge-modal .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 2px rgba(220, 20, 60, 0.2);
            outline: none;
        }

        #horloge-modal .modal-footer {
            border-top: 1px solid #2a2a2a;
            padding: 16px 28px;
            display: flex;
            justify-content: space-between;
        }

        #horloge-modal .btn-annuler,
        #horloge-modal .btn-appliquer {
            font-family: 'Manrope', sans-serif;
            font-weight: 400;
            font-size: 0.9rem;
            border-radius: 6px;
            padding: 7px 18px;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            background: none;
        }

        #horloge-modal .btn-annuler {
            color: #666;
            border: 1px solid #333;
        }

        #horloge-modal .btn-annuler:hover {
            color: #aaa;
            border-color: #555;
        }

        #horloge-modal .btn-appliquer {
            color: #fff;
            background: var(--accent-color);
        }

        #horloge-modal .btn-appliquer:hover {
            background: #b71c3c;
        }
    </style>

    <div id="horloge-modal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Paramètres</h5>
                </div>
                <div class="modal-body">
                    <div>
                        <label for="parametres-heure" class="form-label">Heure de fin de l'activité</label>
                        <input id="parametres-heure" type="time" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-annuler" data-bs-dismiss="modal">Annuler</button>
                    <button id="parametres-sauvegarder" type="button" class="btn-appliquer">Appliquer</button>
                </div>
            </div>
        </div>
    </div>

</body>


<?
/* --------------------------------------------------------------------
 *
 * Horloge — version 4 (tableau vert / craie)
 *
 * -------------------------------------------------------------------- */ ?>

<body>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <style>
        :root {
            --bg-color: #2a3a2a;
            --text-color: rgba(255,255,255,0.85);
            --accent-color: #f0d060;
            --digit-height: 14vw;
            --mini-height: 5.5vw;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: 'Caveat', cursive;
            margin: 0;
            overflow: hidden;
        }

        #horloge {
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            background:
                radial-gradient(ellipse at 30% 40%, rgba(255,255,255,0.03) 0%, transparent 60%),
                radial-gradient(ellipse at 70% 60%, rgba(0,0,0,0.15) 0%, transparent 60%),
                var(--bg-color);
            box-shadow:
                inset 0 0 80px rgba(0,0,0,0.4),
                inset 0 0 200px rgba(0,0,0,0.15);
        }

        #horloge::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 8px;
            background: linear-gradient(to bottom, #5a4a3a, #4a3a2a);
            box-shadow: 0 -1px 3px rgba(0,0,0,0.3);
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

        /* --- Chiffres craie (SVG) --- */

        #horloge-heure {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.8vw;
            margin-bottom: 50px;
        }

        .chalk-digit {
            height: var(--digit-height);
            width: calc(var(--digit-height) * 40 / 70);
            color: #fff;
            filter: url(#chalk-texture);
            overflow: visible;
        }

        .chalk-colon {
            height: var(--digit-height);
            width: calc(var(--digit-height) * 20 / 70);
            color: rgba(255,255,255,0.7);
            filter: url(#chalk-texture);
            overflow: visible;
        }


        /* --- Countdown --- */

        #horloge-temps-restant {
            display: table;
            width: 100%;
        }

        .countdown-group {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        .countdown-label {
            font-family: 'Caveat', cursive;
            font-size: 2.4rem;
            font-weight: 500;
            color: rgba(255,255,255,0.45);
            text-shadow:
                0 0 1px rgba(255,255,255,0.3),
                0 0 4px rgba(255,255,255,0.1);
            margin-bottom: 8px;
        }

        .countdown-value {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.3vw;
            margin-top: 8px;
        }

        .chalk-mini {
            height: var(--mini-height);
            width: calc(var(--mini-height) * 40 / 70);
            color: var(--accent-color);
            filter: url(#chalk-texture-mini);
            overflow: visible;
        }

        .chalk-colon-mini {
            height: var(--mini-height);
            width: calc(var(--mini-height) * 20 / 70);
            color: var(--accent-color);
            filter: url(#chalk-texture-mini);
            overflow: visible;
        }

        #horloge-temps-minutes .chalk-mini {
            color: #e85050;
        }

        /* --- Logo --- */

        #clg-logo {
            margin-top: 65px;
            width: 200px;
            max-width: 200px;
            filter: brightness(0) invert(1) opacity(0.3);
        }

        /* --- Boutons --- */

        .btn-ui {
            background: none;
            border: none;
            color: var(--text-color);
            cursor: pointer;
            opacity: 0.4;
            transition: opacity 0.3s;
        }

        .btn-ui:hover { opacity: 0.8; }

        :fullscreen { background-color: var(--bg-color); }

        @media (prefers-reduced-motion: reduce) {
            .chalk-digit.erasing, .chalk-mini.erasing { animation: none; }
        }
    </style>

    <script src="<?= base_url('assets/js/horloge_v4.js?v=' . date('U')); ?>"></script>

    <!-- Filtres SVG pour texture craie -->
    <svg width="0" height="0" style="position:absolute">
        <filter id="chalk-texture" x="-5%" y="-5%" width="110%" height="110%">
            <feTurbulence type="fractalNoise" baseFrequency="1.2" numOctaves="3" seed="1" result="fine-noise" />
            <feColorMatrix in="fine-noise" type="luminanceToAlpha" result="noise-alpha" />
            <feComponentTransfer in="noise-alpha" result="noise-mask">
                <feFuncA type="linear" slope="2.5" intercept="-0.6" />
            </feComponentTransfer>
            <feTurbulence type="fractalNoise" baseFrequency="0.04" numOctaves="4" seed="2" result="warp" />
            <feDisplacementMap in="SourceGraphic" in2="warp" scale="3" xChannelSelector="R" yChannelSelector="G" result="warped" />
            <feComposite in="warped" in2="noise-mask" operator="in" />
        </filter>
        <filter id="chalk-texture-mini" x="-5%" y="-5%" width="110%" height="110%">
            <feTurbulence type="fractalNoise" baseFrequency="1.5" numOctaves="3" seed="3" result="fine-noise" />
            <feColorMatrix in="fine-noise" type="luminanceToAlpha" result="noise-alpha" />
            <feComponentTransfer in="noise-alpha" result="noise-mask">
                <feFuncA type="linear" slope="2.5" intercept="-0.5" />
            </feComponentTransfer>
            <feTurbulence type="fractalNoise" baseFrequency="0.05" numOctaves="4" seed="4" result="warp" />
            <feDisplacementMap in="SourceGraphic" in2="warp" scale="2" xChannelSelector="R" yChannelSelector="G" result="warped" />
            <feComposite in="warped" in2="noise-mask" operator="in" />
        </filter>
    </svg>

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
                    <div class="countdown-label">minute<span class="horloge-temps-minutes-pluriel"></span> restante<span class="horloge-temps-minutes-pluriel"></span></div>
                    <div class="countdown-value" id="horloge-temps-minutes"><!-- SVG digits built by JS --></div>
                </div>
                <div class="countdown-group">
                    <div class="countdown-label">heure limite</div>
                    <div class="countdown-value" id="horloge-heure-fin-exact"><!-- SVG digits built by JS --></div>
                </div>
            </div>
            </div>

            <img id="clg-logo" src="<?= base_url('assets/img/logoCLG_2019.svg'); ?>" alt="Logo Collège">
        </div>

    </main>

    <!-- Modal Paramètres -->
    <style>
        #horloge-modal .modal-content {
            background: #1a2a1a;
            border: 1px solid #3a4a3a;
            border-radius: 12px;
            color: rgba(255,255,255,0.85);
            font-family: 'Caveat', cursive;
            box-shadow: 0 20px 60px rgba(0,0,0,0.6);
        }

        #horloge-modal .modal-header {
            border-bottom: 1px solid #2a3a2a;
            padding: 20px 28px;
        }

        #horloge-modal .modal-title {
            font-family: 'Caveat', cursive;
            font-weight: 600;
            font-size: 1.4rem;
            color: rgba(255,255,255,0.6);
        }

        #horloge-modal .modal-body {
            padding: 28px;
        }

        #horloge-modal .form-label {
            font-weight: 500;
            font-size: 1.2rem;
            color: rgba(255,255,255,0.5);
            margin-bottom: 10px;
        }

        #horloge-modal .form-control {
            background: #152015;
            border: 1px solid #3a4a3a;
            border-radius: 8px;
            color: #fff;
            font-family: 'Caveat', cursive;
            font-size: 1.6rem;
            letter-spacing: 2px;
            padding: 12px 16px;
            text-align: center;
        }

        #horloge-modal .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 2px rgba(240,208,96,0.2);
            outline: none;
        }

        #horloge-modal .modal-footer {
            border-top: 1px solid #2a3a2a;
            padding: 16px 28px;
            display: flex;
            justify-content: space-between;
        }

        #horloge-modal .btn-annuler,
        #horloge-modal .btn-appliquer {
            font-family: 'Caveat', cursive;
            font-weight: 500;
            font-size: 1.1rem;
            border-radius: 6px;
            padding: 7px 18px;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            background: none;
        }

        #horloge-modal .btn-annuler {
            color: rgba(255,255,255,0.4);
            border: 1px solid #3a4a3a;
        }

        #horloge-modal .btn-annuler:hover {
            color: rgba(255,255,255,0.7);
            border-color: #5a6a5a;
        }

        #horloge-modal .btn-appliquer {
            color: #1a2a1a;
            background: var(--accent-color);
        }

        #horloge-modal .btn-appliquer:hover {
            background: #d4b840;
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


<?
/* --------------------------------------------------------------------
 *
 * Horloge — version 5 (compte à rebours examen)
 *
 * -------------------------------------------------------------------- */ ?>

<body>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300;9..144,340;9..144,440;9..144,560&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <style>
        :root {
            --bg: #14171b;
            --bg-glow: #6f9e82;
            --text-color: #f3efe6;
            --accent: #6f9e82;
            --accent-soft: rgba(111,158,130,0.16);
        }

        body.alerte-jaune {
            --bg-glow: #d1a35a;
            --accent: #d1a35a;
            --accent-soft: rgba(209,163,90,0.16);
        }

        body.alerte-rouge {
            --bg-glow: #c96b6b;
            --accent: #c96b6b;
            --accent-soft: rgba(201,107,107,0.18);
        }

        body.alerte-terminee {
            --bg-glow: #c94a4a;
            --accent: #c94a4a;
            --accent-soft: rgba(201,74,74,0.22);
            animation: respirer 1.4s ease-in-out infinite;
        }

        @keyframes respirer {
            0%, 100% { --accent-soft: rgba(201,74,74,0.16); }
            50% { --accent-soft: rgba(201,74,74,0.34); }
        }

        body {
            background-color: var(--bg);
            background-image: radial-gradient(ellipse 60% 50% at 50% 42%, var(--accent-soft), transparent 70%);
            color: var(--text-color);
            font-family: 'Jost', sans-serif;
            margin: 0;
            overflow: hidden;
            transition: background-image 1.2s ease;
        }

        #horloge {
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        #horloge-settings {
            position: absolute;
            top: 26px;
            right: 28px;
            z-index: 100;
        }

        .btn-ui {
            background: none;
            border: none;
            color: var(--text-color);
            cursor: pointer;
            opacity: 0.32;
            transition: opacity 0.3s;
        }

        .btn-ui:hover { opacity: 0.7; }

        #horloge-contenu {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        #horloge-temps-minutes {
            font-family: 'Fraunces', serif;
            font-optical-sizing: auto;
            font-weight: 340;
            font-size: 15vw;
            line-height: 1;
            letter-spacing: -0.01em;
            color: var(--text-color);
            text-shadow: 0 0 60px var(--accent-soft);
            transition: text-shadow 1.2s ease;
            display: flex;
        }

        .horloge-digit, .horloge-colon {
            display: inline-block;
            text-align: center;
        }

        #horloge-temps-minutes .horloge-digit { width: 0.62em; }
        #horloge-temps-minutes .horloge-colon { width: 0.32em; }

        #horloge-temps-label {
            font-size: 1.3vw;
            font-weight: 400;
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: 0.32em;
            margin-top: 1.4vw;
            margin-bottom: 2vw;
            transition: color 1.2s ease;
        }

        #horloge-bas {
            display: flex;
            align-items: center;
            gap: 10vw;
            margin-top: 2.8vw;
            color: var(--text-color);
        }

        .horloge-bas-bloc {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .horloge-bas-bloc .titre {
            font-size: 0.8vw;
            text-transform: uppercase;
            letter-spacing: 0.18em;
            opacity: 0.45;
            margin-bottom: 0.5vw;
        }

        #horloge-bas .valeur {
            font-family: 'Fraunces', serif;
            font-weight: 440;
            font-size: 3vw;
            display: inline-flex;
            opacity: 0.9;
        }

        #horloge-bas .horloge-digit { width: 0.6em; }
        #horloge-bas .horloge-colon { width: 0.3em; }

        #clg-logo {
            width: clamp(60px, 9vw, 150px);
            margin-top: 4vw;
            filter: brightness(0) invert(1);
            opacity: 0.18;
        }

        :fullscreen { background-color: var(--bg); }
    </style>

    <script src="<?= base_url('assets/js/horloge_v5.js?v=' . date('U')); ?>"></script>

    <div id="maintenant-epoch" class="d-none" data-epoch-ms="<?= round(microtime(true) * 1000); ?>"><?= date('U'); ?></div>

    <main id="horloge">

        <div id="horloge-settings">
            <button id="fullscreen-btn" class="btn-ui" title="Plein écran">
                <i class="bi bi-fullscreen" style="font-size: 1.4rem;"></i>
            </button>

            <button id="parametres" class="btn-ui" title="Paramètres">
                <i class="bi bi-sliders2" style="font-size: 1.7rem;"></i>
            </button>
        </div>

        <div id="horloge-contenu">
            <div id="horloge-temps-minutes">--:--</div>
            <div id="horloge-temps-label">temps restant</div>

            <div id="horloge-bas">
                <div class="horloge-bas-bloc">
                    <div class="titre">heure actuelle</div>
                    <span class="valeur" id="horloge-heure">--:--</span>
                </div>
                <div class="horloge-bas-bloc">
                    <div class="titre">heure limite</div>
                    <span class="valeur" id="horloge-heure-fin-exact">--:--</span>
                </div>
            </div>

            <img id="clg-logo" src="<?= base_url('assets/img/logoCLG_2019.svg'); ?>" alt="Logo Collège">
        </div>

    </main>

    <!-- Modal Paramètres -->
    <div id="horloge-modal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Paramètres</h5>
                </div>
                <div class="modal-body">
                    <div>
                        <label for="parametres-heure" class="form-label">Heure de fin de l'examen</label>
                        <input id="parametres-heure" type="time" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button id="parametres-sauvegarder" type="button" class="btn btn-primary">Appliquer</button>
                </div>
            </div>
        </div>
    </div>

</body>

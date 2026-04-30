
<?
/* --------------------------------------------------------------------
 *
 * Horloge2 - version 2.1
 *
 * -------------------------------------------------------------------- */ ?>

<body>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Gugi&family=Manrope:wght@200;400&family=Montserrat:wght@300;600&family=Rubik:wght@300..900&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <style>
        :root {
            --bg-color: #000;
            --text-color: #fff;
            --accent-color: crimson;
            --border-color: #777;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            overflow: hidden; /* Évite les scrolls inutiles en fullscreen */
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
            text-align: center;
        }

        #horloge-heure {
            font-size: 12rem; /* Plus réactif que 4em pour un grand écran */
            font-weight: 600;
            font-family: "Gugi", sans-serif;
            margin-bottom: 20px;
        }

        #horloge-temps-restant {
            display: inline-block;
            padding: 20px 40px;
            border-radius: 60px;
            font-size: 2.5rem;
            font-family: "Manrope", sans-serif;
            font-weight: 200;
        }

        #horloge-temps-minutes {
            font-weight: bold;
            color: var(--accent-color);
		}

		#horloge-heure-fin-exact {
			font-weight: bold;
            color: var(--accent-color);
		}

        #clg-logo { 
			margin-top: 50px;
			width: 250px;
            max-width: 250px;
			filter: brightness(0) invert(1);
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

        /* --- Morphing Logic --- */
        #horloge-heure.morph {
            display: inline-flex;
            align-items: center;
            gap: 0.15rem;
            font-variant-numeric: tabular-nums;
            line-height: 1;
        }

        .digit {
            position: relative;
            width: 0.65em;
            height: 1em;
            overflow: hidden;
        }

        .stack {
            position: absolute;
            left: 0;
            top: 0;
            transition: transform 320ms cubic-bezier(.2,.7,.2,1);
            will-change: transform;
        }

        .stack span {
            display: block;
            height: 1em;
            line-height: 1em;
        }

        .colon {
            width: 0.3em;
            text-align: center;
            opacity: 0.8;
            transform: translateY(-0.05em);
        }

        @media (prefers-reduced-motion: reduce) {
            .stack { transition: none; }
        }

        /* Fullscreen specific */
        :fullscreen { background-color: #000; }
    </style>

    <script src="<?= base_url('assets/js/horloge.js?v=' . date('U')); ?>"></script>

    <div id="maintenant-epoch" class="d-none"><?= date('U'); ?></div>

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
            <time id="horloge-heure"><?= date('H:i:s'); ?></time>

            <div>
                <span id="horloge-temps-restant">
                    Il reste 
                    <span id="horloge-temps-minutes">0</span> 
                    minute<span id="horloge-temps-minutes-pluriel"></span> 
                    avant 
                    <span id="horloge-heure-fin-exact"></span>
                </span>
            </div>

            <img id="clg-logo" src="<?= base_url('assets/img/logoCLG_2019.svg'); ?>" alt="Logo Collège">
        </div>

    </main>

    <!-- Modal Paramètres -->
    <div id="horloge-modal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Paramètres de l'horloge</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <label for="parametres-heure" class="form-label">Heure de fin de l'activité</label>
                        <input id="parametres-heure" type="time" class="form-control form-control-lg">
                    </div>
                    <div class="mb-2">
                        <label for="select-police" class="form-label">Style de police</label>
                        <select id="select-police" class="form-select">
                            <option value="Gugi" selected>Gugi (Futuriste)</option>
                            <option value="Rubik">Rubik (Moderne)</option>
                            <option value="Manrope">Manrope (Minimaliste)</option>
                            <option value="Montserrat">Montserrat (Classique)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
					<button type="button" class="btn btn-link text-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button id="parametres-sauvegarder" type="button" class="btn btn-primary px-4">Appliquer</button>
                </div>
            </div>
        </div>
    </div>

</body>

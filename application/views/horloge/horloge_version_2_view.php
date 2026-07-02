
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
            top: 24px;
            right: 24px;
            z-index: 100;
            display: flex;
            gap: 8px;
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
            display: flex;
            justify-content: center;
            gap: 140px;
            margin-top: 32px;
            font-family: "Manrope", sans-serif;
        }

        .countdown-group {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .countdown-label {
            font-size: 1.4rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            opacity: 0.6;
            margin-bottom: -10px;
        }

        #horloge-temps-minutes {
			font-weight: 600;
			font-size: 4.8rem;
            color: var(--accent-color);
		}

		#horloge-heure-fin-exact {
			font-weight: 600;
			font-size: 4.8rem;
            color: var(--accent-color);
		}

        #clg-logo {
			margin-top: 50px;
			width: 150px;
            max-width: 150px;
			filter: brightness(0) invert(1);
			opacity: 0.18;
        }

        .btn-ui {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            background: none;
            border: none;
            border-radius: 50%;
            color: var(--text-color);
            cursor: pointer;
            opacity: 0.55;
            transition: opacity 0.2s ease, background 0.2s ease;
        }

        .btn-ui:hover {
            opacity: 1;
            background: rgba(255, 255, 255, 0.08);
        }

        .btn-ui.is-active {
            opacity: 1;
            color: var(--accent-color);
        }

		/* --- Morphing Logic --- */

        #horloge-heure.morph {
            display: inline-flex;
            align-items: center;
            gap: 0.1rem; /* Léger espacement entre les groupes */
            font-variant-numeric: tabular-nums;
        }

        .digit {
            position: relative;
            /* 1ch s'ajuste dynamiquement à la vraie largeur du chiffre selon la police */
            width: 1ch; 
            /* 1.2em au lieu de 1em donne une marge respiratoire pour éviter de tronquer */
            height: 1.2em; 
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
            height: 1.2em; /* Doit être identique au conteneur .digit */
            line-height: 1.2em; /* Assure le centrage vertical parfait du chiffre */
            text-align: center;
        }

        .colon {
            /* On laisse la police dicter la taille naturelle des deux-points */
            display: inline-block;
            padding: 0 0.05em;
            text-align: center;
            opacity: 0.8;
            line-height: 1.2em;
            /* Le transform: translateY a été retiré pour ne pas forcer de décalage */
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

            <div id="horloge-temps-restant">
                <div class="countdown-group">
                    <span class="countdown-label">minute<span class="horloge-temps-minutes-pluriel"></span> restante<span class="horloge-temps-minutes-pluriel"></span></span>
                    <span id="horloge-temps-minutes">0</span>
                </div>
                <div class="countdown-group">
                    <span class="countdown-label">heure limite</span>
                    <span id="horloge-heure-fin-exact"></span>
                </div>
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
						<select id="select-police" class="form-select"></select>
					</div>
					<div class="mt-4 mb-2">
						<label for="range-taille" class="form-label">
							Taille de l'horloge — <span id="label-taille">12</span>rem
						</label>
						<input id="range-taille" type="range" class="form-range"
							   min="10" max="20" step="1" value="12">
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

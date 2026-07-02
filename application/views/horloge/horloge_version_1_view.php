<!doctype html>

<?
/* --------------------------------------------------------------------
 *
 * Horloge2 - version 1 (original)
 *
 * -------------------------------------------------------------------- */ ?>

<style>

#horloge {
	font-family: Montserrat;
}

.horloge-icon-btn {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 44px;
	height: 44px;
	margin-top: 20px;
	border-radius: 50%;
	color: #8a9099;
	background: transparent;
	transition: background 0.15s ease, color 0.15s ease;
	cursor: pointer;
}

.horloge-icon-btn:hover {
	color: #d22630;
	background: rgba(210, 38, 48, 0.08);
}

.horloge-icon-btn.is-active {
	color: #d22630;
}

#horloge-contenu {
    font-size: 4em;
    text-align: center;
	font-weight: 300;
	margin-top: 80px;
}

#horloge-heure-titre {
	color: #fff;
	background: #d22630;
	text-align: left;
	padding: 8px 16px 6px 16px;
	font-size: 0.28em;
	font-weight: 500;
	letter-spacing: 0.12em;
	text-transform: uppercase;
	border-radius: 6px 6px 0 0;
}

#horloge-heure {
    color: #222;
    font-size: 1.6em;
    font-family: "Roboto Mono";
    font-variant-numeric: tabular-nums;
    letter-spacing: 0.02em;
    border: 2px solid #d22630;
    border-top: none;
    border-radius: 0 0 6px 6px;
    padding: 8px 50px 18px 50px;
    background: #fff;
    box-shadow: 0 18px 40px -18px rgba(20, 22, 25, 0.35);
}

#horloge-temps-restant {
    display: inline-block;
    margin-top: 70px;
    margin-bottom: -20px;
    padding: 22px 48px;
    border-radius: 999px;
    background: #fff;
    box-shadow: 0 8px 20px -12px rgba(20, 22, 25, 0.25);
    color: #4a4f57;
    font-weight: 300;
    font-size: 0.4em;
}

#horloge-temps-minutes {
    font-size: 1.5em;
    font-family: "Roboto Mono";
	font-weight: 500;
    color: #d22620;
}

#horloge-heure-fin {
    font-size: 0.6em;
}

#horloge-heure-fin-exact {
    font-size: 1.5em;
    font-family: "Roboto Mono";
	font-weight: 500;
    color: #d22620;
}

</style>

<script src="<?= base_url() . '/assets/js/horloge_sans_morph.js?v=' . date('U'); ?>"></script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400&subset=latin,latin-ext" type="text/css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400&family=Share+Tech+Mono&display=swap">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;500;600&family=Zilla+Slab:wght@300;400;600&display=swap">

<body style="
	background-color: #e9ebed;
	background-image:
		linear-gradient(rgba(20, 22, 25, 0.035) 1px, transparent 1px),
		linear-gradient(90deg, rgba(20, 22, 25, 0.035) 1px, transparent 1px);
	background-size: 32px 32px;
">

<div id="maintenant-epoch" class="d-none"><?= date('U'); ?></div>

<div id="horloge">

    <div class="row">

		<div class="col"></div>

        <div class="col" style="text-align: center; margin-top: 40px">
    
            <a href="<?= base_url(); ?>">
                <img style="width: 250px" src="<?= base_url() ?>assets/img/logoCLG.svg" />
			</a>

        </div> <!-- .col -->

        <div class="col" style="text-align: right">

            <div id="horloge-settings">

                <button id="fullscreen-btn" class="btn" style="padding: 0; border: none; background: none" title="Plein écran">
                    <span id="horloge-fullscreen-btn" class="horloge-icon-btn">
                        <i class="bi bi-fullscreen" style="font-size: 22px"></i>
                    </span>
                </button>

                <div id="parametres" class="btn" style="padding: 0">
                    <span id="horloge-settings-btn" class="horloge-icon-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-sliders" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M11.5 2a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM9.05 3a2.5 2.5 0 0 1 4.9 0H16v1h-2.05a2.5 2.5 0 0 1-4.9 0H0V3h9.05zM4.5 7a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM2.05 8a2.5 2.5 0 0 1 4.9 0H16v1H6.95a2.5 2.5 0 0 1-4.9 0H0V8h2.05zm9.45 4a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zm-2.45 1a2.5 2.5 0 0 1 4.9 0H16v1h-2.05a2.5 2.5 0 0 1-4.9 0H0v-1h9.05z"/>
                        </svg>
                    </span>
                </div>

			</div>

		</div> <!-- .col -->

	</div> <!-- .row -->


    <div id="horloge-contenu">

		<div class="row">

			<div class="col"></div>

			<div class="col">

				<div id="horloge-heure-titre">
					Heure
				</div>

				<div id="horloge-heure">
					<?= date('H:i:s'); ?>
				</div>

			</div> <!-- .col -->

			<div class="col"></div>

		</div> <!-- .row -->

		<div class="row">

			<div class="col">

				<div id="horloge-temps-restant">
					Il reste <span id="horloge-temps-minutes">0</span> minute<span id="horloge-temps-minutes-pluriel"></span>
					avant <span id="horloge-heure-fin-exact"></span>.
				</div>

			</div> <!-- .col -->

		</div> <!-- .row -->

    </div>

</div> <!-- #horloge -->

<!-- Modal -->

<div id="horloge-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Paramètres</h5>
            </div>
        
            <div class="modal-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <label for="inputPassword6" class="col-form-label">Heure de fin</label>
                    </div>
                    <div class="col-auto">
                        <input id="parametres-heure" type="time" class="form-control" style="width: 200px" max="23:59" required>
                    </div>
                </div>
            </div>
      
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close" data-dismiss="modal">Fermer</button>
                <button id="parametres-sauvegarder" type="button" class="btn btn-primary">Sauvegarder</button>
            </div>

        </div>
    </div>
</div>

</body>

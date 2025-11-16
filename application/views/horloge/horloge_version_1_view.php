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

#horloge-settings {
}

#horloge-contenu {
    font-size: 4em;
    text-align: center;
	font-weight: 300;
	margin-top: 50px;
    /*
    font-size: 4em;
    display: flex;
    position: fixed;
    top: 45%;
    left: 50%;
    transform: translate(-50%, -50%);
    */
}

#horloge-heure-titre {
	color: #fff;
	background: #d22630;
	text-align: left;
	padding: 6px 12px 4px 12px;
	font-size: 0.3em;
	font-weight: 300;
}

#horloge-heure {
    color: #222;
    font-size: 1.6em;
    font-family: "Roboto Mono";
    border: 2px solid #d22630;
    padding: 0 50px 10px 50px;
    background: #fff;
}

#horloge-temps-restant {
    margin-top: 30px;
    margin-bottom: -20px;
    font-weight: 300;
    font-size: 0.4em;
}

#horloge-temps-minutes {
    font-size: 1.5em;
    font-family: "Roboto Mono";
	font-weight: 400;
    color: #d22620;
}

#horloge-heure-fin {
    font-size: 0.6em;
}

#horloge-heure-fin-exact {
    font-size: 1.5em;
    font-family: "Roboto Mono";
	font-weight: 400;
}

</style>

<script src="<?= base_url() . '/assets/js/horloge_sans_morph.js?v=' . date('U'); ?>"></script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400&subset=latin,latin-ext" type="text/css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400&family=Share+Tech+Mono&display=swap">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;500;600&family=Zilla+Slab:wght@300;400;600&display=swap">

<body style="background: #e9ebed">

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

                <div id="parametres" class="btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-sliders" viewBox="0 0 16 16" style="margin-left: 10px">
                        <path fill-rule="evenodd" d="M11.5 2a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM9.05 3a2.5 2.5 0 0 1 4.9 0H16v1h-2.05a2.5 2.5 0 0 1-4.9 0H0V3h9.05zM4.5 7a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM2.05 8a2.5 2.5 0 0 1 4.9 0H16v1H6.95a2.5 2.5 0 0 1-4.9 0H0V8h2.05zm9.45 4a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zm-2.45 1a2.5 2.5 0 0 1 4.9 0H16v1h-2.05a2.5 2.5 0 0 1-4.9 0H0v-1h9.05z"/>
                    </svg>
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

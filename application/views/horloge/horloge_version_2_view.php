<!doctype html>

<?
/* --------------------------------------------------------------------
 *
 * Horloge2 - version 2
 *
 * -------------------------------------------------------------------- */ ?>

<style>

body {
	background: #000;
}

#horloge {
	font-family: Montserrat;
}

#horloge-settings {
}

#horloge-contenu {
    font-size: 4em;
    text-align: center;
	font-weight: 300;
}

#horloge-heure {
	margin-top: 60px;
	margin-bottom: 45px;
    color: #fff;
	font-size: 3em;
	font-weight: 600;
	font-family: "Gugi";
}

#horloge-temps-restant {
	border: 1px solid #777;
	border: 0;
	padding: 30px;
	border-radius: 60px;
	font-size: 0.75em;
	font-family: "Manrope";
	font-weight: 200;
	color: #fff;
}

#horloge-temps-minutes {
	font-weight: 400;
	color: #d22620;
	color: crimson;
}

#horloge-heure-fin {
}

#horloge-heure-fin-exact {
	font-weight: 400;
}

#clg-logo { 
	margin-top: 70px;
	filter: invert(1) brightness(100);
}

/* --- Clock morphing --- */
#horloge-heure.morph {
 	display: inline-flex;
  	align-items: center; /* centré verticalement au lieu de baseline */
  	gap: 0.25rem;
  	font-variant-numeric: tabular-nums;
  	font-feature-settings: "tnum" 1;
  	line-height: 1;
}

#horloge-heure .digit {
  	position: relative;
  	width: 0.65em;
  	height: 1em;
  	overflow: hidden;
}

#horloge-heure .digit .stack {
  	position: absolute;
  	left: 0;
  	top: 0;
  	transition: transform 320ms cubic-bezier(.2,.7,.2,1);
  	will-change: transform;
}

#horloge-heure .digit .stack span {
  	display: block;
  	height: 1em;
  	line-height: 1em;
}

/* Correction du positionnement et suppression du clignotement */
#horloge-heure .colon {
  	width: 0.35em;
  	text-align: center;
  	opacity: 0.9;
  	transform: translateY(-0.05em); /* remonte légèrement les deux-points */
}

/* Pas d’effet de clignotement */
#horloge-heure .colon.off {
  	opacity: 0.9; /* même opacité */
}

@media (prefers-reduced-motion: reduce) {
  	#horloge-heure .digit .stack { transition: none; }
}

</style>

<script src="<?= base_url() . '/assets/js/horloge.js?v=' . date('U'); ?>"></script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Barrio&family=Francois+One&family=Gugi&family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Manrope:wght@200..800&family=Petit+Formal+Script&family=Roboto:ital,wght@0,100..900;1,100..900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">

<body>

<div id="maintenant-epoch" class="d-none"><?= date('U'); ?></div>

	<div id="horloge">

		<div class="row">

			<div class="col" style="text-align: right">

				<div id="horloge-settings">

					<div id="parametres" class="btn">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#fff" class="bi bi-sliders" viewBox="0 0 16 16" style="margin-left: 10px">
							<path fill-rule="evenodd" d="M11.5 2a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM9.05 3a2.5 2.5 0 0 1 4.9 0H16v1h-2.05a2.5 2.5 0 0 1-4.9 0H0V3h9.05zM4.5 7a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM2.05 8a2.5 2.5 0 0 1 4.9 0H16v1H6.95a2.5 2.5 0 0 1-4.9 0H0V8h2.05zm9.45 4a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zm-2.45 1a2.5 2.5 0 0 1 4.9 0H16v1h-2.05a2.5 2.5 0 0 1-4.9 0H0v-1h9.05z"/>
						</svg>
					</div>

				</div>

			</div> <!-- .col -->

		</div> <!-- .row -->


		<div id="horloge-contenu">

			<div class="row">
				<div class="col">
					<div id="horloge-heure"><?= date('H:i:s'); ?></div>
				</div> <!-- .col -->

			</div> <!-- .row -->

			<div class="row">

				<div class="col">

					<span id="horloge-temps-restant">
						Il reste 
						<span id="horloge-temps-minutes">0</span> 
						minute<span id="horloge-temps-minutes-pluriel"></span>
						avant 
						<span id="horloge-heure-fin-exact"></span>
					</span>

				</div> <!-- .col -->

			</div> <!-- .row -->

			<div class="row">
				<div class="col">
					<img id="clg-logo" src="<?= base_url() . 'assets/img/logoCLG_2019.svg'; ?>" />
				</div>
			</div>

		</div>

	</div> <!-- #horloge -->

<!-- Modal -->

<div id="horloge-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Paramètres</h5>
            </div>
        
            <div class="modal-body">

                <div class="row align-items-center">
                    <div class="col-auto">
                        <label for="parametres-heure" class="col-form-label">Heure de fin</label>
                    </div>
                    <div class="col-auto">
                        <input id="parametres-heure" type="time" class="form-control" style="width: 200px" max="23:59" required>
                    </div>
				</div>

                <div class="mt-3 mb-2">
                    <label for="select-police" class="form-label">Choisissez une police :</label>
                    <select id="select-police" class="form-select">
                        <option id="Barrio" value="Barrio">Barrio</option>
                        <option id="Francois-One" value="Francois One">Francois One</option>
                        <option id="Gugi" value="Gugi" selected>Gugi</option>
                        <option id="K2D" value="K2D" data-width="0.95em">K2D</option>
                        <option id="Manrope" value="Manrope">Manrope</option>
                        <option id="Rubik"value="Rubik">Rubik</option>
                        <option id="Roboto" value="Roboto">Roboto</option>
                    </select>
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

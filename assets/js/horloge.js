/* ====================================================================
 *
 * chimieclg.ca > horloge.js  
 *
 * --------------------------------------------------------------------
 *
 * v2	avec effet "morph" sur HH:MM:SS)
 * v2.1	ameliorer la robustesse et la rapidite
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

	// Morph

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
			// On utilise translate3d pour forcer l'acceleration matérielle (GPU)
			$(this).find('.stack').css('transform', `translate3d(0, ${-(val * h)}px, 0)`);
		});
	}

	// Calcul de la duree

	function calculerDureeRestante() 
	{
		// Formatage ISO robuste pour la date du jour
		const ajdStr = new Date().toISOString().split('T')[0];
		const limiteEpoch = Date.parse(`${ajdStr}T${heureLimite}:00`) / 1000;
		let maintenantEpoch = Number($('#maintenant-epoch').text());

		let diff = limiteEpoch - maintenantEpoch;

		// Si l'heure est passée, on ne descend pas sous 0
		let minutes = Math.max(0, Math.ceil(diff / 60));

		$('#horloge-temps-minutes').text(minutes);
		$('#horloge-temps-minutes-pluriel').text(minutes > 1 ? 's' : '');
	}

	function rafraichirTemps() 
	{
		const maintenantEpoch = Number($('#maintenant-epoch').text());
		const d = new Date(maintenantEpoch * 1000);

		// Formatage rapide HH:MM:SS
		const timeStr = d.toTimeString().split(' ')[0];

		setClock(timeStr);
		$('#maintenant-epoch').text(maintenantEpoch + 1);

		calculerDureeRestante();
		setTimeout(rafraichirTemps, CONFIG.secondeInterval);
	}

	function pingServeur() 
	{
		$.post(`${base_url}horloge/ping`, { ci_csrf_token: cct }, 
		function (data)
		{
			if (data?.epoch) 
			{
				// On ajoute +1 pour compenser la latence réseau moyenne
				$('#maintenant-epoch').text(Number(data.epoch) + 1);
			}
		}, 'json').always(() => {
			setTimeout(pingServeur, CONFIG.pingInterval);
		});
	}

	// Evenements

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

	// Plein ecran

	$('#fullscreen-btn').on('click', function() {
		if (!document.fullscreenElement) {
			// On demande le plein écran sur le document entier
			document.documentElement.requestFullscreen().catch(err => {
				console.error(`Erreur lors du passage en plein écran: ${err.message}`);
			});
		} else {
			// On quitte le plein écran
			document.exitFullscreen();
		}
	});

	// Optionnel : Changer l'icône ou le style si on change d'état via la touche Echap
	document.addEventListener('fullscreenchange', () => {
		if (document.fullscreenElement) {
			$('#fullscreen-btn').addClass('is-active').css('opacity', '0.2');
		} else {
			$('#fullscreen-btn').removeClass('is-active').css('opacity', '1');
		}
	});

	// Lancement

	buildClock($('#horloge-heure'), $('#horloge-heure').text().trim());
	rafraichirTemps();
	pingServeur();
});

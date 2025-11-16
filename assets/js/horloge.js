/* ====================================================================
 *
 * chimieclg.ca > horloge.js  
 *
 * --------------------------------------------------------------------
 *
 * v2: avec effet "morph" sur HH:MM:SS)
 *
 * ==================================================================== */

$(document).ready(function ()
{
	const regex = /:/i;
	const regex2 = / /i;

    let pingInterval = 30e3;
    let secondeInterval = 1e3;

	let police_defaut = 'Rubik';
	let police_defaut_width = '0.65em';

	//
	// Ajuster l'heure limite selon la preference actuelle
	//

    if (docCookies.hasItem('heure_limite')) 
	{
        var heure_limite = docCookies.getItem('heure_limite');
		var heure_limite_safe = heure_limite.replace(regex, 'h');

		$('#parametres-heure').val(heure_limite);
        $('#horloge-heure-fin-exact').html(heure_limite_safe);
    } 
	else 
	{
		var heure_limite = '18:00';
		var heure_limite_safe = '18h00';

		$('#parametres-heure').val(heure_limite);
        $('#horloge-heure-fin-exact').html(heure_limite_safe);
    }

	//
	// Ajuster la police selon la preference actuelle
	//

	if (docCookies.getItem('police_actuelle') != null)
	{
		var police_actuelle = docCookies.getItem('police_actuelle');
		var police_actuelle_id = police_actuelle.replace(regex, '-');

		$('#horloge-heure').css('font-family', police_actuelle);

		$('#select-police').val(police_actuelle);
	}
	else
	{
		var police_actuelle = police_defaut;
		$('#horloge-heure').css('font-family', police_actuelle);
	}

    //
    // Fonctions pour l’horloge “morph” (roulement des chiffres)
    //

    // Construire l’affichage HH:MM:SS avec 6 chiffres + 2 colonnes, en partant d’une valeur initiale
	
    function buildClock($el, initialStr) 
	{
        $el.addClass('morph');
        const parts = ['h1', 'h2', 'colon', 'm1', 'm2', 'colon', 's1', 's2'];
        const nodes = [];

        for (const p of parts) 
		{
            if (p === 'colon') {
                nodes.push($('<div class="colon">:</div>'));
            } 
			else 
			{
                const $d = $('<div class="digit"><div class="stack"></div></div>');
                const $stack = $d.find('.stack');
                for (let i = 0; i <= 9; i++) $stack.append($('<span>').text(i));
                nodes.push($d);
			}
        }

        $el.empty().append(nodes);

        // Positionner selon l’heure initiale
        if (typeof initialStr === 'string') {
            setClock(initialStr);
        }
    }

    // Faire rouler un chiffre vers la valeur souhaitee (0..9)
	
    function setDigit($digit, value) 
	{
        const h = $digit.height(); // hauteur d’un chiffre en px
        $digit.find('.stack').css('transform', 'translateY(' + (-value * h) + 'px)');
    }

    // Appliquer "HH:MM:SS" à l’affichage roulant
	
    function setClock(timeStr) 
	{
        // "12:34:56" -> [1,2,3,4,5,6]
        const digits = timeStr.replace(/:/g, '').split('').map(d => parseInt(d, 10));
        const $digits = $('#horloge-heure .digit');

        for (let i = 0; i < digits.length; i++) {
            setDigit($digits.eq(i), digits[i]);
        }

        // Clignotement des “:”
        // $('#horloge-heure .colon').toggleClass('off');
    }

    //
    // Ce jour-ci
    //

    let ajd = new Date();
    let ajd_str;

    let ajd_y = ajd.getFullYear();
    let ajd_mo = ajd.getMonth();
    let ajd_d = ajd.getDate();

    // La numerotation commence à 0, donc 0 = janvier.

    ajd_mo = ajd_mo + 1;

    if (ajd_mo < 10) {
        ajd_mo = '0' + ajd_mo;
    }

    if (ajd_d < 10) {
        ajd_d = '0' + ajd_d;
    }

    ajd_str = ajd_y + '-' + ajd_mo + '-' + ajd_d;

    //
    // Construire l’horloge morph avec la valeur initiale
    //

    buildClock($('#horloge-heure'), $('#horloge-heure').text().trim());

    //
    // Déterminer la durée (temps) restante.
    //

    function Duree() 
	{
        let heure_limite_epoch = Date.parse(ajd_str + 'T' + heure_limite + ':00') / 1000;

        let temps_maintenant = Number($('#maintenant-epoch').html());

        let temps_diff = heure_limite_epoch - temps_maintenant;

        temps_diff = temps_diff + 60;

        if (temps_diff <= 0) 
		{
            if (heure_limite == '00:00') 
			{
                heure_limite_epoch = Date.parse(ajd_str + ' ' + '23:59:59') / 1000;
                heure_limite_epoch = heure_limite_epoch + 1;

                temps_diff = heure_limite_epoch - temps_maintenant;
                temps_diff = temps_diff + 60;
            } 
			else 
			{
                $('#horloge-temps-minutes').html(0);
                $('#horloge-temps-minutes-pluriel').html('');

                return;
            }
        }

        // Enlever les secondes, conserver les minutes
        let minutes = Math.floor(temps_diff / 60);

        minutes = Math.round(minutes);

        $('#horloge-temps-minutes').html(minutes);

        if (minutes > 1) 
		{
            $('#horloge-temps-minutes-pluriel').html('s');
        } 
		else 
		{
            $('#horloge-temps-minutes-pluriel').html('');
        }
    }

    Duree();

    //
    // Modal
    //

    $('#parametres').click(function () 
	{
		var heure_limite_actuelle = docCookies.getItem('heure_limite');		

        if (heure_limite_actuelle == null)
		{
			$('#parametres-heure').val('18:00');
		}
		else
		{
			$('#paremetres-heure').val(heure_limite_actuelle);
		}

        $('#horloge-modal').modal('show');
    });

    $('.modal .close').click(function () {
        $('#horloge-modal').modal('hide')
    });

	$('#select-police').change(function()
	{
		var police = $(this).val();

		$('#horloge-heure').css('font-family', police);

		docCookies.setItem('police_actuelle', police, 60 * 60 * 6);
	});

    $('#parametres-sauvegarder').click(function () 
	{
		heure_limite_actuelle = docCookies.getItem('heure_limite');
        heure_limite_parametres = $('#parametres-heure').val();

		if (heure_limite_parametres == null || heure_limite_parametres == '')
		{
			$('#horloge-modal').modal('hide');
			return;
		}

		heure_limite = heure_limite_parametres;
		heure_limite_safe = heure_limite.replace(regex, 'h');

		$('#horloge-heure-fin-exact').html(heure_limite_safe);

		docCookies.setItem('heure_limite', heure_limite, 60 * 60 * 6);

		Duree();

		$('#horloge-modal').modal('hide');
    });

    //
    // Rafraichir l’horloge à chaque seconde, puis verifier la duree
    //

    function rafraichirTemps() 
	{
        let temps_maintenant = Number($('#maintenant-epoch').html());

        let temps_maintenant_nouv = new Date(temps_maintenant * 1e3);

        let h = temps_maintenant_nouv.getHours();
        let m = temps_maintenant_nouv.getMinutes();
        let s = temps_maintenant_nouv.getSeconds();

        if (h < 10) {
            h = '0' + h;
        }

        if (m < 10) {
            m = '0' + m;
        }

        if (s < 10) {
            s = '0' + s;
        }

        let server_time_str = h + ':' + m + ':' + s;

        setClock(server_time_str);

        $('#maintenant-epoch').html(temps_maintenant + 1);

        Duree();

        tempsHandler = setTimeout(rafraichirTemps, secondeInterval);
    }

    rafraichirTemps();

    //
    // Synchroniser l'heure avec celle du serveur à un intervalle precis
    //

    function pingHorloge() 
	{
        $.post(base_url + 'horloge/ping', { ci_csrf_token: cct },
            function (data) 
			{
                if (typeof data == 'object' && 'heure' in data) {
                    setClock(data['heure']);
                }

                if (typeof data == 'object' && 'epoch' in data) {
                    let temps_maintenant = Number(data['epoch']) + 1; // Il y a une seconde de retard.
                    $('#maintenant-epoch').html(temps_maintenant);
                }

            }, 'json');

        pingHandler = setTimeout(pingHorloge, pingInterval);
    }

    pingHorloge();

});

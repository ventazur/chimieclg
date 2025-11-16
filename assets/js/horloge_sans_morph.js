/* ====================================================================
 *
 * chimieclg.ca > horloge.js
 *
 * --------------------------------------------------------------------
 *
 *  v2: sans morph
 *
 * ==================================================================== */

$(document).ready(function()
{
    let pingInterval = 30e3;
    let secondeInterval = 1e3;
    
    let heure_limite = '18:00';
    let heure_limite_safe = '18h00';

    //
    // Ce jours-ci
    //

    let ajd = new Date();
    let ajd_str;

    let ajd_y  = ajd.getFullYear();
    let ajd_mo = ajd.getMonth();
    let ajd_d  = ajd.getDate(); 

    // La numerotation commence a 0, donc 0 = janvier
    ajd_mo = ajd_mo + 1; 

    if (ajd_mo < 10) 
    {
        ajd_mo = '0' + ajd_mo;
    }

    if (ajd_d < 10) 
    {
        ajd_d = '0' + ajd_d;
    }
    
    ajd_str = ajd_y + '-' + ajd_mo + '-' + ajd_d;

    //
    // Verifier le temoin de connexion pour une heure deja parametree
    //

    if (docCookies.hasItem('heure_limite'))
    {
        heure_limite_safe = docCookies.getItem('heure_limite');

        const regex = /h/i;
        heure_limite = heure_limite_safe.replace(regex, ':');
        
        $('#horloge-heure-fin-exact').html(heure_limite_safe);
    }
    else
    {
        $('#horloge-heure-fin-exact').html(heure_limite_safe);
    }

    //
    // Determiner la duree (temps) restante
    //

    function Duree() 
    {
        let heure_limite_epoch = Date.parse(ajd_str + 'T' + heure_limite + ':00') / 1000;

        var temps_maintenant = Number($('#maintenant-epoch').html());

        var temps_diff = heure_limite_epoch - temps_maintenant;

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
        var minutes = Math.floor(temps_diff / 60);

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

    $('#parametres').click(function()
    {
         $('#horloge-modal').modal('show');

    });

    $('.modal .close').click(function()
    {
        $('#horloge-modal').modal('hide')
    });

    $('#parametres-sauvegarder').click(function()
    {
        heure_limite = $('#parametres-heure').val();

        const regex = /:/i;
        heure_limite_safe = heure_limite.replace(regex, 'h');

        $('#horloge-heure-fin-exact').html(heure_limite_safe);

        docCookies.setItem('heure_limite', heure_limite_safe, 60*60*6);

        Duree();

        $('#horloge-modal').modal('hide');
    });

    //
    // Rafraichir l'horloge a chaque seconde, puis verifier la duree
    //

    function rafraichirTemps()
    {
        var temps_maintenant = Number($('#maintenant-epoch').html());

        temps_maintenant_nouv = new Date(temps_maintenant * 1e3);

        var h = temps_maintenant_nouv.getHours();
        var m = temps_maintenant_nouv.getMinutes();
        var s = temps_maintenant_nouv.getSeconds();

        if (h < 10) 
        {
            h = '0' + h;
        }

        if (m < 10) 
        {
            m = '0' + m;
        }

        if (s < 10) 
        {
            s = '0' + s;
        }

        var server_time_str = h + ':' + m + ':' + s;

        $('#horloge-heure').html(server_time_str);
        $('#maintenant-epoch').html(temps_maintenant + 1);

        Duree();

        tempsHandler = setTimeout(rafraichirTemps, secondeInterval);
    }

    rafraichirTemps();

    //
    // Synchroniser l'heure avec celle du serveur a un intervalle precis
    //

    function pingHorloge() 
    {
        $.post(base_url + 'horloge/ping', { ci_csrf_token: cct },
        function(data)
        {
            if (typeof data == 'object' && 'heure' in data)
            {
                $('#horloge-heure').html(data['heure']);
            }

            if (typeof data == 'object' && 'epoch' in data)
            {
                var temps_maintenant = Number(data['epoch']) + 1; // Il y a une seconde de retard.

                $('#maintenant-epoch').html(temps_maintenant);
            }

        }, 'json');
        
        pingHandler = setTimeout(pingHorloge, pingInterval);
    }

    pingHorloge();
});

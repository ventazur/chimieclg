/* ====================================================================
 *
 * codeqr.js
 *
 * ==================================================================== */
$(document).ready(function()
{
    var activeType  = 1;
    var cache       = {};  // { url: { 1: img1, 2: img2, ... } }
    var debounceTimer;

    function spinnerShow()
    {
        $('#codeqr-check').addClass('d-none');
        $('#codeqr-spinner').removeClass('d-none');
    }

    function spinnerHide()
    {
        $('#codeqr-spinner').addClass('d-none');
        $('#codeqr-check').removeClass('d-none');
    }

    function generer(qrdata, type, callback)
    {
        spinnerShow();
        $('#generer-erreur').addClass('d-none');

        $.post(base_url + 'codeqr/generer', { ci_csrf_token: cct, qrdata: qrdata, type: String(type) },
        function(data)
        {
            spinnerHide();

            if (data == false)
            {
                $('#generer-erreur').removeClass('d-none');
                return;
            }

            if ( ! cache[qrdata]) cache[qrdata] = {};
            $.extend(cache[qrdata], data);

            if (data['qr_img1']) $('#codeqr1-img').attr('src', data['qr_img1']);
            if (data['qr_img2']) $('#codeqr2-img').attr('src', data['qr_img2']);
            if (data['qr_img3']) $('#codeqr3-img').attr('src', data['qr_img3']);
            if (data['qr_img4']) $('#codeqr4-img').attr('src', data['qr_img4']);

            if (callback) callback();

        }, 'json');
    }

    function genererActive()
    {
        var qrdata = $('#codeqr-data').val().trim();

        if (qrdata.length === 0) return;

        if (cache[qrdata] && cache[qrdata][activeType])
        {
            $('#codeqr' + activeType + '-img').attr('src', cache[qrdata][activeType]);
            return;
        }

        generer(qrdata, activeType);
    }

    // Debounce sur l'input
    $('#codeqr-data').on('input', function()
    {
        var qrdata = $(this).val().trim();
        cache = {};  // invalider le cache quand l'URL change

        clearTimeout(debounceTimer);

        if (qrdata.length === 0) return;

        debounceTimer = setTimeout(function()
        {
            genererActive();
        }, 400);
    });

    // Changement de type
    $('.codeqr-choix').click(function()
    {
        $('.codeqr-choix').removeClass('btn-primary').addClass('btn-outline-primary');
        $(this).removeClass('btn-outline-primary').addClass('btn-primary');

        var id_choix = $(this).attr('id').replace('-choix', '');
        activeType   = parseInt(id_choix.replace('codeqr', ''));

        $('#codeqr1, #codeqr2, #codeqr3, #codeqr4').addClass('d-none');
        $('#' + id_choix).removeClass('d-none');

        genererActive();
    });

    // Téléchargement
    $('[id^="save-btn"]').click(function()
    {
        const id_choix = this.id.replace('save-btn', '');
        const img      = document.getElementById('codeqr' + id_choix + '-img');

        if ( ! img) return;

        const extension = (id_choix === '3' || id_choix === '4') ? 'svg' : 'png';
        const link      = document.createElement('a');
        link.href       = img.src;
        link.download   = 'code_qr' + id_choix + '.' + extension;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });

    // Génération initiale au chargement
    genererActive();
});

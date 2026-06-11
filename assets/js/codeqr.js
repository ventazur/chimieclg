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

    function verifierQR(imgEl, expected)
    {
        var canvas = document.getElementById('qr-decode-canvas');
        var ctx    = canvas.getContext('2d');
        var el     = $('#qr-verify-result');

        function decode()
        {
            var size      = Math.max(imgEl.naturalWidth || 0, imgEl.naturalHeight || 0, 500);
            canvas.width  = size;
            canvas.height = size;
            ctx.drawImage(imgEl, 0, 0, size, size);

            var imageData;

            try
            {
                imageData = ctx.getImageData(0, 0, size, size);
            }
            catch (e)
            {
                el.html('<i class="bi bi-x-circle-fill" style="color:#d22630; margin-right:5px"></i>Erreur canvas : ' + e.message).removeClass('d-none');
                return;
            }

            // Convertir en noir et blanc pur (utile pour les QR colorés)
            var pixels = imageData.data;
            for (var i = 0; i < pixels.length; i += 4)
            {
                var lum = 0.2126 * pixels[i] + 0.7152 * pixels[i+1] + 0.0722 * pixels[i+2];
                var bw  = lum < 128 ? 0 : 255;
                pixels[i] = pixels[i+1] = pixels[i+2] = bw;
            }

            var code = jsQR(imageData.data, imageData.width, imageData.height);

            if (code && code.data === expected)
            {
                el.html('<i class="bi bi-check-circle-fill" style="color:#198754; margin-right:5px"></i>URL vérifié').removeClass('d-none');
            }
            else if (code)
            {
                el.html('<i class="bi bi-x-circle-fill" style="color:#d22630; margin-right:5px"></i>Contenu inattendu : ' + code.data).removeClass('d-none');
            }
            else
            {
                el.html('<i class="bi bi-x-circle-fill" style="color:#d22630; margin-right:5px"></i>Impossible de décoder').removeClass('d-none');
            }
        }

        if (imgEl.complete && imgEl.naturalWidth > 0)
            decode();
        else
            imgEl.onload = decode;
    }

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

            var updatedSrc = null;

            var updatedEl = null;

            if (data['qr_img1']) { $('#codeqr1-img').attr('src', data['qr_img1']); updatedEl = document.getElementById('codeqr1-img'); }
            if (data['qr_img2']) { $('#codeqr2-img').attr('src', data['qr_img2']); updatedEl = document.getElementById('codeqr2-img'); }
            if (data['qr_img3']) { $('#codeqr3-img').attr('src', data['qr_img3']); updatedEl = document.getElementById('codeqr3-img'); }
            if (data['qr_img4']) { $('#codeqr4-img').attr('src', data['qr_img4']); updatedEl = document.getElementById('codeqr4-img'); }

            // Le type 4 (SVG rouge circulaire) n'est pas décodable par jsQR — on vérifie via le type 3 (SVG standard, même contenu)
            var verifyEl = (type == 4) ? document.getElementById('codeqr3-img') : updatedEl;

            if (verifyEl) verifierQR(verifyEl, qrdata);

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
        cache = {};
        $('#qr-verify-result').addClass('d-none');

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

/* ====================================================================
 *
 * codeqr.js
 *
 * ==================================================================== */
$(document).ready(function()
{
    $('#codeqr-generer').click(function()
    {
        var qrdata = $('#codeqr-data').val();

		$('#generer-erreur').addClass('d-none');

        if (qrdata.length > 0)
        {
            $.post(base_url + 'codeqr/generer', { ci_csrf_token: cct, qrdata: qrdata },
            function(data)
            {   
				if (data == false)
				{
					$('#generer-erreur').removeClass('d-none')
				}
				else
				{
					$('#codeqr1-img').attr('src', data['qr_img1']);
					$('#codeqr2-img').attr('src', data['qr_img2']);
					$('#codeqr3-img').attr('src', data['qr_img3']);
					$('#codeqr4-img').attr('src', data['qr_img4']);

					$('#codeqr').removeClass('d-none');
				}

            }, 'json');
        }
        else
        {
            $('#codeqr').addClass('d-none');
        }
    });

	$('.codeqr-choix').click(function() 
	{
		$('.codeqr-choix').removeClass('btn-primary').addClass('btn-outline-primary');
		$(this).removeClass('btn-outline-primary').addClass('btn-primary');

		var id_choix = $(this).attr('id'); 
		var id_choix = id_choix.replace('-choix', '');

		// 4. On cache les 4 divs et on affiche la cible
		$('#codeqr1, #codeqr2, #codeqr3, #codeqr4').addClass('d-none');
		$('#' + id_choix).removeClass('d-none');
	});

	$('[id^="save-btn"]').click(function() 
	{
		const id_choix = this.id.replace('save-btn', '');
		
		const img = document.getElementById(`codeqr${id_choix}-img`);

		if ( ! img) return;

		const img_url = img.src;

		const extension = (id_choix === '3' || id_choix === '4') ? 'svg' : 'png';

		// Telechargement
		const link = document.createElement('a');
		link.href = img_url;
		link.download = `code_qr${id_choix}.${extension}`;
		document.body.appendChild(link);
		link.click();
		document.body.removeChild(link);
	});

});


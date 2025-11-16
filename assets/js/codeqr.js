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

        if (qrdata.length > 0)
        {
            $.post(base_url + 'codeqr/generer', 
                { ci_csrf_token: cct, qrdata: qrdata },
            function(data)
            {   
                $('#codeqr1-img').attr('src', data['qr_img1']);
                $('#codeqr2-img').attr('src', data['qr_img2']);
                $('#codeqr').removeClass('d-none');

            }, 'json');
        }
        else
        {
            $('#codeqr').addClass('d-none');
        }
    });

    $('#save-btn1').click(function()
    {
		const img = document.getElementById('codeqr1-img');
		const imgURL = img.src;

		const link = document.createElement('a');
		link.href = imgURL;
		link.download = 'code_qr1.svg';
		document.body.appendChild(link);
		
		link.click();
		
		document.body.removeChild(link);
	});

    $('#save-btn2').click(function()
    {
		const img = document.getElementById('codeqr2-img');
		const imgURL = img.src;

		const link = document.createElement('a');
		link.href = imgURL;
		link.download = 'code_qr2.svg';
		document.body.appendChild(link);
		
		link.click();
		
		document.body.removeChild(link);
	});
});


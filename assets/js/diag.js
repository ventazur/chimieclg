/* ====================================================================
 *
 * diag.js
 *
 * ==================================================================== */
$(document).ready(function()
{
	$('#generer-action').click(function()
	{
		var $form = $('#form-diagramme');
		var params = [];

		const champs_obligatoires = ['vadm', 'dvadm', 'vexp', 'dvexp', 'unites', 'decaxe'];
		const champs = ['xmin', 'xmax'];

		champs_obligatoires.forEach(function(champ) {
			params[champ] = $('#' + champ).val();
		});
		
		champs.forEach(function(champ) {
			params[champ] = $('#' + champ).val();
		});

		params.vadm_min = params.vadm - params.dvadm;
		params.vadm_max = +params.vadm + +params.dvadm;

		params.vexp_min = params.vexp - params.dvexp;
		params.vexp_max = +params.vexp + +params.dvexp;

		params.v_min = (params.vexp_min < params.vadm_min ? params.vexp_min : params.vadm_min);
		params.v_max = (params.vexp_max > params.vadm_max ? params.vexp_max : params.vadm_max);

		//
		// Determiner les limites de l'axe
		//

		params.d_max = (params.dvexp > params.dvadm ? params.dvexp : params.dvadm);

		if (params.xmin.length)	{
			params.x_min_limite = +params.xmin;
		} else {
			params.x_min_limite = +params.v_min - 2*(+params.d_max)
		}

		if (params.xmax.length)
			params.x_max_limite = +params.xmax;
		else
			params.x_max_limite = +params.v_max + 2*(+params.d_max)

		if (params.x_min_limite < 0) params.x_min_limite = 0;

		var dataset_va = [
			{x: params.vadm_min, y: 20},
			{x: params.vadm, 	 y: 20},
			{x: params.vadm_max, y: 20}
		];

		var dataset_ve = [
			{x: params.vexp_min, y: 40},
			{x: params.vexp, 	 y: 40},
			{x: params.vexp_max, y: 40}
		];
	
		$('#canvas-wrap').html('<canvas id="myChart"></canvas>');

		generer_diagramme(dataset_va, dataset_ve, params);

		$('#download-image-wrap').removeClass('d-none');
	});

	function generer_diagramme(dataset_va, dataset_ve, params)
	{
		var canvas = document.getElementById('myChart');
		var ctx = canvas.getContext('2d');

		var scatterChart = new Chart(ctx, {
			type: 'scatter',
			data: {
				datasets: [
				{
					data: dataset_va,
					backgroundColor: 'rgba(0, 0, 0, 1)',
					// backgroundColor: 'rgba(75, 192, 192, 0.5)',
					borderColor: 'rgba(0, 0, 0, 1)',
					// borderColor: 'rgba(75, 192, 192, 1)',
					borderWidth: 1,
					showLine: true,
					fill: false,
				},
				{
					data: dataset_ve,
					backgroundColor: 'rgba(0, 0, 0, 1)',
					borderColor: 'rgba(0, 0, 0, 1)',
					borderWidth: 1,
					showLine: true,
					fill: false,
				}]
			},
			options: {
				scales: {
					x: {
						type: 'linear',
						position: 'bottom',
						grid: {
							//color: 'rgba(255, 0, 0, 0.5)',
							color: '#dddddd',
						},
						ticks: {
							font: {
								size: 16,
							},
							color: 'black',
							callback: function(value, index, values) {
								// return value.toFixed(params.decaxe);
								return value.toFixed(params.decaxe).replace(/\./g, ',');
							}
						},

						/*
						title: {
						  display: true,
						  text: 'Figure ' + params.nofig + '. ' + params.titrefig,
						  align: 'start',
						  color: '#444',
						  font: {
							family: 'Arial',
							size: 14,
							weight: '400',
							lineHeight: 1.2,
						  },
						  padding: {top: 20, left: 0, right: 0, bottom: 0}
						},
						*/

						min: params.x_min_limite,
						max: params.x_max_limite
					},
					y: {
						display: true,
						type: 'linear',
						grid: {
							color: function(context) {
								if (context.tick.value == 0) {
								  return '#000000';
								} else {
								  return '#ffffff';
								}
							}
						},
						ticks: {
							color: '#ffffff'
						},
						min: 0,
						max: 60,
					}
				},
				plugins: {
					legend: {
						display: false, // Masquer la légende
					},
					annotation: {
						annotations: {
							label1: {
								type: 'label',
								xValue: params.vexp,
								yValue: 35,
								content: ['Valeur expérimentale : (' 
									+ params.vexp.replace(/\./g, ',')
									+ ' ± ' + params.dvexp.replace(/\./g, ',') 
									+ ') ' + params.unites ],
								font: {
									size: 18
								}
							},
							label2: {
								type: 'label',
								xValue: params.vadm,
								yValue: 15,
								content: ['Valeur admise : (' 
									+ params.vadm.replace(/\./g, ',')
									+ ' ± ' + params.dvadm.replace(/\./g, ',')
									+ ') ' + params.unites ],
								font: {
									size: 18
								}
							}
						}
					}
				} // plugins
			} // options
		});

	} // generer_diagramme

	$('#download-image').click(function()
	{
	  	var divToConvert = document.getElementById('canvas-wrap');

	  	html2canvas(divToConvert).then(function(canvas) 
		{
			var lienTelechargement = document.createElement('a');
			lienTelechargement.href = canvas.toDataURL('image/png');
			lienTelechargement.download = 'ma_figure_' + dateheure() + '.png'; // Nom du fichier à télécharger

			lienTelechargement.click();
	  	});

	});

	function dateheure()
	{
		var date = new Date();

		var annee = date.getFullYear();
		var mois = (date.getMonth() + 1).toString().padStart(2, '0'); // Mois commence à 0, donc ajoutez 1
		var jour = date.getDate().toString().padStart(2, '0');
		var heure = date.getHours().toString().padStart(2, '0');
		var minutes = date.getMinutes().toString().padStart(2, '0');
		var secondes = date.getSeconds().toString().padStart(2, '0');

		var dateHeureFormattee = annee + mois + jour + '_' + heure + minutes + secondes;

		return dateHeureFormattee;
	}

});

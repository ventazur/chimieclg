<table class="table ressources">

	<tbody>

		<? foreach($ressources as $scat => $rs) : ?>

			<? if ($scat != 'NOSCAT') : ?>

				<tr>
					<td>
						<span style="color: #555; font-weight: 300;">
							<?= $scats[$scat]['nom']; ?>
						<span>
					</td>
				</tr>

			<? endif; ?>

			<? foreach($rs as $r) : ?>

				<? if ($r['code_scat'] != $scat) : continue; endif; ?>	

				<? $icone = NULL; ?>
	
				<tr>
					<td>
						<i class="bi bi-chevron-right" style="color: #aaa; margin-right: 10px"></i>

						<? if ( ! empty($r['url'])) : ?>

							<a href="<?= $r['url']; ?>" target="_blank">
								<?= $r['nom']; ?>
							</a>

							<? $icone = ( ! preg_match('/chimieclg/', $r['url']) ? 'bi-box-arrow-up-right' : NULL); ?>

						<? elseif ( ! empty($r['fichier'])) : ?>

							<a href="<?= base_url() . 'assets/docs/' . $r['fichier']; ?>" target="_blank">
								<?= $r['nom']; ?>
							</a>

							<?
								$ext = pathinfo($r['fichier'], PATHINFO_EXTENSION);
								$ext = strtolower($ext);

								$icone = $this->config->item('icones_types')[$ext] ?? NULL;
							?>

						<? endif; ?>

						<? if ( ! empty($r['icone'])) : ?>

							<i class="bi <?= $r['icone']; ?> grisfclg" style="margin-left: 7px"></i>

						<? elseif ( ! empty($icone)) : ?>
	
							<i class="bi <?= $icone; ?> grisfclg" style="margin-left: 7px"></i>

						<? endif; ?>

					</td>
				</tr>

			<? endforeach; ?>

		<? endforeach; ?>

    </tbody>

</table>

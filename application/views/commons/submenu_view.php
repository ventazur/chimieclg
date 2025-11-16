<?
/* -----------------------------------------------------------------------
 *
 * Sous-menu 
 *
 * ----------------------------------------------------------------------- */

<? if ( ! empty($submenu)) : ?>

	<div class="submenu hidden-phone">
		<div class="row-fluid">
			<div class="col-md-12 submenu-content">
				<ul class="nav nav-pills" style="margin: 0">

					<? foreach ($submenu as $key => $arr) : 

						  if (array_key_exists('url', $arr))
						  {
							  $url = $arr['url'];
						  }
						  else
						  {
							  $url = base_url() . $controller . '/' . $key;
						  }   
					?>            

						<li class="<?= ($key == $page ? 'active' : ''); ?>"> 
							<a href="<?= $url; ?>">
								<?= $arr['nom']; ?>
							</a>
						</li>

					<? endforeach; ?>

				</ul>
			</div>
		</div>
	</div>

	<div class="submenu visible-phone hidden">
		<div class="row-fluid">
			<div class="col-md-12">

				<ul class="nav nav-pills nav-stacked" style="margin: 0">

					<?php foreach ($submenu as $key => $arr) : ?>            

						<li class="<?= ($key == $page ? 'active' : ''); ?>"> 
							<a href="<?= base_url() . $controller . '/' . $key; ?>">
								<?= $arr['nom']; ?>
							</a>
						</li>

					<?php endforeach; ?>

				</ul>
			</div>
		</div>
	</div>

	<div class="space-d"></div>  

<? endif; ?>

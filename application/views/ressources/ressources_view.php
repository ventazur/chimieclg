<?php
/* ----------------------------------------------------------------------------
 *
 * Ressources
 *
 * ---------------------------------------------------------------------------- */ ?>

<style>
	#ressources a {
		text-decoration: none;
	}

	table.ressources {
		margin-top: -10px;
		margin-bottom: 10px;
		font-size: 1.1em;
	}

	table.ressources thead tr {
		background: #a9abad;
	}

	table.ressources thead td {
		border: 0;
	}

	table.ressources tbody td {
		border: 0;
		border-bottom: 1px solid #eee;
	}

	table.ressources tbody td:hover {
		background: #f8f9fa;
	}
</style>

<?
/* ----------------------------------------------------------------------------
 *
 * Ressources > sous-menu
 *
 * ---------------------------------------------------------------------------- */ ?>

<nav id="sous-menu-nav" class="navbar navbar-expand-lg">
    <div class="container">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
			<li class="nav-item">
				<a class="nav-link" href="<?= base_url() . 'assets/docs/tp.png'; ?>">
					Tableau périodique
				</a>
            </li>
            <li class="nav-item">
				<a class="nav-link" href="<?= base_url() . 'quiz'; ?>">
					Quiz
				</a>
            </li>
        </ul>
    </div>
</nav>

<?
/* ----------------------------------------------------------------------------
 *
 * Ressouces
 *
 * ---------------------------------------------------------------------------- */ ?>

<div id="ressources" class="page-contenu">
<div class="container">

    <div class="page-titre">Les ressources</div>

    <div class="col-12">

		<? if (empty($cats)) : ?>

			Aucune ressource trouvée

			<? if ( ! empty($req_cat)) : ?>

					pour <?= $req_cat; ?>

			<? endif; ?>

        <? else : ?>                    

			<?
 			/*
			 * iteration a travers toutes les categories
			 *
			 */ ?>

            <?
                foreach($cats as $c) : 

					$code_cat = $c['code_cat'];

					$data = [];
					$data['code_cat'] = $code_cat;

					if ( ! isset($ressources[$code_cat])) continue;
            ?>
					<p class="page-section mt-4">
						<?= $c['nom']; ?>
					</p>

					<? /* --- SANS sous categorie --- */ ?>

					<? if (isset($ressources[$code_cat]['NOSCAT'])) : ?>
						<? $data['ressources']['NOSCAT'] = $ressources[$code_cat]['NOSCAT']; ?>

						<? $this->load->view($controller . '/_ressources_table', $data); ?>

					<? endif; ?>			

					<? /* --- AVEC sous categorie --- */ ?>

					<? unset($ressources[$code_cat]['NOSCAT']); ?>

					<? if (empty($ressources[$code_cat])) : continue; endif; ?>

					<? $data['ressources'] = $ressources[$code_cat]; ?>	

					<? if ( ! empty($data['ressources'])) : ?>

						<? $this->load->view($controller . '/_ressources_table', $data); ?>

					<? endif; ?>

                <? endforeach; ?>

        <? endif; ?>

        <div class="space"></div>

    </div> <!-- .col-12 -->
</div> <!-- .container -->
</div> <!-- #ressources -->

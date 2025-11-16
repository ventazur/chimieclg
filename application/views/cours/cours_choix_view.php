<?
/* --------------------------------------------------------------------
 *
 * COURS 001
 * 
 * -------------------------------------------------------------------- */ ?>

<?php $this->load->view('cours/_cours_sous_menu'); ?>

<div id="cours-choix" class="cours-page page-contenu">
<div class="container">

    <div class="col-12 page-content">

            <div class="d-none page-titre">Les choix de cours en chimie</div>

            <div style="text-align: center;">
                <a style="text-decoration: none" href="<?= base_url() . 'cours/choix/page' . ($choix_page_no - 1); ?>">
                    <i class="bi bi-arrow-left" style="margin-right: 5px"></i>
                    précédente
                </a>
                <span style="margin-left: 5px; margin-right: 5px">|</span>
				page <?= $choix_page_no; ?> / <?= $choix_page_max; ?>
                <span style="margin-left: 5px; margin-right: 5px">|</span>
                <a style="text-decoration: none" href="<?= base_url() . 'cours/choix/page' . ($choix_page_no + 1); ?>">
                    suivante
                    <i class="bi bi-arrow-right" style="margin-left: 5px"></i>
                </a>
            </div>

            <div style="text-align: center; margin-top: 25px; margin-bottom: 25px;">
                <a href="<?= base_url() . 'cours/choix/page' . ($choix_page_no + 1); ?>">
					<img src="<?= base_url() . 'assets/img/cours_choix' . $choix_version . '/Diapositive' . $choix_page_no . '.jpeg'; ?>" />
                </a>
            </div>

    </div> <!-- .col-12 -->

</div> <!-- .container -->
</div> <!-- #cours-001 -->

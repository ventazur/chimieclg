<?
/* --------------------------------------------------------------------
 *
 * COURS (GABARIT)
 * 
 * -------------------------------------------------------------------- */ ?>

<?php $this->load->view('cours/_cours_sous_menu'); ?>

<div id="cours-nf1" class="cours-page page-contenu">
<div class="container">

    <div class="col-12 page-content">

            <div class="page-titre"><?= $cours['nom_complet'] . ' (' . $cours['code'] . ')'; ?></div>

			<div class="<?= ! empty($cours['document']) && ! empty($cours['document_apercu']) ? '' : 'd-none'; ?>">
				<div>
					<a href="<?= base_url() . 'assets/cours/' . $cours['document']; ?>" target="_blank">
						<img class="cours-fiches float-start" style="margin-bottom: 5px" src="<?= base_url() . 'assets/cours/' . $cours['document_apercu']; ?>" />
					</a>
				</div>
            </div>

            <?php echo $cours['description']; ?>

            <?php $this->load->view('cours/_ressources_view', $this->data); ?>

    </div> <!-- .col-12 -->

</div> <!-- .container -->
</div> <!-- #cours-nf1 -->

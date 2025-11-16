<?
/* ----------------------------------------------------------------------------
 *
 * COURS VIEW
 *
 * ---------------------------------------------------------------------------- */ ?>

<?php $this->load->view('cours/_cours_sous_menu'); ?>

<div id="cours" class="page-contenu">
<div class="container">

        <div class="col-md-12">

            <div class="page-titre">Les cours de chimie offerts au Collège Lionel-Groulx</div>

            <?
            /* --------------------------------------------------------
             *
             * Sciences de la nature
             *
             * -------------------------------------------------------- */ ?> 

			<?php $data['cours'] = array(); ?>

			<?php foreach($cours_disponibles as $sigle => $c) : ?>

				<?php if ($c['programme'] == 'NAT') : ?>

					<?php $data['cours'][] = $sigle; ?>

				<?php endif; ?>

			<?php endforeach; ?>

            <p class="page-section">Sciences de la nature</p>

            <?php $this->load->view($controller . '/_cours_liste_table', $data); ?>

            <?
            /* --------------------------------------------------------
             *
             * Sciences, lettres et arts
             *
             * -------------------------------------------------------- */ ?> 

            <p class="page-section">Sciences, lettres et arts</p>

            <?php $data['cours'] = ['701', 'je1']; ?>

            <?php $this->load->view($controller . '/_cours_liste_table', $data); ?>

            <?
            /* --------------------------------------------------------
             *
             * Technique de sante animale
             *
             * -------------------------------------------------------- */ ?> 

            <p class="page-section">Technique de santé animale</p>

            <?php $data['cours'] = ['sj1', 'sj2']; ?>

            <?php $this->load->view($controller . '/_cours_liste_table', $data); ?>

            <?
            /* --------------------------------------------------------
             *
             * Technique de soins infirmiers
             *
             * -------------------------------------------------------- */ ?> 

            <p class="page-section">
                Soins infirmiers
            </p>

            <?php $data['cours'] = ['3a3', '4a3']; ?>

            <?php $this->load->view($controller . '/_cours_liste_table', $data); ?>

            <?
            /* --------------------------------------------------------
             *
             * Autres programmes
             *
             * -------------------------------------------------------- */ ?> 

            <p class="page-section">Autres programmes</p>
            
            <?php $data['cours'] = ['001', 'z20', 'va0', 'r56']; ?>
            
            <?php $this->load->view($controller . '/_cours_liste_table', $data); ?>

        </div>  
    </div>
</div>

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

            <?php foreach($programmes as $prog) : ?>

                <?php $data['cours'] = []; ?>

                <?php foreach($cours_disponibles as $sigle => $c) : ?>
                    <?php if ($c['programme_id'] == $prog['programme_id']) : ?>
                        <?php $data['cours'][] = $sigle; ?>
                    <?php endif; ?>
                <?php endforeach; ?>

                <?php if ( ! empty($data['cours'])) : ?>

                    <p class="page-section mt-4 mb-1"><?= $prog['nom']; ?></p>

                    <?php $this->load->view($controller . '/_cours_liste_table', $data); ?>

                <?php endif; ?>

            <?php endforeach; ?>

        </div>
    </div>
</div>

<?
/* ----------------------------------------------------------------------------
 *
 * DOCUMENTS VIEW
 *
 * ---------------------------------------------------------------------------- */ ?>

<nav id="sous-menu-nav" class="navbar navbar-expand-lg">
    <div class="container">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url() . 'assets/docs/Guide_de_redaction_A2021.pdf'; ?>">Guide de réaction A2021</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url() . 'assets/docs/tp.png'; ?>">Tableau périodique</a>
            </li>
        </ul>
    </div>
</nav>

<div id="documents" class="page-contenu">
<div class="container">

    <div class="col-12">

        <div class="page-titre">Les documents & ressources</div>

        <?php if (empty($categories)) : ?>

            <div class="lato-l db-error">
                Il n'y a aucun document dans la base de données.
            </div>

        <?php else : ?>
        
            <?php foreach($categories as $c) : ?>
        
                <p class="page-section"><?= $c['nom']; ?></p>

                <?php $data['cat'] = $c['code']; ?>                

                <?php $this->load->view($controller . '/_documents_table', $data); ?>

            <?php endforeach; ?>

        <?php endif; ?>    

    </div> <!-- .col-12 -->

</div> <!-- .container -->
</div> <!-- #documents -->


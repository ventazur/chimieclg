<?
/* --------------------------------------------------------------------
 *
 * COURS NE3
 * 
 * -------------------------------------------------------------------- */ ?>

<?php $this->load->view('cours/_cours_sous_menu'); ?>

<div id="cours-ne3" class="cours-page page-contenu">
<div class="container">

    <div class="col-12 page-content">

            <div class="page-titre"><?= $cours['nom_complet'] . ' (' . $cours['code'] . ')'; ?></div>

            <p>
                <a href="<?= base_url() . 'assets/cours/ne3/ne3-fiche2.pdf'; ?>">
                    <img class="cours-fiches" align="left" src="<?= base_url() . 'assets/cours/ne3/ne3-fiche2-apercu.png'; ?>" />
                </a>
                
                Comme nous sommes ce que nous mangeons, il y a présentement un intérêt grandissant pour les connaissances dans le domaine 
                de l’alimentation que ce soit en lien avec la santé (prévention, nutrathérapie, molécules anticancéreuses) ou la création 
                et l’exploration culinaire (gastronomie moléculaire). 
            </p>

            <p>
                Dans ce cours, vous approfondirez les principales classes de composés organiques lui permettant de mieux comprendre les 
                interactions impliquant des classes de molécules importantes liées à l’alimentation tels les lipides, les glucides et les 
                protéines.  
            </p>

            <p>
                Vous réaliserez un projet multidisciplinaire en transformation alimentaire qui vous permettra de créer de nouveaux mets 
                ou préparations ou encore de mettre au point de nouveaux procédés à partir des techniques récentes de transformation 
                alimentaire (cuisine moléculaire et autres).  Ces projets de fin d’études seront proposés directement par des entreprises 
                locales du milieu alimentaire voulant créer de nouveaux produits : des entreprises agricoles, des transformateurs 
                artisanaux, des restaurateurs, des brasseries, etc. Ainsi, vous participerez à la création d’un produit alimentaire 
                novateur pour une entreprise locale qui pourra ensuite le commercialiser.
            </p>

            <p>
                L’inscription à ce cours nécessite la réussite du cours <a href="<?= base_url() . $controller . '/ne2'; ?>"><?= $cours_disponibles['ne2']['nom_court']; ?></a> (202-NE2-LG).
            </p>

            <?php $this->load->view('cours/_ressources_view', $this->data); ?>

    </div> <!-- .col-12 -->

</div> <!-- .container -->
</div> <!-- #cours-ne3 -->

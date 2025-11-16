<?
/* --------------------------------------------------------------------
 *
 * COURS nf1
 * 
 * -------------------------------------------------------------------- */ ?>

<?php $this->load->view('cours/_cours_sous_menu'); ?>

<div id="cours-nf1" class="cours-page page-contenu">
<div class="container">

    <div class="col-12 page-content">

            <div class="page-titre"><?= $cours['nom_complet'] . ' (' . $cours['code'] . ')'; ?></div>

            <p>
                Ce cours est offert au choix à la troisième ou quatrième session aux étudiantes et aux étudiants inscrits au programme 
                Sciences de la nature. 
                L’inscription à ce cours nécessite la réussite du cours Chimie générale. 
                Il vise à développer la compétence « Consolider sa culture scientifique dans un domaine des sciences de la nature ».
            </p>

            <p>
                L’objectif principal du cours est de démontrer le rôle clé de la chimie dans l’alimentation, que ce soit en lien avec 
                la santé ou avec la création et l’exploration culinaire. 
                L’atteinte de cet objectif est appuyée par l’étude des principales classes de composés organiques impliquées en alimentation, 
                soit les lipides, les glucides, les protéines et les alcools. Enfin, un projet final en transformation alimentaire permettra 
                la création de nouveaux mets et de produits alcoolisés ou la mise au point de nouveaux procédés.
            </p>

            <?php $this->load->view('cours/_ressources_view', $this->data); ?>

    </div> <!-- .col-12 -->

</div> <!-- .container -->
</div> <!-- #cours-nf1 -->

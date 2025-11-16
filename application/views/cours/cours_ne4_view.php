<?
/* --------------------------------------------------------------------
 *
 * COURS NE4
 * 
 * -------------------------------------------------------------------- */ ?>

<?php $this->load->view('cours/_cours_sous_menu'); ?>

<div id="cours-ne4" class="cours-page page-contenu">
<div class="container">

    <div class="col-12 page-content">

            <div class="page-titre"><?= $cours['nom_complet'] . ' (' . $cours['code'] . ')'; ?></div>

            <p>
                <a href="<?= base_url() . 'assets/cours/ne4/ne4-fiche2.pdf'; ?>">
                    <img class="cours-fiches" align="left" src="<?= base_url() . 'assets/cours/ne4/ne4-fiche2-apercu.png'; ?>" />
                </a>

                <strong><?= $cours['nom_complet']; ?></strong> est un cours offert au choix à la quatrième session aux élèves inscrits au programme Sciences de la nature. 
                Il vise à développer la compétence suivante : rendre l’étudiant apte à « appliquer une démarche scientifique dans un domaine propre aux sciences de la nature ».
            </p>

            <p>
                L’environnement désigne tout ce qui nous entoure. Celui-ci se modifie sous l’influence des activités humaines et de l’augmentation de la population. 
                Ce cours a pour objectif principal de démontrer le rôle de la chimie dans l’environnement. 
                La connaissance des phénomènes chimiques de l’équilibre naturel et des déséquilibres provoqués par la pollution de l’air, des sols et de l’eau 
                sont les aspects les plus importants de ce cours. Les problèmes modernes de l’énergie, de la qualité de la vie, du recyclage des déchets et 
                des ressources de notre planète sont abordés en se concentrant sur les solutions présentes et futures. 
                Le cours propose aussi une réflexion sur le rôle social de l’humain face aux problèmes de la pollution et de la qualité de l’environnement.
            </p>

            <p>
                Il faut avoir réussi <a href="<?= base_url() . $controller . '/nya'; ?>"><?= $cours_disponibles['nya']['nom_court']; ?></a> pour prendre ce cours.
            </p>

            <?php $this->load->view('cours/_ressources_view', $this->data); ?>

            <div class="space"></div>

    </div> <!-- .col-12 -->

</div> <!-- .container -->
</div> <!-- #cours-nya -->

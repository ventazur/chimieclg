<?
/* --------------------------------------------------------------------
 *
 * COURS NE5
 * 
 * -------------------------------------------------------------------- */ ?>

<?php $this->load->view('cours/_cours_sous_menu'); ?>

<div id="cours-ne5" class="cours-page page-contenu">
<div class="container">

    <div class="col-12 page-content">

        <div class="page-titre"><?= $cours['nom_complet'] . ' (' . $cours['code'] . ')'; ?></div>

        <p>
            <a href="<?= base_url() . 'assets/cours/ne5/ne5-fiche2.pdf'; ?>">
                <img class="cours-fiches" align="left" src="<?= base_url() . 'assets/cours/ne5/ne5-fiche2-apercu.png'; ?>" />
            </a>

            Chimie médicinale est un cours au choix offert aux étudiants inscrit au programme Science de la nature. 
            Il vise à développer les compétences suivantes : « Traiter un ou plusieurs sujets, dans le cadre des sciences de la nature, 
            sur la base de ses acquis. » (00UU, MELS) et «Appliquer une démarche scientifique dans un domaine propre 
            aux sciences de la nature.» (00UV, MELS). 
        </p>

        <p>
            Ce cours permet aux étudiants d’acquérir les connaissances et de développer les habiletés et les attitudes relatives 
            à l’application de la chimie dans le domaine de la santé. 
            Il complète l’étude des principales classes de composés organiques acquis dans le cours de chimie organique NE2-LG, 
            tout en abordant l’étude des molécules biologiques importantes comme les lipides, les acides aminés, 
            les glucides et les protéines. 
            Il permet de raffiner les notions de synthèse organique de produits d’intérêt biologique, pharmaceutique et médical. 
            L'étude des molécules biologiques les plus importantes sera également abordée dans ce cours. 
            Ces notions seront appliquées en laboratoire où l’étudiant pourra faire la démonstration de l’intégration des différentes 
            compétences acquises pendant ses études collégiales lors de la réalisation d’un projet de fin d’études, l’ESP.
        </p>

        <p>
            Il faut avoir réussi <a href="<?= base_url() . $controller . '/ne2'; ?>"><?= $cours_disponibles['ne2']['nom_court']; ?></a> 
            pour s'inscrire à ce cours.
        </p>

        <?php $this->load->view('cours/_ressources_view', $this->data); ?>

    </div> <!-- .col-12 -->

</div> <!-- .container -->
</div> <!-- #cours-ne5 -->

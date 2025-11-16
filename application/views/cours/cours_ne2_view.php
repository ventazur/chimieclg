<?
/* --------------------------------------------------------------------
 *
 * COURS NE2
 * 
 * -------------------------------------------------------------------- */ ?>

<?php $this->load->view('cours/_cours_sous_menu'); ?>

<div id="cours-ne2" class="cours-page page-contenu">
<div class="container">

    <div class="col-12 page-content">

            <div class="page-titre"><?= $cours['nom_complet'] . ' (' . $cours['code'] . ')'; ?></div>

            <p>
                <a href="<?= base_url() . 'assets/cours/ne2/ne2-fiche2.pdf'; ?>">
                    <img align="left" class="cours-fiches" src="<?= base_url() . 'assets/cours/ne2/ne2-fiche2-apercu.png'; ?>" />
                </a>
                
                Le cours <strong><?= $cours['nom_complet']; ?></strong> permet aux étudiants du programme d’études Sciences de la nature d’acquérir les connaissances 
                et de développer les habiletés et les attitudes relatives aux mécanismes réactionnels impliquant des composés du carbone.  
            </p>

            <p>
                Ce cours traite des principes sur lesquels se fonde le comportement des molécules organiques dont le recensement de 18 millions de molécules 
                croît de plusieurs milliers chaque année. Plusieurs de ces molécules sont d’origine naturelle alors que d'autres sont créés en laboratoire 
                par des chimistes qui cherchent à obtenir des composés ayant des propriétés particulières dans des domaines aussi divers que les carburants, 
                les médicaments ou les matériaux plastiques. 
            </p>

            <p>
                Suite à ce cours, vous pourrez prédire le comportement de molécules organiques puisque vous aurez établi des relations entre la structure 
                d’une molécule et sa réactivité par la compréhension des mouvements des électrons de valence dans divers groupements fonctionnel.
            </p>

            <p>
                La chimie étant une science expérimentale, des expériences en laboratoire seront réalisées afin d'observer des phénomènes en lien avec 
                la matière à l’étude et rendre compte de ces observations, ce que les étudiants auront à faire tout au long de leur parcours collégial.
            </p>

            <p>
                Le cours de <?= $cours['nom_complet']; ?> est le troisième de six cours de chimie offerts aux élèves inscrits en Sciences de la nature.
                L’inscription à ce cours nécessite la réussite du cours <a href="<?= base_url() . $controller . '/sn1'; ?>"><?= $cours_disponibles['sn1']['nom_court']; ?></a>.
                De plus, la réussite de ce cours est préalable aux cours <a href="<?= base_url() . $controller . '/ne3'; ?>"><?= $cours_disponibles['ne3']['nom_court'];?></a> et 
                <a href="<?= base_url() . $controller . '/ne5'; ?>"><?= $cours_disponibles['ne5']['nom_court']; ?></a> en plus d’être obligatoire pour
                plusieurs programmes universitaires.
            </p>

            <?php $this->load->view('cours/_ressources_view', $this->data); ?>

    </div> <!-- .col-12 -->

</div> <!-- .container -->
</div> <!-- #cours-ne2 -->

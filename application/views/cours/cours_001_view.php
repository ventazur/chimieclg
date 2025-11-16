<?
/* --------------------------------------------------------------------
 *
 * COURS 001
 * 
 * -------------------------------------------------------------------- */ ?>

<?php $this->load->view('cours/_cours_sous_menu'); ?>

<div id="cours-001" class="cours-page page-contenu">
<div class="container">

    <div class="col-12 page-content">

            <div class="page-titre"><?= $cours['nom_complet'] . ' (' . $cours['code'] . ')'; ?></div>

            <p>
                Le cours <span style="font-weight: 400"><?= $cours['nom_complet'];?></span> permet aux étudiants de compléter leur formation afin de pouvoir s’inscrire 
                à des cours de chimie de niveau collégial. Il vise l'acquisition de méthodes de travail utiles pour des études en sciences 
                et le développement des attitudes favorables à la réussite des cours de chimie.
            </p>

            <p>
                Dans un premier temps, l’atome sera étudié en partant des premiers modèles atomiques au modèle actuel fondé sur la dualité onde-particule des électrons. 
                Les propriétés de ces atomes en lien avec la classification périodique des éléments seront également à l’étude. 
                Par la suite, les interactions entre les atomes pour former différents sels ou molécules selon le type de liaison seront approfondies. 
                Finalement, ce cours s’intéressera aux interactions entre les molécules afin d’expliquer différents phénomènes perceptibles à l’échelle macroscopique. 
                Les concepts prescrits sont regroupés autour des concepts généraux suivants : les gaz, l’aspect énergétique des transformations, 
                la vitesse de réaction et l’équilibre chimique.De plus, l’étudiant appliquera la démarche scientifique lors de la résolution de problèmes, 
                ce qui sera repris dans tous ses cours de science.
            </p>

            <p>
                La chimie étant une science expérimentale, des expériences en laboratoire seront réalisées afin d'observer des phénomènes en lien avec la matière 
                à l’étude et rendre compte de ces observations, ce que les étudiants auront à faire tout au long de leur parcours collégial.
            </p>

            <?php $this->load->view('cours/_ressources_view', $this->data); ?>

    </div> <!-- .col-12 -->

</div> <!-- .container -->
</div> <!-- #cours-001 -->

<?
/* --------------------------------------------------------------------
 *
 * COURS EDD
 * 
 * -------------------------------------------------------------------- */ ?>

<?php $this->load->view('cours/_cours_sous_menu'); ?>

<div id="cours-edd" class="cours-page page-contenu">
<div class="container">

    <div class="col-12 page-content">

            <div class="page-titre"><?= $cours['nom_complet'] . ' (' . $cours['code'] . ')'; ?></div>

            <p>
                Le cours Chimie organique appliquée aux TSA (2 1/3 unités) permet aux étudiants du programme d’études Techniques de santé animale d’acquérir 
                les connaissances et de développer les habiletés et les attitudes relatives à la chimie organique et la biochimie. 
            </p>

            <p>
                La chimie organique qui, à l’origine, doit son nom aux composés produits par les organismes vivants (d’où vient le terme "organique") vise 
                d’abord à mieux comprendre les propriétés et la structure de l’atome de carbone dans les composés carbonés, à connaître les principaux groupements 
                fonctionnels associés aux produits organiques et à connaître leurs caractéristiques principales.
            </p>
        
            <p>
                Quant à la biochimie, elle étudie les molécules et les réactions chimiques que l’on retrouve chez les  organismes vivants.  
                La biochimie est une voie d’accès privilégiée pour apprendre les caractéristiques générales et les propriétés physiques et chimiques des 
                principales molécules rencontrées chez les vivants, et pour comprendre les rôles vitaux de ces composés.
            </p>

            <p>
                Tout comme le premier cours de chimie, le cours de Chimie organique appliquée aux TSA permet à l’étudiant d’appliquer la démarche scientifique 
                lors de la résolution de problèmes.
            </p>

            <p> 
                La chimie étant une science expérimentale, des expériences en laboratoire seront réalisées afin d'observer des phénomènes en lien avec la matière à l’étude et rendre compte de ces observations, ce que les étudiants auront à faire tout au long de leur parcours collégial. 
            </p>

            <?php $this->load->view('cours/_ressources_view', $this->data); ?>

    </div> <!-- .col-12 -->

</div> <!-- .container -->
</div> <!-- #cours-nya -->

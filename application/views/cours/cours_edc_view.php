<?
/* --------------------------------------------------------------------
 *
 * COURS EDC
 * 
 * -------------------------------------------------------------------- */ ?>

<?php $this->load->view('cours/_cours_sous_menu'); ?>

<div id="cours-edc" class="cours-page page-contenu">
<div class="container">

    <div class="col-12 page-content">

            <div class="page-titre"><?= $cours['nom_complet'] . ' (' . $cours['code'] . ')'; ?></div>

            <p>
                Chimie générale appliquée aux TSA est un cours obligatoire offert aux étudiants du programme d’études Techniques de santé animale. 
                Ce cours vise à développer les compétences suivantes: « Gérer de l’équipement et du matériel.» (00QK, MELS) 
                et « Utiliser des techniques de laboratoire de chimie et de biochimie. » (00R4, MELS). 
            </p>

            <p>
                Tout organisme vivant sur cette planète, tant animal que végétal, est un véritable laboratoire où ont lieu, à chaque instant, 
                plusieurs réactions chimiques qui assurent la survie de cet organisme: respiration, digestion, photosynthèse en sont quelques exemples.  
                La chimie est donc une voie d'accès privilégiée pour explorer et comprendre ces phénomènes.). 

            <p>
                Ce cours permet d’acquérir les connaissances et de développer les habiletés et les attitudes relatives à la conception 
                de la matière, ses propriétés et ses transformations, plus particulièrement en contexte de solutions. 
                Il vise d'abord à favoriser l'acquisition d'habiletés (méthodes de travail) et à développer des attitudes nécessaires à 
                la réussite de l’étudiant.  
                De plus, l’étudiant appliquera la démarche scientifique lors de la résolution de problèmes, ce qui sera repris dans tous 
                ses cours de science.   
            </p>

            <p>
                Ces acquisitions seront nécessaires pour entreprendre les cours subséquents de chimie organique et de biochimie de votre programme.
            </p>

            <?php $this->load->view('cours/_ressources_view', $this->data); ?>

    </div> <!-- .col-12 -->

</div> <!-- .container -->
</div> <!-- #cours-edc -->

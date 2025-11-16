<?
/* --------------------------------------------------------------------
 *
 * COURS Z20
 * 
 * -------------------------------------------------------------------- */ ?>

<?php $this->load->view('cours/_cours_sous_menu'); ?>

<div id="cours-z20" class="cours-page page-contenu">
<div class="container">

    <div class="col-12 page-content">

            <div class="page-titre"><?= $cours['nom_complet'] . ' (' . $cours['code'] . ')'; ?></div>

            <p>
                <strong>NOTE</strong> : Pour suivre ce cours, il faudra avoir 18 ans au plus tard un mois après le début
                de la session et débourser un montant de 65$ pour couvrir les frais du  matériel requis
                pour la fabrication des alcools et les visites.
            </p>

            <p style="font-weight: 400">
                À qui ce cours, sur un sujet aussi stimulant, s’adresse-t-il?
            </p>

            <p>
                À ceux que le simple mot « chimie »
                n’effraie pas, car ce cours ne requiert aucun préalable en chimie. En réalité, nous traiterons des
                alcools, des bières et des vins. Nous ferons deux visites pertinentes à ce sujet,
                la visite d’une micro-brasserie et d’un vignoble. De plus, nous fabriquerons des boissons alcoolisées : bière et vin.
            </p>

            <p>
                Évidemment, nous ferons des analyses de laboratoire simples et faciles sur ces produits. Nous
                examinerons les effets des alcools sur l’organisme et nous traiterons du langage de la
                dégustation des vins et des bières. Nous parlerons des éléments qui distinguent les divers vins et
                ce qui donne leur goût spécifique. En fin de compte, nous ouvrirons la porte de ce monde
                fantastique des alcools comestibles.
            </p>

            <p>
                Bonne dégustation !
            </p>

            <?php $this->load->view('cours/_ressources_view', $this->data); ?>

    </div> <!-- .col-12 -->

</div> <!-- .container -->
</div> <!-- #cours-nya -->

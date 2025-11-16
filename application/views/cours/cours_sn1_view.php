<?
/* --------------------------------------------------------------------
 *
 * COURS sn1
 * 
 * -------------------------------------------------------------------- */ ?>

<?php $this->load->view('cours/_cours_sous_menu'); ?>

<div id="cours-sn1" class="cours-page page-contenu">
<div class="container">

    <div class="col-12 page-content">

            <div class="page-titre"><?= $cours['nom_complet'] . ' (' . $cours['code'] . ')'; ?></div>

            <p>
                <a href="<?= base_url() . 'assets/cours/nya/nya-fiche-apercu.png'; ?>">
                    <img align="left" class="cours-fiches" src="<?= base_url() . 'assets/cours/nya/nya-fiche-apercu.png'; ?>" />
                </a>

                Le modèle atomique que nous décrivons dans ce cours a été élaboré au début du XXe siècle, alors que les scientifiques ont identifié les premières particules subatomiques et qu'ils ont examiné les interactions entre ces particules et les ondes électromagnétiques. 
                On comprend aujourd'hui que les interactions entre les atomes reposent sur le comportement des électrons de valence qui constituent la couche électronique externe des atomes. 
                Les arrangements d'atomes qui conduisent à des composés stables correspondent à des combinaisons de basse énergie. 
                L’énergie joue toujours un rôle important au cours des réactions chimiques.
            </p>

            <p>
                Nous verrons d'abord de quoi sont faits les atomes et nous tenterons d'expliquer comment ceux-ci ne peuvent se trouver que dans des états particuliers d'énergie. Nous examinerons en fait le modèle atomique fondé sur la dualité onde-particule des électrons, et nous ferons la relation entre ce modèle et la classification périodique des éléments chimiques. Vous pourrez alors prévoir la formule chimique d'un grand nombre de composés. Vous apprendrez à nommer les composés chimiques; vous reverrez comment écrire des équations chimiques équilibrées, et vous ferez des calculs chimiques avec ces équations.
                Nous examinerons ensuite des modèles et des théories afin d'expliquer comment les atomes tiennent ensemble. Vous pourrez alors prévoir la structure géométrique des molécules et celle des ions polyatomiques simples. Vous pourrez également prévoir certaines propriétés physiques des composés simples et expliquer pourquoi ils forment des mélanges homogènes avec certains composés alors qu'ils n'en forment pas avec d'autres.
                Au laboratoire, vous serez initiés aux techniques de base de la chimie. Vous ferez des mesures et des calculs qui vous permettront de vérifier certains principes reliés aux réactions chimiques.
            </p>
            
            <?php $this->load->view('cours/_ressources_view', $this->data); ?>

    </div> <!-- .col-12 -->

</div> <!-- .container -->
</div> <!-- #cours-sn1 -->

<?
/* --------------------------------------------------------------------
 *
 * COURS VA0
 * 
 * -------------------------------------------------------------------- */ ?>

<?php $this->load->view('cours/_cours_sous_menu'); ?>

<div id="cours-nya" class="cours-page page-contenu">
<div class="container">

    <div class="col-12 page-content">

            <div class="page-titre"><?= $cours['nom_complet'] . ' (' . $cours['code'] . ')'; ?></div>

            <p>
                <img align="left" style="width: 300px; padding-right: 15px;" src="<?= base_url() . 'assets/cours/va0/va0.png'; ?>"></img>
                Le cours Histoire des sciences : approches théorique et expérimentale (2 unités) permet aux étudiants  du programme d’études Histoire et civilisation d’acquérir les connaissances et de développer les habiletés et les attitudes relatives à l’histoire du développement des sciences de l’Antiquité à nos jours. Le cours s’organise de façon chronologique et on y étudie les différentes sciences expérimentales ainsi que leurs relations avec la société.  
            </p>

            <p>
                Ce cours permet d’identifier les faits marquants ayant contribué à façonner la science, plus particulièrement en retraçant les personnages, les découvertes, les innovations technologiques et les institutions ayant participé à l’essor des sciences en Occident, dans le monde, et plus récemment, au Québec.  
            </p>

            <p>
                Une partie importante de ce cours est consacrée au côté expérimental de la science tant concernant l’histoire de la méthode scientifique que par l’application de la méthodologie propre aux sciences en utilisant la démarche scientifique pour concevoir, exécuter, analyser et rendre compte d’une expérience ayant marqué l’avancement des connaissances en science.
            </p>

            <p><strong>Une approche historique chronologique</strong></p>

            <p>
                Le cours a une approche historique chronologique menant du scribe au savant en débutant à l’Antiquité où sont vues les contributions des civilisations mésopotamienne, babylonienne, égyptienne, grecque, romaine, chinoise et indienne.  L’apport des civilisations arabes et occidentales s’inscrit à l’époque médiévale alors qu’à la Renaissance est vue l’émergence du savant.  
            </p>

            <p>
                Par la suite, l’étude de l’époque moderne ainsi que l’avènement de l’autonomie et l’indépendance de la science par rapport à la religion se feront par l’entremise de la révolution copernicienne menant au Siècle des Lumières.  
            </p>

            <p>
                L’époque moderne, qui s’échelonne du XVIIe siècle à nos jours, permettra d’étudier les principaux éléments marquants de la physique, de la chimie, de la biologie, de la médecine et des différentes ramifications de spécialités telles la biochimie, la microbiologie, les biotechnologies, la cancérologie, l’environnement et la mécanique quantique.  L’apport spécifique de la communauté scientifique québécoise sera également à l’étude. 
            </p>

            <p>
                Une attention particulière sera portée à la diffusion des connaissances en passant par l’avènement de l’imprimerie, l’enseignement, la création de journaux scientifiques et l’impact de l’informatique et l’Internet.  
            </p>

            <p>
                De plus, le cours inclut une approche expérimentale appliquée en laboratoire où l’étudiant doit préparer différentes expérimentations à partir de protocoles, réaliser ces expériences et rédiger une communication scientifique sous forme de rapport.  Ajouté à cela, ce volet inclut des démonstrations expérimentales et la possibilité de visites.
            </p>

            <?php $this->load->view('cours/_ressources_view', $this->data); ?>

    </div> <!-- .col-12 -->

</div> <!-- .container -->
</div> <!-- #cours-nya -->

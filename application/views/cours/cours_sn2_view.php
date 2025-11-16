<?
/* --------------------------------------------------------------------
 *
 * COURS NYB
 * 
 * -------------------------------------------------------------------- */ ?>

<?php $this->load->view('cours/_cours_sous_menu'); ?>

<div id="cours-nyb" class="cours-page page-contenu">
<div class="container">

    <div class="col-12 page-content">

            <div class="page-titre"><?= $cours['nom_complet'] . ' (' . $cours['code'] . ')'; ?></div>

            <p>
                <a href="<?= base_url() . 'assets/cours/nyb/nyb-fiche-apercu.png'; ?>">
                    <img align="left" class="cours-fiches" src="<?= base_url() . 'assets/cours/nyb/nyb-fiche-apercu.png'; ?>" />
                </a>

                Ce cours traite des aspects qualitatif et quantitatif de l’équilibre chimique. 
                On s’intéresse plus particulièrement aux réactions qui se produisent dans les solutions aqueuses d’électrolytes parce que l’eau est omniprésente dans l’environnement terrestre, y compris dans la très grande majorité des organismes vivants.
                Une bonne compréhension des notions vues dans ce cours permet d’expliquer des phénomènes naturels d’une grande diversité. 
                Elle donne aussi les moyens de contrôler les paramètres sensibles des réactions chimiques qui gouvernent de nombreux procédés industriels de même que ceux qui régissent les réactions chimiques dans les organismes vivants.
            </p>

            <p>
                L’étude de ces phénomènes est fondée sur des modèles mathématiques simples qui permettent une approche rigoureuse. 
                Nous aurons recours à l’algèbre et nous résoudrons des problèmes qui impliquent des équations du premier et du deuxième degré.
            </p>

            <p>
                Les travaux pratiques permettent d’appliquer les notions traitées en classe à des cas d‘espèce. Il y a cinq périodes de rencontres à chaque semaine.
            </p>

            <br /><br />

            <?php $this->load->view('cours/_ressources_view', $this->data); ?>

            <div class="space"></div>

    </div> <!-- .col-12 -->

</div> <!-- .container -->
</div> <!-- #cours-nyb -->

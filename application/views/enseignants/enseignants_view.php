<?
/* ----------------------------------------------------------------------------
 *
 * ENSEIGNANTS VIEW
 *
 * ---------------------------------------------------------------------------- */ ?>

<nav id="sous-menu-nav" class="navbar navbar-expand-lg">
    <div class="container">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link active" href="<?= base_url() . $controller . '#enseigants'; ?>">Le personnel enseignant</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url() . $controller . '#techniciennes'; ?>">Le personnel technicien</a>
            </li>
        </ul>
    </div>
</nav>

<div id="enseignants" class="page-contenu">
<div class="container">

    <div class="col-12">

        <a name="enseignants"></a>    

        <div class="page-titre">Le personnel enseignant</div>

        <?php if ($enseignants === FALSE) : ?>

            <div class="lato-l db-error">
                Il n'y a aucun enseignant dans la base de données.
            </div>

        <?php else : ?>

            <div class="space"></div>

            <table class="table enseignants">
                <thead>
                    <tr>
                        <td>Nom, Prénom</td>
                        <td style="width: 120px; text-align: center">Bureau</td>
                    </tr>
                </thead>
                <tbody>

                <?php foreach ($enseignants as $enseignant) : ?>
                    <tr>
                        <td>
                            <?= $enseignant['nom'] . ', ' . $enseignant['prenom']; ?>

                            <span style="margin-left: 10px">
                                <?php if ($enseignant['coordination_d']) : ?>
                                    (personne coordonnatrice du département)
                                <?php elseif ($enseignant['coordination_p']) : ?>
                                    (personne coordonnatrice du programme)
                                <?php elseif ($enseignant['webmestre']) : ?>
                                    (webmestre)
                                <?php endif; ?>
                            </span>
                        </td>
                        <td style="text-align: center"><?= $enseignant['bureau']; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

        <?php endif; ?>

    </div> <!-- .col-12 -->

</div> <!-- .container -->
</div> <!-- #enseignants -->

<a name="techniciennes"></a>

<div id="techniciennes" class="page-contenu">
<div class="container">

    <div class="col-12">

            <div class="page-titre">Le personnel technicien de laboratoire</div>

            <?php if (empty($techniciens) || $techniciens === FALSE) : ?>

                <div class="lato-l db-error">
                    <i class="fa fa-exclamation-circle" style="color: crimson"></i> Il n'y a aucune personne technicienne dans la base de données.
                </div>

            <?php else : ?>

            <table class="table techniciennes">
                <thead>
                    <tr>
                        <td>Nom, Prénom</td>
                        <td style="width: 120px; text-align: center">Bureau</td>
                    </tr>
                </thead>
                <tbody>

                <?php foreach ($techniciens as $technicien) : ?>
                    <tr>
                        <td><?= $technicien['nom'] . ', ' . $technicien['prenom']; ?></td>
                        <td style="text-align: center;"><?= $technicien['bureau']; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <?php endif; ?>

    </div> <!-- .col-12 -->

</div> <!-- .container -->
</div> <!-- #techniciennes -->

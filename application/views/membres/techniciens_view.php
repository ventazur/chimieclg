<div id="techniciens`">

    <div class="row-fluid">
        <div class="col-md-12 page-content">

            <div class="page-title-membres"><?= $submenu['techniciens']['titre']; ?></div>

            <?php if ($techniciens === FALSE) : ?>
                Il n'y a aucun technicien dans la base de données.

            <?php else : ?>
            <table class="table table-bordered table-striped" style="margin: 0">
                <thead>
                    <tr>
                        <th>Nom, Prénom</th>
                        <th>Bureau</th>
                        <th>Poste</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($techniciens as $technicien) : ?>
                    <tr>
                        <td><?= $technicien['nom'] . ', ' . $technicien['prenom']; ?></td>
                        <td><?= $technicien['bureau']; ?></td>
                        <td><?= $technicien['poste']; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>

        </div>
    </div>

</div>


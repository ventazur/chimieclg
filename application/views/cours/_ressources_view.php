<?php if ($ressources) : ?>

<div class="row">

    <div class="cours-ressources">

        <a name="ressources"></a>

        <p class="page-section">Ressources pour ce cours</p>

        <table class="table">

            <?php foreach ($categories as $c) : ?>

                <tr class="ressources-categorie">
                    <td>
                        <?= $c['nom']; ?>
                    </td>
                </tr>

                <?php foreach ($ressources as $r) : ?>

                    <?php if ($r['category'] != $c['code']) continue; ?>

                    <tr>
                        <td>
                            <a href="<?= $r['url']; ?>" target="_blank"><?= $r['nom']; ?></a>
                        </td>
                    </tr>

                <?php endforeach; ?>

            <?php endforeach; ?>
        
        </table>

    </div>

</div> <!-- .row -->

<?php endif; ?>

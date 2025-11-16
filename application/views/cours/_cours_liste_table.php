<table class="table cours-liste">
    <thead>
        <tr>
            <th style="width: 150px">Code</th>
            <th>Titre du cours</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach($cours as $c) : ?>

            <?php if ( ! array_key_exists($c, $cours_disponibles)) continue; ?>

            <tr id="<?= $c; ?>">
                <td><?= $cours_disponibles[$c]['code']; ?></td>
                <td>
                    <?php if ($cours_disponibles[$c]['page_disponible']) : ?>
                        <a href="<?= base_url() . $controller . '/' . $c; ?>"><?= $cours_disponibles[$c]['nom_complet']; ?></a>
                    <?php else : ?>
                        <?= $cours_disponibles[$c]['nom_complet']; ?>
                    <?php endif; ?>

					<?php /*
                    <?php if (in_array($c, $ressources_cours) && $cours_disponibles[$c]['page_disponible']) : ?>
                        <div class="cours-ressources-liste">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-return-right" viewBox="0 0 16 16" style="margin-right: 3px; margin-top: -3px">
                                <path fill-rule="evenodd" d="M1.5 1.5A.5.5 0 0 0 1 2v4.8a2.5 2.5 0 0 0 2.5 2.5h9.793l-3.347 3.346a.5.5 0 0 0 .708.708l4.2-4.2a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708.708L13.293 8.3H3.5A1.5 1.5 0 0 1 2 6.8V2a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <a href="<?= base_url() . $controller . '/' . $c . '#ressources'; ?>">Ressources</a>
                        </div>

					<?php endif; ?>
					*/ ?>

                </td>
            </tr>

        <?php endforeach; ?>
    </tbody>

</table>

<table class="table documents">

    <?php /*
    <thead>
        <tr>
            <td>Nom du document</th>
        </tr>
    </thead>
    */ ?>

    <tbody>

        <?php foreach($documents as $d) : ?>

            <?php if ($d['cat_code'] != $cat) continue; ?>

            <tr>
                <td>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16" style="color: #aaa; margin-right: 10px">
                        <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                    </svg>

                    <?php if ($d['type'] == 'URL') : ?>
                        
                        <a href="<?= $d['nom_du_fichier']; ?>">

                            <?= $d['description']; ?>
                        </a>

                    <?php else : ?>

                        <a href="<?= base_url() . 'assets/docs/' . $d['nom_du_fichier']; ?>">
                            <?= $d['description']; ?>
                        </a>

                    <?php endif; ?>
                </td>
            </tr>

        <?php endforeach; ?>

    </tbody>

</table>

<?php
/* ----------------------------------------------------------------------------
 *
 * Ressources
 *
 * ---------------------------------------------------------------------------- */ ?>

<div id="ressources" class="page-contenu">
<div class="container">

    <div class="page-titre">Les ressources</div>

    <div class="col-12">

        <?php  if (empty($categories_utiles)) : ?>

            Aucune ressource trouvée

        <?php else : ?>                    

            <?php 

                foreach($categories_utiles as $c) : 

                    $code = $c;
            ?>
                <div class="space"></div>

                <h4><?php echo $categories[$code]['nom']; ?></h4>    

                <div class="space"></div>

                <table class="table table-bordered table-striped" style="margin: 0; font-size: 0.9em">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th style="width: 60px">Lien</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($ressources as $r) : 

                            if ($r['category'] != $code) continue;        
                        ?>
                        <tr>
                            <td><?php echo $r['nom']; ?> </td>
                            <td style="text-align: center">
                                <a href="<?php echo $r['url']; ?>" target="_blank">
                                    <i class="fa fa-link fa-lg"></i>
                                </a>
                            </td>
                        </tr>

                        <?php endforeach; ?>
                    </tbody>
                </table>

                <?php endforeach; ?>

        <?php endif; ?>

        <div class="space"></div>

    </div> <!-- .col-12 -->
</div> <!-- .container -->
</div> <!-- #ressources -->

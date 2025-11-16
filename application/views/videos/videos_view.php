<?
/* ----------------------------------------------------------------------------
 *
 * VIDEOS VIEW
 *
 * ---------------------------------------------------------------------------- */ ?>

<nav id="sous-menu-nav" class="navbar navbar-expand-lg">
    <div class="container">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url() . $controller . '#securite'; ?>">Sécurité</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url() . $controller . '#manipulations'; ?>">Manipulations</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url() . $controller . '#nya'; ?>">Chimie générale</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url() . $controller . '#excel'; ?>">Tutoriels Excel</a>
            </li>
        </ul>
    </div>
</nav>

<div id="videos" class="page-contenu">
<div class="container">

        <div class="page-titre">Les vidéos</div>

        <div class="space"></div>

        <a name="securite"></a>

        <p class="page-section">La sécurité au laboratoire</p> 

        <div class="videos-table">

            <table class="videos">
                <tbody>
                    <tr>
                        <td style="text-align: center">
                            <video controls height="290" width="515">
                                <source src="assets/docs/securite.mp4" type="video/mp4">
                                Désolé, votre fureteur ne supporte pas les vidéos intégrés.
                            </video>
                        </td>
                        <td style="width: 100%"></td>
                        <td style="text-align: center"></td>
                    </tr>

                </tbody>
            </table>

        </div>

        <a name="manipulations"></a>

        <p class="page-section">Les manipulations au laboratoire</p> 

        <div class="videos-table">
            <table>
                <tbody>
                    <tr>
                        <td style="text-align: center">
                            <video controls height="290" width="548">
                                <source src="assets/docs/pipette_jaugee.mp4" type="video/mp4">
                                Désolé, votre fureteur ne supporte pas les vidéos intégrés.
                            </video>
                        </td>
                        <td style="width: 100%"></td>
                        <td style="text-align: center">
                            <video controls height="290" width="515">
                                <source src="assets/docs/fiole_jaugee_2019.mp4" type="video/mp4">
                                Désolé, votre fureteur ne supporte pas les vidéos intégrés.
                            </video>
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center; font-size: 1em;">
                            <a href="assets/docs/pipette_jaugee_diaporama.ppsx" target="_blank" style="margin-right: 20px;">
                                <i class="fa fa-file-powerpoint-o"></i>
                                Diaporama
                            </a>
                            <a href="assets/docs/pipette_jaugee_fiche_accompagnement.pdf" target="_blank">
                                <i class="fa fa-file-pdf-o"></i>
                                Fiche d'accompagnement
                            </a>
                        </td>
                        <td></td>
                        <td style="text-align: center; font-size: 1em;">
                            <a href="assets/docs/fiole_jaugee_diaporama.ppsx" target="_blank" style="margin-right: 20px;">
                                <i class="fa fa-file-powerpoint-o"></i>
                                Diaporama
                            </a>
                            <a href="assets/docs/fiole_jaugee_fiche_accompagnement.pdf" target="_blank">
                                <i class="fa fa-file-pdf-o"></i>
                                Fiche d'accompagnement
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="space-d"></div>

            <table>
                <tbody>
                    <tr>
                        <td style="text-align: center">
                            <video controls height="290" width="515">
                                <source src="assets/docs/burette.mp4" type="video/mp4">
                                Désolé, votre fureteur ne supporte pas les vidéos intégrés.
                            </video>
                        </td>
                        <td style="width: 100%"></td>
                        <td style="text-align: center">
                            <video controls height="290" width="515">
                                <source src="assets/docs/titrage_2019.mp4" type="video/mp4">
                                Désolé, votre fureteur ne supporte pas les vidéos intégrés.
                            </video>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center; font-size: 1em;">
                            <a href="assets/docs/burette_diaporama.ppsx" target="_blank" style="margin-right: 20px;">
                                <i class="fa fa-file-powerpoint-o"></i>
                                Diaporama
                            </a>
                            <a href="assets/docs/burette_fiche_accompagnement.pdf" target="_blank">
                                <i class="fa fa-file-pdf-o"></i>
                                Fiche d'accompagnement
                            </a>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <div class="space-d"></div>

            <table>
                <tbody>
                    <tr>
                        <td style="text-align: center">
                            <video controls height="290" width="515">
                                <source src="assets/docs/filtration_sous_vide.mp4" type="video/mp4">
                                Désolé, votre fureteur ne supporte pas les vidéos intégrés.
                            </video>
                        </td>
                        <td style="width: 100%"></td>
                        <td style="text-align: center">
                            <video controls height="290" width="515">
                                <source src="assets/docs/ccm.mp4" type="video/mp4">
                                Désolé, votre fureteur ne supporte pas les vidéos intégrés.
                            </video>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="space-d"></div>

            <table>
                <tbody>
                    <tr>
                        <td style="text-align: center">
                            <video controls height="290" width="515">
                                <source src="assets/docs/balance_analytique.mp4" type="video/mp4">
                                Désolé, votre fureteur ne supporte pas les vidéos intégrés.
                            </video>
                        </td>
                        <td style="width: 100%"></td>
                        <td style="text-align: center">
                            <video controls height="290" width="515">
                                <source src="assets/docs/extraction_2019.mp4" type="video/mp4">
                                Désolé, votre fureteur ne supporte pas les vidéos intégrés.
                            </video>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div> <!-- .videos-table -->
        
        <a name="nya"></a>
        <a name="sn1"></a>

        <p class="page-section">Chimie générale</p>

        <div class="videos-table">

            <table>
                <tbody>
                    <tr>
                        <td style="text-align: center">
                            <video controls height="290" width="515" poster="assets/img/poster_capsule_calculs_incertitude_absolue.png">
                                <source src="assets/docs/capsule_calculs_incertitude_absolue.mp4" type="video/mp4">
                                Désolé, votre fureteur ne supporte pas les vidéos intégrés.
                            </video>
                        </td>
                        <td style="width: 100%"></td>
                        <td style="text-align: center">
                            <video controls height="290" width="515" poster="assets/img/poster_capsule_validite.png">
                                <source src="assets/docs/capsule_validite.mp4" type="video/mp4">
                                Désolé, votre fureteur ne supporte pas les vidéos intégrés.
                            </video>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="space-d"></div>

            <table>
                <tbody>
                    <tr>
                        <td style="text-align: center">
                            <video controls height="290" width="515" poster="assets/img/poster_capsule_diagramme_de_chevauchement.png">
                                <source src="assets/docs/capsule_diagramme_de_chevauchement.mp4" type="video/mp4">
                                Désolé, votre fureteur ne supporte pas les vidéos intégrés.
                            </video>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div> <!-- .videos-table -->

        <a name="excel"></a>

        <p class="page-section">
            Les tutoriels Excel
        </p>

        <div class="videos-table">
            <table>
                <tbody>
                    <tr>
                        <td style="text-align: center">
                            <video controls height="290" width="515" poster="assets/img/poster_prep_graphique.jpg">
                                <source src="assets/docs/excel_1_graphique.mp4" type="video/mp4">
                                Désolé, votre fureteur ne supporte pas les vidéos intégrés.
                            </video>
                        </td>
                        <td style="width: 100%"></td>
                        <td style="text-align: center">
                            <video controls height="290" width="515">
                                <source src="assets/docs/excel_2_modifications.mp4" type="video/mp4">
                                Désolé, votre fureteur ne supporte pas les vidéos intégrés.
                            </video>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="space-d"></div>

            <table>
                <tbody>
                    <tr>
                        <td style="text-align: center">
                            <video controls height="290" width="515">
                                <source src="assets/docs/excel_3_ajout_de_courbes.mp4" type="video/mp4">
                                Désolé, votre fureteur ne supporte pas les vidéos intégrés.
                            </video>
                        </td>
                        <td style="width: 100%"></td>
                        <td style="text-align: center">
                            <video controls height="290" width="515">
                                <source src="assets/docs/excel_4_barres_erreur.mp4" type="video/mp4">
                                Désolé, votre fureteur ne supporte pas les vidéos intégrés.
                            </video>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div> <!-- .videos-tables -->

    </div> <!-- .col-12 -->

</div> <!-- .container -->
</div> <!-- #videos -->

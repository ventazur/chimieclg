<div id="profil">
    <div class="row-fluid">
        <div class="col-md-12 page-content">

            <div class="page-title-membres"><?= $submenu['profil']['titre']; ?></div>

                <div class="space-h"></div>

                <?php if ( ! empty($error_msg)) : ?>
                <p class="bg-danger" style="padding: 8px 10px 8px 10px; border-radius: 5px; color: firebrick">
                    <i class="fa fa-exclamation-circle" style="margin-right: 5px"></i> <?= @$error_msg; ?>
                </p>
                <div class="space"></div>
                <?php endif; ?>

                <?php if ($success_msg = $this->session->flashdata('success_msg')) : ?>
                <p class="bg-success" style="padding: 8px 10px 8px 10px; border-radius: 5px; color: green;">
                    <i class="fa fa-thumbs-up" style="margin-right: 5px"></i> <?= @$success_msg; ?>
                </p>
                <div class="space"></div>
                <?php endif; ?>

                <form method="post" class="form-horizontal form-profil" style="padding: 25px 0 10px 0; background: #eee; border-radius: 5px">

                    <input type="hidden" name="<?= $csrf['name'];?>" value="<?= $csrf['hash'];?>" />

                      <div class="form-group">
                        <label for="prenom" class="col-sm-2 col-sm-offset-1 control-label">Prénom</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" id="prenom" placeholder="<?= $personne_info['prenom']; ?>" readonly>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="nom" class="col-sm-2 col-sm-offset-1 control-label">Nom</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" id="nom" placeholder="<?= $personne_info['nom']; ?>" readonly>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="bureau" class="col-sm-2 col-sm-offset-1 control-label">Bureau</label>
                        <div class="col-sm-2">
                          <input name="bureau" type="text" class="form-control" id="bureau" value="<?= $personne_info['bureau']; ?>">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="poste" class="col-sm-2 col-sm-offset-1 control-label">Poste</label>
                        <div class="col-sm-2">
                          <input name="poste" type="text" class="form-control" id="poste" style="color: #444" value="<?= $personne_info['poste']; ?>">
                        </div>
                      </div>

                      <p class="col-sm-offset-3" style="font-size: 16px; padding-left: 10px">
                        Pour modifier votre mot-de-passe, vous devez remplir les trois champs suivants : 
                      </p>

                      <div class="form-group">
                        <label for="mot_de_passe_actuel" class="col-sm-2 col-sm-offset-1 control-label">Mot-de-passe actuel</label>
                        <div class="col-sm-3">
                          <input name="mot_de_passe_actuel" type="password" class="form-control" id="mot_de_passe_actuel" placeholder="">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="mot_de_passe_nouveau" class="col-sm-2 col-sm-offset-1 control-label">Nouveau mot-de-passe</label>
                        <div class="col-sm-3">
                          <input name="mot_de_passe_nouveau" type="password" class="form-control" id="mot_de_passe_nouveau" placeholder="" minlenght="8">
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="mot_de_passe_confirmation" class="col-sm-2 col-sm-offset-1 control-label">Nouveau mot-de-passe</label>
                        <div class="col-sm-3">
                          <input name="mot_de_passe_confirmation" type="password" class="form-control" id="mot_de_passe_confirmation" placeholder="">
                        </div>
                      </div>

                      <div class="space-d"></div>

                      <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                          <button type="submit" class="btn btn-default">Mettre à jour mon profil</button>
                        </div>
                      </div>
                </form>

        </div>
    </div>
</div>

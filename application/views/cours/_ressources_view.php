
<? if (isset($ressources[$req_cat])) : ?>


<? $data['ressources'] = $ressources[$req_cat]; ?>

<div class="row">

    <div class="cours-ressources">

        <a name="ressources"></a>

        <p class="page-section mb-1">Ressources pour ce cours</p>

		<? $this->load->view('ressources/_ressources_table', $data); ?>

    </div>

</div> <!-- .row -->

<?php endif; ?>

<?
/* ----------------------------------------------------------------------------
 *
 * HORLOGE2 - index
 *
 * ---------------------------------------------------------------------------- */ ?>

<style>
    #horloge-items {
        font-size: 1.2em;
        font-weight: 300;
    }

</style>

<div id="home" class="page-contenu">
<div class="container mt-3">    

    <h3>Horloges</h3>

    <div id="horloge-items" class="mt-3">
        <ul>
            <li><a href="<?= base_url() . $this->current_controller . '/v/1'; ?>">version 1</a> (original)</li>
            <li><a href="<?= base_url() . $this->current_controller . '/v/2'; ?>">version 2</a> (sombre)</li>
        </ul>
    </div>

</div> <!-- .container -->
</div> <!-- #home -->

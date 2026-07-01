<?
/* ----------------------------------------------------------------------------
 *
 * HORLOGE2 - index
 *
 * ---------------------------------------------------------------------------- */ ?>

<style>
    .horloge-item {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 18px 20px;
        border-left: 4px solid transparent;
        text-decoration: none;
        color: #000;
        border-bottom: 1px solid #ebeced;
        transition: background 0.15s;
    }

    .horloge-item:last-child {
        border-bottom: 0;
    }

    .horloge-item:hover {
        background: #f8f9fa;
        color: #000;
        text-decoration: none;
    }

    .horloge-item-dot {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .horloge-item-texte {
        flex: 1;
    }

    .horloge-item-nom {
        font-weight: 400;
        font-size: 1.05em;
    }

    .horloge-item-desc {
        font-size: 0.88em;
        color: #888;
        margin-top: 2px;
    }

    .horloge-item-fleche {
        color: #ccc;
        font-size: 1.1em;
    }

    .horloge-item:hover .horloge-item-fleche {
        color: #d22630;
    }
</style>

<div id="home" class="page-contenu">
<div class="container mt-3">

    <div class="page-titre">Horloges</div>

    <div id="horloge-items" class="mt-4 mb-3">

        <a class="horloge-item" href="<?= base_url() . $this->current_controller . '/v/1'; ?>" style="border-left-color: #d22630">
            <div class="horloge-item-dot" style="background: #d22630"></div>
            <div class="horloge-item-texte">
                <div class="horloge-item-nom">Version 1 — Originale</div>
                <div class="horloge-item-desc">Fond gris clair, affichage simple et sobre</div>
            </div>
            <i class="bi bi-chevron-right horloge-item-fleche"></i>
        </a>

        <a class="horloge-item" href="<?= base_url() . $this->current_controller . '/v/2'; ?>" style="border-left-color: #222">
            <div class="horloge-item-dot" style="background: #222"></div>
            <div class="horloge-item-texte">
                <div class="horloge-item-nom">Version 2 — Sombre</div>
                <div class="horloge-item-desc">Fond noir, grands chiffres avec animation de morphing</div>
            </div>
            <i class="bi bi-chevron-right horloge-item-fleche"></i>
        </a>

        <a class="horloge-item" href="<?= base_url() . $this->current_controller . '/v/3'; ?>" style="border-left-color: #555">
            <div class="horloge-item-dot" style="background: #555"></div>
            <div class="horloge-item-texte">
                <div class="horloge-item-nom">Version 3 — Aéroport</div>
                <div class="horloge-item-desc">Volets mécaniques animés, style tableau de départs d'un aéroport</div>
            </div>
            <i class="bi bi-chevron-right horloge-item-fleche"></i>
        </a>

        <a class="horloge-item" href="<?= base_url() . $this->current_controller . '/v/4'; ?>" style="border-left-color: #2a3a2a">
            <div class="horloge-item-dot" style="background: #2a3a2a"></div>
            <div class="horloge-item-texte">
                <div class="horloge-item-nom">Version 4 — Tableau craie</div>
                <div class="horloge-item-desc">Fond vert ardoise, chiffres tracés à la craie</div>
            </div>
            <i class="bi bi-chevron-right horloge-item-fleche"></i>
        </a>

    </div>

</div> <!-- .container -->
</div> <!-- #home -->

<?
/* -----------------------------------------------------------------------
 *
 * Navigation principale
 *
 * ----------------------------------------------------------------------- */ ?>

<nav id="header-nav" class="navbar navbar-expand-lg pt-4 pb-4">

    <div class="container">

		<div class="col-sm-8 col-lg-4">
            <img class="d-inline" src="<?= base_url() . 'assets/img/logoCLG_i.png'; ?>" style="vertical-align: top; height: 36px; margin-bottom: 0px; margin-top: 17px; margin-right: 8px;" />
            <a class="navbar-brand" href="<?= base_url(); ?>" style="color: inherit">
                <span style="vertical-align: middle; font-weight: 400; font-size: 2.4em;">CHIMIE</span>
            </a>
        </div>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
            </svg>
        </button>

        <div class="collapse navbar-collapse lato-b" id="navbarSupportedContent">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0 mt-3 mt-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= $this->uri->segment(1) == FALSE ? 'active' : ''; ?>" href="<?= base_url(); ?>">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $this->uri->segment(1) == 'cours' ? 'active' : ''; ?>" href="<?= base_url() . 'cours'; ?>">Cours</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $this->uri->segment(1) == 'enseignants' ? 'active' : ''; ?>" href="<?= base_url() . 'enseignants'; ?>">Enseignants</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $this->uri->segment(1) == 'documents' ? 'active' : ''; ?>" href="<?= base_url() . 'documents'; ?>">Documents</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $this->uri->segment(1) == 'videos' ? 'active' : ''; ?>" href="<?= base_url() . 'videos'; ?>">Vidéos</a>
                </li>
                <li class="nav-item evaluations" style="">
                    <a class="nav-link" href="https://clgchimie.kovao.com">
                        Évaluations
                    </a>
                </li>
            </ul>

            <div class="d-none d-lg-inline">
                <a href="http://clg.qc.ca">
                    <img width="140px" src="<?= base_url(); ?>/assets/img/logoCLG_2019.jpg" style="margin-top: -15px; margin-bottom: -15px;"/>
                </a>
            </div>

        </div>

    </div> <!-- .container -->

</nav>

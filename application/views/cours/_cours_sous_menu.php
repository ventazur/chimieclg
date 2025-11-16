<nav id="sous-menu-nav" class="navbar navbar-expand-lg">

    <div class="container">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link <?= $this->uri->segment(2) == FALSE ? 'active' : ''; ?>" href="<?= base_url() . $controller; ?>">Liste des cours</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $this->uri->segment(2) == 'organigramme' ? 'active' : ''; ?>" href="<?= base_url() . $controller . '/organigramme'; ?>">Organigramme</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $this->uri->segment(2) == 'choix' ? 'active' : ''; ?>" href="<?= base_url() . $controller . '/choix'; ?>">
                    <span class="rougeclg">Choix</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $this->uri->segment(2) == 'sn1' ? 'active' : ''; ?>" href="<?= base_url() . $controller . '/sn1'; ?>">Chimie générale</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $this->uri->segment(2) == 'sn2' ? 'active' : ''; ?>" href="<?= base_url() . $controller . '/sn2'; ?>">Chimie des solutions</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $this->uri->segment(2) == 'snu' ? 'active' : ''; ?>" href="<?= base_url() . $controller . '/snu'; ?>">Chimie organique</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $this->uri->segment(2) == 'z20' ? 'active' : ''; ?>" href="<?= base_url() . $controller . '/z20'; ?>">Chimie du vin</a>
            </li>
        </ul>
    </div>

</nav>

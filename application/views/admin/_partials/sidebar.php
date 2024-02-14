<aside class="main-sidebar sidebar-dark-primary elevation-4">

  <a href="<?= base_url("admin/dashboard"); ?>" class="brand-link">
    <img src="<?= base_url("assets/vendor/AdminLTE/dist/img/AdminLTELogo.png"); ?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light"><?= ucfirst($this->uri->segment(1) . " " . SITE_NAME) ?></span>
  </a>

  <div class="sidebar" style="height: calc(100% - ((3.5rem + 4rem) + 1px));">

    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <div style="width: 2.5rem; height: 2.5rem; border-radius: 9999px; overflow: hidden;">
          <img src="<?= base_url($auth["current_user"]->avatar ?? "/vendor/AdminLTE/dist/img/user2-160x160.jpg") ?>" style="width: 100%; height: 100%; object-fit: cover; object-position: center center;" alt="">
        </div>
      </div>
      <div class="info">
        <a href="#" class="d-block"><?= $auth["current_user"]->username ?></a>
      </div>
    </div>

    <div class="sidebar-search form-inline">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
    </div>

    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <li class="nav-item <?= $this->uri->segment(2) == 'dashboard' ? 'menu-open' : '' ?>">
          <a href="#" class="nav-link <?= $this->uri->segment(2) == 'dashboard' ? 'active' : '' ?>">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= base_url("admin/dashboard"); ?>" class="nav-link <?= $this->uri->segment(2) == 'dashboard' ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Dashboard</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-header">MANAGE</li>
        <?php $manage_menu = ['invoice', 'profile', 'e_commerce', 'project']; ?>
        <li class="nav-item menu-open">
          <a href="#" class="nav-link <?= in_array($this->uri->segment(2), $manage_menu) ? 'active' : '' ?>">
            <i class="nav-icon fas fa-book"></i>
            <p>
              Pages
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= base_url("admin/actor") ?>" class="nav-link <?= $this->uri->segment(2) == 'actor' ? 'active' : '' ?>">
                <i class="fas fa-theater-masks nav-icon"></i>
                <p>Actor</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url("admin/director") ?>" class="nav-link <?= $this->uri->segment(2) == 'director' ? 'active' : '' ?>">
                <i class="fas fa-user-secret nav-icon"></i>
                <p>Director</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url("admin/genre") ?>" class="nav-link <?= $this->uri->segment(2) == 'genre' ? 'active' : '' ?>">
                <i class="fas fa-head-side-mask nav-icon"></i>
                <p>Genre</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url("admin/recommendations") ?>" class="nav-link <?= $this->uri->segment(2) == 'recommendations' ? 'active' : '' ?>">
                <i class="fas fa-thumbs-up nav-icon"></i>
                <p>Recommendations</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url("admin/reviews") ?>" class="nav-link <?= $this->uri->segment(2) == 'reviews' ? 'active' : '' ?>">
                <i class="fas fa-comments nav-icon"></i>
                <p>Reviews</p>
              </a>
            </li>
            <li class="nav-item menu-open">
              <a href="#" class="nav-link">
                <i class="fab fa-squarespace nav-icon"></i>
                <p>
                  Content
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?= base_url("admin/content/studio") ?>" class="nav-link <?= $this->uri->segment(3) == 'studio' ? 'active' : '' ?>">
                    <i class="fab fa-studiovinari nav-icon"></i>
                    <p>Studio</p>
                  </a>
                </li>
                <li class="nav-item <?= $this->uri->segment(3) == 'film' ? 'menu-open' : '' ?>">
                  <a href="#" class="nav-link">
                    <i class="fas fa-film nav-icon"></i>
                    <p>
                      Film
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="<?= base_url("admin/content/film/movie") ?>" class="nav-link <?= $this->uri->segment(4) == 'movie' ? 'active' : '' ?>">
                        <i class="fas fa-compact-disc nav-icon"></i>
                        <p>Movie</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?= base_url("admin/content/film/series") ?>" class="nav-link <?= $this->uri->segment(4) == 'series' ? 'active' : '' ?>">
                        <i class="fas fa-tape nav-icon"></i>
                        <p>Series</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?= base_url("admin/content/film/tv_show") ?>" class="nav-link <?= $this->uri->segment(4) == 'tv_show' ? 'active' : '' ?>">
                        <i class="fas fa-tv nav-icon"></i>
                        <p>Tv Show</p>
                      </a>
                    </li>
                  </ul>
                </li>
              </ul>
            </li>
          </ul>
        </li>
        <li class="nav-header">AUTH</li>
        <li class="nav-item">
          <a href="<?= base_url("admin/user") ?>" class="nav-link <?= $this->uri->segment(2) == 'user' ? 'active' : '' ?>">
            <i class="fas fa-users nav-icon"></i>
            <p>User</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url("admin/subscription_plan") ?>" class="nav-link <?= $this->uri->segment(2) == 'subscription_plan' ? 'active' : '' ?>">
            <i class="fas fa-spa nav-icon"></i>
            <p>Subscription Plan</p>
          </a>
        </li>
      </ul>
    </nav>

  </div>

  <div class="sidebar-custom" style="display: flex; align-items: center; justify-content: space-between; height: 4rem; padding: 0.85rem 0.5rem; border-top: 1px solid #4f5962;">
    <a href="<?= base_url() ?>" class="btn btn-link"><i class="fas fa-globe-asia"></i></a>
    <a href="<?= base_url("admin/auth/logout") ?>" class="btn btn-danger hide-on-collapse pos-right">
      <i class="fas fa-sign-out-alt mr-1" style="transform: scale(-1);"></i>
      Logout
    </a>
  </div>

</aside>
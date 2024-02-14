<nav class="nav">
  <div class="nav-container">
    <div class="content">
      <div id="appHeader">
        <div class="logo">
          <a href="<?= base_url("client/home") ?>" class="d-flex h-100">
            <img src="<?= base_url("assets/icons/logo.svg") ?>" alt="app-logo" width="100%">
          </a>
        </div>
      </div>
      <div id="triggerBlockMenu">
        <span class="navTrigger">
          <i></i>
          <i></i>
          <i></i>
        </span>
      </div>
      <div id="mainListDiv" class="main_list">
        <ul class="navlinks">
          <li>
            <a href="#" class="pb-3">
              <i class="fas fa-film"></i>
              &nbsp;
              Movie
            </a>
            <ul class="dropdown">
              <li><a href="<?= base_url("client/movie/most_watched") ?>"><i class="fas fa-caret-right me-2"></i>Most watched</a></li>
              <li><a href="#"><i class="fas fa-caret-right me-2"></i>Quality</a></li>
              <li><a href="#"><i class="fas fa-caret-right me-2"></i>IMAX</a></li>
              <li><a href="#"><i class="fas fa-caret-right me-2"></i>Marvel Cinematic Universe</a></li>
            </ul>
          </li>
          <li>
            <a href="#" class="pb-3">
              <i class="fas fa-tv"></i>
              &nbsp;
              Tv Series
            </a>
            <ul class="dropdown">
              <li><a href="#"><i class="fas fa-caret-right me-2"></i>Most watched</a></li>
              <li><a href="#"><i class="fas fa-caret-right me-2"></i>Marvel studio series</a></li>
              <li><a href="#"><i class="fas fa-caret-right me-2"></i>Amazon prime</a></li>
              <li><a href="#"><i class="fas fa-caret-right me-2"></i>Apple TV+ series</a></li>
              <li><a href="#"><i class="fas fa-caret-right me-2"></i>Disney+ series</a></li>
              <li><a href="#"><i class="fas fa-caret-right me-2"></i>HBO series</a></li>
              <li><a href="#"><i class="fas fa-caret-right me-2"></i>Netflix series</a></li>
            </ul>
          </li>
          <li>
            <a href="#">
              <i class="far fa-folder-open"></i>
              &nbsp;
              Genre
            </a>
          </li>
          <li>
            <a href="#">
              <i class="fab fa-affiliatetheme"></i>
              &nbsp;
              Year
            </a>
          </li>
          <li>
            <div class="auth-buttons">
              <?php if (!$auth["current_user"]) { ?>
                <a href="<?= base_url("client/auth/login") ?>">
                  <button class="login">Login</button>
                </a>
                <a href="<?= base_url("client/auth/register") ?>">
                  <button class="register">Register</button>
                </a>
              <?php } else { ?>
                <?php if ($auth["current_user"]->role == 1) : ?>
                  <a href="<?= base_url("admin/dashboard") ?>">
                    <button class="admin">Admin</button>
                  </a>
                <?php endif; ?>
                <a href="<?= base_url("client/auth/logout") ?>">
                  <button class="logout">Logout</button>
                </a>
            </div>
          <?php } ?>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>
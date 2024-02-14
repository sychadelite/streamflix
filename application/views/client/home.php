<section id="home">
  <div class="hero">
    <div class="overlay"></div>
    <div class="container m-auto">
      <div class="content">
        <div>
          <form action="#" method="post">
            <div class="search-box">
              <?php if ($auth["current_user"]) { ?>
                <h3 class="fs-4 fw-medium text-white text-center mb-0">Welcome, <span class="fw-bold"><?= $auth["current_user"]->username ?></span></h3>
              <?php } ?>
              <h1 class="fs-1 fw-bolder text-white text-center mb-0">Search Movie / TV Series</h1>

              <div class="pseudo-search">
                <input type="text" class="fw-bold" placeholder="Search..." autofocus required>
                <div class="d-flex align-items-center" style="gap: 6px;">
                  <i class="fa fa-globe places"></i>
                  <button type="submit" class="fa fa-search p-0"></button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="divider"></div>

  <div class="jumbotron">
    <div class="container m-auto">
      <div class="side-by-side">
        <div class="substance">
          <h1 class="fs-2 fw-bold mb-4">Enjoy on your TV</h1>
          <p class="fs-6 fw-medium">Watch on Smart TVs, Playstation, Xbox, Chromecast, Apple TV, Blu-ray players, and more.</p>
        </div>
        <div class="substance">
          <div class="frame">
            <img alt="" src="<?= base_url("assets/img/frame/tv.png") ?>">
            <div class="motion" style="top: 20%; left: 13%;">
              <video autoplay="" playsinline="" muted="" loop="">
                <source src="<?= base_url("assets/video/motion/video-tv-0819.m4v") ?>" type="video/mp4">
              </video>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="divider"></div>

  <div class="jumbotron">
    <div class="container m-auto">
      <div class="side-by-side">
        <div class="substance">
          <div class="frame">
            <img alt="" src="<?= base_url("assets/img/frame/mobile-0819.jpg") ?>">
            <div class="motion" style="bottom: 8%; left: 50%; width: 60%; z-index: 1;">
              <div class="d-flex flex-wrap justify-content-between align-items-center w-100 bg-black" style="gap: 1rem; min-width: 15rem; margin: 0 auto; padding: 0.35rem 0.75rem; border: 2px solid rgba(128,128,128,0.7); box-shadow: 0 0 2em 0 rgb(0,0,0); border-radius: 0.75rem; transform: translateX(-50%);">
                <div class="d-flex flex-wrap align-items-center" style="gap: 10px;">
                  <div>
                    <img alt="" src="<?= base_url("assets/img/frame/boxshot.png") ?>" style="max-height: 64px;">
                  </div>
                  <div>
                    <div style="font-size: .75rem; font-weight: 500;">Stranger Things</div>
                    <div style="font-size: .75rem; font-weight: 500;"><small style="color: #0071eb;">Downloading...</small></div>
                  </div>
                </div>
                <div aria-hidden="true" style="width: 1.5rem;">
                  <img src="<?= base_url("assets/gif/icons8-download.gif") ?>" alt="">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="substance">
          <h1 class="fs-2 fw-bold mb-4">Download your shows to watch offline</h1>
          <p class="fs-6 fw-medium">Save your favorites easily and always have something to watch.</p>
        </div>
      </div>
    </div>
  </div>

  <div class="divider"></div>

  <div class="jumbotron">
    <div class="container m-auto">
      <div class="side-by-side">
        <div class="substance">
          <h1 class="fs-2 fw-bold mb-4">Watch everywhere</h1>
          <p class="fs-6 fw-medium">Stream unlimited movies and TV shows on your phone, tablet, laptop, and TV.</p>
        </div>
        <div class="substance">
          <div class="frame">
            <img alt="" src="<?= base_url("assets/img/frame/device-pile-id.png") ?>">
            <div class="motion" style="top: 10%; left: 18%; width: 62%;">
              <video autoplay="" playsinline="" muted="" loop="">
                <source src="<?= base_url("assets/video/motion/video-devices-id.m4v") ?>" type="video/mp4">
              </video>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="divider"></div>

  <div class="jumbotron">
    <div class="container m-auto">
      <div class="side-by-side">
        <div class="substance">
          <div class="frame">
            <img alt="" src="<?= base_url("assets/img/frame/AAAABejKYujIIDQciqmGJJ8BtXkYKKTi5jiqexltvN1YmvXYIfX8B9CYwooUSIzOKneblRFthZAFsYLMgKMyNfeHwk16DmEkpIIcb6A3.png") ?>">
            <div class="motion"></div>
          </div>
        </div>
        <div class="substance">
          <h1 class="fs-2 fw-bold mb-4">Create profiles for kids</h1>
          <p class="fs-6 fw-medium">Send kids on adventures with their favorite characters in a space made just for them—free with your membership.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  <?php if ($this->session->flashdata('message_validation_error')) : ?>
    toastr.error('<?= $this->session->flashdata("message_validation_error") ?>');
  <?php endif; ?>
  <?php if ($this->session->flashdata('message_login_error')) : ?>
    toastr.error('<?= $this->session->flashdata("message_login_error") ?>');
  <?php endif; ?>
  <?php if ($this->session->flashdata('message_login_success')) : ?>
    toastr.success('<?= $this->session->flashdata("message_login_success") ?>');
  <?php endif; ?>
</script>
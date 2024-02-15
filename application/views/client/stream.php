<?php
$url_parts = parse_url(current_url());
$current_domain = str_replace('www.', '', $url_parts['host']);

$film = $content["stream"]["data"];
?>

<section id="movie_stream">
  <div class="explore">
    <div class="overlay"></div>
    <div class="container m-auto">
      <div class="content">
        <div class="substance-header">
          <div class="alert">
            <h4>
              <span class="fw-bold p-2 bg-danger rounded me-2">ATTENTION !!</span>
              DOMAIN TERBARU <?= strtoupper(SITE_NAME) ?> <a href="<?= base_url() ?>"><?= $current_domain ?></a>,
              KOMPLAIN SUBTITLE TIDAK COCOK ATAU FILM ERROR MELALUI DISCORD AGAR DI PROSES !!!!
              JOIN GROUP <a href="<?= base_url() ?>">TELEGRAM <?= strtoupper(SITE_NAME) ?></a> UNTUK MENGGUNAKAN BOT PENCARIAN <?= strtoupper(SITE_NAME) ?>.
            </h4>
          </div>
        </div>
        <div class="substance-body">
          <div class="substance-title">
            <h1 class="fs-4 fw-bold">
              <?= formatColumnName($context) . " | " . $film->title ?>
              (<span class="moment-parse" data-moment-time="<?= $film->release_year ?>" data-moment-format="YYYY">N/A</span>)
            </h1>
            <div class="badge text-bg-warning p-2 mt-3">
              <p class="mb-0">
                <i class="fas fa-star"></i>
                <?= $film->rating ?>
              </p>
            </div>
          </div>
          <div>
            <div>
              <?= $film->video_embed ?>
            </div>
            <div class="info mt-5 fs-6 fw-light">
              <h3 class="fs-4 mb-4">Description</h3>
              <small style="color: darkgray"><?= $film->description ?></small>
            </div>
            <div class="info mt-5 fs-6 fw-light">
              <h3 class="fs-4 mb-4">Actor</h3>
              <div class="d-flex flex-wrap align-items-center" style="gap: 1rem;">
                <?php
                if ($film->cast) :
                  foreach (json_decode($film->cast) as $key => $value) { ?>
                    <div class="entry">
                      <a href="#">
                        <span>
                          <small style="color: darkgray"><?= $value ?></small>
                        </span>
                      </a>
                    </div>
                <?php
                  }
                endif; ?>
              </div>
            </div>
          </div>
          <div class="text-center mt-5">
            <button class="btn-discover">Discover More</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="divider"></div>

</section>

<script>
  $(document).ready(function() {
    resizeIframe("iframe", {
      "width": "100%",
      "height": "480px",
      "max-height": "70vw"
    }, {
      "width": "100%"
    });
  })

  function resizeIframe(selector, css, attr) {
    const iframe = $(selector);

    iframe.attr(attr);
    iframe.css(css);
  }
</script>
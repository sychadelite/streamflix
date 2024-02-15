<?php
$url_parts = parse_url(current_url());
$current_domain = str_replace('www.', '', $url_parts['host']);
?>

<section id="tv_series_most_watched">
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
              List of <?= formatColumnName($context) ?>
            </h1>
          </div>
          <div class="d-flex flex-wrap align-items-center" style="gap: 1rem;">
            <?php foreach ($content[$context]["data"] as $index => $row) { ?>
              <div class="entry">
                <a href="#">
                  <span><?= formatColumnName($row->YYYY) ?></span>
                </a>
              </div>
            <?php } ?>
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
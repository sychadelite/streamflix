<?php
$url_parts = parse_url(current_url());
$current_domain = str_replace('www.', '', $url_parts['host']);
?>

<section id="movie_most_watched">
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
              New Release
            </h1>
          </div>
          <div class="substance-movie-list">
            <?php foreach ($content["most_watched"]["data"] as $index => $row) { ?>
              <a href="<?= base_url("client/movie/stream/" . $row->content_id) ?>">
                <div class="movie-card">
                  <div class="cover">
                    <img src="<?= base_url($row->cover_image) ?>" alt="" />
                    <div class="detail">
                      <div class="play-icon">
                        <i class="far fa-play-circle"></i>
                      </div>
                      <div class="chip">
                        <p>
                          <i class="fas fa-star"></i>
                          <?= $row->rating ?>
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="info">
                    <small><span class="moment-parse" data-moment-time="<?= $row->release_year ?>">N/A</span> / <?= formatColumnName($row->content_type) ?></small>
                    <h3 class="fs-5 fw-semibold text-truncate"><?= $row->title ?></h3>
                  </div>
                </div>
              </a>
            <?php } ?>
          </div>
          <div>
            <?= $content["most_watched"]["links"] ?>
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
    $(".moment-parse").each(function() {
      const el = $(this);
      const time = el.data("moment-time");
      if (time) {
        const parsed_time = moment(time).format('YYYY');
        el.text(parsed_time);
      }
    })
  })
</script>
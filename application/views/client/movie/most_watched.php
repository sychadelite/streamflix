<section id="movie_most_watched">
  <div class="explore">
    <div class="overlay"></div>
    <div class="container m-auto">
      <div class="content">
        <div class="mt-4">
          <h1 class="fs-1 fw-bold">New Release</h1>
        </div>
        <div class="d-flex flex-wrap justify-content-between my-4" style="gap: 2rem;">
          <?php foreach ($content["most_watched"]["data"] as $index => $row) { ?>
            <div class="movie-card">
              <div class="position-relative">
                <div class="cover">
                  <img src="<?= base_url($row->cover_image) ?>" alt="">
                </div>
                <div class="detail">
                  <div class="chip">
                    <p>7.5</p>
                  </div>
                </div>
              </div>
              <div class="info">
                <small><?= $row->release_year . "/" . $row->content_type ?></small>
                <h3 class="fs-3 fw-semibold"><?= $row->title ?></h3>
              </div>
            </div>
          <?php } ?>
        </div>
        <div class="text-center mt-5">
          <button class="btn-discover">Discover More</button>
        </div>
      </div>
    </div>
  </div>

  <div class="divider"></div>

</section>
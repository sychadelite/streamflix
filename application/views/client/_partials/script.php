<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars/js/OverlayScrollbars.min.js"></script>

<?php foreach ($parts as $part) { ?>
  <script src="<?= base_url("assets/js/_partials/client." . $part . ".js"); ?>"></script>
<?php } ?>

<script src="<?= base_url("assets/js/overlay-scroller.js"); ?>"></script>

<script src="<?= base_url("assets/js/client.js"); ?>"></script>

<script src="<?= base_url("assets/js/script.js"); ?>"></script>
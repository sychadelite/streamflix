<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo SITE_NAME . " | " . ucwords($context) ?></title>

<link rel="icon" type="image/x-icon" href="<?= base_url("assets/icons/logo.svg") ?>">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars/css/OverlayScrollbars.min.css">

<link rel="stylesheet" href="<?= base_url("assets/vendor/AdminLTE/plugins/fontawesome-free/css/all.min.css"); ?>">

<link rel="stylesheet" href="<?= base_url("assets/vendor/AdminLTE/plugins/toastr/toastr.min.css"); ?>">

<link rel="stylesheet" href="<?= base_url("assets/css/_pages/client." . $context . ".main.css"); ?>">

<?php foreach ($parts as $part) { ?>
  <link rel="stylesheet" href="<?= base_url("assets/css/_partials/client." . $part . ".css"); ?>">
<?php } ?>

<link rel="stylesheet" href="<?= base_url("assets/css/client.main.css"); ?>">

<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.2/anime.min.js" integrity="sha512-aNMyYYxdIxIaot0Y1/PLuEu3eipGCmsEUBrUq+7aVyPGMFH8z0eTP0tkqAvv34fzN6z+201d3T8HPb1svWSKHQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="<?= base_url("assets/vendor/AdminLTE/plugins/jquery/jquery.min.js"); ?>"></script>

<script src="<?= base_url("assets/vendor/AdminLTE/plugins/moment/moment.min.js"); ?>"></script>

<script src="<?= base_url("assets/vendor/AdminLTE/plugins/toastr/toastr.min.js"); ?>"></script>
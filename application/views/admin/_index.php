<?php
  $data = [
    "layout" => $layout,
    "page" => $page,
    "content" => $content,
    "context" => $context,
    "path_prefix" => $path_prefix,
    "auth" => $auth ?? NULL,
    "class" => $class ?? NULL
  ];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php $this->load->view("admin/_partials/head") ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed <?= $data["class"]["body"] ?? '' ?>">

  <div class="wrapper">

    <?php $this->load->view("admin/_partials/navbar") ?>

    <?php $this->load->view("admin/_partials/sidebar", $data) ?>

    <div class="content-wrapper">

      <?php $this->load->view("admin/_partials/breadcrumb") ?>

      <?php $this->load->view($page) ?>

    </div>

    <aside class="control-sidebar control-sidebar-dark"></aside>

    <?php $this->load->view("admin/_partials/footer") ?>
  </div>

  <?php $this->load->view("admin/_partials/modal", $data) ?>
  <?php $this->load->view("admin/_partials/script") ?>
</body>

</html>
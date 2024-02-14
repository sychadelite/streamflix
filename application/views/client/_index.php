<?php
$data = [
  "layout" => $layout,
  "page" => $page,
  "parts" => $parts,
  "config" => $config ?? NULL,
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
  <?php $this->load->view("client/_partials/head", $data) ?>
</head>

<body class="" data-overlayscrollbars-initialize>


  <?php
  if (in_array("navbar", $parts)) {
    $this->load->view("client/_partials/navbar");
  }
  ?>

  <div class="wrapper">

    <?php $this->load->view($page) ?>

  </div>


  <?php
  if (in_array("footer", $parts)) {
    $this->load->view("client/_partials/footer");
  }
  ?>

</body>

<?php $this->load->view("client/_partials/script.php", $data) ?>

</html>
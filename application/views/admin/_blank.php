<?php
  $data = [
    "layout" => $layout,
    "page" => $page,
    "content" => $content,
    "auth" => $auth ?? NULL,
    "class" => $class ?? NULL
  ];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php $this->load->view("admin/_partials/head") ?>
</head>

<body class="hold-transition <?= $data["class"]["body"] ?? '' ?>">

  <?php $this->load->view($page) ?>

  <?php $this->load->view("admin/_partials/script") ?>
</body>

</html>
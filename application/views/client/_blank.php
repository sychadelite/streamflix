<?php
$data = [
  "layout" => $layout,
  "page" => $page,
  "parts" => $parts,
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

<body>

  <?php $this->load->view($page) ?>

</body>

<?php $this->load->view("client/_partials/script.php", $data) ?>

</html>
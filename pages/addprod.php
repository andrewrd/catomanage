<?php
//Includes the application header
include '../layouts/header.php';
//Allows function calls and connection database
include '../controller/controller.php';
?>

<div class=container>
  <?php add_prod(); ?>
</div>

<?php
//includes the footer of the application
include '../layouts/footer.php';
?>

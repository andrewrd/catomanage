<?php
//Includes the application header
include '../layouts/header.php';
//Allows function calls and connection database
include '../controller/controller.php';

?>
<div class="container">
  <div class="col-xs-4">
  </div>
  <div class="col-xs-8">
    <?php test(); ?>
  </div>
</div>
<?php
//includes the footer of the application
include '../layouts/footer.php';
?>

<?php
//Includes the application header
include '../layouts/header.php';
//Allows function calls and connection database
include '../controller/controller.php';

?>
<div class="container">
  <div class="col-xs-4">
    <h3>Catalogue Menu</h3>
    <?php get_categories($dbo); ?>
  </div>
  <div class="col-xs-8">
    <h1><?php get_category_name($dbo); ?></h1>
  </div>
</div>
<?php
//includes the footer of the application
include '../layouts/footer.php';
?>

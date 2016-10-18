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
    <?php if (isset($_GET['id'])){
  		$parent_id = $_GET['id'];
  	} else {
  		$parent_id = 1;
  	}
    echo get_bread_crumb($dbo, $parent_id);
    ?>
    <?php get_category_products($dbo); ?>
  </div>
</div>
<?php
//includes the footer of the application
include '../layouts/footer.php';
?>

<?php
//Includes the application header
include '../layouts/header.php';
//Allows function calls and connection database
include '../controller/controller.php';
?>

<div class = "container">
      <?php displayproduct($dbo);?>
      <!--Form-->
      <form class = "form-inline" method = "post" action = "addToCart.php" name = "addtocart" onsubmit="return validateAddToCart()">
        <div class="form-group">
          <?php displayproductattributes($dbo); ?>
        </div>
      </form>
      <br/>

  <div class = "row">
    <h3>Other Recommended Products</h3>
    <div class = "col-md-4 recommendation">
      <h3>Product 1</h3>
    </div>
    <div class = "col-md-4 recommendation">
      <h3>Product 2</h3>
    </div>
    <div class = "col-md-4 recommendation">
      <h3>Product 3</h3>
    </div>
  </div>
</div>
<script type = "text/javascript" src = "../js/validateAddToCart.js"></script>

<?php

//includes the footer of the application
include '../layouts/footer.php';
?>

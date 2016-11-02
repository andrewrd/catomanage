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
          <!--<label for="ex1">Qty:</label>
          <input class = "form-control" id = "quantity" type="number" name="quantity" value = "1" min="1" max="6">
          <br/><br/>-->
          <?php displayproductattributes($dbo); ?>
          <!--<br/><br/><button type="submit" class="btn btn-default btn-lg btn-success">Add To Cart</button>-->
        </div>
      </form>
      <br/>
      <a href = "ProductComparison.php">Compare this product</a>
    </div>
  </div>
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


<?php
 /*includes the database */
include '../database/model.php';
$dbo = db_connect();

function try_or_die($statement){
  try {
    $statement->execute();
  }
  catch (PDOException $ex){
    echo $ex->getMessage();
    die("Invalid Query");
  }
  //standard attempt query or die code
}

function get_bread_crumb($dbo, $id){
  //this function generates the breadcrumb to place on the catalogue.php page
  //this is a recursive function, that fetches the parent_id of each subsequent level
  if ($id==1){
    //if the $id is the root category, stop recursion and return root link
    return "<a href='catalogue.php'>Home</a>";
  } else {

    $stmt = $dbo->prepare("SELECT cat_name FROM category WHERE cat_id = (:id)");
  	$stmt->bindParam(':id', $id);
    //this gets the current category name

    try_or_die($stmt);

    $results = $stmt->fetchColumn();
    //fetches the result
    $stmt = null;
    //frees statement variable
    $thislink = "<a href='catalogue.php?id=".$id."'>".$results."</a>";
    //templates the link to be appended to the full result


    $stmt = $dbo->prepare("SELECT cgryrel_id_parent FROM cgryrel WHERE cgryrel_id_child = (:id)");
    $stmt->bindParam(':id', $id);
    //gets the parent of the current category

    try_or_die($stmt);

    $results = $stmt->fetchColumn();
    //fetches the result of the query
    return get_bread_crumb($dbo, $results)." / ".$thislink;
    //calls the functions again, using the parent categories ID

    $stmt = null;
    //frees statement variable
  }


}

function get_category_products($dbo){
	//gets the category name, depending on $_get['id']
  //probably want to expand this function out to deal with getting the categories products,
  //and other category information
	if (isset($_GET['id'])){
		$parent_id = $_GET['id'];
	} else {
		$parent_id = 1;
	}
  //checks if get variable is set, if not, just get the root category

	$stmt = $dbo->prepare("SELECT cat_name FROM category WHERE cat_id = (:id)");
	$stmt->bindParam(':id', $parent_id);
  //selects the category name for the selected cat_id

	try_or_die($stmt);

	$results = $stmt->fetchColumn();
  //return the result
 	echo "<div class='col-xs-12'><h2>".$results."</h2></div>";
  //echo out the result
  //maybe change this to return?
  $stmt = null;
  //free statement

  $stmt = $dbo->prepare("SELECT product.prod_name FROM product INNER JOIN cgprrel ON product.prod_id = cgprrel.cgpr_prod_id WHERE cgprrel.cgpr_cat_id = (:id)");
  $stmt->bindParam(':id', $parent_id);

  try_or_die($stmt);

  while($row = $stmt->fetch()) {
    echo "<p>".$row["prod_name"]."</p>";
  }
	$stmt = null;
}

function get_categories($dbo){
	if (isset($_GET['id'])){
		$parent_id = $_GET['id'];
	} else {
		$parent_id = 1;
	}

	$stmt = $dbo->prepare("SELECT cgryrel.cgryrel_id_child, category.cat_name FROM cgryrel INNER JOIN category ON cgryrel.cgryrel_id_child=category.cat_id WHERE cgryrel.cgryrel_id_parent = (:id)");
	$stmt->bindParam(':id', $parent_id);

	try_or_die($stmt);

	while($row = $stmt->fetch()) { ?>
		<p><a href=<?php echo "catalogue.php?id=".$row[0];?>><?php echo $row[1]; ?></a></p>
	<?php	}
	$stmt = null;
}

function check_user_permission_level() {
  //placeholder function for the role based access control functionality
  return;
}

function add_prod($dbo){
  check_user_permission_level();
  if (isset($_POST['prod_name'])) {
    //if info has been posted, add that info to the DB
    submit_product($dbo);
    submit_product_category($dbo);
    //test code for checking what categories have been selected

    /* need a function that unsets all the post variables to prevent accidental
    resubmission - this is an example of that */
    unset($_POST['prod_name']);
    unset($_POST['cat']);

    //echos out a link for testing purposes only DO NOT LEAVE THIS IN
    echo "<a href='addprod.php'>Add another product</a>";
  }
  else {
    //if info hasnt been added, show the form to add new info
    include '../layouts/addprodform.php';
  }
}

function submit_product($dbo){
  //prepare statement to insert the basic product details into the product table

  //should break these statements up into smaller pieces
  $stmt = $dbo->prepare("INSERT INTO product(prod_name, prod_desc, prod_img_url, prod_long_desc, prod_sku, prod_disp_cmd, prod_weight, prod_l, prod_w, prod_h) VALUES(:prod_name, :prod_desc, :prod_img_url, :prod_long_desc, :prod_sku, :prod_disp_cmd, :prod_weight, :prod_l, :prod_w, :prod_h)");
  /*bind variables, using post variables without any
  validation, which still needs to be done */
	$stmt->bindParam(':prod_name', $_POST['prod_name']);
  $stmt->bindParam(':prod_desc', $_POST['prod_desc']);
  $stmt->bindParam(':prod_img_url', $_POST['prod_img_url']);
  $stmt->bindParam(':prod_long_desc', $_POST['prod_long_desc']);
  $stmt->bindParam(':prod_sku', $_POST['prod_sku']);
  $stmt->bindParam(':prod_disp_cmd', $_POST['prod_disp_cmd']);
  $stmt->bindParam(':prod_weight', $_POST['prod_weight']);
  $stmt->bindParam(':prod_l', $_POST['prod_l']);
  $stmt->bindParam(':prod_w', $_POST['prod_w']);
  $stmt->bindParam(':prod_h', $_POST['prod_h']);

	try_or_die($stmt);

  $stmt = null;
}

function submit_product_category($dbo) {
  //need to get the prod_id of the new product
  $stmt = $dbo->prepare("SELECT prod_id FROM product WHERE prod_name = (:prod_name)");
  $stmt->bindParam(':prod_name', $_POST['prod_name']);

  try_or_die($stmt);

  $prod_id = $stmt->fetchColumn();
  echo $prod_id;
  $cats = $_POST['cat'];
  foreach($cats as $cat){
    echo $cat;
    $stmt = $dbo->prepare("INSERT INTO cgprrel(cgpr_cat_id, cgpr_prod_id) VALUES(:cat_id, :prod_id)");
    $stmt->bindParam(':cat_id', $cat);
    $stmt->bindParam(':prod_id', $prod_id);

    try_or_die($stmt);
  }
}

function get_all_categories($dbo){
  /* function that gets all of the categories and their ids for using in adding
  producs to categories - we might also want to filter out the root category
  as an option */
  $stmt = $dbo->prepare("SELECT cat_id, cat_name FROM category");
  $stmt->bindParam(':id', $parent_id);
  try_or_die($stmt);

  //outputs the html for category selection, with a checkbox for each possible category
  while($row = $stmt->fetch()) { ?>
		<div class="checkbox">
      <label>
        <input type="checkbox" name="cat[]" value="<?php echo $row['cat_id']; ?>">
        <?php echo $row['cat_name']; ?>
      </label>
    </div>
	<?php
  }

  $stmt = null;
}

function displayproduct($dbo) {
  $prod_id = $_GET['prod_id'];
  //$prod_id = 7;
  $shopper_group = 1;
  $stmt = $dbo->prepare("SELECT PROD_NAME, PROD_IMG_URL, PROD_LONG_DESC, PRPR_PRICE FROM Product, ProdPrices where PROD_ID = (:id)
  and Product.prod_id = ProdPrices.prpr_prod_id and prpr_shopgrp = (:shgroup) group by prod_id");
  //I had to use a group by because duplicates were being returned
  $stmt->bindParam(':id', $prod_id);
  $stmt->bindParam(':shgroup', $shopper_group);
  try_or_die($stmt);

  while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
    <div class = "row">
      <div class = "col-md-6">
        <img src = "../img/<?php echo $row['PROD_IMG_URL'] ?>"/>
      </div>
    <div class = "col-md-6">
    <h1><?php echo $row['PROD_NAME']?></h1>
    <i class="fa fa-star" aria-hidden="true"></i>
    <i class="fa fa-star" aria-hidden="true"></i>
    <i class="fa fa-star" aria-hidden="true"></i>
    <i class="fa fa-star" aria-hidden="true"></i>
    <i class="fa fa-star-half-o" aria-hidden="true"></i>
    <a href = "#">See Reviews</a>
    <h3 class = "price"><?php echo $row['PRPR_PRICE'] ?></h3>
    <p class = "lead"><?php echo $row['PROD_LONG_DESC'] ?></p>
  <?php
  }
  $stmt = null;
}

function displayproductattributes($dbo) {
  $prod_id = $_GET['prod_id'];
  //$prod_id = 7;
  $stmt = $dbo->prepare("SELECT ID, PRODUCT_PROD_ID, NAME FROM ATTRIBUTE WHERE PRODUCT_PROD_ID = (:id)");
  //I had to use a group by because duplicates were being returned
  $stmt->bindParam(':id', $prod_id);
  try_or_die($stmt);
  $attribute_ids = array(); //create an array to store ID's of attributes

  while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
    <?php $attribute_ids[] = $row['ID']; ?>
  <?php
  }
  $stmt = null;
  /* 2nd query */
  $arrlength = count($attribute_ids);
  for ($i = 0; $i < $arrlength; ++$i) {
    $stmt = $dbo->prepare("SELECT ATTRVAL_VALUE FROM ATTRIBUTEVALUE WHERE ATTRVAL_ATTR_ID = (:attrID)");
    $stmt->bindParam(':attrID', $attribute_ids[$i]);
    try_or_die($stmt);
    ?> <select class = "form-control"> <?php
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
        <option><?php echo $row['ATTRVAL_VALUE'] ?> </option>
    <?php }
    ?> </select>
    <?php }
  }
  $stmt = null;
?>

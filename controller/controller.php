
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

    $stmt = $dbo->prepare("SELECT product.prod_id FROM product INNER JOIN cgprrel ON product.prod_id = cgprrel.cgpr_prod_id WHERE cgprrel.cgpr_cat_id = (:id)");
    $stmt->bindParam(':id', $parent_id);

    try_or_die($stmt);

    $product_ids = array();
    while($row = $stmt->fetch()) {
      $id = $row[0];
      $stmt2 = $dbo->prepare("SELECT prod_id, prod_name, prod_desc, prod_img_url, prod_disp_cmd, prpr_price FROM product, prodprices
      where prod_id = (:prodid) and product.prod_id = prodprices.prpr_prod_id group by prod_id");
      $stmt2->bindParam(':prodid', $id);
      try_or_die($stmt2);

      while($row2 = $stmt2->fetch()) { ?>
          <a href = "<?php echo $row2[4]."?prod_id=".$row2[0]?>">
            <div class=  "col-md-4 productBox">
              <img src = "../img/<?php echo $row2[3] ?>" width = "250" height = "250"/><br/>
              <h3 class = "productname"><?php echo $row2[1] ?></h3>
              <h3 class = "price"><?php echo $row2[5] ?></h3>
              <p class = "desc"><?php echo $row2[2] ?></p>
            </div>
          </a>
      <?php }
      echo 'complete';
      $stmt2 =null;
      $row2=null;
    }

    /*
    $stmt = null;
    $arrlength = count($product_ids);

    for ($i = 0; $i < $arrlength; ++$i) {
      $id = $product_ids[$i];
      $stmt = $dbo->prepare("SELECT prod_id, prod_name, prod_desc, prod_img_url, prod_disp_cmd, prpr_price FROM product, prodprices
      where prod_id = (:prodid) and product.prod_id = prodprices.prpr_prod_id group by prod_id");
      $stmt->bindParam(':prodid', $id);
      try_or_die($stmt);

    while($row = $stmt->fetch()) { ?>
        <a href = "<?php echo $row[4]."?prod_id=".$row[0]?>">
          <div class=  "col-md-4 productBox">
            <img src = "../img/<?php echo $row[3] ?>" width = "250" height = "250"/><br/>
            <h3 class = "productname"><?php echo $row[1] ?></h3>
            <h3 class = "price"><?php echo $row[5] ?></h3>
            <p class = "desc"><?php echo $row[2] ?></p>
          </div>
        </a>
    <?php }
  } */
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

function add_cat($dbo){
    include '../layouts/addcatform.php';
}

/*Validation and sanitisation helper functions start here*/

//Sanitisation: Function to sanitise input strings from the internet
function sanitise_string($input){
    $input = trim($input);
    $input = stripslashes($input);
    $input = strip_tags($input);
    $input = htmlspecialchars($input);
    $input = filter_var($input, FILTER_SANITIZE_STRING);
    return $input;
}

function sanitise_number($input){
    $input = trim($input);
    $input = stripslashes($input);
    $input = strip_tags($input);
    $input = htmlspecialchars($input);
    $input = filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    return $input;
}

//Validation: checks whether a string matches letters, numbers, dashes or spaces.
function isAlphanumeric($input){
    if(!preg_match("/^[\w\-\s]+$/", $input)){
        return false;
    }
    return true;
}

function isEmpty($input){
    if(strlen($input)<=0){
        return true;
    }
    return false;
}

function isArrayEmpty($input){
    if(empty($input)){
        return true;
    }
    return false;
}

function isValidLength($input, $maxLen){
    if(strlen($input)>$maxLen){
        return false;
    }
    return true;
}

function isNumber($input){
    if(!preg_match("/^(?=.)([+-]?([0-9]*)(\.([0-9]+))?)$/", $input) ){
        return false;
    }
    return true;
}

function isSKU($input){
    if(!preg_match("([A-Za-z0-9\-\_]+$)", $input)){
        return false;
    }
    return true;
}

//Function that validates the add product from
function validateProd(){

    //set validated to true, all other checks upon fail will set it to false.
    //If none of the attributes pass then
    $validated = true;

    $prod_name = $cat = $prod_desc = $prod_img_url = $prod_long_desc = $prod_sku = $prod_display_cmd = 0;
    $prod_weight = $prod_l = $prod_w = $prod_h = 0;


    if(isset($_POST['prod_name'])){
        $prod_name = sanitise_string($_POST['prod_name']);
        $prod_name_error = "";
        if(isEmpty($prod_name)){
            $prod_name_error = "The product name cannot be empty" ;

            $validated = false;
        }

        if(!isValidLength($prod_name, 40)){
            $prod_name_error = "<br>The product name that you entered was either too short or too long(max " . 40 . " characters )";
            $validated = false;
        }
        if(!isAlphanumeric($prod_name)){
            $prod_name_error .= "<br>The product name that you entered included characters that weren't alphanumeric or spaces";
            $validated = false;
        }
        $_POST['prod_name_error'] = "<span class='errorMessage'>". $prod_name_error."</span>";
    }
    if(isset($_POST['prod_desc'])){
        $prod_desc = sanitise_string($_POST['prod_desc']);
        $prod_desc_error = "";
        if(isEmpty($prod_desc)){
            $prod_desc_error = "The product description cannot be empty";
            $validated = false;
        }
        if(!isValidLength($prod_desc, 128)){
            $prod_desc_error = "The product description that you entered was either too short or too long(max " . 128 . " characters )";
            $validated = false;
        }
        if(!isAlphanumeric($prod_desc)){
            $prod_desc_error = "The product name that you entered included characters that weren't alphanumeric or spaces";
            $validated = false;
        }
        $_POST['prod_desc_error'] = "<span class='errorMessage'>". $prod_desc_error . "</span>";
    }

    if(isset($_POST['prod_long_desc'])){
        $prod_long_desc = sanitise_string($_POST['prod_long_desc']);
        $prod_long_desc_error = "";
        if(isEmpty($prod_long_desc)){
            $prod_long_desc_error = "The product long description cannot be empty";
            $validated = false;
        }
        if(!isValidLength($prod_long_desc, 128)){
            $prod_long_desc_error = "The product description that you entered was either too short or too long(max " . 128 . " characters )";
            $validated = false;
        }
        if(!isAlphanumeric($prod_long_desc)){
            $prod_long_desc_error = "The product name that you entered included characters that weren't alphanumeric or spaces";
            $validated = false;
        }
        $_POST['prod_long_desc_error'] = "<span class='errorMessage'>" . $prod_long_desc_error ."</span>";
    }

    if(isset($_POST['cat'])){
        $cat = $_POST['cat'];
        $cat_error = "";

        if(empty($cat)){
            $validated = false;
            $cat_error = "You must select a category to add a product into.";
        }

        $_POST['cat_error'] = $cat_error;
    } else{
        $cat_error = "";
        $cat_error = "You must select a category to add a product into.";
        $_POST['cat_error'] = "<span class='errorMessage'>" . $cat_error . "</p>";
    }

    //Packaging validation
    if(isset($_POST['prod_l'])){
        $prod_l_error = "";
        $prod_l = sanitise_number($_POST['prod_l']);
        if(!isNumber($prod_l)){
            $validated = false;
            $prod_l_error = "This field must only contain numbers or decimals";
        }
        $_POST['prod_l_error'] ="<span class='errorMessage'>". $prod_l_error."</p>";
    }
    if(isset($_POST['prod_w'])){
        $prod_w_error = "";
        $prod_w = sanitise_number($_POST['prod_w']);
        if(!isNumber($prod_w)){
            $validated = false;
            $prod_w_error = "This field must only contain numbers or decimals";
        }
        $_POST['prod_w_error'] = "<span class='errorMessage'>".$prod_w_error."</p>";
    }
    if(isset($_POST['prod_h'])){
        $prod_h_error = "";
        $prod_h = sanitise_number($_POST['prod_h']);
        if(!isNumber($prod_h)){
            $validated = false;
            $prod_h_error = "This field must only contain numbers or decimals";
        }
        $_POST['prod_h_error'] = "<span class='errorMessage'>".$prod_h_error."</p>";
    }

    if(isset($_POST['prod_weight'])){
        $prod_weight_error = "";
        $prod_weight = sanitise_number($_POST['prod_weight']);
        if(!isNumber($prod_weight)){
            $validated = false;
            $prod_weight_error = "This field must only contain numbers or decimals";
        }
        $_POST['prod_weight_error'] = "<span class='errorMessage'>".$prod_weight_error."</p>";
    }


    if(isset($_POST['prod_sku'])){
        $prod_sku_error = "";
        $prod_sku = sanitise_string($_POST['prod_sku']);
        if(!isSKU($prod_sku)){
            $validated = false;
            $prod_sku_error = "The SKU you entered included characters that weren't alphanumeric";
        }
        $_POST['prod_sku_error'] = "<span class='errorMessage'>".$prod_sku_error."</p>";
    }

    if(isset($_POST['json'])){
        //Validate the attributes
        if(count(json_decode($_POST['json']))==0){
            //Products don't have to have attribute values, so do nothing.
        }
        //If there are attributes added
        else if(count(json_decode($_POST['json']))>0) {
            $prod_attr_error = "";
            $json = json_decode($_POST['json']);

            foreach(get_object_vars($json) as $property=>$value) {

                //Attribute name
                if(sanitise_string($property)=="_empty_"){
                    $prod_attr_error="You have to enter a attribute name";
                    $validated = false;
                }

                else{
                    for($i = 0; $i < sizeOf($value); $i++){


                        $valueAttr = sanitise_string($value[$i]->Value);
                        $price = sanitise_number($value[$i]->Price);


                        if(isEmpty($valueAttr)){
                            $validated = false;
                            $prod_attr_error .= "Product attribute value cannot be empty";
                        }

                        if(isEmpty($price)){
                            $validated = false;
                            $prod_attr_error .= "Product Price cannot be empty";

                        }

                        else if(!isNumber($price)){
                            $validated = false;
                            $prod_attr_error .="Product Price must be a number";
                        }
                    }
                }
                $_POST['prod_price_error'] = "<span class='errorMessage'>".$prod_attr_error. "</p>";
            }
        }
    }

    if(isset($_POST['prod_prices'])){
        $prod_prices_error = "";
        $json_input = json_decode($_POST['prod_prices']);

        if(count($json_input)==0){
            $prod_prices_error = "You have to enter at least one product price and shopper group for this product";
        }
        else if(count($json_input)>0){
            for($i = 0; $i < sizeOf($json_input); $i++){
                $price = sanitise_number($json_input[$i]->Price);
                $shopGrp = sanitise_string($json_input[$i]->Group);

                if(isEmpty($shopGrp)){
                    $prod_prices_error .= "A shopper group was not selected, please select a shopper group";
                }

                if(isEmpty($price)){
                    $prod_prices_error .= "A product price was not entered. Please enter a product price.";
                }
                if(!isNumber($price)){
                    $prod_prices_error .= "Sorry only numbers can be entered into this field";
                }
            }
        }

        $_POST['product_shopGrp_error'] = "<span class='errorMessage'>".$prod_prices_error."</p>";
    }


    return $validated;
}

//Function that unsets all post variables that were set from the form on the addprod.php page
function unsetProdForm(){
    unset($_POST['prod_name']);
    unset($_POST['prod_desc']);
    unset($_POST['prod_long_desc']);
    unset($_POST['cat']);
    unset($_POST['prod_l']);
    unset($_POST['prod_h']);
    unset($_POST['prod_w']);
    unset($_POST['prod_weight']);
    unset($_POST['prod_sku']);
    unset($_POST['prod_json']);
    unset($_POST['prod_prices']);
}

//Function to unset all the error messages that were set within validateProd() above
function unsetProdFormErrors(){
    if(isset($_POST['prod_name_error'])){
        unset($_POST['prod_name_error']);
    }

    if(isset($_POST['prod_desc_error'])){
        unset($_POST['prod_desc_error']);

    }

    if(isset($_POST['prod_long_desc_error'])){
        unset($_POST['prod_long_desc_error']);

    }
    if(isset($_POST['cat_error'])){
        unset($_POST['cat_error']);

    }
    if(isset($_POST['prod_l_error'])){
        unset($_POST['prod_l_error']);

    }
    if(isset($_POST['prod_w_error'])){
        unset($_POST['prod_w_error']);

    }
    if(isset($_POST['prod_h_error'])){
        unset($_POST['prod_h_error']);

    }
    if(isset($_POST['prod_weight_error'])){
        unset($_POST['prod_weight_error']);
    }

    if(isset($_POST['prod_sku_error'])){
        unset($_POST['prod_sku_error']);

    }
    if(isset($_POST['prod_price_error'])){
        unset($_POST['prod_price_error']);

    }
    if(isset($_POST['prod_shpGrp_error'])){
        unset($_POST['prod_shopGrp_error']);

    }
}

function add_prod($dbo){
    //function that handles adding a new product into the database
    check_user_permission_level();


    //Validate the form
    $validated = validateProd();

    //If the form passes the validation test
    if ($validated==true) {
        echo "validated is true<br>";
        echo $_POST['prod_name'];
        //add the form data info to the DB
        $prod_id = submit_product($dbo);
        submit_product_category($dbo, $prod_id);
        submit_product_attributes($dbo, $prod_id);
        insert_product_prices($dbo, $prod_id);

        //unset the post variables from the last form
        unsetProdForm();
        //unset the post error message variables from the last form
        unsetProdFormErrors();

        //echos out a link for testing purposes only DO NOT LEAVE THIS IN

        echo "<p>Product successfully added to the system.</p>";
        echo "<p><p><a href='addprod.php'>Add another product</a></p></p>";
    }
    else {
        $validated = false;
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

    //need to get the prod_id of the new product
    $stmt = $dbo->prepare("SELECT prod_id FROM product WHERE prod_name = (:prod_name)");
    $stmt->bindParam(':prod_name', $_POST['prod_name']);

    try_or_die($stmt);

    $prod_id = $stmt->fetchColumn();

    return $prod_id;
}

function submit_product_category($dbo, $prod_id) {
    //function to submit product-category relationship
    $cats = $_POST['cat'];
    foreach($cats as $cat){
        $stmt = $dbo->prepare("INSERT INTO cgprrel(cgpr_cat_id, cgpr_prod_id) VALUES(:cat_id, :prod_id)");
        $stmt->bindParam(':cat_id', $cat);
        $stmt->bindParam(':prod_id', $prod_id);

        try_or_die($stmt);
    }
}

function submit_product_attributes($dbo, $prod_id) {
    //this function submits the selected product attributes and their values

    //a php object is created out of the json string posted to the server
    $json = json_decode($_POST['json']);

    //prepares 3 statements for querying and selecting from the database
    $stmt = $dbo->prepare("INSERT INTO attribute(product_prod_id, name) VALUES(:prod_id, :name)");
    $getAttribId = $dbo->prepare("SELECT id FROM attribute WHERE product_prod_id = (:prod_id) AND name = (:name)");
    $insertAttributeValues = $dbo->prepare("INSERT INTO attributevalue(attrval_prod_id, attrval_attr_id, attrval_value, attrval_price) VALUES(:prod_id, :attr_id, :value, :price)");

    //loop through the properties and their values
    foreach(get_object_vars($json) as $property=>$value) {

        //binds the passed product id and pro
        $stmt->bindParam(':prod_id', $prod_id);
        $stmt->bindParam(':name', $property);

        //inserts the property to the proprty table
        try_or_die($stmt);

        $getAttribId->bindParam(':prod_id', $prod_id);
        $getAttribId->bindParam(':name', $property);

        //returns the id of the newly submitted property
        try_or_die($getAttribId);
        $attr_id = $getAttribId->fetchColumn();

        //for each object inside the properties arry
        for($i = 0; $i < sizeOf($value); $i++) {

          $insertAttributeValues->bindParam(':prod_id', $prod_id);
          $insertAttributeValues->bindParam(':attr_id', $attr_id);
          $insertAttributeValues->bindParam(':value', $value[$i]->Value);
          $insertAttributeValues->bindParam(':price', $value[$i]->Price);

          //inserts a row into property values, with links to the property and product
          try_or_die($insertAttributeValues);
        }
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
    $attribute_id_names = array();

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
        <?php $attribute_ids[] = $row['ID']; //contains ID's related to product
          $attribute_id_names[$row['ID']]=$row['NAME']; //contains the ID's and their associated names
        ?>
    <?php
    }

    $stmt = null;
    /* 2nd query */
    $arrlength = count($attribute_ids);
    for ($i = 0; $i < $arrlength; ++$i) {
        $stmt = $dbo->prepare("SELECT ATTRVAL_ID, ATTRVAL_VALUE, ATTRVAL_PRICE FROM ATTRIBUTEVALUE WHERE ATTRVAL_ATTR_ID = (:attrID)");
        $stmt->bindParam(':attrID', $attribute_ids[$i]);
        try_or_die($stmt);
        $attribute_name = $attribute_id_names[$attribute_ids[$i]]; //assigns attribute_name by looking up from associative array
        ?> <label for="<?php echo $attribute_name ?>"><?php echo $attribute_name ?></label>
        <select class = "form-control" name = "<?php echo $attribute_name ?>" onchange="window.location='displayprod.php?>
          <option value></option> <?php
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
            <option id = "<?php echo $row['ATTRVAL_ID']?>" value = "<?php echo $row['ATTRVAL_PRICE']?>"><?php echo $row['ATTRVAL_VALUE'] ?> </option>
        <?php }
        ?> </select>
        <?php }
    }
    $stmt = null;

function get_all_shopper_groups($dbo){
    /*function returns all the shopper groups in a list format,
    so the user can select from any of them*/
    $stmt = $dbo->prepare("SELECT shopgrp_name, shopgrp_id FROM shoppergroup");

    try_or_die($stmt);

    //for each row found, output in the following format
    while($row = $stmt->fetch()) { ?>
        <option value="<?php echo $row[1]?>"><?php echo $row[0]; ?></option>
	<?php	}
}

function insert_product_prices($dbo, $prod_id){
    //function that inserts the product prices for the different shopper groups

    $array = json_decode($_POST['prod_prices']);
    //passed a json array

    $insertStatement = $dbo->prepare("INSERT INTO prodprices(prpr_prod_id, prpr_shopgrp, prpr_price) VALUES(:prod_id, :shopgrp, :price)");
    //statement prepared

    for($i = 0; $i < sizeOf($array); $i++){
        //for each shopper group, price is inserted
        $insertStatement->bindParam(':prod_id', $prod_id);
        $insertStatement->bindParam(':shopgrp', $array[$i]->Group);
        $insertStatement->bindParam(':price', $array[$i]->Price);

        try_or_die($insertStatement);
    }
}

?>

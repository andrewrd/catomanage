
<?php
 /*includes the database */
include '../database/model.php';
include 'validateprodphp.php';
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
  //this function gets the products and category details of the selected category

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
 	echo "<div class='col-xs-12'><h2>".$results."</h2></div>";
  //echo out the result
  //maybe change this to return?
  $stmt = null;
  //free statement

  $stmt = $dbo->prepare("SELECT product.prod_id FROM product INNER JOIN cgprrel ON product.prod_id = cgprrel.cgpr_prod_id WHERE cgprrel.cgpr_cat_id = (:id)");
  $stmt->bindParam(':id', $parent_id);

  try_or_die($stmt);
  //statement gathers the list of products associated to the category

  $product_ids = array();
  while($row = $stmt->fetch()) {
    //iterates through each product
    $id = $row[0];
    $stmt2 = $dbo->prepare("SELECT prod_id, prod_name, prod_desc, prod_img_url, prod_disp_cmd, prpr_price FROM product, prodprices where prod_id = (:prodid) and product.prod_id = prodprices.prpr_prod_id group by prod_id");
    $stmt2->bindParam(':prodid', $id);
    try_or_die($stmt2);
    //gathers product details for each of the products

    while($row2 = $stmt2->fetch()) { ?>
        <a href = "<?php echo $row2[4]."?prod_id=".$row2[0]?>">
          <div class=  "col-md-4 productBox">
            <img src = "../img/<?php echo $row2[3] ?>" width = "200" height = "200"/><br/>
            <h3 class = "productname"><?php echo $row2[1] ?></h3>
            <h3 class = "price">$<?php echo $row2[5] ?></h3>
            <p class = "desc"><?php echo $row2[2] ?></p>
            <a href = "editprod.php?prod_id=<?php echo $row2[0]?>">Edit</a> / <a href = "addspecial.php?prod_id=<?php echo $row2[0] ?>">Add Specials</a>
          </div>
        </a>
    <?php }
    //creates and outputs HTML for displaying each product

    $stmt2 =null;
    $row2=null;
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

//This function handles the adding of categories into the database.
function add_cat($dbo){


    //Validate the form using validate_cat(). Set it to false to allow it to display until this is completed.
    //$validated = validateCategory();
    $validated = validateCategory();

    if($validated==true){
    //If the form passes the validation test
   // if ($validated==true) {
        //add the form data info to the DB using submit category, then figure out if parents are needed
        //This adds the product to the database
        $cat_id = submit_category($dbo);
        //Submits the category parent
        submit_category_rel($dbo, $cat_id);

        //unset the post variables from the last form
        unsetCatForm();
        unsetCatFormErrors();

        //echos out a link for testing purposes only DO NOT LEAVE THIS IN

        echo "<p>Category has successfully been added to the system.</p>";
        echo "<p><p><a href='addcategory.php'>Add another category</a></p></p>";
     }
    else {
    //      $validated = false;
        //if info hasnt been added, show the form to add new info
        include '../layouts/addcatform.php';
    }
}

//Inserts a new category into the sql database
//Gets all posted categories from layouts/addcatform.php and adds them to the database.
function submit_category($dbo){
    //prepare statement to insert the basic category details into the product table
    //should break these statements up into smaller pieces
    $stmt = $dbo->prepare("INSERT INTO category(cat_name, cat_desc, cat_img_url, cat_disp_cmd) VALUES(:cat_name_title, :cat_desc, :cat_img_url, :cat_disp_cmd)");
    /*bind variables, using post variables without any
    validation, which still needs to be done */
    $stmt->bindParam(':cat_name_title', $_POST['cat_name_title']);
    $stmt->bindParam(':cat_desc', $_POST['cat_desc']);
    $stmt->bindParam(':cat_img_url', $_FILES['cat_img_url']['name']);
    $stmt->bindParam(':cat_disp_cmd', $_POST['cat_disp_cmd']);

    try_or_die($stmt);

    $stmt = null;

    //need to get the prod_id of the new product
    $stmt = $dbo->prepare("SELECT cat_id FROM category WHERE cat_name = (:cat_name_title)");
    $stmt->bindParam(':cat_name_title', $_POST['cat_name_title']);

    try_or_die($stmt);

    $cat_id = $stmt->fetchColumn();

    return $cat_id;
}

//Submits the category's parents and child relations.
function submit_category_rel($dbo, $cat_id){
    //redo this for the categories table, do I need to select child?
    $cats = $_POST['cat'];
    foreach($cats as $cat){
        $stmt = $dbo->prepare("INSERT INTO CGRYREL(CGRYREL_ID_PARENT, CGRYREL_ID_CHILD) VALUES(:id, :cat_id)");
        $stmt->bindParam(':cat_id', $cat_id);
        $stmt->bindParam(':id', $cat);

        try_or_die($stmt);
    }

    $stmt = null;

}

//Select category populates a dropdown menu, allowing the selection of categories.
function select_category($dbo) {


    //list categories using a function call to populate a dropdown
}

//This function is used in the view to retrieve category info for updating and displaying the past information
function get_cat_info($dbo, $cats){
    if($cats!=null){
        sanitise_number($cats);


        $stmt = $dbo->prepare("SELECT * FROM category WHERE cat_id = (:id)");
        $stmt->bindParam(':id', $cats);

        try_or_die($stmt);

        $row = $stmt->fetch();

        return $row;
    }
}

//This gets the relational information for the editcatoform
function get_parent_info($dbo, $post_id){
        sanitise_number($post_id);

        $stmt = $dbo->prepare("SELECT CGRYREL_ID_CHILD FROM CGRYREL WHERE CGRYREL_ID_PARENT = (:id)");
        $stmt->bindParam(':id', $post_id);

        try_or_die($stmt);

        $row = $stmt->fetch();

        if($row != null) {
            return true;
        }
    /*    for ($i=0; $i<count($row); $i++){
            if($row[$i]==$post_id){
                return true; 
            }
        } */

}
//This updates the category after displaying a dropdown to select using select_category()
function edit_category_form($dbo){

    //Validate the form using validate_cat(). Set it to false to allow it to display until this is completed.
    //$validated = validateCategory();
    $cats = $_POST['cats'];
    if($cats!=null){
        sanitise_number($cats);

        //need to get the prod_id of the new product
        $stmt = $dbo->prepare("SELECT * FROM category WHERE cat_id = (:id)");
        $stmt->bindParam(':id', $cats);

        try_or_die($stmt);

        $row = $stmt->fetch();
        include '../layouts/editcatupdater.php';
    }
    else {
        include '../layouts/editcatform.php';
    }


    $stmt = null;
/*    if($validated==true){
    //If the form passes the validation test
   // if ($validated==true) {
        //add the form data info to the DB using submit category, then figure out if parents are needed
        //This adds the product to the database
        $cat_id = submit_category($dbo);
        //Submits the category parent
        submit_category_rel($dbo, $cat_id);

        //unset the post variables from the last form
        unsetCatForm();
        unsetCatFormErrors();

        //echos out a link for testing purposes only DO NOT LEAVE THIS IN

        echo "<p>Category has successfully updated to the system.</p>";
        echo "<p><p><a href='editcat.php'>Update another category</a></p></p>";
     }
    else {
    //      $validated = false;
        //if info hasnt been added, show the form to add new info
        include '../layouts/editcatform.php';
    }
*/
}


//Function that unsets all post variables that were set from the form on the addcatform.php page
function unsetCatForm(){
    unset($_POST['cat_name_title']);
    unset($_POST['cat_desc']);
    unset($_POST['cat_disp_cmd']);
    unset($_POST['cat_img_url']);
    unset($_POST['cat_id']);
    unset($_POST['id']);
    unset($_POST['category_child_name']);
}

function unsetCatFormErrors(){
    if(isset($_POST['cat_name_error'])){
        unset($_POST['cat_name_error']);
    }
    if(isset($_POST['cat_desc_error'])){
        unset($_POST['cat_desc_error']);
    }
    if(isset($_POST['cat_error'])){
        unset($_POST['cat_error']);
    }
    if(isset($_POST['cat_img_error'])){
        unset($_POST['cat_img_error']);
    }
}

/*Validation and sanitisation helper functions start here*/

//Sanitisation: Function to sanitise input strings from the internet
function sanitise_string($input){
    $newinput = trim($input);
    $newinput = stripslashes($newinput);
    $newinput = strip_tags($newinput);
    $newinput = htmlspecialchars($newinput);
    $newinput = filter_var($newinput, FILTER_SANITIZE_STRING);
    return $newinput;
}

function sanitise_number($input){
    $newinput = trim($input);
    $newinput = stripslashes($newinput);
    $newinput = strip_tags($newinput);
    $newinput = htmlspecialchars($newinput);
    $newinput = filter_var($newinput, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    return $newinput;
}

//Validation: checks whether a string matches letters, numbers, dashes or spaces.
function isAlphanumeric($input){
    if(!preg_match("/^[\w\-\s&#;]+$/", $input)){
        return false;
    }
    return true;
}

//Function that uses
function isPHPFilename($input){
    if(!preg_match("/^[a-z0-9-]+\.php$/", $input)){
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
function validateCategory(){

    //set validated to true, all other checks upon fail will set it to false.
    //If none of the attributes pass then
    $validated = true;

    $cat_name = $cat_desc = "";


    //If the product name is set
    if(isset($_POST['cat_name_title'])){
        //set the variable to the sanitised version of the string
        $cat_name = sanitise_string($_POST['cat_name_title']);

        //Initialise the input error message
        $cat_name_error = "";

        //If the variable is empty
        if(isEmpty($cat_name)){
            //Set the error message
            $cat_name_error = "The product name cannot be empty" ;

            //Validation hasn't passed, validate is now false
            $validated = false;
        }

        //Check if the input length is equal to or less than what is allowed in the database
        if(!isValidLength($cat_name, 40)){
            //Set the error message
            $cat_name_error = "<br>The product name that you entered was either too short or too long(max " . 40 . " characters )";
            //Validation hasn't passed, validate is now false
            $validated = false;
        }
        //If the input contains characters other than letters, numbers, spaces or dashes
        if(!isAlphanumeric($cat_name)){
            //Set error message
            $cat_name_error .= "<br>The product name that you entered included characters that weren't alphanumeric or spaces";
            //Validation hasn't passed, validate is now false
            $validated = false;
        }
        //Post the error message back to the page
        $_POST['cat_name_error'] = "<span class='errorMessage'>". $cat_name_error."</span>";
    }
    //If the post variable isn't set, validation hasn't passed
    else if(!isset($_POST['cat_name_title'])){
        $validated = false;
    }

    //If the product short description is set
    if(isset($_POST['cat_desc'])){
        //set the variable to the sanitised version of the string
        $cat_desc = sanitise_string($_POST['cat_desc']);
        //Initialise the input error message
        $cat_desc_error = "";

        //If the variable is empty
        if(isEmpty($cat_desc)){
            //Set the error message
            $cat_desc_error = "The product description cannot be empty";

            //Validation hasn't passed, validate is now false
            $validated = false;
        }

        //If is longer than what is allowed in the databse, it isn't valid
        if(!isValidLength($cat_desc, 128)){
            //Set the error message
            $cat_desc_error = "The product description that you entered was either too short or too long(max " . 128 . " characters )";
            //Validation hasn't passed, validate is now false
            $validated = false;
        }

        //If the input contains characters other than letters, numbers, spaces or dashes
        if(!isAlphanumeric($cat_desc)){
            //Set the error message
            $cat_desc_error = "The product name that you entered included characters that weren't alphanumeric or spaces";
            //Validation hasn't passed, validate is now false
            $validated = false;
        }
        //Post the error message back to the page
        $_POST['cat_desc_error'] = "<span class='errorMessage'>". $cat_desc_error . "</span>";
    }
    //If the variable isn't set, set validated variable to false
    else if(!isset($_POST['cat_desc'])){
        $validated = false;
    }

    //If the category variable has been set
    if(isset($_POST['cat'])){

        //Set the variable to the post variable, sanistisation isn't needed
        //since categorys are inserted by admins, and sanitised/validated at that point
        $cat = $_POST['cat'];
        $cat_error = "";

        //If the post is false, validated is false
        if(empty($cat)){
            $validated = false;
            $cat_error = "You must select a category to add a product into.";
        }

        //Post the category editor back to the page
        $_POST['cat_error'] = $cat_error;
    }

    /*
    ISSUE: Since checkboxes are either posted or not depending on if they are clicked or not
    It is somewhat impossible to validate if it is set or not upon POST
    */
    //If the variable isn't set
    else if(!isset($_POST['cat'])){
        //Validated set to false
        $validated = false;

        //Set error and pass it to the next page
        $cat_error = "You must select a category to add a product into.";
        $_POST['cat_error'] = "<span class='errorMessage'>" . $cat_error . "</p>";
    }

    if(isset($_FILES['cat_img_url']) && $validated){

        $errors = "";
        $file_name = $_FILES['cat_img_url']['name'];
        $file_size = $_FILES['cat_img_url']['size'];
        $file_tmp = $_FILES['cat_img_url']['tmp_name'];
        $file_type = $_FILES['cat_img_url']['type'];
        $exploded = explode('.', $_FILES['cat_img_url']['name']);
        $file_ext = strtolower(end($exploded));

        $allowedExtensions = array("jpeg","jpg","png");
        if(empty($_FILES['cat_img_url']['name'])){
            $errors .="You have to add an image";
        }
        if(in_array($file_ext,$allowedExtensions)=== false){
            $errors .="<br>That extension isn't allowed, please only use JPEG, JPG or PNG";
        }
        if($file_size > 2097152){
            $errors .= "File must be under 2MB";
        }
        if(empty($errors)==true){
            move_uploaded_file($file_tmp, "../img/".$file_name);
            echo "Success";
        } else{

            $_POST['cat_img_error'] = "<span class='errorMessage'>". $errors . "</span>";
            $validated = false;
        }
    }
    else if(!isset($_FILES['cat_img_url'])){
        $validated = false;
    }
    return $validated;
}

//Function that validates the add product from

function add_prod($dbo){



    //Validate the form
    $validated = validateProd();

    //If the form passes the validation test
    if ($validated==true) {
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
    $stmt->bindParam(':prod_name', $_POST['prod_name'], PDO::PARAM_STR);
    $stmt->bindParam(':prod_desc', $_POST['prod_desc'], PDO::PARAM_STR);
    $stmt->bindParam(':prod_img_url', $_FILES['prod_img_url']['name'], PDO::PARAM_STR);
    $stmt->bindParam(':prod_long_desc', $_POST['prod_long_desc'],PDO::PARAM_STR);
    $stmt->bindParam(':prod_sku', $_POST['prod_sku'], PDO::PARAM_STR);
    $stmt->bindParam(':prod_disp_cmd', $_POST['prod_disp_cmd'], PDO::PARAM_STR);
    $stmt->bindParam(':prod_weight', $_POST['prod_weight'], PDO::PARAM_INT);
    $stmt->bindParam(':prod_l', $_POST['prod_l'], PDO::PARAM_INT);
    $stmt->bindParam(':prod_w', $_POST['prod_w'], PDO::PARAM_INT);
    $stmt->bindParam(':prod_h', $_POST['prod_h'], PDO::PARAM_INT);

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

function get_all_categories_edit($dbo){
    /* function that gets all of the categories and their ids for using in adding
    producs to categories - we might also want to filter out the root category
    as an option */
    $stmt = $dbo->prepare("SELECT cat_id, cat_name FROM category");
    $stmt->bindParam(':id', $parent_id);
    try_or_die($stmt);

    //outputs the html for category selection, with a checkbox for each possible category

    ?>
    <select name="cats">
    <?php
    while($row = $stmt->fetch()) { ?>
                <option value="<?php echo $row['cat_id']; ?>">
                <?php echo $row['cat_name'];
    }

    ?>
</select>
        <button class="btn" type="submit" value="Submit">Submit</button>
    <?php
    $stmt = null;
}

function get_all_categories_editor($dbo, $params){
    /* function that gets all of the categories and their ids for using in adding
    producs to categories. this is done for editing, it loops and compares the values and determines the parent categories and checks them */
    $stmt = $dbo->prepare("SELECT cat_id, cat_name FROM category");
    $stmt->bindParam(':id', $parent_id);
    try_or_die($stmt);

    //outputs the html for category selection, with a checkbox for each possible category
    while($row = $stmt->fetch()) {?>
        <div class="checkbox">
            <label>
                <input <?php
                        if (get_parent_info($dbo, $row['cat_id'])==true){
                            echo "checked";
                        }

                     //iterated loop to check whether they match }
                 ?>

                type="checkbox" name="cat[]" value="<?php echo $row['cat_id']; ?>">
                <?php echo $row['cat_name']; ?>
            </label>
        </div>
       <?php
    }

    $stmt = null;
}


function displayproduct($dbo) {
    /* Displays only the details of the product on displayproduct.php page */
    $shopper_group = 1; //the default shopper group
    //check whether a valid product ID is in GET parameter
    $validated = false; //can't trust the browser, so assume prod_id is invalid
    $result = false; //to keep track of whether we should display a friendly error message based on results returned from querying
    if (isset($_GET['prod_id']) && !empty($_GET['prod_id'])) { //if a prod_id is specified
      $id = sanitise_number($_GET['prod_id']); //sanitise the prod_id specified
      if (isNumber($id)) { //if it is a number (product ID's can only be numbers)
        $prod_id = $id; //get the product ID
        $validated = true; //the product ID is valid
      }
    }
    if ($validated) { //then query the db for respective product details
      $stmt = $dbo->prepare("SELECT PROD_NAME, PROD_IMG_URL, PROD_LONG_DESC, PRPR_PRICE FROM Product, ProdPrices where PROD_ID = (:id)
      and Product.prod_id = ProdPrices.prpr_prod_id and prpr_shopgrp = (:shgroup) group by prod_id"); //selects the product details
      $stmt->bindParam(':id', $prod_id);
      $stmt->bindParam(':shgroup', $shopper_group);
      try_or_die($stmt);
      if ($stmt->rowCount() > 0) {
      /* Displays the product details */
        $result = true; //the product ID specified corresponds to a product in the DB
        while($row = $stmt->fetch()) { ?>
            <div class = "row">
              <div class = "col-md-6">
                <img class = "productDisplay" src = "../img/<?php echo $row[1] ?>"/>
              </div>
            <div class = "col-md-6">
                <h1><?php echo sanitise_string($row[0]);?></h1>
            <i class="fa fa-star" aria-hidden="true"></i>
            <i class="fa fa-star" aria-hidden="true"></i>
            <i class="fa fa-star" aria-hidden="true"></i>
            <i class="fa fa-star" aria-hidden="true"></i>
            <i class="fa fa-star-half-o" aria-hidden="true"></i>
            <a href = "#">See Reviews</a>
                <h3 class = "price" id = "productPrice">$<?php echo sanitise_number($row[3]) ?></h3>
            <p class = "lead"><?php echo sanitise_string($row[2]) ?></p>
            <?php
        }
      } else { //there were no matches on the product ID specified
        $result = false;
      }
      $stmt = null;
  }
    if (!$result) { //if no results to display, display a friendly sorry message
      ?>
      <div class = "row unavailable">
        <div class = "col-xs-12">
          <h2 class = "notFound">Page not found</h2>
          <h3>Sorry, but we couldn't find the product you were looking for. Please go back to the <a href = "catalogue.php">catalogue</a> and search for another product.</h3>
        </div>
      </div> <?php
    }
}

function displayproductattributes($dbo) {
    /* Displays the attributes of the product on displayprouct.php by populating
    dropdowns with attribute values which can be chosen by the customer */
    //check whether a valid product ID is in GET parameter
    $validated = false; //can't trust the browser, so assume prod_id is invalid
    if (isset($_GET['prod_id']) && !empty($_GET['prod_id'])) { //if a prod_id is specified
      $id = sanitise_number($_GET['prod_id']); //sanitise the prod_id specified
      if (isNumber($id)) { //if it is a number (product ID's can only be numbers)
        $prod_id = $id; //get the product ID
        $validated = true;
      }
    }
    if ($validated) { //then query the db for respective product details
      $stmt = $dbo->prepare("SELECT ID, NAME FROM ATTRIBUTE WHERE PRODUCT_PROD_ID = (:id)");
      $stmt->bindParam(':id', $prod_id);
      try_or_die($stmt);
      if ($stmt->rowCount() > 0) { ?>
        <label for="quantity">Qty:</label>
        <input class = "form-control" id = "quantity" type="number" name="quantity" value = "1" min="1" max="6" onchange = "updatePrice()">
        <br/><br/> <?php
        $attribute_ids = array(); //create an array to store ID's of attributes
        $attribute_id_names = array(); //creates an array to store names of attributes

        while($row = $stmt->fetch()) {
            $attribute_ids[] = $row[0]; //contains ID's related to product
            $attribute_id_names[$row[0]]=$row[1]; //contains the ID's and their associated names
        }

        $stmt = null;

        $arrlength = count($attribute_ids);
        for ($i = 0; $i < $arrlength; ++$i) { //for each attribute_id in $attribute_ids, find its attribute values
            $stmt = $dbo->prepare("SELECT ATTRVAL_ID, ATTRVAL_VALUE, ATTRVAL_PRICE FROM ATTRIBUTEVALUE WHERE ATTRVAL_ATTR_ID = (:attrID)");
            $stmt->bindParam(':attrID', $attribute_ids[$i]);
            try_or_die($stmt);
            $attribute_name = $attribute_id_names[$attribute_ids[$i]]; //assigns attribute_name by looking up from associative array using the ID of the attribute
            /* Populate the dropdown with its respective attribute values */
            ?> <label for="<?php echo $attribute_name ?>"><?php echo sanitise_string($attribute_name) ?></label>
            <select class = "form-control productAttr" name = "<?php echo sanitise_string($attribute_name) ?>" onchange = "updatePrice(this)">

            <?php $count = $stmt->rowCount();
            if ($count == 1) { //then there's only one attribute value, display it as the default option value
              while($row = $stmt->fetch()) { ?>
                <option value = "<?php echo sanitise_string($row[0])?>|<?php echo sanitise_string($row[2])?>"><?php echo sanitise_string($row[1])?></option> <?php
              } ?>
            </select>
            <?php } else {
              ?>
            <option value>--Select--</option> <?php
            while($row = $stmt->fetch()) { ?>
                <option value = "<?php echo sanitise_string($row[0])?>|<?php echo sanitise_string($row[2])?>"><?php echo sanitise_string($row[1]) ?></option>
            <?php }
            ?> </select>
            <?php }
            $stmt = null;
          } ?> <br/><br/><button type="submit" class="btn btn-default btn-lg btn-success" name="product" value="<?php echo $prod_id;?>">Add To Cart</button> <?php
      }
  }
}

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

function edit_prod($dbo){
  if(isset($_POST['prod_id'])){
    submit_edit_prod($dbo);
  }
  else{
    if(isset($_GET['prod_id'])){
      $prod_id = $_GET['prod_id'];

      $row = get_product_info($dbo, $prod_id);
      include '../layouts/editprodform.php';
    } else {
      echo 'not a valid product';
    }
  }
}

function get_product_info($dbo, $prod_id){
  //function that gets all of the information for the product to be edited, and returns it so that it may be prefilled
  $stmt = $dbo->prepare("SELECT * FROM product WHERE prod_id = (:prod_id)");

  $stmt->bindParam(':prod_id', $prod_id);

  try_or_die($stmt);

  $row = $stmt->fetch();

  return $row;
}

function submit_edit_prod($dbo){
  //function needs to submit updated product information to the database
  return;
}

function delete_prod($dbo){


    $validated = false; //can't trust the browser, so assume prod_id is invalid
    if (isset($_GET['prod_id']) && !empty($_GET['prod_id'])) { //if a prod_id is specified
      $id = sanitise_number($_GET['prod_id']); //sanitise the prod_id specified
      if (isNumber($id)) { //if it is a number (product ID's can only be numbers)
        $prod_id = $id; //get the product ID
        $validated = true;
      }
    }
    if ($validated == true){//if validation passes

      //statement for deleting the attribute, prices, category relationship and product values
      $stmt = $dbo->prepare("DELETE FROM cgprrel WHERE cgpr_prod_id = (:prod_id);");

      $stmt->bindParam(':prod_id', $prod_id);

      try_or_die($stmt);

      $stmt = $dbo->prepare("DELETE FROM prodprices WHERE prpr_prod_id = (:prod_id);");

      $stmt->bindParam(':prod_id', $prod_id);

      try_or_die($stmt);

      $stmt = $dbo->prepare("DELETE FROM attributevalue WHERE attrval_prod_id = (:prod_id);");

      $stmt->bindParam(':prod_id', $prod_id);

      try_or_die($stmt);

      $stmt = $dbo->prepare("DELETE FROM attribute WHERE product_prod_id = (:prod_id);");

      $stmt->bindParam(':prod_id', $prod_id);

      try_or_die($stmt);

      $stmt = $dbo->prepare("DELETE FROM product WHERE prod_id = (:prod_id);");

      $stmt->bindParam(':prod_id', $prod_id);

      try_or_die($stmt);
    }

      //confirmation message
      echo("This product has been successfully deleted");

      //link back to catalogue page
      echo '<br><br><a href="catalogue.php">Back to Catalogue</a>';
}

?>

<?php include 'controller.php';

function validateSpecial(){
  return false;
}

function add_special_to_db(){
  return;
}

function get_attrs_and_vals($dbo){
  if (isset($_GET['prod_id'])){
    $prod_id = $_GET['prod_id'];
    $stmt = $dbo->prepare("SELECT ID, NAME FROM ATTRIBUTE WHERE PRODUCT_PROD_ID = (:prod_id)");
    $stmt->bindParam(':prod_id', $prod_id);
    try_or_die($stmt);

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
        ?> <label for="<?php echo $attribute_name; ?>"><?php echo $attribute_name; ?></label>
        <select class = "form-control productAttr" name = "<?php echo $attribute_name; ?>">
        <option value>--Select--</option> <?php
        while($row = $stmt->fetch()) { ?>
            <option value = "<?php echo $row[0]?>"><?php echo $row[1] ?></option>
        <?php }
        ?> </select><br>
        <?php }
        $stmt = null;
      } else {
		echo 'Not a valid product ID';
	}
}

function add_special($dbo){
  //Validate the form
  $validated = validateSpecial();

  if ($validated==true) {
    add_special_to_db($dbo);
  } else {
    include '/catomanage/layouts/specialsform.php';
  }
}

?>

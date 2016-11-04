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

    $stmt = $dbo->prepare("SELECT id, name FROM attribute WHERE product_prod_id = (:prod_id)");
  	$stmt->bindParam(':prod_id', $prod_id);

    try_or_die($stmt);
    ?> <select> <?php
    while($row=$stmt->fetch()){
      ?> <option value = "<?php echo $row[0] ?>"><?php echo $row[1]; ?> </option> <?php
    } ?> </select>
    <?php

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

<?php include 'controller.php';

function validateSpecial(){
  return false;
}

function add_special_to_db(){
  return;
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

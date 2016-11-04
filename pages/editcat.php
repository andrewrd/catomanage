<?php

//Includes the application header
include '../layouts/header.php';
//Allows function calls and connection database
include '../controller/controller.php';

?>

<?php

 edit_category_form($dbo); ?>

<?php

//includes the footer of the application
include '../layouts/footer.php';
?>

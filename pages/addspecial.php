<?php
//Includes the application header
include '../layouts/header.php';
//Allows function calls and connection database
include '../controller/addspecialcontroller.php';
?>

<?php add_special($dbo); ?>

<?php
//includes the footer of the application
include '../layouts/footer.php';
?>

<?php
//Includes the application header
include '../layouts/header.php';
//Allows function calls and connection database
include '../controller/addspecialcontroller.php';
?>

<?php get_attrs_and_vals($dbo); ?>

<?php
//includes the footer of the application
include '../layouts/footer.php';
?>

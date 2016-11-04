<?php
//Includes the application header
include '../layouts/header.php';
//Allows function calls and connection database
include '../controller/controller.php';

//calls delete function from controller.php
delete_prod($dbo);

?>

<<?php

include '..include/layouts/footer.php';

?>

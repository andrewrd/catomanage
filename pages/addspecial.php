<?php
//Includes the application header
include '../layouts/header.php';
//Allows function calls and connection database
include '../controller/addspecialcontroller.php';
?>

<form class = "form" method = "post" action = "addspecial.php">
  <div class = "form-group">
    <?php get_attrs_and_vals($dbo); ?>
    <label for="special">Special Offer</label>
    <input class  ="form-control" name = "offer" type = "text">
    <br>
    <label for="start-date">Special Offer Start Date</label>
    <input class  ="form-control" name = "startDate" type = "text">
    <br>
    <label for="end-date">Special Offer End Date</label>
    <input class  ="form-control" name = "endDate" type = "text">
    <br>
    <label for="comment">Comments</label>
    <input class  ="form-control" name = "comment" type = "text">
    <br>
    <button class = "btn btn-success" type = "submit">Add +</button>
  </div>
</form>
<?php
//includes the footer of the application
include '../layouts/footer.php';
?>

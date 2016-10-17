
<?php
 /*includes the database */
include '../database/model.php';
$dbo = db_connect();

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

    try {
  		$stmt->execute();
  	}
  	catch (PDOException $ex){
  	  echo $ex->getMessage();
  	  die("Invalid Query");
  	}
    //standard attempt query or die code

    $results = $stmt->fetchColumn();
    //fetches the result
    $stmt = null;
    //frees statement variable
    $thislink = "<a href='catalogue.php?id=".$id."'>".$results."</a>";
    //templates the link to be appended to the full result


    $stmt = $dbo->prepare("SELECT cgryrel_id_parent FROM cgryrel WHERE cgryrel_id_child = (:id)");
    $stmt->bindParam(':id', $id);
    //gets the parent of the current category

    try {
  		$stmt->execute();
  	}
  	catch (PDOException $ex){
  	  echo $ex->getMessage();
  	  die("Invalid Query");
  	}
    //standard attempt query or die code

    $results = $stmt->fetchColumn();
    //fetches the result of the query
    return get_bread_crumb($dbo, $results)." / ".$thislink;
    //calls the functions again, using the parent categories ID

    $stmt = null;
    //frees statement variable
  }


}

function get_category_name($dbo){
	//gets the category name, depending on $_get['id']
	if (isset($_GET['id'])){
		$parent_id = $_GET['id'];
	} else {
		$parent_id = 1;
	}
  //checks if get variable is set, if not, just get the root category

	$stmt = $dbo->prepare("SELECT cat_name FROM category WHERE cat_id = (:id)");
	$stmt->bindParam(':id', $parent_id);
  //selects the category name for the selected cat_id

	try {
		$stmt->execute();
	}
	catch (PDOException $ex){
	  echo $ex->getMessage();
	  die("Invalid Query");
	}
  //standard attempt to query or die

	$results = $stmt->fetchColumn();
  //return the result
 	echo $results;
  //echo out the result
  //maybe change this to return?

	$stmt = null;
  //free statement
}

function get_categories($dbo){
	if (isset($_GET['id'])){
		$parent_id = $_GET['id'];
	} else {
		$parent_id = 1;
	}

	$stmt = $dbo->prepare("SELECT cgryrel.cgryrel_id_child, category.cat_name FROM cgryrel INNER JOIN category ON cgryrel.cgryrel_id_child=category.cat_id WHERE cgryrel.cgryrel_id_parent = (:id)");
	$stmt->bindParam(':id', $parent_id);

	try {
		$stmt->execute();
	}
	catch (PDOException $ex){
	  echo $ex->getMessage();
	  die("Invalid Query");
	}

	while($row = $stmt->fetch()) { ?>
		<p><a href=<?php echo "catalogue.php?id=".$row[0];?>><?php echo $row[1]; ?></a></p>
	<?php	}
	$stmt = null;
}


?>

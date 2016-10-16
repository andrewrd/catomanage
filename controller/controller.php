
<?php
 /*includes the database */
include '../database/model.php';
$dbo = db_connect();

function check_get(){

}

function get_category_name($dbo){
	/*runs a test query to display all category names*/
	$parent_id = check_get();
	if (isset($_GET['id'])){
		$parent_id = $_GET['id'];
	} else {
		$parent_id = 1;
	}

	$stmt = $dbo->prepare("SELECT cat_name FROM category WHERE cat_id = (:id)");
	$stmt->bindParam(':id', $parent_id);

	try {
		$stmt->execute();
	}
	catch (PDOException $ex){
	  echo $ex->getMessage();
	  die("Invalid Query");
	}

	$results = $stmt->fetchColumn();
 	echo $results;

	$stmt = null;
}

function get_categories($dbo){
	$parent_id = check_get();
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

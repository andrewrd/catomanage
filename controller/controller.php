
<?php
 /*includes the database */
include '../database/model.php';

function get_categories(){
	$dbo = db_connect();
	/*runs a test query to display all category names*/
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

	while($row = $stmt->fetch()) { ?> <!-- see http://www.php.net/manual/en/pdostatement.fetch.php -->
		<p><strong>
			<?php	for ($j = 0; $j < $stmt->columnCount(); $j++) { ?>
				    <TD><?php echo $row[$j]?></TD>
			<?php	} ?>
		</strong></p>
	<?php	}
	# Drop the reference to the database
	$dbo = null;
}





?>

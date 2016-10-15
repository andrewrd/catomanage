
<?php
 /*includes the database */
include '../database/model.php';

$dbo = db_connect();

function test(){
	/*runs a test query to display all category names*/
	$query = "SELECT cat_name ";
	$query .= "FROM CATEGORY";

	try {
	  $statement = $dbo->query($query);
	}
	catch (PDOException $ex){
	  echo $ex->getMessage();
	  die("Invalid Query");
	}

	while($row = $statement->fetch()) { ?> <!-- see http://www.php.net/manual/en/pdostatement.fetch.php -->
			<TR>
		<?php	for ($j = 0; $j < $statement->columnCount(); $j++) { ?>
			    <TD><?php echo $row[$j]?></TD>
		<?php	} ?>
			</TR>
	<?php	}
}


	# Drop the reference to the database
	$dbo = null;

?>

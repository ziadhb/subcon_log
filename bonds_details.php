<?php 
ob_start();
session_start();
?>
<!DOCTYPE html>
	<?php
		if (!isset($_SESSION['userName'])) {
		  header("Location: login.php");
		}
		//CONNECT TO DATABASE
		include 'db_connection.php';

		$subcontract_id = $_GET['subcontract_id'];
		$sql = "
				SELECT subcontractors.name, subcontracts.subcontract_id, subcontracts.subcontract_scope
				FROM subcontractors INNER JOIN subcontracts on subcontractors.subcontractor_id = subcontracts.subcontractor_id
				WHERE subcontracts.subcontract_id = ".$subcontract_id." order by subcontractors.name";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
    		while($row = $result->fetch_assoc()) {
    		$subcontractor_name = $row["name"];
    		$subcontract_scope = $row["subcontract_scope"];
    		}
		} else {
    		echo "0 results";
		}
	?>
<html>
	<head>
	<title>TG2007</title>
		<link rel="stylesheet" type="text/css" href="stylesheet.css">
	</head>
	<body>
		<a href='subcontractors.php'><h1>TG2007</h1></a>
		<h2>List of bonds</h2>
		<h3>Subcontractor: <?php echo $subcontractor_name?>;</h3>
		<h3>Subcontract: <?php echo $subcontract_scope?>;</h3>
		<table>
			<tr><th>bond_id</th><th>bond_ref</th><th>bond_type</th><th>bond_purpose</th><th>issue_date</th><th>exp_date</th><th>issuing_bank</th><th>bond_vaue</th><th>notes</th></tr>
			<?php
				$sql = "
						SELECT * FROM bonds WHERE bonds.subcontract_id = ".$subcontract_id;
				$result = $conn->query($sql);
				if ($result->num_rows > 0) {
		    		while($row = $result->fetch_assoc()) {
		    			echo "<tr>";
		    			echo "<td>".$row['bond_id']."</td>";
		    			echo "<td>".$row['bond_ref']."</td>";
		    			echo "<td>".$row['bond_type']."</td>";
		    			echo "<td>".$row['bond_purpose']."</td>";
		    			echo "<td>".$row['issue_date']."</td>";
		    			echo "<td>".$row['exp_date']."</td>";
		    			echo "<td>".$row['issuing_bank']."</td>";
		    			echo "<td class='money'>".$row['bond_value']."</td>";
		    			echo "<td>".$row['notes']."</td>";
		    			echo "</tr>";
		    		}
				} else {
		    		echo "0 results";
				}
			?>
		</table>
	</body>
</html>
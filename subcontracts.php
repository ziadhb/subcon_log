<?php 
ob_start();
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>TG2007</title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>
<a href='subcontractors.php'><h1>TG2007</h1></a>

<?php
if (!isset($_SESSION['userName'])) {
  header("Location: login.php");
}

// it will never let you open index(login) page if session is set
if ( isset($_SESSION['user'])!="" ) {
  echo "<script>window.location = 'http://ziad77.onlinewebshop.net/login.php'</script>";
  exit;
}

//CONNECT TO DATABASE
include 'db_connection.php';

$subcontractor_id = $_GET['subcontractor_id'];
echo "<h1> List of subcontracts for subcontractor id:".$subcontractor_id."</h1>";
$sql = "
SELECT subcontractors.name, subcontracts.subcontract_id, subcontracts.subcontract_scope
FROM subcontractors INNER JOIN subcontracts on subcontractors.subcontractor_id = subcontracts.subcontractor_id
WHERE subcontractors.subcontractor_id = ".$subcontractor_id." order by subcontractors.name";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    echo "<table><th>Subcontractor Name</th><th>Scope</th><th>Action</th>";
    while($row = $result->fetch_assoc()) {
    	//$myUrl = "window.open('http://testz.000webhostapp.com/subcon_payment.php?subcontract_id=".$row["subcontract_id"]."')";
        $paymentsUrl = "subcon_payment.php?subcontract_id=".$row["subcontract_id"];
        $bondsUrl = "bonds_details.php?subcontract_id=".$row["subcontract_id"];
    	//$myUrl = "http://localhost/test/subcon_payment.php?subcontract_id=".$row["subcontract_id"];
        echo "<tr><td>".$row["name"]."</td><td>".$row["subcontract_scope"]."<td><a class='withborder'href='".$paymentsUrl."'>Payments</a>&nbsp;<a class='withborder' href='".$bondsUrl."'>Bonds</a></td></tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}
$conn->close();
ob_end_flush();
?>
</body>
</html>
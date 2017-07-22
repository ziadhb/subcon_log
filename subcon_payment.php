<!DOCTYPE html>
<html>
<head>
<title>TG2007</title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">

</head>
<body>
<a href='subcontractors.php'><h1>TG2007</h1></a>
<?php

ob_start();
session_start();
if (!isset($_SESSION['userName'])) {
  header("Location: login.php");
}

$totalCertified = 0.0;
$totalPaid = 0.0;
//CONNECT TO DATABASE
include 'db_connection.php';

$subcontract_id = $_GET['subcontract_id'];

$sql = "
  SELECT subcontractors.name, subcontracts.subcontract_scope FROM subcontracts
  INNER JOIN subcontractors ON subcontracts.subcontractor_id = subcontractors.subcontractor_id
  WHERE subcontracts.subcontract_id=".$subcontract_id;
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
  $subcontractor_name = $row["name"];
  $subcontract_scope = $row["subcontract_scope"];
}
 

echo "<h1>Payment Log for subcontract id:".$subcontract_id."</h1>";
echo "<h2>Subcontractor: $subcontractor_name</h2>";
echo "<h2>Scope: $subcontract_scope</h2>";


$sql = "SELECT * FROM subcontractor_payment_cert where subcontract_id = ".$subcontract_id;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    echo "<table><th>Cert. ID</th><th>Payment sn</th><th>Payment date</th><th>Work Exec.</th><th>Adv. Payment</th><th>On Account</th><th>Variations</th><th>Mat. on Site</th><th>Retention</th><th>Retention Release</th><th>Adv. Rec.</th><th>Used Material</th><th>Deductions</th><th>Prev. paid</th><th>Amount</th><th>Paid Amount</th><th>Remarks</th><th>Action</th>";
    while($row = $result->fetch_assoc()) {
        $delete_command = "/sql.php?user_command=delete&cert_id=".$row['cert_id']."&subcontract_id=".$subcontract_id;
        $delete_confirmation = "=return confirm('Are you sure?')";
        echo "<tr><td>".$row["cert_id"];
        echo "</td><td>".$row["payment_sn"];
        echo "</td><td>".$row["payment_date"];
        echo "</td><td class='money'>".number_format($row["work_exec"],3);
        echo "</td><td class='money'>".number_format($row["ap"],3);
        echo "</td><td class='money'>".number_format($row["on_acc"],3);
        echo "</td><td class='money'>".number_format($row["variations"],3);
        echo "</td><td class='money'>".number_format($row["mos"],3);
        echo "</td><td class='money'>".number_format($row["ret"],3);
        echo "</td><td class='money'>".number_format($row["ret_rel"],3);
        echo "</td><td class='money'>".$row["ap_rec"];
        echo "</td><td class='money'>".number_format($row["used_mos"],3);
        echo "</td><td class='money'>".number_format($row["deductions"],3);
        echo "</td><td class='money'>".number_format($row["prev_paid"],3);
        echo "</td><td class='money'>".number_format($row["amount"],3);
        echo "</td><td>".$row["remarks"];
        echo "</td><td>";//Put the total paid here
        echo "<td><a href=$delete_command onclick=\"return confirm('You are deleting a payment, ARE YOU SURE?')\"";
        echo ">&#10060</a>&#9997";
        echo "</td></tr>";
        $totalCertified += $row["amount"];
    }
    echo "</table>";
} else {
    echo "0 results";
}


//echo "<a href='/test/add_subcontractor_payment_form.php?subcontract_id=$subcontract_id'>Add subcontractor payment</a>";
echo "<a href='add_subcontractor_payment_form.php?subcontract_id=$subcontract_id'>Add subcontractor payment certificate</a>";


echo "<h1>List of previous payments</h1>";
$sql = "
SELECT
    subcontracts.subcontract_id,
    subcontracts.subcontract_scope,
    subcontractor_payment_transaction.trans_id,
    subcontractor_payment_transaction.trans_no,
    subcontractor_payment_transaction.trans_date,
    subcontractor_payment_transaction.trans_amount
FROM
    (subcontracts
        INNER JOIN
            subcontractor_payment_cert
                on subcontracts.subcontract_id = subcontractor_payment_cert.subcontract_id)
    INNER JOIN
        subcontractor_payment_transaction
            ON subcontractor_payment_cert.cert_id=subcontractor_payment_transaction.cert_id
WHERE
    subcontracts.subcontract_id=".$subcontract_id."
ORDER BY
    subcontractor_payment_transaction.trans_date";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    echo "<table><th>Trans id</th><th>Transaction date</th><th>Transaction Ref</th><th>Transaction amount</th>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["trans_id"]."</td><td>".$row["trans_date"]."</td><td>".$row["trans_no"]."</td><td class='money'>".number_format($row["trans_amount"],3)."</td></tr>";
        $totalPaid += $row["trans_amount"];
    }
    echo "</table>";
} else {
    echo "0 results";
}
echo "<a href='add_transaction.php?subcontract_id=$subcontract_id'>Add subcontractor payment transaction</a>";
$conn->close();


echo "<h1>SUMMARY</h1>";
echo "<table><th>Total Certified</th><th>Total Paid</th><th>Balance</th>";
echo "<tr><td class='money'>".number_format($totalCertified,3)."</td><td class='money'>".number_format($totalPaid,3)."</td><td class='money'>".number_format(($totalCertified+$totalPaid),3)."</td></tr>";
echo "</table>";
echo "<br><br><br><br>";
number_format($totalCertified,3);
ob_end_flush();
?>
</body>
</html>
<?php
  ob_start();
  session_start();
  if (!isset($_SESSION['userName'])) {
    header("Location: login.php");
  }
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script type="text/javascript">
  function foo(zCaller){
    /*
    var prevValue = parseFloat(document.getElementById("todate_"+zCaller).innerHTML);
    var todateValue = document.getElementById(zCaller).value;
    document.getElementById("current_"+zCaller).innerHTML = todateValue - prevValue;
    */
   
    //Prepare the variables for the todate
    if (!(document.getElementById("manEntry").checked)) {
		
		var todate_payment_date = 0;
		var todate_work_exec = document.getElementById("work_exec").value;
		var todate_ap = document.getElementById("ap").value;
		var todate_on_acc = document.getElementById("on_acc").value;
		var todate_variations = document.getElementById("variations").value;
		var todate_mos = document.getElementById("mos").value;
		var todate_ret = document.getElementById("ret").value;
		var todate_ret_rel = document.getElementById("ret_rel").value;
		var todate_ap_rec = document.getElementById("ap_rec").value;
		var todate_used_mos = document.getElementById("used_mos").value;
		var todate_deductions = document.getElementById("deductions").value;
		var todate_prev_paid = document.getElementById("prev_paid").value;

		//Prepare the variables for the previous
		var prev_work_exec = parseFloat(document.getElementById("prev_work_exec").innerHTML);
		var prev_ap = parseFloat(document.getElementById("prev_ap").innerHTML);
		var prev_on_acc = parseFloat(document.getElementById("prev_on_acc").innerHTML);
		var prev_variations = parseFloat(document.getElementById("prev_variations").innerHTML);
		var prev_mos = parseFloat(document.getElementById("prev_mos").innerHTML);
		var prev_ret = parseFloat(document.getElementById("prev_ret").innerHTML);
		var prev_ret_rel = parseFloat(document.getElementById("prev_ret_rel").innerHTML);
		var prev_ap_rec = parseFloat(document.getElementById("prev_ap_rec").innerHTML);
		var prev_used_mos = parseFloat(document.getElementById("prev_used_mos").innerHTML);
		var prev_deductions = parseFloat(document.getElementById("prev_deductions").innerHTML);
		var prev_prev_paid = parseFloat(document.getElementById("prev_prev_paid").innerHTML);
		var prev_amount = parseFloat(document.getElementById("prev_amount").innerHTML);
		//Update all the current amounts and the certificate amount
		document.getElementById("current_work_exec").innerHTML = (todate_work_exec - prev_work_exec).toFixed(3);
		document.getElementById("current_ap").innerHTML = (todate_ap - prev_ap).toFixed(3);
		document.getElementById("current_on_acc").innerHTML = (todate_on_acc - prev_on_acc).toFixed(3);
		document.getElementById("current_variations").innerHTML = (todate_variations - prev_variations).toFixed(3);
		document.getElementById("current_mos").innerHTML = (todate_mos - prev_mos).toFixed(3);
		document.getElementById("current_ret").innerHTML = (todate_ret - prev_ret).toFixed(3);
		document.getElementById("current_ret_rel").innerHTML = (todate_ret_rel - prev_ret_rel).toFixed(3);
		document.getElementById("current_ap_rec").innerHTML = (todate_ap_rec - prev_ap_rec).toFixed(3);
		document.getElementById("current_used_mos").innerHTML = (todate_used_mos - prev_used_mos).toFixed(3);
		document.getElementById("current_deductions").innerHTML = (todate_deductions - prev_deductions).toFixed(3);
		document.getElementById("current_prev_paid").innerHTML = (todate_prev_paid - prev_prev_paid).toFixed(3);
	//    document.getElementById("amount").value = (parseInt(todate_work_exec)+parseInt(todate_ap)+parseInt(todate_on_acc)+parseInt(todate_variations)+parseInt(todate_mos)+parseInt(todate_ret)+parseInt(todate_ret_rel)+parseInt(todate_ap_rec)+parseInt(todate_used_mos)+parseInt(todate_deductions)+parseInt(todate_prev_paid)).toFixed(3);
		document.getElementById("amount").value = (parseFloat(todate_work_exec)+parseFloat(todate_ap)+parseFloat(todate_on_acc)+parseFloat(todate_variations)+parseFloat(todate_mos)+parseFloat(todate_ret)+parseFloat(todate_ret_rel)+parseFloat(todate_ap_rec)+parseFloat(todate_used_mos)+parseFloat(todate_deductions)+parseFloat(todate_prev_paid)).toFixed(3);
		}
	}
</script>
</head>
<body>


<?php


  $next_cert_id = 0;
  $payment_sn = 0;
  $payment_date = date("yyyy-mm-dd");
  $work_exec = 0.0;
  $ap = 0.0;
  $on_acc = 0.0;
  $variations = 0.0;
  $mos = 0.0;
  $ret = 0.0;
  $ret_rel = 0.0;
  $ap_rec = 0.0;
  $used_mos = 0.0;
  $deductions = 0.0;
  $prev_paid = 0.0;
  $amount = 0.0;
  $remarks = "";

  $subcontract_id = $_GET['subcontract_id'];

  //CONNECT TO DATABASE
  include 'db_connection.php';


  //Prepare the headder
  
  $sql = "
    SELECT subcontractors.name, subcontracts.subcontract_scope FROM subcontracts
    INNER JOIN subcontractors ON subcontracts.subcontractor_id = subcontractors.subcontractor_id
    WHERE subcontracts.subcontract_id=".$subcontract_id;
  $result = $conn->query($sql);
  while($row = $result->fetch_assoc()) {
    $subcontractor_name = $row["name"];
    $subcontract_scope = $row["subcontract_scope"];
  }
echo "<h1>Subcontract Payment Certificate</h1>";
echo "<h2>Subcontractor: $subcontractor_name</h2>";
echo "<h2>Scope: $subcontract_scope</h2>";



  $sql = "
    SELECT MAX(subcontractor_payment_cert.cert_id) FROM subcontractor_payment_cert
    ";
    $result = $conn->query($sql);
    
    while($row = $result->fetch_assoc()) {
        $next_cert_id = $row["MAX(subcontractor_payment_cert.cert_id)"]+1;
    }




  $sql = "
    SELECT * FROM subcontractor_payment_cert
    WHERE subcontractor_payment_cert.subcontract_id=
    ".$subcontract_id."
    ORDER BY subcontractor_payment_cert.cert_id DESC
    LIMIT 1
    ";
    $result = $conn->query($sql);
    
    while($row = $result->fetch_assoc()) {
        $prev_cert_id = $row["cert_id"];
        $prev_payment_sn = $row["payment_sn"];
        $prev_payment_date = $row["payment_date"];
        $prev_work_exec = $row["work_exec"];
        $prev_ap = $row["ap"];
        $prev_on_acc = $row["on_acc"];
        $prev_variations = $row["variations"];
        $prev_mos = $row["mos"];
        $prev_ret = $row["ret"];
        $prev_ret_rel = $row["ret_rel"];
        $prev_ap_rec = $row["ap_rec"];
        $prev_used_mos = $row["used_mos"];
        $prev_deductions = $row["deductions"];
        $prev_prev_paid = $row["prev_paid"];
        $prev_amount = $row["amount"];
        $prev_remarks = $row["remarks"];
    }
    echo "
          <form method='GET' action='add_subcontractor_payment_action.php'>
            <table>
            <tr><th>Field Description</th><th>To-dat Value</th><th>Current Value</th><th>Previous Value</th></tr>
            <tr><td>cert_id</td><td><input type='number' name='cert_id' value='".$next_cert_id."'></td><td></td><td>".$prev_cert_id."</td></tr>
            <tr><td>subcontract_id</td><td><input type='number' name='subcontract_id' readonly value='".$subcontract_id."'></td><td id=''></td><td id=''>".$subcontract_id."</td></tr>
            <tr><td>payment_sn</td><td><input type='number' name='payment_sn' value=''></td><td id=''></td><td id=''>".$prev_payment_sn."</td></tr>
            <tr><td>payment_date</td><td><input type='date' name='payment_date' value=".$prev_payment_date."></td><td id=''></td><td id=''>".$prev_payment_date."</td></tr>
            <tr><td>work_exec</td><td><input type='number' step='any' name='work_exec' value=".$prev_work_exec." id='work_exec' onblur='foo(".'"work_exec"'.")'></td><td id='current_work_exec' class='money'>0</td><td id='prev_work_exec' class='money'>".$prev_work_exec."</td></tr>
            <tr><td>ap</td><td><input type='number' step='any' name='ap' value=".$prev_ap." id='ap' onblur='foo(".'"ap"'.")'></td><td id='current_ap' class='money'>0</td><td id='prev_ap' class='money'>".$prev_ap."</td></tr>
            <tr><td>on_acc</td><td><input type='number' step='any' name='on_acc' value=".$prev_on_acc." id='on_acc' onblur='foo(".'"on_acc"'.")'></td><td id='current_on_acc' class='money'>0</td><td id='prev_on_acc' class='money'>".$prev_on_acc."</td></tr>
            <tr><td>variations</td><td><input type='number' step='any' name='variations' value=".$prev_variations." id='variations' onblur='foo(".'"variations"'.")'></td><td id='current_variations' class='money'>0</td><td id='prev_variations' class='money'>".$prev_variations."</td></tr>
            <tr><td>mos</td><td><input type='number' step='any' name='mos' value=".$prev_mos." id='mos' onblur='foo(".'"mos"'.")'></td><td id='current_mos' class='money'>0</td><td id='prev_mos' class='money'>".$prev_mos."</td></tr>
            <tr><td>ret</td><td><input type='number' step='any' name='ret' value=".$prev_ret." id='ret' onblur='foo(".'"ret"'.")'></td><td id='current_ret' class='money'>0</td><td id='prev_ret' class='money'>".$prev_ret."</td></tr>
            <tr><td>ret_rel</td><td><input type='number' step='any' name='ret_rel' value=".$prev_ret_rel." id='ret_rel' onblur='foo(".'"ret_rel"'.")'></td><td id='current_ret_rel' class='money'>0</td><td id='prev_ret_rel' class='money'>".$prev_ret_rel."</td></tr>
            <tr><td>ap_rec</td><td><input type='number' step='any' name='ap_rec' value=".$prev_ap_rec." id='ap_rec' onblur='foo(".'"ap_rec"'.")'></td><td id='current_ap_rec' class='money'>0</td><td id='prev_ap_rec' class='money'>".$prev_ap_rec."</td></tr>
            <tr><td>used_mos</td><td><input type='number' step='any' name='used_mos' value=".$prev_used_mos." id='used_mos' onblur='foo(".'"used_mos"'.")'></td><td id='current_used_mos' class='money'>0</td><td id='prev_used_mos' class='money'>".$prev_used_mos."</td></tr>
            <tr><td>deductions</td><td><input type='number' step='any' name='deductions' value=".$prev_deductions." id='deductions' onblur='foo(".'"deductions"'.")'></td><td id='current_deductions' class='money'>0</td><td id='prev_deductions' class='money'>".$prev_deductions."</td></tr>
            <tr><td>prev_paid</td><td><input type='number' step='any' name='prev_paid' value=".($prev_prev_paid-$prev_amount)." id='prev_paid' onblur='foo(".'"prev_paid"'.")'></td><td id='current_prev_paid' class='money'>0</td><td id='prev_prev_paid' class='money'>".$prev_prev_paid."</td></tr>
            <tr><td>amount<br><input type='checkbox' id='manEntry'>Manual Entry</td><td><input type='number' step='any' name='amount' value='0' id='amount'></td><td id='current_amount' class='money'>0</td><td id='prev_amount' class='money'>".$prev_amount."</td></tr>
            <tr><td>remarks</td><td><input type='text' name='remarks' value=''></td><td></td><td>".$prev_remarks."</td></tr>
            </table>
            <input type='submit' value='Submit'>
            </form>
    ";
ob_end_flush();
?>
</body>
</html>

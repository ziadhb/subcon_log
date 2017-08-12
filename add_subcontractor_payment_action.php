<?php
session_start();
ob_start();
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</style>
<script type="text/javascript">
  function foo(zCaller){
    var prevValue = parseFloat(document.getElementById("todate_"+zCaller).innerHTML);
    var todateValue = document.getElementById(zCaller).value;
    document.getElementById("current_"+zCaller).innerHTML = todateValue - prevValue;
  }
</script>
</head>
<body>
<?php

  
  
  if (!isset($_SESSION['userName'])) {
    header("Location: login.php");
  }

  //CONNECT TO DATABASE
  include 'db_connection.php';


  if (isset($_GET['cert_id'])
      && isset($_GET['subcontract_id'])
      && isset($_GET['payment_sn'])
      && isset($_GET['payment_date'])
      && isset($_GET['work_exec'])
      && isset($_GET['ap'])
      && isset($_GET['on_acc'])
      && isset($_GET['variations'])
      && isset($_GET['mos'])
      && isset($_GET['ret'])
      && isset($_GET['ret_rel'])
      && isset($_GET['ap_rec'])
      && isset($_GET['used_mos'])
      && isset($_GET['deductions'])
      && isset($_GET['prev_paid'])
      && isset($_GET['amount'])
    ) {
    $sql = "
            INSERT INTO `subcontractor_payment_cert`(`cert_id`,
                                                      `subcontract_id`,
                                                      `payment_sn`,
                                                      `payment_date`,
                                                      `work_exec`,
                                                      `ap`,
                                                      `on_acc`,
                                                      `variations`,
                                                      `mos`,
                                                      `ret`,
                                                      `ret_rel`,
                                                      `ap_rec`,
                                                      `used_mos`,
                                                      `deductions`,
                                                      `prev_paid`,
                                                      `amount`,
                                                      `remarks`)
                                                      VALUES ("
                                                      .$_GET['cert_id'].","
                                                      .$_GET['subcontract_id'].","
                                                      .$_GET['payment_sn'].","
                                                      .'"'.$_GET['payment_date'].'"'.","
                                                      .$_GET['work_exec'].","
                                                      .$_GET['ap'].","
                                                      .$_GET['on_acc'].","
                                                      .$_GET['variations'].","
                                                      .$_GET['mos'].","
                                                      .$_GET['ret'].","
                                                      .$_GET['ret_rel'].","
                                                      .$_GET['ap_rec'].","
                                                      .$_GET['used_mos'].","
                                                      .$_GET['deductions'].","
                                                      .$_GET['prev_paid'].","
                                                      .$_GET['amount'].","
                                                      .'"'.$_GET['remarks'].'"'.")"
            ;
    if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
    //header("subcon_payment.php?subcontract_id=".$_GET['subcontract_id']);
    //echo "<script>window.location = '/subcon_payment.php?subcontract_id=".$_GET['subcontract_id']."'</script>";
	} else {
	    echo "Error: " . $sql . "<br>" . $conn->error;
      echo "<br><br><br>";
      echo "SQL statement is:<br>".$sql;
	}
  }
  ob_end_flush();
  header("Location: subcon_payment.php?subcontract_id=".$_GET['subcontract_id']);
?>
</body>
</html>
<?php
	ob_start();
	session_start();
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

  $user_command = $_POST['user_command'];
  switch ($user_command) {
    case "insert_transactions":
      $conn->query($_POST['qry']);
      header("Location: {$_SERVER["HTTP_REFERER"]}");
      break;
  }
  ob_end_flush();
  //header("Location: subcon_payment.php?subcontract_id=".$_GET['subcontract_id']);
?>
</body>
</html>
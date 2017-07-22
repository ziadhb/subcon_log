<!DOCTYPE html>
<html>
<head>
	<title>TG2007 LOG IN</title>
</head>
<body>
	<?php
		ob_start();
		session_start();
		//CONNECT TO DATABASE
		include 'db_connection.php';
		//CHECK IF ALREADY LOGGED IN THEN GO TO HOMEPAGE
		if (isset($_SESSION['userName'])) {
			header("Location: subcontractors.php");
		}
		if (isset($_POST['userName'])){
			$sql = "SELECT * FROM users WHERE users.userName='".$_POST['userName']."'";
			//echo "<br>SQL: ".$sql;
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					if ($_POST['userPass']==$row['userPass']) {
						echo "Success";
						$_SESSION['userName'] = $row['userName'];
						header("Location: subcontractors.php");
						} else {
							echo "Wrong Credentials";
						}
					}
				}
			}
	?>
	<form action="" method="post">
		User:<br>
		<input type="text" name="userName">
		<br>
		Password:<br>
		<input type="password" name="userPass">
		<br>
		<input type="submit" value="Login">
	</form>
	<?php
		ob_end_flush();
	?>
</body>
</html>
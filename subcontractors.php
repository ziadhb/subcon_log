<?php 
ob_start();
session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>TG2007</title>
    <link rel="stylesheet" type="text/css" href="stylesheet.css">

    <script>
    function filterTable() {
      var input, filter, table, tr, td, i;
      input = document.getElementById("myInput");
      filter = input.value.toUpperCase();
      table = document.getElementById("subcontractors_table");
      tr = table.getElementsByTagName("tr");
      for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[1];
        if (td) {
          if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          } else {
            tr[i].style.display = "none";
          }
        }       
      }
    }
    </script>

  </head>
  <body>
    <form action="logout.php" method="get">
      <input type="submit" name="logout" value="logout">
    </form>
    <a href='subcontractors.php'><h1>TG2007</h1></a>
    <h2>List of subcontractors</h2>
    <input type="text" id="myInput" onkeyup="filterTable()" placeholder="Search for names.." title="Type in a name">
    <a href="/add_subcontractor.html">Add new subcontractor</a><br><br>

    <?php
    if (!isset($_SESSION['userName'])) {
      header("Location: login.php");
    }

    //CONNECT TO DATABASE
    include 'db_connection.php';

    $sql = "SELECT subcontractor_id, name, ScBSCode FROM subcontractors ORDER BY name";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        echo "<table id='subcontractors_table'><th>Subcontractor ID</th><th>Subcontractor Name</th><th>BuildSmart Code</th><th>Action</th>";
        while($row = $result->fetch_assoc()) {
        	//$myUrl = "window.open('http://testz.000webhostapp.com/subcontracts.php?subcontractor_id=".$row["subcontractor_id"]."')";
            $myUrl = "subcontracts.php?subcontractor_id=".$row["subcontractor_id"]."'";
        	//$myUrl = "http://localhost/test/subcontracts.php?subcontractor_id=".$row["subcontractor_id"]."'";

            echo "<tr><td>". $row["subcontractor_id"]."</td><td>".$row["name"]."</td><td class='mono'>".$row["ScBSCode"]."<td><a href='".$myUrl."'>Subcontracts</a></td></tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }
    $conn->close();
    echo $myUrl;
    echo "<br>"."http://$_SERVER[HTTP_HOST]";
    ob_end_flush();
    ?>
  </body>
</html>
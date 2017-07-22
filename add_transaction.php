<?php
    ob_start();
    session_start();
    if (!isset($_SESSION['userName'])) {
        header("Location: login.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript">
            function checkAllowedChars(criteria, str) {
                var stringLength = str.length;
                if (stringLength == 0) {
                    return false;
                }
                for (var i=0; i<stringLength; i++){
                    if (criteria.search(str[i])<0) {
                        return false;
                    }
                }
                return true;
            }
            function formatAsNumber(element){
                if(checkAllowedChars("-+0123456789.,", element.value)) {
                    element.value = parseFloat(element.value).toFixed(3);
                } else {
                    element.value = "0.000";
                }
            }

            function updateTotals(){
                var totalRecons = 0.0;
                var recon_amounts = document.getElementsByClassName("recon_amount");
                for (var i=0; i<recon_amounts.length; i++){
                    if (recon_amounts[i].value.length>0) {
                        if (parseFloat(recon_amounts[i].value) !== 0) {
                            recon_amounts[i].classList.add('notZero');
                        } else {
                            recon_amounts[i].classList.remove('notZero');
                        }
                        totalRecons += parseFloat(recon_amounts[i].value);
                        currentRow = 
                        document.getElementById('payment_log').rows[i+1].cells[6].innerHTML =(
                            parseFloat(document.getElementById('payment_log').rows[i+1].cells[3].innerHTML.replace(",",""))+
                            parseFloat(document.getElementById('payment_log').rows[i+1].cells[4].innerHTML.replace(",",""))+
                            parseFloat(recon_amounts[i].value)
                            ).toFixed(3);
                    }
                }
                document.getElementById("trans_amounts_sum").value = totalRecons.toFixed(3);
                document.getElementById("trans_amount_balance").value = (totalRecons-parseFloat(document.getElementById("trans_amount").value)).toFixed(3);
                if (document.getElementById("trans_amount_balance").value == 0){
                    document.getElementById("trans_amount_balance").className = "z_valid";
                } else {
                    document.getElementById("trans_amount_balance").className = "z_invalid";
                }
            }
            function validate_page(){
                console.log("validating values..");
                var transaction_amount = parseFloat(document.getElementById("trans_amount").value);
                if (!(transaction_amount < 0)) {
                    console.log("problem with transaction amount");
                    return false;
                }
                var trans_amount_balance = parseFloat(document.getElementById("trans_amount_balance").value);
                if (!(trans_amount_balance == 0)) {
                    console.log("problem with balance");
                    return false;
                }
                var trans_no = document.getElementById("trans_no").value;
                if (trans_no == "") {
                    console.log("problem with trans_no");
                    return false;
                }
                var trans_date = document.getElementById("trans_date").value;
                if (trans_date == "") {
                    console.log("problem with date");
                    return false;
                }
                console.log("values looks ok, proceeding..")
                return true;
            }

            function prepare_sql() {
                if (!(validate_page())) {
                    return false;
                }
                var trans_id = parseInt(document.getElementById('trans_id').value);
                var next_trans_id = trans_id;
                var trans_no = document.getElementById("trans_no").value;
                var trans_date = document.getElementById("trans_date").value;
                var sql = "INSERT INTO `subcontractor_payment_transaction`(`trans_id`, `cert_id`, `trans_no`, `trans_date`, `trans_amount`, `remarks`) VALUES ";
                var recon_amounts = document.getElementsByClassName("recon_amount");
                for (var i=0; i<recon_amounts.length; i++){
                    if (recon_amounts[i].value.length>0) {
                        if (parseFloat(recon_amounts[i].value) !== 0) {
                            sql += "\n(" + next_trans_id + ", " + recon_amounts[i].id + ", '" + trans_no + "', '" + trans_date + "', " + recon_amounts[i].value + ", ''), ";
                            next_trans_id += 1;
                        }
                    }
                }
                if (next_trans_id > trans_id) {
                    sql = sql.substring(0, sql.length - 2);
                    console.log("Our sql for this form is: ");
                    console.log(sql);
                    return sql;
                }
            }

            function post_form() {
                if (validate_page()) {
                    document.getElementById("qry").value = prepare_sql();
                    document.getElementById("action_form").submit();
                }
            }
        </script>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="stylesheet.css">
        <title>Add transaction</title>
    </head>
    <body>
        <?php
            //CONNECT TO DATABASE
            include 'db_connection.php';

            $totalCertified = 0.0;
            $totalPaid = 0.0;
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
        ?>

        <h1>Subcontract Payment Transaction</h1>
        <h2>Subcontractor: <?php echo $subcontractor_name ?></h2>
        <h2>Scope: <?php echo $subcontract_scope ?></h2>
        <!-- GET MAX TRANSACTION ID -->
        <?php
            $next_trans_id = 0;
            $sql = "
                    SELECT (MAX(trans_id)+1) as next_trans_id from subcontractor_payment_transaction
                    ";
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()) {
                $next_trans_id = $row["next_trans_id"];
            }
        ?>

        <h3>Transaction Details</h3>
        <table id="transaction_details">
            <tr><td>Transaction ID</td><td><input type="number" id="trans_id" onfocus="this.select()" disabled="disabled" value="<?php echo $next_trans_id ?>"></td></tr>
            <tr><td>Transaction Number</td><td><input type="text" id="trans_no" onfocus="this.select()" placeholder="Transaction reference is the number on the transaction (Bank/Petty cash/other), not unique."></td></tr>
            <tr><td>Transaction Date</td><td><input type="date" id="trans_date"></td></tr>
            <tr><td>Transaction Amount</td><td><input type="number" value="0.000" id="trans_amount" onchange="updateTotals()" onblur="formatAsNumber(this)" onfocus="this.select()" placeholder="Transaction amount is always negative."></td></tr>
            <tr><td>Sum of allocated amounts</td><td><input type="number" value="0.000" id="trans_amounts_sum" readonly="readonly"></td></tr>
            <tr><td>Balance</td><td><input type="number" value="0.000" id="trans_amount_balance" readonly="readonly" class="z_valid"></td></tr>
        </table>
        <h3>Transaction/Payment reconcilliation</h3>
        <table id="payment_log"><th>Cert. ID</th><th>Payment sn</th><th>Payment date</th><th>Amount</th><th>Paid Amount</th><th>This Transaction</th><th>Balance</th>
        <?php
            $sql = "
                SELECT
                    subcontractor_payment_cert.cert_id,
                    subcontractor_payment_cert.subcontract_id,
                    subcontractor_payment_cert.payment_sn,
                    subcontractor_payment_cert.payment_date,
                    subcontractor_payment_cert.work_exec,
                    subcontractor_payment_cert.ap,
                    subcontractor_payment_cert.on_acc,
                    subcontractor_payment_cert.variations,
                    subcontractor_payment_cert.mos,
                    subcontractor_payment_cert.ret,
                    subcontractor_payment_cert.ret_rel,
                    subcontractor_payment_cert.ap_rec,
                    subcontractor_payment_cert.used_mos,
                    subcontractor_payment_cert.deductions,
                    subcontractor_payment_cert.prev_paid,
                    subcontractor_payment_cert.amount,
                    subcontractor_payment_cert.remarks,
                    IFNULL(SUM(subcontractor_payment_transaction.trans_amount), 0) AS 'sum_paid'
                FROM subcontractor_payment_cert
                LEFT JOIN subcontractor_payment_transaction
                ON subcontractor_payment_cert.cert_id = subcontractor_payment_transaction.cert_id
                WHERE subcontractor_payment_cert.subcontract_id = ".$subcontract_id.
                " GROUP BY subcontractor_payment_cert.cert_id";

            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                // output data of each row
                while($row = $result->fetch_assoc()) {
                    echo "<tr id='row_".$row["cert_id"]."''><td>".$row["cert_id"];
                    echo "</td><td>".$row["payment_sn"];
                    echo "</td><td>".$row["payment_date"];
                    echo "</td><td class='money'>".number_format($row["amount"],3);
                    echo "</td><td class='money'>".number_format($row["sum_paid"],3);
                    echo "</td><td><input type='number' id='".$row["cert_id"]."' class='recon_amount' value='0.000' onchange='updateTotals()' onblur='formatAsNumber(this)' onfocus='this.select()'>";
                    echo "</td><td class='money'>".number_format($row["amount"]+$row["sum_paid"],3);
                    echo "</td></tr>";
                    $totalCertified += $row["amount"];
                }
                echo "</table>";
            } else {
                echo "0 results";
            }
        ?>
        </table>
        <form id="action_form" action="action.php" method="post">
            <input type="hidden" id="user_command" name="user_command" value="insert_transactions">
            <input type="hidden" id="qry" name="qry" value="">
        </form>
        <input type="button" onclick="post_form()" value="Post transactions">
        <h3>List of previous payments</h3>
        <?php
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
            $conn->close();
        ?>
        <h3>Summary of account</h3>
        <?php
            echo "<table><th>Total Certified</th><th>Total Paid</th><th>Balance</th>";
            echo "<tr><td class='money'>".number_format($totalCertified,3)."</td><td class='money'>".number_format($totalPaid,3)."</td><td class='money'>".number_format(($totalCertified+$totalPaid),3)."</td></tr>";
            echo "</table>";
            echo "<br><br><br><br>";
            number_format($totalCertified,3);
            ob_end_flush();
        ?>
    </body>
<!--
==================WHERE DID WE STOP?======================
1- 
-->
</html>
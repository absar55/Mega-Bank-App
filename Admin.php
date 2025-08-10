<?php
session_start();
require __DIR__ . '/encryption.php';
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
$mode = 'decrypt0172';
$mode1 = 'encrypt0172';
$hash = $_SESSION["username"];
$username = aes($hash, $mode);
$nu=NULL;



$username_hash=$hash;

$host = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "bank";
// Create connection
$conn = new mysqli ($host, $dbusername, $dbpassword, $dbname);
    if (mysqli_connect_error())
    {
      die('Connect Error ('. mysqli_connect_errno() .') '
     . mysqli_connect_error());
    }
    else
    {
	
     $query = "SELECT * FROM users WHERE username = '$username_hash'";
      $run_query = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($run_query);
   

        $name=$row["username"];
		$balance_hash=$row["balance"];
		$account_number_hash=$row["account_number"];
		$role=$row["role"];

		$balance = aes($balance_hash, $mode);
		$account_number = aes($account_number_hash, $mode);


		$_SESSION["account_number"]=$account_number_hash;








		if ($role=='Customer')
		{
			header("location: Dashboard.php");


		}
    }

?>





























<!DOCTYPE html>
<html>
<style>
/* Global Styles */
/* Global Styles */

* {
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

h1 {
    font-size: 36px;
    font-weight: bold;
    margin-bottom: 20px;
}

h2 {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 10px;
}

p {
    font-size: 16px;
    line-height: 1.5;
    margin-bottom: 20px;
}

button {
    background-color: #4CAF50;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background-color: #3e8e41;
}

input[type=text],
input[type=email],
input[type=tel],
textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 4px;
    resize: vertical;
}

label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}

table {
    border-collapse: collapse;
    width: 100%;
    margin-bottom: 20px;
}

th,
td {
    text-align: left;
    padding: 12px;
}

th {
    background-color: #4CAF50;
    color: #fff;
}

tr:nth-child(even) {
    background-color: #f2f2f2;
}

/* Layout Styles */

.container {
    display: flex;
    flex-wrap: wrap;
}

.dashboard {
    flex: 3;
    padding: 20px;
}

.sidebar {
    flex: 1;
    background-color: #f2f2f2;
    padding: 20px;
}

#transaction-history {
    margin-bottom: 40px;
}

#edit-profile {
    max-width: 600px;
    margin: 0 auto;
}

/* Login Page Styles */

.login-page {
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.login-form {
    background-color: #f2f2f2;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
    max-width: 500px;
    margin: 0 auto;
}

.login-form h2 {
    text-align: center;
    margin-bottom: 20px;
}

.login-form label {
    display: block;
    margin-bottom: 5px;
}

.login-form input[type=text],
.login-form input[type=password] {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: none;
    border-radius: 5px;
    background-color: #fff;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.login-form button {
    background-color: #4CAF50;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
}

.login-form button:hover {
    background-color: #3e8e41;
}

/* Account Details Styles */

.account-details {
    max-width: 600px;
    margin: 0 auto;
    background-color: #f2f2f2;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
}

.account-details h2 {
    margin-bottom: 20px;
}

.account-details label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}

.account-details p {
    margin-bottom: 10px;
}

.account-details input[type=text],
.account-details input[type=email],
.account-details input[type=tel],
.account-details textarea {
    margin-bottom: 20px;
}

.account-details button {
    background-color: #4CAF50;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
}

.account-details button:hover {
    background-color: #3e8e41;
}

/* Transaction History Styles */

.transaction-history-table {
    max-width: 800px;
    margin: 0 auto;
}

.transaction-history-table th:first-child,
.transaction-history-table td:first-child {
    width: 30%;
}

.transaction-history-table th:nth-child(2),
.transaction-history-table td:nth-child(2) {
    width: 20%;
}

.transaction-history-table th:nth-child(3),
.transaction-history-table td:nth-child(3) {
    width: 25%;
}

.transaction-history-table th:last-child,
.transaction-history-table td:last-child {
    width: 25%;
}

.transaction-history-table button {
    background-color: #4CAF50;
    color: #fff;
    border: none;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
}

.transaction-history-table button:hover {
    background-color: #3e8e41;
}

form {
    margin: 50px auto;
    width: 90%;
    background-color: #DFF2FF;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    padding: 20px;
}
</style>


<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="Styles/style.css">
</head>

<body style="  background-color: lightblue;">
    <header>




        <h1>Admin Dashboard</h1>
        <a href="Dashboard.php"><img src="./Bank/Bank-logos_white.png" alt="" width="150" height="150"></a>

    </header>
    <nav>
        <ul>
            <li><a href="Loan_Requests.php" style="color:black;">Loan Requests</a></li>
            <li><a href="logout.php" style="color:black;">Log Out</a></li>

        </ul>
    </nav>







    <main>
        <h2>All Accounts</h2>
        <table>
            <thead>
                <tr>
                    <th>Account Number</th>
                    <th>Account Holder</th>
                    <th>Balance</th>
                    <th>Joined On</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
      $sql = "SELECT * FROM users WHERE role = 'Customer'";
      $results = mysqli_query($conn, $sql);



      while ($row = mysqli_fetch_assoc($results))
      {
		$c_username=$row["username"];
		$c_account_number=$row["account_number"];
		$c_balance=$row["balance"];
		$c_date=$row["created_at"];

		$send=$c_account_number;

		$c_username = aes($c_username, $mode);
		$c_account_number = aes($c_account_number, $mode);
		$c_balance = aes($c_balance, $mode);


?>
                <tr>




                    <td><?php echo $c_username; ?></td>
                    <td><?php echo $c_account_number; ?></td>
                    <td>Rs <?php echo $c_balance; ?></td>
                    <td><?php echo $c_date; ?></td>
                    <td>

                        <button
                            onclick="location.href='admin1.php?var=<?php echo $send;?>,<?php echo $nu;?>';">More</button>

                    </td>




                </tr>
                <?php

}
?>

            </tbody>
        </table>
    </main>




    <footer>
        <p style="text-align: center; padding-top:10%;">&copy; 2023 Bank Dashboard</p>
    </footer>
    <script src="JavaScript/function1.js"></script>
</body>

</html>
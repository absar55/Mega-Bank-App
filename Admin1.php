<?php
$change_username = filter_input(INPUT_POST, 'name');
$change_password = filter_input(INPUT_POST, 'password');
session_start();

$var = explode(",", $_GET["var"]);
$user1=$var[0];


require __DIR__ . '/encryption.php';
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
$mode = 'decrypt0172';
$mode1 = 'encrypt0172';
$hash = $_SESSION["username"];
$username = aes($hash, $mode);

$user2=$var[0];
$user1 = aes($var[0], $mode);



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


		if (($change_username!=NULL) && (($change_password!=NULL)))
		{
			$change_username = aes($change_username, $mode1);
			$change_password = aes($change_password, $mode1);


			$sql = "UPDATE users SET username='$change_username' WHERE account_number='$user2'";
			$run_sql = mysqli_query($conn, $sql);

			$sql = "UPDATE users SET password='$change_password' WHERE account_number='$user2'";
			$run_sql = mysqli_query($conn, $sql);

		}









		if ($var[1]!=NULL)
		{


			$query = "SELECT * FROM transaction WHERE tid = '$var[1]'";
			$run_query = mysqli_query($conn, $query);
			$row = mysqli_fetch_assoc($run_query);
		 
	  
			  $t_sender_hash=$row["sender"];
			  $t_receiver_hash=$row["receiver"];
			  $t_amount_hash=$row["amount"];

			  $t_sender = aes($t_sender_hash, $mode);
			  $t_receiver = aes($t_receiver_hash, $mode);
			  $t_amount = aes($t_amount_hash, $mode);


			  $query = "SELECT * FROM users WHERE account_number = '$t_sender_hash'";
			  $run_query = mysqli_query($conn, $query);
			  $row = mysqli_fetch_assoc($run_query);

			  $update_balance_hash=$row["balance"];
			  $update_balance = aes($update_balance_hash, $mode);

			  $update_balance=$update_balance+$t_amount;
			  $update_balance_hash = aes($update_balance, $mode1);



			  
			  $sql = "UPDATE users SET balance='$update_balance_hash' WHERE account_number='$t_sender_hash'";
			  $run_sql = mysqli_query($conn, $sql);




			  $query = "SELECT * FROM users WHERE account_number = '$t_receiver_hash'";
			  $run_query = mysqli_query($conn, $query);
			  $row = mysqli_fetch_assoc($run_query);

			  $update_balance_hash=$row["balance"];
			  $update_balance = aes($update_balance_hash, $mode);

			  $update_balance=$update_balance-$t_amount;

			  $update_balance_hash = aes($update_balance, $mode1);


			  $sql = "UPDATE users SET balance='$update_balance_hash' WHERE account_number='$t_receiver_hash'";
			  $run_sql = mysqli_query($conn, $sql);




			$sql = "DELETE FROM transaction WHERE tid='$var[1]'";
			$run_sql = mysqli_query($conn, $sql);



		}






	
     $query = "SELECT * FROM users WHERE username = '$hash'";
      $run_query = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($run_query);
   

        $name=$row["username"];
		$balance_hash=$row["balance"];
		$account_number_hash=$row["account_number"];
		$role=$row["role"];

		$balance = aes($balance_hash, $mode);
		$account_number = aes($account_number_hash, $mode);







		$query = "SELECT * FROM users WHERE account_number = '$var[0]'";
		$run_query = mysqli_query($conn, $query);
		  $row = mysqli_fetch_assoc($run_query);
	 
  
		  $c_name_hash=$row["username"];
		  $c_password_hash=$row["password"];

		  $c_balance_hash=$row["balance"];
  		


		  $c_name = aes($c_name_hash, $mode);
		  $c_password = aes($c_password_hash, $mode);

		  $c_balance = aes($c_balance_hash, $mode);










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

input[type=text], input[type=email], input[type=tel], textarea {
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

th, td {
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

.login-form input[type=text], .login-form input[type=password] {
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

.account-details input[type=text], .account-details input[type=email], .account-details input[type=tel], .account-details textarea {
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

.transaction-history-table th:first-child, .transaction-history-table td:first-child {
	width: 30%;
}

.transaction-history-table th:nth-child(2), .transaction-history-table td:nth-child(2) {
	width: 20%;
}

.transaction-history-table th:nth-child(3), .transaction-history-table td:nth-child(3) {
	width: 25%;
}

.transaction-history-table th:last-child, .transaction-history-table td:last-child {
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
		<ul >

			<li><a href="logout.php" style="color:black;">Log Out</a></li>

		</ul>
	</nav>







	<div class="container">
	<div class="dashboard">
		<div id="account-summary">
			<h2>Account Summary</h2>
			<table>
				<tr>
					<th>Account Number</th>
					<td><?php  echo $user1;?></td>
				</tr>
				<tr>
					<th>Account Holder</th>
					<td><?php  echo $c_name;?></td>
				</tr>
				<tr>
					<th>Available Balance</th>
					<td>Rs <?php  echo $c_balance;?></td>
				</tr>
				<tr>
					<th>Account Status</th>
					<td>Active</td>
				</tr>
			</table>
		</div>






		<div id="transaction-history">
			<h2>Received</h2>
			<table>
				<thead>
					<tr>
						<th>Date</th>
						<th>Sender</th>
						<th>Amount</th>
						<th>Status</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>





<?php
      $sql = "SELECT * FROM transaction WHERE receiver = '$user2'";
      $results = mysqli_query($conn, $sql);



      while ($row = mysqli_fetch_assoc($results))
      {

		$temp_tid = $row["tid"];
		$temp_date = $row["date"];
		$temp_sender = aes($row["sender"], $mode);
		$temp_amount = aes($row["amount"], $mode);


?>




					<tr>
						<td><?php echo $temp_date; ?></td>
						<td><?php echo $temp_sender; ?></td>
						<td><?php echo $temp_amount; ?></td>
						<td>Completed</td>
						<td>

							<button onclick="location.href='admin1.php?var=<?php echo $user2;?>,<?php echo $temp_tid;?>';">Reverse</button>
						</td>
					</tr>


	
					<?php

}
?>	


	

	
				</tbody>
			</table>
		</div>



		<div id="transaction-history">
			<h2>Sent</h2>
			<table>
				<thead>
					<tr>
						<th>Date</th>
						<th>Receiver</th>
						<th>Amount</th>
						<th>Status</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>


				<?php
      $sql = "SELECT * FROM transaction WHERE sender = '$user2'";
      $results = mysqli_query($conn, $sql);



      while ($row = mysqli_fetch_assoc($results))
      {

		$temp_tid = $row["tid"];
		$temp_date = $row["date"];
		$temp_receiver = aes($row["receiver"], $mode);
		$temp_amount = aes($row["amount"], $mode);


?>




					<tr>
						<td><?php echo $temp_date; ?></td>
						<td><?php echo $temp_receiver; ?></td>
						<td><?php echo $temp_amount; ?></td>
						<td>Completed</td>
						<td>
							<button onclick="location.href='admin1.php?var=<?php echo $user2;?>,<?php echo $temp_tid;?>';">Reverse</button>
						</td>
					</tr>
				

<?php

}
?>				


			
				</tbody>
			</table>
		</div>





<form action="admin1.php?var=<?php echo $var[0];?>" method="post">
  <h2 style="text-align: center;">Edit Profile</h2>
  <label for="name">Name:</label>
  <input type="text" id="name" name="name" value="<?php echo $c_name;?>">
  <label for="password">Password:</label>
  <input type="text" id="password" name="password" value="<?php echo $c_password;?>">
  <label for="account-number">Account Number:</label>
  <input type="text" id="account-number" name="account-number" value="<?php echo $user1;?>" readonly>

  <div style="text-align: center;">

  <button type="submit">Save Changes</button>
</div>
 </form>






	<footer>
		<p style="text-align: center; padding-top:10%;">&copy; 2023 Bank Dashboard</p>
	</footer>
	<script src="JavaScript/function1.js"></script>
</body>
</html>

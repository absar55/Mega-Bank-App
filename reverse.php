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
	
     $query = "SELECT * FROM users WHERE username = '$hash'";
      $run_query = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($run_query);
   

        $name=$row["username"];
		$balance_hash=$row["balance"];
		$account_number_hash=$row["account_number"];

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

    }

?>
 






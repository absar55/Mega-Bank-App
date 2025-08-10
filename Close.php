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

        $query = "DELETE  FROM users WHERE username = '$username_hash'";

    

        if (mysqli_query($conn, $query))
        {

            
            header("location: logout.php");
            
        }
        




		if ($role=='Admin')
		{
			header("location: Admin.php");


		}

    }

?>
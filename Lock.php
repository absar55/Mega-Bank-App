<?php

$locked = filter_input(INPUT_POST, 'lock');
$unlocked = filter_input(INPUT_POST, 'unlock');

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
        $lock=$row["locked"];
        
		$balance = aes($balance_hash, $mode);
		$account_number = aes($account_number_hash, $mode);
        


		$_SESSION["account_number"]=$account_number_hash;

        if ((isset($locked)) && ($locked==1))
        {
            $sql = "UPDATE users SET locked='1' WHERE username='$username_hash'";
            $run_sql = mysqli_query($conn, $sql);
            $lock=1;
            
        }

        if ((isset($unlocked)) && ($unlocked==1))
        {
            $sql = "UPDATE users SET locked='0' WHERE username='$username_hash'";
            $run_sql = mysqli_query($conn, $sql);
            $lock=0;

        }




		if ($role=='Admin')
		{
			header("location: Admin.php");


		}

    }

?>













<!DOCTYPE html>
<html>

<head>
    <title>Bank Lock/Unlock</title>
    <link rel="stylesheet" href="Styles/style.css">
</head>

<body style="  background-color: lightblue;">
    <header>




        <h1>Lock/Unlock</h1>
        <a href="Dashboard.php"><img src="./Bank/Bank-logos_white.png" alt="" width="150" height="150"></a>

    </header>
    <nav>
        <ul>
            <li><a href="Dashboard.php" style="color:black;">Dashboard</a></li>
            <li><a href="Investment.php" style="color:black;">Investment</a></li>
            <li><a href="Loan.php" style="color:black;">Loan</a></li>
            <li><a href="Credit_Card.php" style="color:black;">Credit Card</a></li>
            <li><a href="Debit_Card.php" style="color:black;">Debit Card</a></li>
            <li><a href="Lock.php" style="color:black;">Lock/Unlock</a></li>
            <li><a href="Close.php" style="color:black;">Account Closure</a></li>
            <li><a href="logout.php" style="color:black;">Log Out</a></li>

        </ul>
    </nav>
    <main>






        <section style="text-align:center;">




            <?php
                    if ($lock==1)

                    {
            ?>


            <h2>Unlock Account</h2>
            <form method="POST" enctype="multipart/form-data" action="Lock.php">


                <input type="hidden" name="unlock" value="1">
                <button type="submit" name="submit">Unlock</button>

            </form>

            <?php

                    }

                    else if ($lock==0)

                    {

                        ?>
            <h2>Lock Account</h2>
            <form method="POST" enctype="multipart/form-data" action="Lock.php">


                <input type="hidden" name="lock" value="1">
                <button type="submit" name="submit">Lock</button>

            </form>
            <?php



                    }
            ?>


        </section>

    </main>
    <footer>
        <p style="text-align: center; padding-top:10%;">&copy; 2023 Bank Dashboard</p>
    </footer>
    <script src="JavaScript/function1.js"></script>


</body>

</html>
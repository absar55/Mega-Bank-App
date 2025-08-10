<?php

$receiver = filter_input(INPUT_POST, 'receiver');
$amount = filter_input(INPUT_POST, 'amount');


session_start();


if (!isset($_SESSION["apply"]))
{
    $_SESSION["apply"] = 0;
}


if (!isset($_SESSION["apply1"]))
{
    $_SESSION["apply1"] = 0;
}
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


		if ($receiver)
		{
		$receiver_hash = aes($receiver, $mode1);
		$amount_hash = aes($amount, $mode1);

		$sql = "SELECT * FROM users WHERE account_number='$receiver_hash'";
		$results = mysqli_query($conn, $sql);
		if (mysqli_num_rows($results) > 0) 
		{


			

            $query = "SELECT * FROM users WHERE account_number = '$receiver_hash'";
            $run_query = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($run_query);

            $locked_receiver=$row["locked"];

            if ($locked_receiver==1)
            {
                echo "<script>alert('Receiver has locked his/her wallet. Please try again later!');</script>";
            }


            else

            {



                        
                        // Start the transaction
                        mysqli_begin_transaction($conn);
                    
                        try {
                            $sender_hash = $_SESSION["account_number"];
                            $receiver_hash = mysqli_real_escape_string($conn, $receiver_hash);
                            $amount_hash = mysqli_real_escape_string($conn, $amount_hash);
                    
                            // Insert the transaction record
                            $sql = "INSERT INTO transaction (sender, receiver, amount) VALUES ('$sender_hash','$receiver_hash', '$amount_hash')";
                            mysqli_query($conn, $sql);
                    
                            // Update sender's balance
                            $query = "SELECT balance FROM users WHERE account_number = '$sender_hash' FOR UPDATE";
                            $run_query = mysqli_query($conn, $query);
                            $row = mysqli_fetch_assoc($run_query);      
                            $sender_balance = aes($row["balance"], $mode);
                            $sender_balance = $sender_balance - $amount;
                            $sender_balance = aes($sender_balance, $mode1);
                            $sql = "UPDATE users SET balance='$sender_balance' WHERE account_number='$sender_hash'";
                            mysqli_query($conn, $sql);
                    
                            // Update receiver's balance
                            $query = "SELECT balance FROM users WHERE account_number = '$receiver_hash' FOR UPDATE";
                            $run_query = mysqli_query($conn, $query);
                            $row = mysqli_fetch_assoc($run_query);
                
                            $receiver_balance = aes($row["balance"], $mode);
                            $receiver_balance = $receiver_balance + $amount;
                            $receiver_balance = aes($receiver_balance, $mode1);          
                            $sql = "UPDATE users SET balance='$receiver_balance' WHERE account_number='$receiver_hash'";
                            mysqli_query($conn, $sql);
                    
                            // Commit the transaction
                            mysqli_commit($conn);
                        } catch (Exception $e) {
                            // Rollback the transaction if an exception is thrown
                            mysqli_rollback($conn);
                        }




        }


		







		
		}
		else
		{
			echo "Invalid Account Number.";
			die();
		}


		}

        

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






		if ($role=='Admin')
		{
			header("location: Admin.php");


		}

    }

?>













<!DOCTYPE html>
<html>

<head>
    <title>Bank Dashboard</title>
    <link rel="stylesheet" href="Styles/style.css">
</head>

<body style="  background-color: lightblue;">
    <header>




        <h1>Dashboard</h1>
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
        <section>
            <h2>Account Holder: <?php echo $username; ?></h2>
            <ul>
                <li>
                    <p>Balance: Rs <?php echo $balance; ?></p>
                </li>
                <li>
                    <h3>Account Number</h3>
                    <p><?php echo $account_number; ?></p>
                </li>
            </ul>
        </section>




        <section>
            <h2>Received</h2>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Sender</th>
                        <th>Name</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>


                    <?php
      $sql = "SELECT * FROM transaction WHERE receiver = '$account_number_hash'";
      $results = mysqli_query($conn, $sql);



      while ($row = mysqli_fetch_assoc($results))
      {
?>






                    <tr>
                        <td><?php echo $row["date"];
						
						$temp_sender = aes($row["sender"], $mode);
						$temp_amount = aes($row["amount"], $mode);




						$queryy = "SELECT u.username FROM users u INNER JOIN transaction t ON u.account_number = t.sender";
						$run_queryy = mysqli_query($conn, $queryy);
						$roww = mysqli_fetch_assoc($run_queryy);
						$temp_uname= aes($roww["username"], $mode);

						?></td>
                        <td><?php echo $temp_sender ?></td>
                        <td><?php echo $temp_uname ?></td>

                        <td><?php echo $temp_amount ?></td>
                    </tr>


                    <?php

	}
?>
                </tbody>
            </table>
        </section>



        <section>
            <h2>Sent</h2>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Receiver</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>


                    <?php
      $sql = "SELECT * FROM transaction WHERE sender = '$account_number_hash'";
      $results = mysqli_query($conn, $sql);



      while ($row = mysqli_fetch_assoc($results))
      {
?>


                    <tr>
                        <td><?php echo $row["date"];
						
						$temp_receiver = aes($row["receiver"], $mode);
						$temp_amount = aes($row["amount"], $mode);

						?>
                        </td>
                        <td><?php echo $temp_receiver; ?></td>
                        <td><?php echo $temp_amount; ?></td>
                    </tr>
                    <?php

		}
?>
                </tbody>
            </table>
        </section>


        <?php
        if ($lock==0)

        {

        ?>

        <section>
            <h2>Payments</h2>
            <form method="POST" enctype="multipart/form-data" action="Dashboard.php">
                <label for="payee">Payee Account Number:</label>
                <input type="number" id="payee" name="receiver">
                <label for="amount">Amount:</label>
                <input type="number" min="1" max="<?php echo $balance;?>" id="amount" name="amount">
                <button type="submit" name="submit">Submit</button>
            </form>
        </section>


        <?php

        }
        ?>

    </main>
    <footer>
        <p style="text-align: center; padding-top:10%;">&copy; 2023 Bank Dashboard</p>
    </footer>
    <script src="JavaScript/function1.js"></script>
</body>

</html>
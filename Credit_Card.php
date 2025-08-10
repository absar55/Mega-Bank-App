<?php

$apply = filter_input(INPUT_POST, 'apply');


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





        if ((isset($apply)) && ($apply==1))
        {

            $_SESSION["apply"]=1;
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
    <title>Bank Credit Card</title>
    <link rel="stylesheet" href="Styles/style.css">

    <style>
    .activate-btn {
        display: none;
    }
    </style>
</head>

<body style="  background-color: lightblue;">
    <header>




        <h1>Credit Card</h1>
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


        <?php


            if ($_SESSION["apply"]==0)
            {

            

?>
        <section>
            <h2>Apply For Credit Card</h2>
            <form method="POST" enctype="multipart/form-data" action="Credit_Card.php">
                <input type="hidden" name="apply" value="1">

                <button type="submit" name="submit">Apply</button>
            </form>
        </section>


        <?php

}

else if ($_SESSION["apply"]==1)

{


?>


        <section>
            <h2>Credit Card Details</h2>

            <label>Card Number:</label>
            <p id="cardNumber"></p>

            <label>Expiry Date:</label>
            <p id="expiryDate">MM/YY</p>

            <label>CVV:</label>
            <p id="cvv">123</p>

            <label>Cardholder Name:</label>
            <p><?php echo $username ?></p>

            <button onclick="deactivateCreditCard()">Deactivate Credit Card</button>
            <button class="activate-btn" onclick="activateCreditCard()">Activate Credit Card</button>
        </section>
        <?php

}
?>

    </main>
    <footer>
        <p style="text-align: center; padding-top:10%;">&copy; 2023 Bank Dashboard</p>
    </footer>
    <script src="JavaScript/function1.js"></script>


    <script>
    function generateRandomCreditCard() {
        var storedCardNumber = localStorage.getItem('cardNumber');
        var storedExpiryDate = localStorage.getItem('expiryDate');
        var storedCvv = localStorage.getItem('cvv');
        var storedCardHolderName = localStorage.getItem('cardHolderName');

        if (storedCardNumber && storedExpiryDate && storedCvv && storedCardHolderName) {
            document.getElementById('cardNumber').textContent = storedCardNumber;
            document.getElementById('expiryDate').textContent = storedExpiryDate;
            document.getElementById('cvv').textContent = storedCvv;
            document.getElementById('cardHolderName').textContent = storedCardHolderName;

            var isDeactivated = localStorage.getItem('isDeactivated');
            if (isDeactivated === 'true') {
                document.querySelector('.activate-btn').style.display = 'block';
            }
        } else {
            var cardNumber = Math.floor(Math.random() * 10000 + 1000) + ' ' + Math.floor(Math.random() * 10000 + 1000) +
                ' ' + Math.floor(Math.random() * 10000 + 1000) +
                ' ' + Math.floor(Math.random() * 10000 + 1000);
            var expiryDate = ('0' + (Math.floor(Math.random() * 12) + 1)).slice(-2) + '/' + (Math.floor(Math.random() *
                9) + 22);
            var cvv = Math.floor(Math.random() * 900 + 100);
            var cardHolderName = 'John Doe';

            document.getElementById('cardNumber').textContent = cardNumber;
            document.getElementById('expiryDate').textContent = expiryDate;
            document.getElementById('cvv').textContent = cvv;
            document.getElementById('cardHolderName').textContent = cardHolderName;

            localStorage.setItem('cardNumber', cardNumber);
            localStorage.setItem('expiryDate', expiryDate);
            localStorage.setItem('cvv', cvv);
            localStorage.setItem('cardHolderName', cardHolderName);
        }
    }

    generateRandomCreditCard();

    function deactivateCreditCard() {
        localStorage.setItem('isDeactivated', 'true');

        document.querySelector('button').style.display = 'none';

        document.querySelector('.activate-btn').style.display = 'block';

        alert('Credit card deactivated successfully!');
    }

    function activateCreditCard() {
        localStorage.removeItem('isDeactivated');

        document.querySelector('button').style.display = 'block';

        document.querySelector('.activate-btn').style.display = 'none';

        alert('Credit card activated successfully!');
    }
    </script>
</body>

</html>
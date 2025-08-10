<?php

$loan_amount = filter_input(INPUT_POST, 'loan_amount');


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




        if ((isset($loan_amount)) && ($loan_amount>=1))

        {

		    $loan_amount = aes($loan_amount, $mode1);
            $sql1 = "INSERT INTO loan (account, amount)
            values ('$account_number_hash','$loan_amount')";
            $conn->query($sql1);
                
            
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
    <title>Bank Loan</title>
    <link rel="stylesheet" href="Styles/style.css">
</head>

<body style="  background-color: lightblue;">
    <header>




        <h1>Loan</h1>
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
            <h2>Apply For Loan</h2>
            <form method="POST" enctype="multipart/form-data" action="Loan.php">
                <label for="amount">Loan Amount:</label>
                <input type="number" min="1" name="loan_amount">
                <button type="submit" name="submit">Submit</button>
            </form>

            <p style="color:red;">Interest is 8%</p>
        </section>






        <section>
            <h2>Loan History</h2>
            <table>
                <thead>
                    <tr>
                        <th>Amount</th>

                        <th>Paid</th>
                        <th>Status</th>

                    </tr>
                </thead>
                <tbody>


                    <?php
      $sql = "SELECT * FROM loan WHERE account = '$account_number_hash'";
      $results = mysqli_query($conn, $sql);



      while ($row = mysqli_fetch_assoc($results))
      {

        $temp_amount = aes($row["amount"], $mode);
        $temp_paid = $row["paid"];
        $temp_status=$row["status"];
?>






                    <tr>
                        <td><?php echo $temp_amount;?></td>
                        <td><?php echo $temp_paid ?></td>
                        <td><?php
                        
                        if ($temp_status==0)

                        {
                            echo "Not Approved";

                        }

                        else{
                            echo "Approved";
                        }
                         ?></td>
                    </tr>


                    <?php

	}
?>
                </tbody>
            </table>
        </section>




        <section>
            <h2>Loan Interest Calculator</h2>

            <div class="input-group">
                <label for="loanAmount">Loan Amount:</label>
                <input type="number" id="loanAmount" placeholder="Enter loan amount" required>
            </div>

            <div class="input-group">
                <label for="loanTerm">Loan Term (years):</label>
                <input type="number" id="loanTerm" placeholder="Enter loan term in years" required>
            </div>

            <button onclick="calculateInterest()" type="submit">Calculate Interest</button>

            <div id="result"></div>
        </section>






    </main>
    <footer>
        <p style="text-align: center; padding-top:10%;">&copy; 2023 Bank Dashboard</p>
    </footer>
    <script src="JavaScript/function1.js"></script>
    <script>
    function calculateInterest() {
        var loanAmount = parseFloat(document.getElementById('loanAmount').value);
        var interestRate = 8; // Fixed interest rate of 8%
        var loanTerm = parseFloat(document.getElementById('loanTerm').value);

        var monthlyInterestRate = interestRate / 100 / 12;
        var numberOfPayments = loanTerm * 12;

        var monthlyPayment = (loanAmount * monthlyInterestRate) / (1 - Math.pow(1 + monthlyInterestRate, -
            numberOfPayments));

        var totalRepayment = monthlyPayment * numberOfPayments;
        var totalInterest = totalRepayment - loanAmount;

        document.getElementById('result').innerHTML = `
                Monthly Payment: Rs ${monthlyPayment.toFixed(2)}<br>
                Total Repayment: Rs ${totalRepayment.toFixed(2)}<br>
                Total Interest: Rs ${totalInterest.toFixed(2)}
            `;
    }
    </script>
</body>

</html>
<?php
$invest_amount = filter_input(INPUT_POST, 'invest_amount');
$hfm_name = filter_input(INPUT_POST, 'hfm_name');


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



        if ((isset($invest_amount)) && (isset($hfm_name)))
        {

            if ($invest_amount<=$balance)
            {

                $balance=$balance-$invest_amount;
                $balance_hash = aes($balance, $mode1);

                $sql = "UPDATE users SET balance='$balance_hash' WHERE username='$username_hash'";
                $run_sql = mysqli_query($conn, $sql);

                $sql1 = "INSERT INTO investments (account, hfm, capital)
                values ('$account_number_hash','$hfm_name','$invest_amount')";
                $conn->query($sql1);

            }


            else
            {

                echo '<script>alert("Insufficient Balance!");</script>';

            }
            
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
    <title>Bank Investment</title>
    <link rel="stylesheet" href="Styles/style.css">
</head>

<body style="  background-color: lightblue;">
    <header>




        <h1>Investment</h1>
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




        <section class="new">

            <h2>Hedge Fund Managers</h2>
            <table>
                <thead>
                    <tr>
                        <th>HFM</th>
                        <th>Profit</th>
                        <th>Risk</th>
                        <th>Amount</th>
                        <th>Invest</th>
                    </tr>
                </thead>
                <tbody>


                    <?php
      $sql = "SELECT * FROM hfm";
      $results = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_assoc($results))
        {
  
          $temp_name=$row["name"];
          $temp_monthly_return=$row["monthly_return"];
          $temp_risk=$row["risk"];
        
?>






                    <tr>

                        <td><?php echo $temp_name ?></td>
                        <td><?php echo $temp_monthly_return ?></td>
                        <td><?php echo $temp_risk ?></td>
                        <form method="POST" enctype="multipart/form-data" action="Investment.php">
                            <td><input type="number" min="1" name="invest_amount"></td>
                            <input type="hidden" name="hfm_name" value="<?php echo $temp_name; ?>">
                            <td><button type="submit" name="submit">Invest</button></td>

                        </form>
                    </tr>


                    <?php

	}
?>
                </tbody>
            </table>
        </section>










        <section>

            <h2>Investments</h2>
            <table>
                <thead>
                    <tr>
                        <th>HFM</th>
                        <th>Capital</th>
                        <th>Profit</th>
                    </tr>
                </thead>
                <tbody>


                    <?php
      $sql = "SELECT * FROM investments where account = '$account_number_hash'";
      $results = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_assoc($results))
        {
  
          $temp_hfm=$row["hfm"];
          $temp_capital=$row["capital"];
          $temp_profit=$row["profit"];

        
?>






                    <tr>

                        <td><?php echo $temp_hfm ?></td>
                        <td><?php echo $temp_capital ?></td>
                        <td><?php echo $temp_profit ?></td>

                    </tr>


                    <?php

	}
?>
                </tbody>
            </table>
        </section>














        <section>
            <h2>Profit Calculator</h2>


            <div class="input-group">
                <label for="investedAmount">Invested Amount (Rs):</label>
                <input type="number" id="investedAmount" min="8000" placeholder="Enter invested amount (min Rs 8000)"
                    min="8000" required>
            </div>

            <div class="input-group">
                <label for="monthlyProfit">Monthly Profit Percentage:</label>
                <input type="number" id="monthlyProfit" placeholder="Enter monthly profit percentage" required>
            </div>

            <button type="submit" style=" margin-top: 10px;" onclick="calculateInvestment()">Calculate
                Investment</button>

            <div id="result"></div>
        </section>






    </main>
    <footer>
        <p style="text-align: center; padding-top:10%;">&copy; 2023 Bank Dashboard</p>
    </footer>
    <script src="JavaScript/function1.js"></script>

    <script>
    function calculateInvestment() {
        var investedAmount = parseFloat(document.getElementById('investedAmount').value);
        var monthlyProfitPercentage = parseFloat(document.getElementById('monthlyProfit').value);

        if (investedAmount < 8000) {
            alert('Invested amount must be at least Rs 8000.');
            return;
        }

        var monthlyProfit = (investedAmount * monthlyProfitPercentage) / 100;
        var totalValue = investedAmount + (monthlyProfit * 12 * 5); // Assuming 5 years of investment

        document.getElementById('result').innerHTML = `
                Monthly Profit: Rs ${monthlyProfit.toFixed(2)}<br>
                Total Value after 5 years: Rs ${totalValue.toFixed(2)}
            `;
    }
    </script>
</body>

</html>
<?php
session_start();
require __DIR__ . '/encryption.php';
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
$mode = 'decrypt0172';
$hash = $_SESSION["username"];
$username = aes($hash, $mode);
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="assets/bootstrap.css">
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="assets/css/style.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Hi, <b><?php echo $username; ?></b>. Welcome</h1>
    </div>

    <section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6 text-center mb-5">
					<h2 class="heading-section">Dashboard</h2>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="table-wrap">
						<table class="table">
						  <thead class="thead-primary">
						    <tr>
						      <th>No.</th>
						      <th>Salary</th>
						      <th>Date</th>
						      <th>Status</th>
						    </tr>
						  </thead>
						  <tbody>
						    <tr>
						      <th scope="row">1</th>
						      <td>38000</td>
						      <td>04-08-2022</td>
						      <td>Sent</td>
						    </tr>
						    <tr>
						      <th scope="row">2</th>
						      <td>38000</td>
						      <td>04-09-2022</td>
						      <td>Sent</td>
						    </tr>
						    <tr>
						      <th scope="row">3</th>
						      <td>42000</td>
						      <td>04-10-2022</td>
						      <td>Sent</td>
						    </tr>
						    <tr>
						      <th scope="row">4</th>
						      <td>42000</td>
						      <td>04-11-2022</td>
						      <td>Sent</td>
						    </tr>
						    <tr>
						      <th scope="row">5</th>
						      <td>48000</td>
						      <td>04-12-2022</td>
						      <td>Pending</td>
						    </tr>
						  </tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>

    <p>
        <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
    </p>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/popper.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
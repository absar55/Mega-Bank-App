<!DOCTYPE html>
<html>
<head>
	<title>Transfer Payment</title>
	<link rel="stylesheet" href="Styles/styles_transfer.css">
</head>
<body>
	<header>
		<nav>
			<ul>
				<li><a href="#">Home</a></li>
				<li><a href="#">Accounts</a></li>
				<li><a href="#">Transfer Payment</a></li>
				<li><a href="#">Settings</a></li>
			</ul>
		</nav>
	</header>
	<main>
		<div class="container">
			<h1>Transfer Payment</h1>
			<form>
				<label for="recipient">Recipient:</label>
				<input type="text" id="recipient" name="recipient" required>

				<label for="amount">Amount:</label>
				<input type="number" id="amount" name="amount" required>

				<label for="currency">Currency:</label>
				<select id="currency" name="currency">
					<option value="USD">USD</option>
					<option value="EUR">EUR</option>
					<option value="GBP">GBP</option>
				</select>

				<label for="date">Date:</label>
				<input type="date" id="date" name="date" required>

				<button type="submit">Transfer</button>
			</form>
		</div>
	</main>
	<footer>
		<p>&copy; 2023 Bank of Example. All rights reserved.</p>
	</footer>
	<script src="script.js"></script>
</body>
</html>

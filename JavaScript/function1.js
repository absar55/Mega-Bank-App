// Data for accounts and transactions
let accounts = [
	{ id: 1, name: "Checking", balance: 1000 },
	{ id: 2, name: "Savings", balance: 5000 },
	{ id: 3, name: "Credit Card", balance: -2500 },
];

let transactions = [
	{ id: 1, account: "Checking", amount: -100, date: "2022-04-30" },
	{ id: 2, account: "Savings", amount: 500, date: "2022-04-28" },
	{ id: 3, account: "Credit Card", amount: 50, date: "2022-04-25" },
	{ id: 4, account: "Checking", amount: 200, date: "2022-04-20" },
	{ id: 5, account: "Savings", amount: -300, date: "2022-04-15" },
	{ id: 6, account: "Credit Card", amount: -75, date: "2022-04-10" },
];

// DOM elements
const accountSelect = document.getElementById("account-select");
const amountInput = document.getElementById("amount-input");
const dateInput = document.getElementById("date-input");
const transactionTable = document.getElementById("transaction-table");

// Populate account select dropdown
function populateAccountSelect() {
	for (let account of accounts) {
		let option = document.createElement("option");
		option.value = account.id;
		option.text = account.name;
		accountSelect.add(option);
	}
}

// Populate transaction table
function populateTransactionTable() {
	for (let transaction of transactions) {
		let row = document.createElement("tr");
		let accountCell = document.createElement("td");
		let amountCell = document.createElement("td");
		let dateCell = document.createElement("td");

		accountCell.innerText = transaction.account;
		amountCell.innerText = `$${transaction.amount.toFixed(2)}`;
		dateCell.innerText = transaction.date;

		row.appendChild(accountCell);
		row.appendChild(amountCell);
		row.appendChild(dateCell);

		transactionTable.appendChild(row);
	}
}

// Add transaction to transactions array
function addTransaction(event) {
	event.preventDefault();

	let account = accountSelect.options[accountSelect.selectedIndex].text;
	let amount = parseFloat(amountInput.value);
	let date = dateInput.value;

	// Check if account is valid
	let validAccount = false;
	for (let acc of accounts) {
		if (acc.name === account) {
			validAccount = true;
			break;
		}
	}

	if (!validAccount) {
		alert("Invalid account selected.");
		return;
	}

	// Check if amount is valid
	if (isNaN(amount) || amount === 0) {
		alert("Invalid amount entered.");
		return;
	}

	// Add transaction to transactions array
	let id = transactions.length + 1;
	transactions.push({ id, account, amount, date });

	// Update account balance
	for (let acc of accounts) {
		if (acc.name === account) {
			acc.balance += amount;
			break;
		}
	}

	// Update transaction table
	let row = document.createElement("tr");
	let accountCell = document.createElement("td");
	let amountCell = document.createElement("td");
	let dateCell = document.createElement("td");

	accountCell.innerText = account;
	amountCell.innerText = `$${amount.toFixed(2)}`;
	dateCell.innerText = date;

	row.appendChild(accountCell);
	row.appendChild(amountCell);
	row.appendChild(dateCell);

	transactionTable.appendChild(row);


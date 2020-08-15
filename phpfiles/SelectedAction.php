<?php
$servername = "localhost";
$username = "root";
$password = "mysql";
$dbname = "meryemkardelen_naiboglu";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} 
//FIRST PART
if ($_POST['action'] == 'Product') {
	$sql = "SELECT COUNT(sale.product_id) AS tot_sale,product.product_id AS id,product.product_name AS name FROM sale\n"
    . "	LEFT JOIN salesman\n"
    . "	ON salesman.salesman_id = sale.salesman_id\n"
    . " LEFT JOIN product\n"
    . " ON sale.product_id = product.product_id\n"
    . " WHERE salesman.market_id =". $_POST['selected_market'] ."\n"
    . " GROUP BY sale.product_id\n"
    . " ORDER BY product.product_id";
	$result = mysqli_query($conn,$sql) or die("Error");
	if (mysqli_num_rows($result) > 0) {
		echo "<table border='1'>";
		echo "<tr><td>Total Sale</td><td>Product ID</td><td>Product Name</td></tr>";
		while($row = mysqli_fetch_array($result)) {
			echo "<tr>";
			echo "<td>" . $row['tot_sale']. "</td><td>" . $row['id'].  "</td><td>" . $row['name'] . "</td>";
			echo "</tr>";
		}
		echo "</table>";
	} else {
		echo "0 results";
	}
}
//SECOND PART 
else if ($_POST['action'] == 'Salesman') {
	$sql = "SELECT COUNT(sale.product_id) AS tot_sale, salesman.salesman_name AS name, salesman.salesman_surname AS surname FROM sale\n"
    . "	LEFT JOIN salesman\n"
    . "	ON salesman.salesman_id = sale.salesman_id\n"
    . " WHERE salesman.market_id =". $_POST['selected_market'] ."\n"
    . " GROUP BY salesman.salesman_name";
	$result = mysqli_query($conn,$sql) or die("Error");
	if (mysqli_num_rows($result) > 0) {
		echo "<table border='1'>";
		echo "<tr><td>Total Sale</td><td>Salesman Name</td><td>Salesman Surname</td></tr>";
		while($row = mysqli_fetch_array($result)) {
			echo "<tr>";
			echo "<td>" . $row['tot_sale']. "</td><td>" . $row['name'].  "</td><td>" . $row['surname'] . "</td>";
			echo "</tr>";
		}
		echo "</table>";
	} else {
		echo "0 results";
	}
} 
//THIRD PART
else if ($_POST['action'] == 'Choose Salesman') {
$sql = "SELECT salesman_name,salesman_surname,salesman_id FROM salesman\n"
    . "WHERE salesman.market_id = " . $_POST['selected_market'];
$result = mysqli_query($conn,$sql) or die("Error");
if (mysqli_num_rows($result) > 0) {
    // output data of each row
	echo "<form action='SalesmanInformation.php' method='post'>";
	echo '<select name="selected_salesman">';
	while($row = mysqli_fetch_array($result)) {
		echo $row;
		echo "<option value='" . $row["salesman_id"] . "'>";
		echo $row["salesman_name"].$row["salesman_surname"];
		echo "</option>";
	}
	echo '</select>';
	echo '<input type="submit" value="Submit">';
	echo "</form>";
}	else {
	echo "0 results";
}

}
//FOURTH PART
 else if ($_POST['action'] == 'Invoice') {
$sql = "SELECT customer.customer_name,customer.customer_surname, customer.customer_id FROM sale\n"
    . "INNER JOIN customer\n"
    . "ON customer.customer_id = sale.customer_id\n"
    . "INNER JOIN salesman \n"
    . "ON salesman.salesman_id=sale.salesman_id\n"
    . "WHERE salesman.market_id = " . $_POST['selected_market'];
$result = mysqli_query($conn,$sql) or die("Error");
if (mysqli_num_rows($result) > 0) {
    // output data of each row
	echo "<form action='CustomerInformation.php' method='post'>";
	echo '<select name="selected_customer">';
	while($row = mysqli_fetch_array($result)) {
		echo $row;
		echo "<option value='" . $row["customer_id"] . "'>";
		echo $row["customer_name"].$row["customer_surname"];
		echo "</option>";
	}
	echo '</select>';
	echo '<input type="submit" value="Submit">';
	echo "</form>";
}	else {
	echo "0 results";
}
}
echo "<br>";

echo $_POST['market_name'];
?>
<html>
<head>
<title></title>
</head>
<body>
<a href="index.php"><button>MAIN PAGE</button></a>
<a href="choosemarket.php"><button>GO BACK</button></a>
</body>
</html>

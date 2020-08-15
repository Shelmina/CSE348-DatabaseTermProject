<?php
	$servername = "localhost";
	$username = "root";
	$password = "mysql";
	$dbname = "meryemkardelen_naiboglu";
	// Create connection
	ini_set('max_execution_time', 300);
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	$sql_product = "SELECT COUNT(sale.product_id) AS total,product.product_id,product.product_name FROM sale
		INNER JOIN salesman
		ON salesman.salesman_id = sale.salesman_id
        INNER JOIN product
        ON sale.product_id = product.product_id
		WHERE salesman.market_id = ".$_GET['selectedcity'].
		" GROUP BY sale.product_id
		";
	$product_count = mysqli_query($conn,$sql_product);
	if (mysqli_num_rows($product_count) > 0) {
		// output data of each row
		echo "<table border='1'>";
		echo "<tr><td>Total Sale</td><td>Product ID</td><td>Product Name</td></tr>";
		while($row = mysqli_fetch_array($product_count)) {
			echo "<tr>";
			echo "<td>" . $row['total']. "</td><td>" . $row['product.product_id']. $row ['product.product_name'].  "</td>";
			echo "</tr>";
		}
		echo "</table>";
	} else {
		echo "0 results";
	}
	
	
	mysqli_close($conn);
?>
<html>
<head>
<title></title>
</head>
<body>
<a href="index.php"><button>MAIN PAGE</button></a>
</body>
</html>
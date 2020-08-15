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
$sql = "SELECT \n"
    . "	sale_id, \n"
    . "	sale.product_id AS id, \n"
    . "	salesman_id, \n"
    . "	product_name \n"
    . "FROM \n"
    . "	sale\n"
    . "	LEFT JOIN product ON sale.product_id = product.product_id \n"
    . "WHERE \n"
    . "	sale.customer_id = ". $_POST['selected_customer'];
$result = mysqli_query($conn,$sql) or die("Error");

if (mysqli_num_rows($result) > 0) {
    // output data of each row
	echo "<table border='1'>";
	echo "<tr><td>Sales</td><td>Product ID</td><td>Product Name</td></tr>";
    while($row = mysqli_fetch_array($result)) {
		echo "<tr>";
        echo "<td>" . $row['sale_id']. "</td><td>" . $row['id'].  "</td><td>".$row['product_name']."</td>";
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
<a href="choosemarket.php"><button>GO BACK </button></a>
<a href="index.php"><button>MAIN PAGE</button></a>
</body>
</html>

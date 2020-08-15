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

$sql = "SELECT COUNT(sale.product_id ) AS tot_sale,market.market_name AS m_name\n"
    . "FROM sale\n"
    . "LEFT JOIN salesman ON salesman.salesman_id= sale.salesman_id\n"
    . "LEFT JOIN market ON market.market_id = salesman.market_id\n"
    . "WHERE salesman.city_id =". $_POST['selectedcity'] ."\n"
    . "GROUP BY market_name";

$result = mysqli_query($conn,$sql) or die("Error");

if (mysqli_num_rows($result) > 0) {
    // output data of each row
	echo "<table border='1'>";
	echo "<tr><td>Total Sale</td><td>Market Name</td></tr>";
    while($row = mysqli_fetch_array($result)) {
		echo "<tr>";
        echo "<td>" . $row['tot_sale']. "</td><td>" . $row['m_name'].  "</td>";
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
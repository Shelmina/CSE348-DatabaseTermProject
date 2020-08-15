<?php
$servername = "localhost";
$username = "root";
$password = "mysql";
$dbname = "meryemkardelen_naiboglu";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
 
$sql = "SELECT market_name AS m_name, market_id FROM market ";
$result = mysqli_query($conn,$sql) or die("Error");

if (mysqli_num_rows($result) > 0) {
	echo "<form action='SelectedAction.php' method='post'>";
	echo '<select name="selected_market">';
    while($row = mysqli_fetch_array($result)) {
		echo "<option value='" . $row["market_id"] . "'>";
        echo $row["m_name"];
		echo "</option>";
    }
	echo '</select>';
	echo '<input type="submit" name="action" value="Product">';
	echo '<input type="submit" name="action" value="Salesman">';
	echo '<input type="submit" name="action" value="Choose Salesman">';
	echo '<input type="submit" name="action" value="Invoice">';


	echo "</form>";
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
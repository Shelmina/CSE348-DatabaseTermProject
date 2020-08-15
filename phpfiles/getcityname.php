<!DOCTYPE html>
<html>
<body>
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

$sql = "SELECT city_name,city_id FROM city ";
$result = mysqli_query($conn,$sql) or die("Error");

if (mysqli_num_rows($result) > 0) {
    // output data of each row
	echo "<form action='ShowCitySalesInformation.php' method='post'>";
	echo '<select name="selectedcity">';
	while($row = mysqli_fetch_array($result)) {
		echo "<option value='" . $row["city_id"] . "'>";
		echo $row["city_name"];
		echo "</option>";
	}
	echo '</select>';
	echo '<input type="submit" value="Submit">';
	echo "</form>";
}	else {
	echo "0 results";
}
mysqli_close($conn);
?>

</body>
</html>
<html>
<head>
<title></title>
</head>
<body>
<a href="index.php"><button>MAIN PAGE</button></a>
</body>
</html>
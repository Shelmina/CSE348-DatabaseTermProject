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

$sql = "SELECT COUNT(DISTINCT(sale.sale_id)) AS tot,district.district_name AS dist\n"
    . "	FROM sale\n"
    . "	INNER JOIN salesman ON salesman.salesman_id=sale.salesman_id\n"
    . "	INNER JOIN market_city ON market_city.city_id=salesman.city_id\n"
    . "	INNER JOIN city ON city.city_id=market_city.city_id\n"
    . "	INNER JOIN district ON district.district_id=city.district_id\n"
    . "	GROUP BY district.district_id";
	
$result = mysqli_query($conn,$sql) or die("Error");

if (mysqli_num_rows($result) > 0) {
	$first_chart = "[";
    while($row = mysqli_fetch_array($result)) {
		$first_chart .= "{y:".$row["tot"].",label:\"".$row["dist"]."\"},";
    }
} else {
    echo "0 results";
}
$first_chart = substr_replace($first_chart,"",-1);
$first_chart .= "]";

$sql= "SELECT market.market_name AS mark , COUNT(distinct(sale_id)) as tot\n"
."FROM sale\n"
."INNER JOIN salesman ON salesman.salesman_id=sale.salesman_id\n"
."INNER JOIN market_city ON market_city.market_id=salesman.market_id\n"
."INNER JOIN market ON market.market_id=market_city.market_id\n"
."GROUP BY market.market_id\n";
$result = mysqli_query($conn,$sql) or die("Error");

if (mysqli_num_rows($result) > 0) {
	$second_chart = "[";
    while($row = mysqli_fetch_array($result)) {
		$second_chart .= "{y:".$row["tot"].",label:\"".$row["mark"]."\"},";
    }
} else {
    echo "0 results";
}
$second_chart = substr_replace($second_chart,"",-1);
$second_chart .= "]";
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
	<head<!DOCTYPE HTML>
<html>
<head>
<script>
window.onload = function() {

	var chart = new CanvasJS.Chart("first_c", {
		animationEnabled: true,
		title: {
			text: " All sales divided into districts"
		},
		data: [{
			type: "pie",
			startAngle: 240,
			yValueFormatString: "##0\"\"",
			indexLabel: "{label} {y}",
			dataPoints:	<?php echo $first_chart ?>	
		}]
	});
	chart.render();
	
		var chart = new CanvasJS.Chart("second_c", {
		animationEnabled: true,
		title: {
			text: " All sales divided into markets"
		},
		data: [{
			type: "pie",
			startAngle: 240,
			yValueFormatString: "##0\"\"",
			indexLabel: "{label} {y}",
			dataPoints:	<?php echo $second_chart ?>	
		}]
	});
	chart.render();
}
</script>
</head>
<body><div id="first_c" style="height: 370px; width: 50%; float:left"></div>
<div id="second_c" style="height: 370px; width: 50%; float:right"></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
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
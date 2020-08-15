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
	
	$sql = "DROP DATABASE IF EXISTS meryemkardelen_naiboglu;";
	mysqli_query($conn,$sql);
	$sql = "CREATE DATABASE meryemkardelen_naiboglu;";
	mysqli_query($conn,$sql);
	$sql = "USE meryemkardelen_naiboglu;";
	mysqli_query($conn,$sql);
	$sql = "CREATE TABLE IF NOT EXISTS district (
		district_id INT NOT NULL AUTO_INCREMENT,
		district_name VARCHAR(50) NOT NULL,
		PRIMARY KEY(district_id)
		) ENGINE = INNODB;";
	$result = mysqli_query($conn,$sql) or die("0");
	$sql = "CREATE TABLE IF NOT EXISTS city (
		city_name VARCHAR(50) NOT NULL,
		city_id INT NOT NULL AUTO_INCREMENT,
		district_id INT NOT NULL,
		FOREIGN KEY (district_id) REFERENCES district (district_id),
		PRIMARY KEY (city_id)
	) ENGINE = INNODB;";
	$result = mysqli_query($conn,$sql) or die("1");

	$sql = "CREATE TABLE IF NOT EXISTS market (
		market_name VARCHAR(50) NOT NULL,
		market_id INT NOT NULL AUTO_INCREMENT,
		PRIMARY KEY (market_id)
	) ENGINE = INNODB;";
	$result = mysqli_query($conn,$sql) or die("2");
	$sql = "CREATE TABLE IF NOT EXISTS market_city (
		market_id INT NOT NULL, 
		city_id INT NOT NULL, 
		PRIMARY KEY(city_id, market_id),
		FOREIGN KEY (market_id) REFERENCES market (market_id), 
		FOREIGN KEY (city_id) REFERENCES city (city_id)	
	) ENGINE = INNODB;";
	$result = mysqli_query($conn,$sql) or die("2.5");
	$sql = "CREATE TABLE IF NOT EXISTS salesman (
		salesman_name VARCHAR(50) NOT NULL,
		salesman_surname VARCHAR(50) NOT NULL,
		salesman_id INT NOT NULL AUTO_INCREMENT,
		market_id INT NOT NULL,
		city_id INT NOT NULL,
		FOREIGN KEY (city_id, market_id) REFERENCES market_city (city_id,market_id),
		PRIMARY KEY (salesman_id)
	) ENGINE = INNODB;
	";
	$result = mysqli_query($conn,$sql) or die("3");

	$sql = "CREATE TABLE IF NOT EXISTS customer ( 
		customer_name VARCHAR(50) NOT NULL,
		customer_surname VARCHAR(50) NOT NULL,
		customer_id INT NOT NULL AUTO_INCREMENT,
		PRIMARY KEY (customer_id)
	) ENGINE = INNODB;";
	$result = mysqli_query($conn,$sql) or die("4");

	$sql = "CREATE TABLE IF NOT EXISTS product (
		product_name VARCHAR(50) NOT NULL,
		product_id INT NOT NULL AUTO_INCREMENT,
		PRIMARY KEY (product_id)
	) ENGINE = INNODB;";
	$result = mysqli_query($conn,$sql) or die("5");

	$sql = "CREATE TABLE IF NOT EXISTS sale (
		sale_id INT NOT NULL AUTO_INCREMENT,
		product_id INT NOT NULL,
		customer_id INT NOT NULL,
		salesman_id INT NOT NULL,
		FOREIGN KEY (product_id) REFERENCES product(product_id),
		FOREIGN KEY (customer_id) REFERENCES customer(customer_id),
		FOREIGN KEY (salesman_id) REFERENCES salesman(salesman_id),
		PRIMARY KEY (sale_id)
	) ENGINE = INNODB;
	";
	$result = mysqli_query($conn,$sql) or die("6");
	//adding districts
	$filename = "csv/districts.csv";
	$row = 0;
	if(!file_exists($filename) || !is_readable($filename))
			return FALSE;
	$header = NULL;
	if (($handle = fopen($filename, 'r')) !== TRUE)
	{
		while(($row = fgetcsv($handle, 1000, ';')) !== FALSE){
			if(!$header)
				$header = $row;
			
			$sql = "INSERT INTO district(district_name) VALUES ('$row[1]');";
			mysqli_query($conn,$sql);
		}
		fclose($handle);
	}

	//adding names OK
	$filename = "csv/names.csv";
	$row = 0;
	if(!file_exists($filename) || !is_readable($filename))
			return FALSE;
	$header = NULL;
	if (($handle = fopen($filename, 'r')) !== FALSE)
	{
		$names = array();
		while(($row = fgetcsv($handle, 1000,';')) !== FALSE){
			if(!$header)
				$header = $row;
			array_push($names,$row[0]);
			
		}
		fclose($handle);
	}
	//adding surnames OK
	$filename = "csv/surnames.csv";
	$row = 0;
	if(!file_exists($filename) || !is_readable($filename))
			return FALSE;
	$header = NULL;
	if (($handle = fopen($filename, 'r')) !== FALSE)
	{
		$surnames = array();
		while(($row = fgetcsv($handle, 1000,';')) !== FALSE){
			if(!$header)
				$header = $row;
			array_push($surnames,$row[0]);
			
		}
		fclose($handle);
	}
	

	//adding district id's and cities OK
	$filename = "csv/districts-and-cities.csv";
	$row = 0;
	if(!file_exists($filename) || !is_readable($filename))
			return FALSE;
	$header = NULL;
	if (($handle = fopen($filename, 'r')) !== TRUE)
	{
		while(($row = fgetcsv($handle, 1000, ';')) !== FALSE)
		{
			if(!$header)
				$header = $row;
			
			$sql = "INSERT INTO city(city_name,district_id) VALUES ('$row[2]','$row[1]');";
			mysqli_query($conn,$sql);
		}
		fclose($handle);
	}
	
	
	//adding markets OK
	$filename = "csv/markets.csv";
	$row = 0;
	$sql = "INSERT INTO market(market_name) VALUES ";
	if(!file_exists($filename) || !is_readable($filename))
			return FALSE;
	$header = NULL;
	if (($handle = fopen($filename, 'r')) != FALSE)
	{
		while(($row = fgetcsv($handle, 1000, ';')) != FALSE) {
			if(!$header)
				$header = $row;
			$sql .= "('$row[0]'),";
			
		}
		fclose($handle);
	}
	$sql = substr_replace($sql, "", -1);
	mysqli_query($conn,$sql);
	
	//distributing markets OK (market_city)
	$full_salesman = array(); //to control if a salesman name already exists
	for($i = 1; $i < 82; $i++) {
		$bool = TRUE;
		$counter = 0;
		$markets_in_a_city = array();
		while ($bool){
			$num = mt_rand(1,10);
			if(in_array($num,$markets_in_a_city) != TRUE){
				$counter++;
				array_push($markets_in_a_city, $num);
				$sql = "INSERT INTO market_city(market_id,city_id) VALUES ('$num','$i');";
				mysqli_query($conn,$sql);
				//
				//

				//adding salesman
				for($k = 0; $k < 3; $k++){
					$name = mt_rand(0,499);
					$surname = mt_rand(0,499);
					$tempsalesman = $names[$name]. $surnames[$surname];
					if((in_array($tempsalesman, $full_salesman) !== TRUE)&&($names[$name] != NULL )&&($surnames[$surname] != NULL)){
						array_push($full_salesman,$tempsalesman);
						$sql = "INSERT INTO salesman (salesman_name, salesman_surname, market_id, city_id) VALUES ('$names[$name]','$surnames[$surname]','$num','$i')";
						mysqli_query($conn,$sql);
					}
					else{
						$k--;
					}
					
				}
				if ($counter == 5){
					$bool = FALSE;
				}
			}
		}
	}
	//adding products OK
	$filename = "csv/products.csv";
	$row = 0;
	$sql = "INSERT INTO product(product_name) VALUES ";
	if(!file_exists($filename) || !is_readable($filename))
			return FALSE;
	$header = NULL;
	if (($handle = fopen($filename, 'r')) !== FALSE)
	{
		while(($row = fgetcsv($handle, 1000,';')) !== FALSE){
			if(!$header)
				$header = $row;
			else{
				$sql .= "('$row[0]'),";
				
			}
		}
		fclose($handle);
	}
	$sql = substr_replace($sql, "", -1);
	mysqli_query($conn,$sql);

	//adding customer OK
	$full_customer = array();
	$bool = TRUE;
	$i = 0;
	$sql =  "INSERT INTO customer(customer_name,customer_surname) VALUES ";
	while($bool){
		$num1 = mt_rand(0,499);
		$num2 = mt_rand(0,499);
		$tempcustomer = $names[$num1]. $surnames[$num2];
		if((in_array($tempcustomer, $full_customer) !== TRUE)&&($names[$num1] != NULL )&&($surnames[$num2] != NULL)){
				array_push($full_customer,$tempcustomer);
				$sql .= "('$names[$num1]','$surnames[$num2]'),";
				
				$i++;
				if($i == 1620){
					$bool = FALSE;
				}
		}
	}
	$sql = substr_replace($sql, "", -1);
	mysqli_query($conn,$sql);
	
	//sales OK
	$cust_id = 1;
	$sales = array();
	$sql = "INSERT INTO sale(product_id, customer_id, salesman_id) VALUES ";
	while($cust_id < 1621){
		$num = mt_rand(0,5);
		for($itemcount = 0; $itemcount < $num; $itemcount++){
			$prod_id = mt_rand(1,200);
			$slsman_id = mt_rand(1,1215);
			$sql.="('$prod_id','$cust_id','$slsman_id'),";
		}
		$cust_id++;
	}
	$sql_a = substr($sql, 0, strlen($sql)-1);
		//echo $sql_a;

	mysqli_query($conn,$sql_a);
	
	mysqli_close($conn);
	echo "<h1>Installed!</h1>";
	
?>
<html>
<head>
<title>Index</title>
</head>
<body>
<a href="index.php"><button>GO BACK TO MAIN PAGE</button></a>
</body>
</html>
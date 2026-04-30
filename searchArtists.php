<!DOCTYPE html>
<html>
<head>
    <title>Search Artists from DB</title>
    <style>
    
        table, th, td, tr {
            border: solid grey;
			border-collapse:collapse;
        }
    </style>
</head>
<body>
<form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    Artist Name:  <input type="text" name="artistName" />
    <br><br>
    Nationality: <input type="text" name="nation" />
    <br><br>
    <input type="submit" />
	<input type="reset"	/>
</form>
<br>
<hr>
<br>
<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "vrg";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }
    //echo "Connected successfully<br>";

    $name = isset($_GET["artistName"])?$_GET["artistName"]:"";
    $nationality = isset($_GET["nation"])?$_GET["nation"]:"";
    
    if($name == "" and $nationality == "")
		$sql = "select * from ARTIST";
	else if($name != "" and $nationality=="")
		$sql = "select * from ARTIST where FirstName like '%$name%' or LastName like '%$name%'";
	else if($name == "" and $nationality!="")
		$sql = "select * from ARTIST where Nationality = '$nationality'";
	else
		$sql = "select * from ARTIST where (FirstName like '%$name%' or LastName like '%$name%') and Nationality = '$nationality'";
	
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		echo "<table>";
		echo "<tr>";
			echo "<th>ArtistID</th>";
			echo "<th>FirstName</th>";
			echo "<th>LastName</th>";
			echo "<th>DOB</th>";
			echo "<th>DOD</th>";
			echo "<th>Nationality</th>";
		echo "</tr>";
		// output data of each row
		while($row = $result->fetch_assoc()) {
			echo "<tr>";
				echo "<td>" . $row['ArtistID'] . "</td>";
				echo "<td>" . $row["FirstName"] . "</td>";
				echo "<td>" . $row['LastName'] . "</td>";
				echo "<td>" . $row['DateOfBirth'] . "</td>";
				echo "<td>" . $row['DateDeceased'] . "</td>";
				echo "<td>" . $row['Nationality'] . "</td>";
			echo "</tr>";

		}
		echo "</table>";
	} 
	else {
		echo "0 results";
	}
    
    $conn->close();
?>
</body>
</html>









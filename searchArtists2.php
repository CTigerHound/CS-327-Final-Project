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
	
	<script>
			function magic()
			{
				document.getElementById("submitButton").type = "submit";
				//document.getElementById("myForm").submit();				
			}
	</script>
</head>
<body>
<?php
	session_start();
    if(isset($_SESSION["userid"]))
	{
?>
<form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" id="myForm">
    Artist Name:  <input type="text" name="artistName" id="artistName"/>
    <br><br>
    Nationality: <input type="text" name="nation" id="nation"/>
    <br><br>
    <input type="button" value = "Submit" onclick='magic()' id="submitButton"/>
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

	if(isset($_GET["artistName"]) or isset($_GET["nation"]))
	{	
		$name = isset($_GET["artistName"])?$_GET["artistName"]:"";
		$nationality = isset($_GET["nation"])?$_GET["nation"]:"";
		/*
		if($name == "" and $nationality == "")
			$sql = "select * from ARTIST";
		else if($name != "" and $nationality=="")
			$sql = "select * from ARTIST where FirstName like '%$name%' or LastName like '%$name%'";
		else if($name == "" and $nationality!="")
			$sql = "select * from ARTIST where Nationality = '$nationality'";
		else
			$sql = "select * from ARTIST where (FirstName like '%$name%' or LastName like '%$name%') and Nationality = '$nationality'";
		*/
		
		
		$sql = "select * from ARTIST where artistid is not null";
		if($name != "")
			$sql = $sql . " and FirstName like '%$name%' or LastName like '%$name%'";
		if($nationality!="")
			$sql = $sql . " and Nationality = '$nationality'";
		
		//echo $sql;
		$_SESSION["searchString"] = "?artistName="."$name&nation=".$nationality;
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
					echo "<td><a href=\"findPaintings.php?id=" . $row['ArtistID'] 
						."&fname=".$row["FirstName"]."&lname=".$row['LastName']."\">" ;
					echo $row['ArtistID'] . "</a></td>";
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
    }
    $conn->close();
	 
	}
	else
	{
		echo "You shouldn't be on this page!";
		echo "<br>";
		echo "<a href=\"index.php\">Log in</a> and try again.";
	}
	
?>
</body>
</html>









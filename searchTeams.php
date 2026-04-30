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
    Team Name:  <input type="text" name="teamName" id="teamName"/>
    <br><br>
    Sport: <input type="text" name="sport" id="sport"/>
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
    $dbname = "project_del_2";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }
    //echo "Connected successfully<br>";
	/* insert into users values (11111111111, 09-09-2009, 'M', 'John', 'Doe', 'password')
	insert into player values (111111111111, 0.00)
	insert into coach values (11111111112, 01-01-2000)
	*/
	if(isset($_GET["teamName"]) or isset($_GET["sport"]))
	{	
		$name = isset($_GET["teamName"])?$_GET["teamName"]:"";
		$sport = isset($_GET["sport"])?$_GET["sport"]:"";
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
		
		
		$sql = "select * from team where teamid is not null";
		if($name != "")
			$sql = $sql . " and Name like '%$name%'";
		if($sport!="")
			$sql = $sql . " and SprtName = '$sport'";
		
		//echo $sql;
		$_SESSION["searchString"] = "?teamName="."$name&sport=".$sport;
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			echo "<table>";
			echo "<tr>";
				echo "<th>Name</th>";
				echo "<th>SprtName</th>";
				echo "<th>FrstPlayer</th>";
				echo "<th>HeadCoach</th>";
				echo "<th>TeamID</th>";
				echo "<th>Sex</th>";
				echo "<th>Min_age</th>";
				echo "<th>Max_age</th>";
			echo "</tr>";
			// output data of each row
			while($row = $result->fetch_assoc()) {
				echo "<tr>";
					// echo "<td><a href=\"findPaintings.php?id=" . $row['TeamID'] 
					// 	."&fname=".$row["FirstName"]."&lname=".$row['LastName']."\">" ; // Ahhhh this creates the links for the IDs
					echo $row['TeamID'] . "</a></td>";
					echo "<td>" . $row["Name"] . "</td>";
					echo "<td>" . $row['SprtName'] . "</td>";
					echo "<td>" . $row['FrstPlayer'] . "</td>";
					echo "<td>" . $row['HeadCoach'] . "</td>";
					echo "<td>" . $row['TeamID'] . "</td>";
					echo "<td>" . $row['Sex'] . "</td>";
					echo "<td>" . $row['Min_age'] . "</td>";
					echo "<td>" . $row['Max_age'] . "</td>";
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
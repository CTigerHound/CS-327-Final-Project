<!DOCTYPE html>
<html>
<head>
    <title>View coachs from DB</title>
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
    Coach Name:  <input type="text" name="coachName" id="coachName"/>
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

	if(!isset($_GET["coachName"]))
		{
			$result = $conn->query("Select * from coach, users where C_USERID = USERID");
			if ($result->num_rows > 0) {
			echo "<a href=\"assignCoach.php\">Assign coach to team</a>";
			echo "<table>";
			echo "<tr>";
				echo "<th>C_USERID</th>";
				echo "<th>FirstName</th>";
				echo "<th>LastName</th>";
				echo "<th>DOB</th>";
				echo "<th>Sex</th>";
				echo "<th>HireDate</th>";
				echo "<th>Password</th>";
			echo "</tr>";
			// output data of each row
			while($row = $result->fetch_assoc()) {
				echo "<tr>";
					echo "<td>" . $row['C_USERID'] . "</td>";
					echo "<td>" . $row["Fname"] . "</td>";
					echo "<td>" . $row['Lname'] . "</td>";
					echo "<td>" . $row['DOB'] . "</td>";
					echo "<td>" . $row['Sex'] . "</td>";
					echo "<td>" . $row['HireDate'] . "</td>";
					echo "<td>" . $row['Password'] . "</td>";
				echo "</tr>";

			}
			echo "</table>";
			}
		} else if (isset($_GET["coachName"])) {
		$name = isset($_GET["coachName"])?$_GET["coachName"]:"";
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

		$sql = "select * from coach, users where C_USERID is not null and C_USERID = USERID";
		if($name != "")
			$sql = $sql . " and Fname like '%$name%' or Lname like '%$name%'";
		
		//echo $sql;
		$_SESSION["searchString"] = "?coachName="."$name";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			echo "<a href=\"assignCoach.php\">Assign coach to team</a>";
			echo "<table>";
			echo "<tr>";
				echo "<th>C_USERID</th>";
				echo "<th>FirstName</th>";
				echo "<th>LastName</th>";
				echo "<th>DOB</th>";
				echo "<th>Sex</th>";
				echo "<th>HireDate</th>";
				echo "<th>Password</th>";
			echo "</tr>";
			// output data of each row
			while($row = $result->fetch_assoc()) {
				echo "<tr>";
					echo "<td>" . $row['C_USERID'] . "</td>";
					echo "<td>" . $row["Fname"] . "</td>";
					echo "<td>" . $row['Lname'] . "</td>";
					echo "<td>" . $row['DOB'] . "</td>";
					echo "<td>" . $row['Sex'] . "</td>";
					echo "<td>" . $row['HireDate'] . "</td>";
					echo "<td>" . $row['Password'] . "</td>";
				echo "</tr>";

			}
			echo "</table>";
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
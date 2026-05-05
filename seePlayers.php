<!DOCTYPE html>
<html>
<head>
    <title>View Players from DB</title>
	<style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Georgia', serif; background: #0f1923; color: #e8e0d0; min-height: 100vh; padding: 40px 20px; }
        .container { max-width: 600px; margin: 0 auto; }
        h1 { font-size: 2rem; color: #c9a84c; margin-bottom: 4px; }
        .subtitle { color: #888; margin-bottom: 40px; font-size: 0.95rem; }
        .menu-item { display: block; background: #1a2535; border: 1px solid #2a3a4a; border-left: 4px solid #c9a84c; color: #e8e0d0; text-decoration: none; padding: 18px 24px; margin-bottom: 12px; border-radius: 4px; font-size: 1rem; transition: background 0.2s; }
        .menu-item:hover { background: #243040; }
        .menu-item span { display: block; font-size: 0.78rem; color: #778; margin-top: 3px; }
        .logout { border-left-color: #a84c4c; margin-top: 30px; }
        .logout:hover { border-left-color: #d06060; }
    </style>
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
    if(isset($_SESSION["USERID"]) && $_SESSION["role"] == "admin")
	{
?>
<form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" id="myForm">
     Name:  <input type="text" name="playerName" id="playerName"/>
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
    $dbname = "sportlfc";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }
    //echo "Connected successfully<br>";

	if(!isset($_GET["playerName"]))
		{
			$result = $conn->query("Select * from player, Users where P_USERID = USERID");
			if ($result->num_rows > 0) {
			echo "Average fee per player: ". $conn->query("select round(avg(fee), 2) as Average from player") -> fetch_assoc()["Average"];
			echo "<table>";
			echo "<tr>";
				echo "<th>USERID</th>";
				echo "<th>FirstName</th>";
				echo "<th>LastName</th>";
				echo "<th>DOB</th>";
				echo "<th>Sex</th>";
				echo "<th>Fee</th>";
				echo "<th>Password</th>";
			echo "</tr>";
			// output data of each row
			while($row = $result->fetch_assoc()) {
				echo "<tr>";
					echo "<td>" . $row['P_USERID'] . "</td>";
					echo "<td>" . $row["Fname"] . "</td>";
					echo "<td>" . $row['Lname'] . "</td>";
					echo "<td>" . $row['DOB'] . "</td>";
					echo "<td>" . $row['Sex'] . "</td>";
					echo "<td>" . $row['Fee'] . "</td>";
					echo "<td>" . $row['Password'] . "</td>";
				echo "</tr>";

			}
			echo "</table>";
			}
		} else if (isset($_GET["playerName"])) {
		$name = isset($_GET["playerName"])?$_GET["playerName"]:"";
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

		$sql = "select * from player, Users where P_USERID is not null and P_USERID = USERID";
		if($name != "")
			$sql = $sql . " and Fname like '%$name%' or Lname like '%$name%'";
		
		//echo $sql;
		$_SESSION["searchString"] = "?playerName="."$name";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			echo "Average fee per player: ". $conn->query("select round(avg(fee), 2) as Average from player") -> fetch_assoc()["Average"];
			echo "<table>";
			echo "<tr>";
				echo "<th>P_USERID</th>";
				echo "<th>FirstName</th>";
				echo "<th>LastName</th>";
				echo "<th>DOB</th>";
				echo "<th>Sex</th>";
				echo "<th>Fee</th>";
				echo "<th>Password</th>";
			echo "</tr>";
			// output data of each row
			while($row = $result->fetch_assoc()) {
				echo "<tr>";
					echo "<td>" . $row['P_USERID'] . "</td>";
					echo "<td>" . $row["Fname"] . "</td>";
					echo "<td>" . $row['Lname'] . "</td>";
					echo "<td>" . $row['DOB'] . "</td>";
					echo "<td>" . $row['Sex'] . "</td>";
					echo "<td>" . $row['Fee'] . "</td>";
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
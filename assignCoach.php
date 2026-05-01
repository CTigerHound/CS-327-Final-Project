<html lang="en">
    <head>
        <title>Assign Coach to a Team</title>
	<script>
			// function magic()
			// {
			// 	document.getElementById("submitButton").type = "submit";
			// 	//document.getElementById("myForm").submit();				
			// }
	</script>
</head>
    
    <body>
        <?php
        
            session_start();
            if (isset($_SESSION["userid"]) && $_SESSION["userid"]!="")
            {
                $servername="localhost";
                $username="root";
                $password="";
                $dbname="sportlfc";

                $conn = new mysqli($servername, $username, $password, $dbname);
                if($conn->connect_error)
                {
                    die("Connection failed: ".$conn->connect_error);
                }
      
                if(isset($_POST["C_USERID"]) && isset($_POST["TeamID"]) && isset($_POST["Role"])) {
                    if ($_POST["Role"] == 'Head Coach') {
                        $sql = "update team set HeadCoach = ".$_POST["C_USERID"]." where TeamID = ".$_POST["TeamID"];
                        $result = $conn->query($sql);
                        if (!$result) {
                            echo("<h4>Some error occurred! <a href=\"assignCoach.php\">Try again!</a>.<h4>");
                        }

                        $result = $conn->query("Select * from coaches where TeamID =".$_POST["TeamID"]." and Role ='".$_POST["Role"]."'");
                        // error handling
                        if(!$result)
                        {
                            echo("<h4>Some error occurred! <a href=\"assignCoach.php\">Try again!</a>.<h4>");
                        }

                        $row = $result->fetch_assoc();
                        if ($result->num_rows > 0) {
                            $newres = $conn->query("update coaches set C_USERID =".$_POST["C_USERID"]." where TeamID = ".$row['TeamID']." and C_USERID = ".$row['C_USERID']);

                            if($newres)
                                {
                                echo("<h4>Coach ".$_POST["C_USERID"]." successfully added to Team ".$_POST["TeamID"]."<h4>");
                                echo("<br> Return to <a href=\"seeCoaches.php\">Coach Management</a>.");
                                }
                        }
                    else
                        {
                            echo("<h4>Some error occurred! <a href=\"assignCoach.php\">Try again!</a>.<h4>");
                        }

                    } else {
                    
                    $sql = "insert into coaches values ('".$_POST["TeamID"]."', '".$_POST["C_USERID"]."', '".$_POST["Role"]."')";
                    //Run query
                    $result = $conn->query($sql);
                        
                    if($result)
                        {
                            echo("<h4>Coach ".$_POST["C_USERID"]." successfully added to Team ".$_POST["TeamID"]."<h4>");
                            echo("<br> Return to <a href=\"seeCoaches.php\">Coach Management</a>.");
                        }
                    else
                        {
                            echo("<h4>Some error occurred! <a href=\"assignCoach.php\">Try again!</a>.<h4>");
                        }
                }

                // Add to team form
                } else {

                    echo "<h4> Assign a coach to a team</h4>";
                    ?>
                    <form action="assignCoach.php" method="post">
                        Team ID*: <input type="text" name="TeamID"><br>
                        Coach ID*: <input type="text" name="C_USERID"><br>
                        Role: <input type="text" name="Role"><br>


                        <input type="submit"> &nbsp;&nbsp;
                        <input type="reset">
                    
                    </form>
                    <?php
                
                echo "<br><br><a href=\"seeCoaches.php\">Back to Coach Management</a>";
        
                $conn->close();
                }

            }
            else
            {
                echo "<h1> You have to log in to access this page!</h1>";
                echo "<a href=\"index.php\">Go to login page</a>";
            }
        ?>
    </body>
</html>
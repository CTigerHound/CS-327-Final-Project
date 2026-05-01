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
                $dbname="project_del_2";

                $conn = new mysqli($servername, $username, $password, $dbname);
                if($conn->connect_error)
                {
                    die("Connection failed: ".$conn->connect_error);
                }
      
                if(isset($_POST["C_USERID"]) && isset($_POST["TeamID"])) {
                    
                    $sql = "update team set HeadCoach = ".$_POST["C_USERID"]." where TeamID = ".$_POST["TeamID"];
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
                else {

                    echo "<h4> Assign a coach to a team</h4>";
                    ?>
                    <form action="assignCoach.php" method="post">
                        Team ID*: <input type="text" name="TeamID"><br>
                        Coach ID*: <input type="text" name="C_USERID"><br>

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
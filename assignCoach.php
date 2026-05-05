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
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Georgia', serif; background: #0f1923; color: #e8e0d0; min-height: 100vh; padding: 40px 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        h1 { font-size: 1.8rem; color: #c9a84c; margin-bottom: 30px; }
        form { background: #1a2535; border: 1px solid #2a3a4a; border-radius: 6px; padding: 24px; margin-bottom: 30px; }
        label { display: block; font-size: 0.85rem; color: #aaa; margin-bottom: 6px; }
        input[type=text], select { width: 100%; padding: 10px 14px; background: #0f1923; border: 1px solid #2a3a4a; border-radius: 4px; color: #e8e0d0; font-size: 0.95rem; margin-bottom: 16px; }
        .btn { background: #c9a84c; color: #0f1923; border: none; padding: 10px 28px; border-radius: 4px; font-size: 1rem; cursor: pointer; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; background: #1a2535; border-radius: 6px; overflow: hidden; }
        th { background: #c9a84c; color: #0f1923; padding: 12px 14px; text-align: left; font-size: 0.8rem; text-transform: uppercase; }
        td { padding: 12px 14px; border-bottom: 1px solid #2a3a4a; font-size: 0.9rem; }
        tr:hover td { background: #243040; }
        .back { display: inline-block; margin-top: 24px; color: #c9a84c; text-decoration: none; }
        .none { color: #778; padding: 20px; text-align: center; }
    </style>
    <body>
        <?php
        
            session_start();
            if (isset($_SESSION["USERID"]) && $_SESSION["USERID"]!="")
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
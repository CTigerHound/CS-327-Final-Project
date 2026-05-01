<html lang="en">
<head>
<title>Create a Team</title>
</head>

<body>
    <?php
    session_start();
    if(isset($_SESSION["userid"]))
	{
        if(isset($_POST["Name"]) && isset($_POST["TeamID"]) &&
                isset($_POST["SprtName"]) && isset($_POST["FrstPlayer"]) && isset($_POST["HeadCoach"]) && isset($_POST["Sex"])
                && isset($_POST["Min_age"]) && isset($_POST["Max_age"]) &&
                                            $_POST["TeamID"]!="")
        {
            $servername="localhost";
            $username="root";
            $password="";
            $dbname="sportlfc";
            
            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            if($conn->connect_error)
            {
                    die("Connection failed: ".$conn->connect_error);
            }
            else
            {   //Read the form data
                $name = $_POST["Name"];
                $teamid = $_POST["TeamID"];
                $sport = $_POST["SprtName"];
                $player = $_POST["FrstPlayer"];
                $coach = $_POST["HeadCoach"];
                $sex = $_POST["Sex"];
                $minage = $_POST["Min_age"];
                $maxage = $_POST["Max_age"];
                    
                //Generate query
                $sql = "select TeamID";
                $sql = $sql. " from team where TeamID ='".$teamid."' or Name ='".$name."'";
                    
                //Run query
                $result = $conn->query($sql);
                if($result->num_rows>0)
                {  #TODO: MORE STUFFS! CHECKS! DEFAULT VALUES!
                    echo("<h4>ID number ".$teamid." or Team Name ".$name." already exists! Enter something else.<h4>");
                    echo("<h4><a href=\"createTeams.php\">Try again!</a>.<h4>");
                }
                else
                {
                    //Generate second query
                    $sql = "insert into team(TeamID, SprtName, FrstPlayer, HeadCoach, Name, Sex, Min_age, Max_age)";
                    $sql = $sql. " values ('".$teamid."', '".$sport."', '".$player."', '".$coach."', '".$name."', '".$sex."', '".$minage."', '".$maxage."')";
                    
                    //Run query
                    $result = $conn->query($sql);
                    
                    if($result)
                    {
                        echo("<h4>Team ".$name." successfully created.<h4>");
                        echo("<br> Return to <a href=\"searchTeams.php\">Team Management</a>.");
                    }
                    else
                    {
                        echo("<h4>Some error occurred! <a href=\"createTeams.php\">Try again!</a>.<h4>");
                    }
                }
            }
        }
        else
        {?>
        <h1>Create a new team</h1>
        <br>
        <form action="createTeams.php" method="post">
            Team ID*: <input type="text" name="TeamID"><br>
            Team Name*: <input type="text" name="Name"><br>
            Sport*: <input type="text" name="SprtName"><br>
            Sex: <input type="text" name="Sex"><br>
            Minimum age: <input type="text" name="Min_age"><br>
            Maximum age: <input type="text" name="Max_age"><br>
            First Player*: <input type="text" name="FrstPlayer"><br>
            Head Coach*: <input type="text" name="HeadCoach"><br>
            <br>
            <input type="submit"> &nbsp;&nbsp;
            <input type="reset">
        
        </form>

    <br>
    <br>
    <hr>
    Return to functions page <a href="index.php">here</a>.
        <?php
        }
    } else
	{
		echo "You shouldn't be on this page!";
		echo "<br>";
		echo "<a href=\"index.php\">Log in</a> and try again.";
	}
        ?>
</body>

</html>
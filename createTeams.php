<html lang="en">
<head>
<title>Create a Team</title>
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
    if(isset($_SESSION["USERID"]) && $_SESSION["role"] == "admin")
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
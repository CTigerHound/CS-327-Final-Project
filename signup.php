<html lang="en">
<head>
<title>Sign up for accessing Lake Forest College Sports</title>
</head>

<body>
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
        
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    #isset($_POST["Fname"]) &&
    #isset($_POST["Lname"]) &&
    #isset($_POST["USERID"]) &&
    #isset($_POST["Password"]) &&
    #isset($_POST["Password2"]) &&
    #$_POST["Fname"] != ""

    if ($_POST["Password"] != $_POST["Password2"]) {
        die("Passwords do not match!");
    }
        
        $servername="localhost";
        $username="root";
        $Password="";
        $dbname="test";
        
        // Create connection
        $conn = new mysqli($servername, $username, $Password, $dbname);
        if($conn->connect_error)
        {
                die("Connection failed: ".$conn->connect_error);
        }
        else
        {   //Read the form data
            $Fname = $_POST["Fname"];
            $Lname = $_POST["Lname"]; 
            $USERID = $_POST["USERID"];
            $userPassword = $_POST["Password"];
            $DOB = isset($_POST["DOB"]) ? $_POST["DOB"] : "";
            $Sex = isset($_POST["Sex"]) ? $_POST["Sex"] : "";
                
            //Generate query
            
            $sql = "SELECT USERID FROM Users WHERE USERID='$USERID'";
            
            //Run query
            $result = $conn->query($sql);
            if (!$result) {
                die("SQL Error: " . $conn->error);
            }
            if($result->num_rows>0)
            {
                echo("<h4>Username ".$USERID." already exists! Enter something else.<h4>");
                 echo("<h4><a href=\"signup.php\">Try again!</a>.<h4>");

            }
            else
            {
                //Generate second query
                $sql = "INSERT INTO Users (USERID, DOB, Sex, Fname, Lname, Password)
                VALUES ('$USERID', '$DOB', '$Sex', '$Fname', '$Lname', '$userPassword')";
                //Run query
                $result = $conn->query($sql);
                
                if($result)
                {
                    echo("<h4>Username ".$USERID." successfully created.<h4>");
                    echo("<br> Go to <a href=\"index.php\">login page</a>.");
                }
                else
                {
                    echo("<h4>Some error occurred! <a href=\"signup.php\">Try again!</a>.<h4>");
                }
            }
        }
    }
    else
    {?>
    <h1>Create your profile for the Lake Forest College Sports Center!</h1>
    <br>
    <form action="signup.php" method="post">
        First Name*: <input type="text" name="Fname"><br>
        Last Name*: <input type="text" name="Lname"><br>
        USERID*: <input type="text" name="USERID"><br>
        DOB: <input type="date" name="DOB"><br>
        Sex: <input type="text" name="Sex"><br>
        Password*: <input type="Password" name="Password"><br>
        Re-type Password*: <input type="Password" name="Password2"><br>
        
        <br>
        <input type="submit"> &nbsp;&nbsp;
        <input type="reset">
    
    </form>
    <?php
    }
    ?>
    <br>
    <br>
    <hr>
    Already have a username? Sign in <a href="index.php">here</a>.
</body>

</html>
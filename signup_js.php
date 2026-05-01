<!DOCTYPE html>
<html lang = 'eng'>
<head>
    <title>Create New User</title>
    <style>
    
        table, th, td, tr {
            border: solid black;
            border-collapse: collapse
        }
        
    </style>
    <script>
        function checkpasswords()
        {
            p1 = document.getElementById("pwd1").value
            p2 = document.getElementById("pwd2").value
            if(p1!=p2)
                {
                alert("The password fields dont match!");
                document.getElementById("pwd1").value = "";
                document.getElementById("pwd2").value = "";
                }
                else
                {
                    document.getElementById("myform").submit();
                }
        }
    </script>
</head>
<body>
 
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="myform">
    First Name:  <input type="text" name="Fname"/>
	<br><br>
    Last Name:  <input type="text" name="Lname"/>
	<br><br>
    User ID:  <input type="text" name="USERID"/>
	<br><br>
    DOB: <input type="date" name="DOB"/>
    <br><br>
    Sex: <input type="text" name="Sex"/>
    <br><br>
    Password: <input type="Password" name="Password" id="pwd1"/>
    <br><br>
    Retype Password: <input type="Password" name="Password2" id="pwd2"/>
    <br><br>
    <input type="button" value="Create User" onclick="checkpasswords()"/>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <input type="reset" value="Clear"/>
    <br><br>
    Already have an account? Sign in <a href="index.php">here</a>.
</form>

<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    $servername = "localhost";
    $username = "root";
    $dbPassword = "";
    $dbname = "test";

    // Create connection
    $conn = new mysqli($servername, $username, $dbPassword, $dbname);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $sql="";

    // Check connection
    if ($conn->connect_error) 
    {
        die("Connection failed: " . $conn->connect_error);
    }
    else
    {
        $Fname = isset($_POST["Fname"]) ? $_POST["Fname"] : "";
        $Lname = isset($_POST["Lname"]) ? $_POST["Lname"] : "";
        $USERID = isset($_POST["USERID"]) ? $_POST["USERID"] : "";
        $userPassword = isset($_POST["Password"]) ? $_POST["Password"] : "";
        $DOB = isset($_POST["DOB"]) ? $_POST["DOB"] : "";
        $Sex = isset($_POST["Sex"]) ? $_POST["Sex"] : "";
    }

        $fname = isset($_POST["firstname"]) ? $_POST["firstname"] : "";
        $lname = isset($_POST["lastname"]) ? $_POST["lastname"] : "";
        $userid = isset($_POST["userid"]) ? $_POST["userid"] : "";
        $password = isset($_POST["password1"]) ? $_POST["password1"] : "";
        
	}
	if($userid!=""){
        //Create the SQL query

        $sql = "select username from Users";
        $sql = $sql . " where username = '$userid'";
        
	//Run the query
	$result = $conn->query($sql);
    
    if ($result->num_rows == 0) 
	{

        $sql2 = "insert into Users(USERID,DOB,SEX,Fname,Lname, Password) values(";
        $sql2 = $sql2 . "'$DOB,$fname','$lname','$userid','$password',0)";
	    $result = $conn->query($sql2);
        
        /*if ($result->num_rows > 0) 
            echo "Created User";
        else
            echo "Something went wrong!";*/

        if ($result) {
            echo "User created successfully!";
        } else {
            echo "Error: " . $conn->error;
    if (isset($_POST["Password"], $_POST["Password2"])) {
        if ($_POST["Password"] != $_POST["Password2"]) {
            die("Passwords do not match!");
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && $USERID != "") {

        $sql = "select USERID from Users where USERID = '$USERID'";

        $result = $conn->query($sql);

        if (!$result) {
            die("SQL Error: " . $conn->error);
        }

        if ($result->num_rows == 0) 
        {
            $sql2 = "INSERT INTO Users (USERID, DOB, Sex, Fname, Lname, Password)
            VALUES ('$USERID', '$DOB', '$Sex', '$Fname', '$Lname', '$userPassword')";

            $result2 = $conn->query($sql2);

            if (!$result2) {
                die("Insert failed: " . $conn->error);
            } else {
                echo "User created successfully!";
                echo "<br><a href='index.php'>Login</a>";
            }
        }
        else
        {
            echo "Sorry! That userid already exists!";
            echo "<br><a href='signup.php'>Try again</a>";
        }
    }

    $conn->close();
?>
</body>
</html>



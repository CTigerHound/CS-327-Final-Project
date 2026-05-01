<html lang="en">
<head>
    <title>Welcome to Sport LFC</title>
</head>
<body>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

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
{
    // FIXED variable names
    #$USERID = $_POST["USERID"];
    #$Password = $_POST["Password"];
    $USERID = isset($_POST["USERID"]) ? $_POST["USERID"] : "";
    $Password = isset($_POST["Password"]) ? $_POST["Password"] : "";
    // FIXED table + column names (must match your signup table)
    $sql = "SELECT Fname, Lname, USERID, is_admin 
            FROM Users 
            WHERE USERID='".$USERID."' 
            AND Password='".$Password."'";

    $result = $conn->query($sql);

    if(!$result)
    {
        die("SQL Error: " . $conn->error);
    }

    if($result->num_rows > 0)
    {
        $row = $result->fetch_assoc();
        $fullname = $row["Fname"]." ".$row["Lname"];

        echo "<h1>Welcome to the Main Menu ".$fullname."</h1>";

        if($row["is_admin"] == 1)
        {
            echo "<h3>You're an admin, so you'll see the special menu.</h3>";
            echo "<br><a href=\"seeSalesFigures.php\">See Sales Numbers</a>";
        }
        else
        {
            echo "<h3>You're not an admin, so you'll see regular menu.</h3>";
        }

        echo "<br><a href=\"searchArtists2.php\">Search for an artist</a>";
        echo "<br><a href=\"searchWorks.php\">Search for a work of art</a>";
        echo "<br><a href=\"searchCustomers.php\">Search for a customer</a>";
    }
    else
    {
        echo "<h3>Sorry! Login failed!</h3>";
        echo "Go back to the <a href=\"index.php\">login page</a> and try again.";
    }

    $conn->close();

    // SESSION AFTER variables exist
    $_SESSION["USERID"] = $USERID;
    $_SESSION["FullName"] = $fullname ?? "";
}
?>

<br><br><a href="logout.php">Log out</a>

</body>
</html>
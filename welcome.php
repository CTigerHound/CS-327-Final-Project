<html lang="en">
<head>
    <title>Welcome to Sport LFC</title>
    
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
    
</head>
<body>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

$servername="localhost";
$username="root";
$Password="";
$dbname="sportlfc";

// Create connection
$conn = new mysqli($servername, $username, $Password, $dbname);

if($conn->connect_error)
{
    die("Connection failed: ".$conn->connect_error);
}
else
{
    
    $USERID = isset($_POST["USERID"]) ? $_POST["USERID"] : "";
    $Password = isset($_POST["Password"]) ? $_POST["Password"] : "";
    $sql = "SELECT Fname, Lname, USERID, role 
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

        if($row["role"] == 'admin')
        {
            echo "<h3>You're an admin, so you'll see the special menu.</h3>";
            echo "<br><a href=\"adminMenu.php\">Admin Functions</a>";
        }
        else if ($row["role"] == 'player')
        {
            echo "<h3>You're a player, so you'll see regular menu.</h3>";
            echo "<br><a href=\"playerMenu.php\">Player Functions</a>";
        }
        else if ($row["role"] == 'coach')
        {
            echo "<h3>You're a coach, so you'll see regular menu.</h3>";
            echo "<br><a href=\"coachMenu.php\">Coach Functions</a>";
            
        }
            

        
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
    $_SESSION["role"] = $row["role"]; 
}
?>

<br><br><a href="logout.php">Log out</a>

</body>
</html>
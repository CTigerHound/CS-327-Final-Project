<?php
session_start();
if (!isset($_SESSION["userid"]) || $_SESSION["role"] != "player") {
    echo "<h2>Access denied. <a href='index.php'>Login</a></h2>";
    exit();
}
$fullname = $_SESSION["FullName"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Player Menu - Sport LFC</title>
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
<div class="container">
    <h1>Welcome, <?php echo htmlspecialchars($fullname); ?></h1>
    <p class="subtitle">Player Dashboard &mdash; Sport LFC</p>
    <a class="menu-item" href="searchTeam.php">Search for a Team<span>Find teams by sport or team name</span></a>
    <a class="menu-item" href="joinSport.php">Join a Sport<span>Register yourself in a new sport</span></a>
    <a class="menu-item" href="orderEquipment.php">Order Equipment<span>Buy equipment required for your sport</span></a>
    <a class="menu-item" href="viewMyTeams.php">View My Teams<span>See all teams you are currently on</span></a>
    <a class="menu-item" href="leaveTeam.php">Leave a Team<span>Remove yourself from a team</span></a>
    <a class="menu-item" href="viewFees.php">View My Fees<span>Check your current fee balance</span></a>
    <a class="menu-item logout" href="logout.php">Logout</a>
</div>
</body>
</html>
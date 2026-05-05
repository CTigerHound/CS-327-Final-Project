<?php
session_start();
if (!isset($_SESSION["USERID"]) || $_SESSION["role"] != "coach") {
    echo "<h2>Access denied. <a href='index.php'>Login</a></h2>";
    exit();
}
$fullname = $_SESSION["FullName"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Coach Menu - Sport LFC</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Georgia', serif; background: #0f1923; color: #e8e0d0; min-height: 100vh; padding: 40px 20px; }
        .container { max-width: 600px; margin: 0 auto; }
        h1 { font-size: 2rem; color: #c9a84c; margin-bottom: 4px; }
        .subtitle { color: #888; margin-bottom: 40px; font-size: 0.95rem; }
        .menu-item { display: block; background: #1a2535; border: 1px solid #2a3a4a; border-left: 4px solid #4ca8c9; color: #e8e0d0; text-decoration: none; padding: 18px 24px; margin-bottom: 12px; border-radius: 4px; font-size: 1rem; transition: background 0.2s; }
        .menu-item:hover { background: #243040; }
        .menu-item span { display: block; font-size: 0.78rem; color: #778; margin-top: 3px; }
        .logout { border-left-color: #a84c4c; margin-top: 30px; }
    </style>
</head>
<body>
<div class="container">
    <h1>Welcome, <?php echo htmlspecialchars($fullname); ?></h1>
    <p class="subtitle">Coach Dashboard &mdash; Sport LFC</p>
    <a class="menu-item" href="searchTeam.php">Search for a Team<span>Find teams by sport or team name</span></a>
    <a class="menu-item" href="viewMyTeams.php">View My Teams<span>See all teams you are currently coaching</span></a>
    <a class="menu-item" href="leaveTeam.php">Leave a Team<span>Remove yourself from a team you coach</span></a>
    <a class="menu-item" href="addPlayerToTeam.php">Add a Player to a Team<span>Assign a player to one of your teams</span></a>
    <a class="menu-item logout" href="logout.php">Logout</a>
</div>
</body>
</html>
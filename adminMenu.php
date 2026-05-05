<?php
session_start();
if (!isset($_SESSION["USERID"]) || $_SESSION["role"] != "admin") {
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
    make sure to add the style to all the php below
    <p class="subtitle">Player Dashboard &mdash; Sport LFC</p>
    <a class="menu-item" href="searchTeam.php">Search for a Team<span>Find teams by sport or team name</span></a>
    <a class="menu-item" href="seePlayers.php">View Players<span>View all players</span></a>
    <a class="menu-item" href="seeCoaches.php">View Coaches<span>View all Coaches</span></a>

    <a class="menu-item" href="createTeams.php">Create a team<span>Create a team to add to the roster</span></a>
    
    <a class="menu-item" href="assignCoach.php">Assign Coach<span>Assign a coach to a team</span></a>
    <a class="menu-item" href="popularSports.php">Popular Sports<span>Prints a list of the most popular sports</span></a>
    <a class="menu-item" href="playersInMostTeams.php">Players in most teams<span>Prints a list of the players in the most teams</span></a>
    <a class="menu-item" href="coachesInMostTeams.php">Coaches in most teams<span>Prints a list of the coaches coaching the most teams</span></a>
    <a class="menu-item" href="popularEquipment.php">Popular Equipment<span>Finds the top 10 most popular equipment</span></a>

    <a class="menu-item logout" href="logout.php">Logout</a>
</div>
</body>
</html>
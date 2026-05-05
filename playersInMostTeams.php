<?php
session_start();
if (!isset($_SESSION["USERID"]) || $_SESSION["role"] != "admin") {
    echo "<h2>Access denied. <a href='index.php'>Login</a></h2>"; exit();
}
$conn = new mysqli("localhost", "root", "", "sportlfc");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$result = $conn->query(
    "SELECT u.Fname, u.Lname, u.USERID, COUNT(pi.TeamID) AS TeamCount
     FROM Plays_In pi
     JOIN Users u ON pi.PlayerID = u.USERID
     GROUP BY pi.PlayerID
     ORDER BY TeamCount DESC"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Players in Most Teams - Sport LFC</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Georgia', serif; background: #0f1923; color: #e8e0d0; min-height: 100vh; padding: 40px 20px; }
        .container { max-width: 700px; margin: 0 auto; }
        h1 { font-size: 1.8rem; color: #c9a84c; margin-bottom: 6px; }
        .sub { color: #778; font-size: 0.9rem; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; background: #1a2535; border-radius: 6px; overflow: hidden; }
        th { background: #c9a84c; color: #0f1923; padding: 12px 14px; text-align: left; font-size: 0.8rem; text-transform: uppercase; }
        td { padding: 12px 14px; border-bottom: 1px solid #2a3a4a; font-size: 0.9rem; }
        tr:hover td { background: #243040; }
        .rank { color: #c9a84c; font-weight: bold; }
        .back { display: inline-block; margin-top: 24px; color: #c9a84c; text-decoration: none; }
    </style>
</head>
<body>
<div class="container">
    <h1>Players in Most Teams</h1>
    <p class="sub">Ranked by number of teams each player is on</p>
    <table>
        <tr><th>Rank</th><th>Player</th><th>ID</th><th>Teams</th></tr>
        <?php $rank = 1; while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td class="rank">#<?php echo $rank++; ?></td>
            <td><?php echo htmlspecialchars($row["Fname"] . " " . $row["Lname"]); ?></td>
            <td><?php echo $row["USERID"]; ?></td>
            <td><?php echo $row["TeamCount"]; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <a class="back" href="adminMenu.php">&larr; Back to Menu</a>
</div>
</body>
</html>
<?php $conn->close(); ?>


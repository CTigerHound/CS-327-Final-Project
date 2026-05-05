<?php
session_start();
if (!isset($_SESSION["USERID"]) || $_SESSION["role"] != "coach") {
    echo "<h2>Access denied. <a href='index.php'>Login</a></h2>"; exit();
}
$coachID = $_SESSION["USERID"];
$conn = new mysqli("localhost", "root", "", "sportlfc");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["playerid"], $_POST["teamid"], $_POST["role"], $_POST["uniform"])) {
    $playerID  = (int)$_POST["playerid"];
    $teamID    = (int)$_POST["teamid"];
    $pRole     = $conn->real_escape_string($_POST["role"]);
    $uniformNo = (int)$_POST["uniform"];
    $authCheck = $conn->query("SELECT * FROM Coaches WHERE C_USERID=$coachID AND TeamID=$teamID");
    if ($authCheck->num_rows == 0) { $message = "error|You can only add players to teams you coach."; }
    else {
        $dup = $conn->query("SELECT * FROM Plays_In WHERE PlayerID=$playerID AND TeamID=$teamID");
        if ($dup->num_rows > 0) { $message = "error|That player is already on this team."; }
        else {
            $sport = $conn->query("SELECT SprtName FROM Team WHERE TeamID=$teamID")->fetch_assoc()["SprtName"];
            $playsIt = $conn->query("SELECT * FROM Plays WHERE PlayerID=$playerID AND SportName='$sport'");
            if ($playsIt->num_rows == 0) { $message = "error|That player does not play $sport."; }
            else {
                if ($conn->query("INSERT INTO Plays_In (PlayerID, TeamID, Role, Uniform_No) VALUES ($playerID, $teamID, '$pRole', $uniformNo)"))
                    $message = "success|Player added successfully!";
                else $message = "error|Something went wrong.";
            }
        }
    }
}
$myTeams = [];
$tRes = $conn->query("SELECT t.TeamID, t.Name, t.SprtName FROM Coaches co JOIN Team t ON co.TeamID=t.TeamID WHERE co.C_USERID=$coachID");
while ($row = $tRes->fetch_assoc()) $myTeams[] = $row;
$players = [];
$pRes = $conn->query("SELECT u.USERID, u.Fname, u.Lname, GROUP_CONCAT(p.SportName SEPARATOR ', ') AS Sports FROM Users u JOIN Player pl ON u.USERID=pl.P_USERID LEFT JOIN Plays p ON u.USERID=p.PlayerID GROUP BY u.USERID ORDER BY u.Lname");
while ($row = $pRes->fetch_assoc()) $players[] = $row;
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Player to Team - Sport LFC</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Georgia', serif; background: #0f1923; color: #e8e0d0; min-height: 100vh; padding: 40px 20px; }
        .container { max-width: 700px; margin: 0 auto; }
        h1 { font-size: 1.8rem; color: #4ca8c9; margin-bottom: 6px; }
        .sub { color: #778; font-size: 0.9rem; margin-bottom: 30px; }
        .card { background: #1a2535; border: 1px solid #2a3a4a; border-radius: 6px; padding: 24px; margin-bottom: 20px; }
        .card h2 { font-size: 0.8rem; color: #aaa; margin-bottom: 16px; text-transform: uppercase; letter-spacing: 1px; }
        label { display: block; font-size: 0.85rem; color: #aaa; margin-bottom: 6px; }
        select, input[type=text], input[type=number] { width: 100%; padding: 10px 14px; background: #0f1923; border: 1px solid #2a3a4a; border-radius: 4px; color: #e8e0d0; font-size: 0.95rem; margin-bottom: 16px; }
        .btn { background: #4ca8c9; color: #0f1923; border: none; padding: 10px 28px; border-radius: 4px; font-size: 1rem; cursor: pointer; font-weight: bold; }
        .msg-success { background: #1a3525; border-left: 4px solid #4caf70; padding: 12px 16px; border-radius: 4px; margin-bottom: 20px; color: #7de0a0; }
        .msg-error { background: #351a1a; border-left: 4px solid #af4c4c; padding: 12px 16px; border-radius: 4px; margin-bottom: 20px; color: #e07070; }
        .hint { font-size: 0.78rem; color: #556; margin-top: -12px; margin-bottom: 14px; }
        .back { display: inline-block; margin-top: 24px; color: #4ca8c9; text-decoration: none; }
    </style>
</head>
<body>
<div class="container">
    <h1>Add a Player to a Team</h1>
    <p class="sub">Assign a player to one of your teams</p>
    <?php if ($message): [$type, $text] = explode("|", $message, 2); ?>
        <div class="msg-<?php echo $type; ?>"><?php echo htmlspecialchars($text); ?></div>
    <?php endif; ?>
    <div class="card">
        <h2>Assignment Form</h2>
        <form method="post" action="addPlayerToTeam.php">
            <label>Select Your Team</label>
            <select name="teamid" required>
                <option value="">-- Choose a team --</option>
                <?php foreach ($myTeams as $t): ?>
                    <option value="<?php echo $t["TeamID"]; ?>"><?php echo htmlspecialchars($t["Name"]); ?> (<?php echo $t["SprtName"]; ?>)</option>
                <?php endforeach; ?>
            </select>
            <label>Select Player</label>
            <select name="playerid" required>
                <option value="">-- Choose a player --</option>
                <?php foreach ($players as $p): ?>
                    <option value="<?php echo $p["USERID"]; ?>"><?php echo htmlspecialchars($p["Fname"] . " " . $p["Lname"]); ?><?php if ($p["Sports"]): ?> — plays: <?php echo htmlspecialchars($p["Sports"]); ?><?php endif; ?></option>
                <?php endforeach; ?>
            </select>
            <p class="hint">Player must already play the team's sport.</p>
            <label>Role on Team</label>
            <input type="text" name="role" placeholder="e.g. Forward, Goalkeeper..." required>
            <label>Uniform Number</label>
            <input type="number" name="uniform" min="1" max="99" placeholder="e.g. 10" required>
            <input type="submit" class="btn" value="Add Player">
        </form>
    </div>
    <a class="back" href="coachMenu.php">&larr; Back to Menu</a>
</div>
</body>
</html>
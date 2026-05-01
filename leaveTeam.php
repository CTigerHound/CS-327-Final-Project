<?php
session_start();
if (!isset($_SESSION["userid"])) {
    echo "<h2>Access denied. <a href='index.php'>Login</a></h2>"; exit();
}
$role = $_SESSION["role"];
$userID = $_SESSION["userid"];
$backPage = ($role == "coach") ? "coachMenu.php" : "playerMenu.php";
$conn = new mysqli("localhost", "root", "", "sportlfc");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["teamid"])) {
    $teamID = (int)$_POST["teamid"];
    $sql = ($role == "player")
        ? "DELETE FROM Plays_In WHERE PlayerID=$userID AND TeamID=$teamID"
        : "DELETE FROM Coaches WHERE C_USERID=$userID AND TeamID=$teamID";
    if ($conn->query($sql) && $conn->affected_rows > 0)
        $message = "success|You have left the team successfully.";
    else $message = "error|Could not leave that team.";
}
$sql = ($role == "player")
    ? "SELECT t.TeamID, t.Name AS TeamName, t.SprtName, pi.Role FROM Plays_In pi JOIN Team t ON pi.TeamID = t.TeamID WHERE pi.PlayerID = $userID"
    : "SELECT t.TeamID, t.Name AS TeamName, t.SprtName, co.Role FROM Coaches co JOIN Team t ON co.TeamID = t.TeamID WHERE co.C_USERID = $userID";
$result = $conn->query($sql);
$teams = [];
while ($row = $result->fetch_assoc()) $teams[] = $row;
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Leave a Team - Sport LFC</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Georgia', serif; background: #0f1923; color: #e8e0d0; min-height: 100vh; padding: 40px 20px; }
        .container { max-width: 700px; margin: 0 auto; }
        h1 { font-size: 1.8rem; color: #c9a84c; margin-bottom: 6px; }
        .sub { color: #778; font-size: 0.9rem; margin-bottom: 30px; }
        .team-card { background: #1a2535; border: 1px solid #2a3a4a; border-radius: 6px; padding: 18px 24px; margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center; }
        .team-info h3 { font-size: 1rem; margin-bottom: 4px; }
        .team-info p { font-size: 0.82rem; color: #778; }
        .btn-leave { background: transparent; border: 1px solid #a84c4c; color: #e07070; padding: 8px 18px; border-radius: 4px; cursor: pointer; font-size: 0.88rem; }
        .btn-leave:hover { background: #a84c4c; color: #fff; }
        .msg-success { background: #1a3525; border-left: 4px solid #4caf70; padding: 12px 16px; border-radius: 4px; margin-bottom: 20px; color: #7de0a0; }
        .msg-error { background: #351a1a; border-left: 4px solid #af4c4c; padding: 12px 16px; border-radius: 4px; margin-bottom: 20px; color: #e07070; }
        .none { background: #1a2535; border-radius: 6px; padding: 40px; text-align: center; color: #778; }
        .back { display: inline-block; margin-top: 24px; color: #c9a84c; text-decoration: none; }
    </style>
</head>
<body>
<div class="container">
    <h1>Leave a Team</h1>
    <p class="sub">Select a team to remove yourself from</p>
    <?php if ($message): [$type, $text] = explode("|", $message, 2); ?>
        <div class="msg-<?php echo $type; ?>"><?php echo htmlspecialchars($text); ?></div>
    <?php endif; ?>
    <?php if (count($teams) > 0): foreach ($teams as $t): ?>
        <div class="team-card">
            <div class="team-info">
                <h3><?php echo htmlspecialchars($t["TeamName"]); ?> &mdash; <small><?php echo $t["SprtName"]; ?></small></h3>
                <p>Your role: <?php echo htmlspecialchars($t["Role"]); ?></p>
            </div>
            <form method="post" action="leaveTeam.php" onsubmit="return confirm('Leave <?php echo addslashes($t["TeamName"]); ?>?')">
                <input type="hidden" name="teamid" value="<?php echo $t["TeamID"]; ?>">
                <button type="submit" class="btn-leave">Leave</button>
            </form>
        </div>
    <?php endforeach; else: ?>
        <div class="none">You are not currently on any teams.</div>
    <?php endif; ?>
    <a class="back" href="<?php echo $backPage; ?>">&larr; Back to Menu</a>
</div>
</body>
</html>
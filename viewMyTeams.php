<?php
session_start();
if (!isset($_SESSION["USERID"])) {
    echo "<h2>Access denied. <a href='index.php'>Login</a></h2>"; exit();
}
$role = $_SESSION["role"];
$USERID = $_SESSION["USERID"];
$backPage = ($role == "coach") ? "coachMenu.php" : "playerMenu.php";
$conn = new mysqli("localhost", "root", "", "sportlfc");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
$teams = [];
if ($role == "player") {
    $sql = "SELECT t.TeamID, t.Name AS TeamName, t.SprtName, pi.Role, pi.Uniform_No, u.Fname, u.Lname
            FROM Plays_In pi JOIN Team t ON pi.TeamID = t.TeamID
            LEFT JOIN Coach c ON t.HeadCoach = c.C_USERID
            LEFT JOIN Users u ON c.C_USERID = u.USERID
            WHERE pi.PlayerID = $USERID";
} else {
    $sql = "SELECT t.TeamID, t.Name AS TeamName, t.SprtName, co.Role, u.Fname AS PFname, u.Lname AS PLname
            FROM Coaches co JOIN Team t ON co.TeamID = t.TeamID
            LEFT JOIN Player fp ON t.FrstPlayer = fp.P_USERID
            LEFT JOIN Users u ON fp.P_USERID = u.USERID
            WHERE co.C_USERID = $USERID";
}
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) $teams[] = $row;
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Teams - Sport LFC</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Georgia', serif; background: #0f1923; color: #e8e0d0; min-height: 100vh; padding: 40px 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        h1 { font-size: 1.8rem; color: #c9a84c; margin-bottom: 6px; }
        .sub { color: #778; font-size: 0.9rem; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; background: #1a2535; border-radius: 6px; overflow: hidden; }
        th { background: #c9a84c; color: #0f1923; padding: 12px 14px; text-align: left; font-size: 0.8rem; text-transform: uppercase; }
        td { padding: 12px 14px; border-bottom: 1px solid #2a3a4a; font-size: 0.9rem; }
        tr:hover td { background: #243040; }
        .none { background: #1a2535; border-radius: 6px; padding: 40px; text-align: center; color: #778; }
        .back { display: inline-block; margin-top: 24px; color: #c9a84c; text-decoration: none; }
    </style>
</head>
<body>
<div class="container">
    <h1>My Teams</h1>
    <p class="sub"><?php echo ucfirst($role); ?> view &mdash; <?php echo htmlspecialchars($_SESSION["FullName"]); ?></p>
    <?php if (count($teams) > 0): ?>
        <table>
            <tr>
                <th>ID</th><th>Team Name</th><th>Sport</th><th>Your Role</th>
                <?php if ($role == "player"): ?><th>Uniform #</th><th>Head Coach</th>
                <?php else: ?><th>First Player</th><?php endif; ?>
            </tr>
            <?php foreach ($teams as $t): ?>
            <tr>
                <td><?php echo $t["TeamID"]; ?></td>
                <td><?php echo htmlspecialchars($t["TeamName"]); ?></td>
                <td><?php echo htmlspecialchars($t["SprtName"]); ?></td>
                <td><?php echo htmlspecialchars($t["Role"]); ?></td>
                <?php if ($role == "player"): ?>
                    <td>#<?php echo $t["Uniform_No"]; ?></td>
                    <td><?php echo htmlspecialchars($t["Fname"] . " " . $t["Lname"]); ?></td>
                <?php else: ?>
                    <td><?php echo htmlspecialchars(($t["PFname"] ?? "-") . " " . ($t["PLname"] ?? "")); ?></td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <div class="none">You are not on any teams yet.</div>
    <?php endif; ?>
    <a class="back" href="<?php echo $backPage; ?>">&larr; Back to Menu</a>
</div>
</body>
</html>
<?php
session_start();
    $menuPage = "index.php";

if ($_SESSION["role"] == "admin") {
    $menuPage = "adminMenu.php";
}
else if ($_SESSION["role"] == "coach") {
    $menuPage = "coachMenu.php";
}
else if ($_SESSION["role"] == "player") {
    $menuPage = "playerMenu.php";
}

if (!isset($_SESSION["USERID"])) {
    echo "<h2>Access denied. <a href='index.php'>Login</a></h2>";
    exit();
}
$role = $_SESSION["role"];
$backPage = ($role == "coach") ? "coachMenu.php" : "playerMenu.php";
$conn = new mysqli("localhost", "root", "", "sportlfc");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
$results = [];
$searched = false;
if (isset($_GET["sport"]) || isset($_GET["teamname"])) {
    $searched = true;
    $sport    = $conn->real_escape_string($_GET["sport"] ?? "");
    $teamname = $conn->real_escape_string($_GET["teamname"] ?? "");
    $sql = "SELECT t.TeamID, t.Name AS TeamName, t.SprtName, t.Sex, t.Min_age, t.Max_age, u.Fname, u.Lname
            FROM Team t LEFT JOIN Coach c ON t.HeadCoach = c.C_USERID
            LEFT JOIN Users u ON c.C_USERID = u.USERID WHERE 1=1";
    if ($sport != "")    $sql .= " AND t.SprtName LIKE '%$sport%'";
    if ($teamname != "") $sql .= " AND t.Name LIKE '%$teamname%'";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) $results[] = $row;
}
$sportsResult = $conn->query("SELECT Name FROM Sport ORDER BY Name");
$sports = [];
while ($s = $sportsResult->fetch_assoc()) $sports[] = $s["Name"];
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Search Teams - Sport LFC</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Georgia', serif; background: #0f1923; color: #e8e0d0; min-height: 100vh; padding: 40px 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        h1 { font-size: 1.8rem; color: #c9a84c; margin-bottom: 30px; }
        form { background: #1a2535; border: 1px solid #2a3a4a; border-radius: 6px; padding: 24px; margin-bottom: 30px; }
        label { display: block; font-size: 0.85rem; color: #aaa; margin-bottom: 6px; }
        input[type=text], select { width: 100%; padding: 10px 14px; background: #0f1923; border: 1px solid #2a3a4a; border-radius: 4px; color: #e8e0d0; font-size: 0.95rem; margin-bottom: 16px; }
        .btn { background: #c9a84c; color: #0f1923; border: none; padding: 10px 28px; border-radius: 4px; font-size: 1rem; cursor: pointer; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; background: #1a2535; border-radius: 6px; overflow: hidden; }
        th { background: #c9a84c; color: #0f1923; padding: 12px 14px; text-align: left; font-size: 0.8rem; text-transform: uppercase; }
        td { padding: 12px 14px; border-bottom: 1px solid #2a3a4a; font-size: 0.9rem; }
        tr:hover td { background: #243040; }
        .back { display: inline-block; margin-top: 24px; color: #c9a84c; text-decoration: none; }
        .none { color: #778; padding: 20px; text-align: center; }
    </style>
</head>
<body>
<div class="container">
    <h1>Search for a Team</h1>
    <form method="get" action="searchTeam.php">
        <label>Sport</label>
        <select name="sport">
            <option value="">-- Any Sport --</option>
            <?php foreach ($sports as $s): ?>
                <option value="<?php echo $s; ?>" <?php echo (isset($_GET["sport"]) && $_GET["sport"] == $s) ? "selected" : ""; ?>><?php echo htmlspecialchars($s); ?></option>
            <?php endforeach; ?>
        </select>
        <label>Team Name</label>
        <input type="text" name="teamname" value="<?php echo htmlspecialchars($_GET["teamname"] ?? ""); ?>" placeholder="e.g. Lions">
        <input type="submit" class="btn" value="Search">
    </form>
    <?php if ($searched): ?>
        <?php if (count($results) > 0): ?>
            <table>
                <tr><th>ID</th><th>Team Name</th><th>Sport</th><th>Gender</th><th>Age Range</th><th>Head Coach</th></tr>
                <?php foreach ($results as $row): ?>
                <tr>
                    <td><?php echo $row["TeamID"]; ?></td>
                    <td><?php echo htmlspecialchars($row["TeamName"]); ?></td>
                    <td><?php echo htmlspecialchars($row["SprtName"]); ?></td>
                    <td><?php echo $row["Sex"] == "M" ? "Male" : "Female"; ?></td>
                    <td><?php echo $row["Min_age"]; ?> - <?php echo $row["Max_age"]; ?></td>
                    <td><?php echo htmlspecialchars($row["Fname"] . " " . $row["Lname"]); ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p class="none">No teams found.</p>
        <?php endif; ?>
    <?php endif; ?>
    <a class="back" href="<?php echo $menuPage; ?>">&larr; Back to Menu</a>
</div>
</body>
</html>
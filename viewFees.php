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

if (!isset($_SESSION["USERID"]) || $_SESSION["role"] != "admin" && $_SESSION["role"] != "player") {
    echo "<h2>Access denied. <a href='index.php'>Login</a></h2>"; exit();
}
$playerID = $_SESSION["USERID"];
$conn = new mysqli("localhost", "root", "", "sportlfc");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
$fee = 0;
$feeRes = $conn->query("SELECT Fee FROM Player WHERE P_USERID=$playerID");
if ($row = $feeRes->fetch_assoc()) $fee = $row["Fee"];
$teams = [];
$tRes = $conn->query("SELECT t.Name AS TeamName, t.SprtName, pi.Role FROM Plays_In pi JOIN Team t ON pi.TeamID=t.TeamID WHERE pi.PlayerID=$playerID");
while ($row = $tRes->fetch_assoc()) $teams[] = $row;
$equipTotal = 0;
$eRes = $conn->query("SELECT SUM(e.Price * b.QTY) AS Total FROM Buys b JOIN Equipment e ON b.EQID=e.EQID WHERE b.PlayerID=$playerID");
if ($row = $eRes->fetch_assoc()) $equipTotal = $row["Total"] ?? 0;
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Fees - Sport LFC</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Georgia', serif; background: #0f1923; color: #e8e0d0; min-height: 100vh; padding: 40px 20px; }
        .container { max-width: 600px; margin: 0 auto; }
        h1 { font-size: 1.8rem; color: #c9a84c; margin-bottom: 6px; }
        .sub { color: #778; font-size: 0.9rem; margin-bottom: 30px; }
        .card { background: #1a2535; border: 1px solid #2a3a4a; border-radius: 6px; padding: 24px; margin-bottom: 20px; }
        .card h2 { font-size: 0.8rem; color: #aaa; margin-bottom: 16px; text-transform: uppercase; letter-spacing: 1px; }
        .fee-amount { font-size: 2.8rem; color: #c9a84c; font-weight: bold; }
        .row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #2a3a4a; font-size: 0.9rem; }
        .row:last-child { border-bottom: none; }
        .row .label { color: #aaa; }
        .row .gold { color: #c9a84c; font-weight: bold; }
        .team-row { padding: 10px 0; border-bottom: 1px solid #2a3a4a; font-size: 0.88rem; }
        .back { display: inline-block; margin-top: 24px; color: #c9a84c; text-decoration: none; }
    </style>
</head>
<body>
<d class="container">
    <h1>My Fees</h1>
    <p class="sub"><?php echo htmlspecialchars($_SESSION["FullName"]); ?></p>
    <div class="card">
        <h2>Registration Fee</h2>
        <div class="fee-amount">$<?php echo number_format($fee, 2); ?></div>
    </div>
    <div class="card">
        <h2>Financial Summary</h2>
        <div class="row"><span class="label">Registration Fee</span><span>$<?php echo number_format($fee, 2); ?></span></div>
        <div class="row"><span class="label">Equipment Purchased</span><span>$<?php echo number_format($equipTotal, 2); ?></span></div>
        <div class="row"><span class="label">Total</span><span class="gold">$<?php echo number_format($fee + $equipTotal, 2); ?></span></div>
    </div>
    <div class="card">
        <h2>My Teams (<?php echo count($teams); ?>)</h2>
        <?php if (count($teams) > 0): foreach ($teams as $t): ?>
            <div class="team-row"><?php echo htmlspecialchars($t["TeamName"]); ?> &mdash; <?php echo $t["SprtName"]; ?></div>
        <?php endforeach; else: echo "<p style='color:#778'>Not on any teams.</p>"; endif; ?>
    </div>
    <a class="back" href="<?php echo $menuPage; ?>">&larr; Back to Menu</a>
    </div>
</body>
</html>
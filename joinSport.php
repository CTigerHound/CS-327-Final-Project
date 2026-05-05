<?php
session_start();
if (!isset($_SESSION["USERID"]) || $_SESSION["role"] != "player") {
    echo "<h2>Access denied. <a href='index.php'>Login</a></h2>"; exit();
}
$playerID = $_SESSION["USERID"];
$conn = new mysqli("localhost", "root", "", "sportlfc");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["sport"])) {
    $sport = $conn->real_escape_string($_POST["sport"]);
    $check = $conn->query("SELECT * FROM Plays WHERE PlayerID=$playerID AND SportName='$sport'");
    if ($check->num_rows > 0) {
        $message = "error|You are already registered in $sport.";
    } else {
        if ($conn->query("INSERT INTO Plays (PlayerID, SportName) VALUES ($playerID, '$sport')"))
            $message = "success|You have successfully joined $sport!";
        else $message = "error|Something went wrong.";
    }
}
$allSports = [];
$res = $conn->query("SELECT Name FROM Sport ORDER BY Name");
while ($s = $res->fetch_assoc()) $allSports[] = $s["Name"];
$mySports = [];
$res2 = $conn->query("SELECT SportName FROM Plays WHERE PlayerID=$playerID");
while ($s = $res2->fetch_assoc()) $mySports[] = $s["SportName"];
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Join a Sport - Sport LFC</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Georgia', serif; background: #0f1923; color: #e8e0d0; min-height: 100vh; padding: 40px 20px; }
        .container { max-width: 600px; margin: 0 auto; }
        h1 { font-size: 1.8rem; color: #c9a84c; margin-bottom: 6px; }
        .sub { color: #778; font-size: 0.9rem; margin-bottom: 30px; }
        .card { background: #1a2535; border: 1px solid #2a3a4a; border-radius: 6px; padding: 24px; margin-bottom: 20px; }
        .card h2 { font-size: 0.8rem; color: #aaa; margin-bottom: 16px; text-transform: uppercase; letter-spacing: 1px; }
        label { display: block; font-size: 0.85rem; color: #aaa; margin-bottom: 6px; }
        select { width: 100%; padding: 10px 14px; background: #0f1923; border: 1px solid #2a3a4a; border-radius: 4px; color: #e8e0d0; font-size: 0.95rem; margin-bottom: 16px; }
        .btn { background: #c9a84c; color: #0f1923; border: none; padding: 10px 28px; border-radius: 4px; font-size: 1rem; cursor: pointer; font-weight: bold; }
        .msg-success { background: #1a3525; border-left: 4px solid #4caf70; padding: 12px 16px; border-radius: 4px; margin-bottom: 20px; color: #7de0a0; }
        .msg-error { background: #351a1a; border-left: 4px solid #af4c4c; padding: 12px 16px; border-radius: 4px; margin-bottom: 20px; color: #e07070; }
        .sport-tag { display: inline-block; background: #243040; border-radius: 20px; padding: 4px 14px; margin: 4px; font-size: 0.85rem; color: #c9a84c; border: 1px solid #2a3a4a; }
        .back { display: inline-block; margin-top: 24px; color: #c9a84c; text-decoration: none; }
    </style>
</head>
<body>
<div class="container">
    <h1>Join a Sport</h1>
    <p class="sub">Register yourself in a new sport</p>
    <?php if ($message): [$type, $text] = explode("|", $message, 2); ?>
        <div class="msg-<?php echo $type; ?>"><?php echo htmlspecialchars($text); ?></div>
    <?php endif; ?>
    <div class="card">
        <h2>Sports You Currently Play</h2>
        <?php if (count($mySports) > 0): foreach ($mySports as $s): ?>
            <span class="sport-tag"><?php echo htmlspecialchars($s); ?></span>
        <?php endforeach; else: echo "<p style='color:#778'>None yet.</p>"; endif; ?>
    </div>
    <div class="card">
        <h2>Join a New Sport</h2>
        <form method="post" action="joinSport.php">
            <label>Select Sport</label>
            <select name="sport" required>
                <option value="">-- Choose a sport --</option>
                <?php foreach ($allSports as $s): ?>
                    <option value="<?php echo $s; ?>"><?php echo htmlspecialchars($s); ?></option>
                <?php endforeach; ?>
            </select>
            <input type="submit" class="btn" value="Join Sport">
        </form>
    </div>
    <a class="back" href="playerMenu.php">&larr; Back to Menu</a>
</div>
</body>
</html>
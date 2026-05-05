<?php
session_start();
if (!isset($_SESSION["USERID"]) || $_SESSION["role"] != "player") {
    echo "<h2>Access denied. <a href='index.php'>Login</a></h2>"; exit();
}
$playerID = $_SESSION["USERID"];
$conn = new mysqli("localhost", "root", "", "sportlfc");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["eqid"], $_POST["qty"])) {
    $eqid = (int)$_POST["eqid"];
    $qty  = (int)$_POST["qty"];
    if ($qty < 1) { $message = "error|Quantity must be at least 1."; }
    else {
        $check = $conn->query("SELECT * FROM Buys WHERE PlayerID=$playerID AND EQID=$eqid");
        if ($check->num_rows > 0) { $conn->query("UPDATE Buys SET QTY=QTY+$qty WHERE PlayerID=$playerID AND EQID=$eqid"); $message = "success|Order updated!"; }
        else { $conn->query("INSERT INTO Buys (EQID, PlayerID, QTY) VALUES ($eqid, $playerID, $qty)"); $message = "success|Equipment ordered!"; }
    }
}
$equipment = [];
$eqRes = $conn->query("SELECT DISTINCT e.EQID, e.Name, e.Size, e.Price, n.Sport_name FROM Equipment e JOIN Needs n ON e.EQID=n.EQID JOIN Plays p ON n.Sport_name=p.SportName WHERE p.PlayerID=$playerID ORDER BY n.Sport_name, e.Name");
while ($row = $eqRes->fetch_assoc()) $equipment[] = $row;
$orders = [];
$ordRes = $conn->query("SELECT e.Name, e.Price, b.QTY, (e.Price*b.QTY) AS Total FROM Buys b JOIN Equipment e ON b.EQID=e.EQID WHERE b.PlayerID=$playerID");
while ($row = $ordRes->fetch_assoc()) $orders[] = $row;
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Order Equipment - Sport LFC</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Georgia', serif; background: #0f1923; color: #e8e0d0; min-height: 100vh; padding: 40px 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        h1 { font-size: 1.8rem; color: #c9a84c; margin-bottom: 6px; }
        .sub { color: #778; font-size: 0.9rem; margin-bottom: 30px; }
        .card { background: #1a2535; border: 1px solid #2a3a4a; border-radius: 6px; padding: 24px; margin-bottom: 20px; }
        .card h2 { font-size: 0.8rem; color: #aaa; margin-bottom: 16px; text-transform: uppercase; letter-spacing: 1px; }
        label { display: block; font-size: 0.85rem; color: #aaa; margin-bottom: 6px; }
        select, input[type=number] { width: 100%; padding: 10px 14px; background: #0f1923; border: 1px solid #2a3a4a; border-radius: 4px; color: #e8e0d0; font-size: 0.95rem; margin-bottom: 16px; }
        .btn { background: #c9a84c; color: #0f1923; border: none; padding: 10px 28px; border-radius: 4px; font-size: 1rem; cursor: pointer; font-weight: bold; }
        .msg-success { background: #1a3525; border-left: 4px solid #4caf70; padding: 12px 16px; border-radius: 4px; margin-bottom: 20px; color: #7de0a0; }
        .msg-error { background: #351a1a; border-left: 4px solid #af4c4c; padding: 12px 16px; border-radius: 4px; margin-bottom: 20px; color: #e07070; }
        table { width: 100%; border-collapse: collapse; }
        th { color: #c9a84c; text-align: left; font-size: 0.8rem; text-transform: uppercase; padding: 8px 0; border-bottom: 1px solid #2a3a4a; }
        td { padding: 10px 0; border-bottom: 1px solid #1a2535; font-size: 0.9rem; }
        .total-row td { color: #c9a84c; font-weight: bold; border-top: 2px solid #2a3a4a; }
        .back { display: inline-block; margin-top: 24px; color: #c9a84c; text-decoration: none; }
    </style>
</head>
<body>
<div class="container">
    <h1>Order Equipment</h1>
    <p class="sub">Equipment available for your registered sports</p>
    <?php if ($message): [$type, $text] = explode("|", $message, 2); ?>
        <div class="msg-<?php echo $type; ?>"><?php echo htmlspecialchars($text); ?></div>
    <?php endif; ?>
    <div class="card">
        <h2>Place an Order</h2>
        <?php if (count($equipment) > 0): ?>
        <form method="post" action="orderEquipment.php">
            <label>Select Equipment</label>
            <select name="eqid" required>
                <option value="">-- Choose equipment --</option>
                <?php $lastSport = ""; foreach ($equipment as $eq):
                    if ($eq["Sport_name"] != $lastSport) { if ($lastSport != "") echo "</optgroup>"; echo "<optgroup label='" . htmlspecialchars($eq["Sport_name"]) . "'>"; $lastSport = $eq["Sport_name"]; } ?>
                    <option value="<?php echo $eq["EQID"]; ?>"><?php echo htmlspecialchars($eq["Name"]); ?> (<?php echo $eq["Size"]; ?>) - $<?php echo number_format($eq["Price"], 2); ?></option>
                <?php endforeach; echo "</optgroup>"; ?>
            </select>
            <label>Quantity</label>
            <input type="number" name="qty" min="1" value="1" required>
            <input type="submit" class="btn" value="Order">
        </form>
        <?php else: ?><p style="color:#778">Join a sport first to see available equipment.</p><?php endif; ?>
    </div>
    <div class="card">
        <h2>My Current Orders</h2>
        <?php if (count($orders) > 0): $grand = 0; ?>
            <table>
                <tr><th>Item</th><th>Unit Price</th><th>Qty</th><th>Total</th></tr>
                <?php foreach ($orders as $o): $grand += $o["Total"]; ?>
                <tr><td><?php echo htmlspecialchars($o["Name"]); ?></td><td>$<?php echo number_format($o["Price"],2); ?></td><td><?php echo $o["QTY"]; ?></td><td>$<?php echo number_format($o["Total"],2); ?></td></tr>
                <?php endforeach; ?>
                <tr class="total-row"><td colspan="3">Grand Total</td><td>$<?php echo number_format($grand,2); ?></td></tr>
            </table>
        <?php else: ?><p style="color:#778">No orders yet.</p><?php endif; ?>
    </div>
    <a class="back" href="playerMenu.php">&larr; Back to Menu</a>
</div>
</body>
</html>
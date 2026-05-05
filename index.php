<!DOCTYPE html>
<html lang="en">
<head>
    <title>Welcome to Sport LFC</title>
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
<h1>Welcome to Sport LFC</h1>
<h3>Please log into the system to continue</h3>
<form action="welcome.php" method="POST">

    ID: <input type="text" name="USERID">
    <br><br>
    Password: <input type="Password" name="Password">
    <br><br>
    Don't have an account yet? Sign up <a href="signup.php">here</a>.
    <br><br>
    <input type="reset" value="Clear">
    <input type = "submit" value="Login">
</form>

</body>
</html>
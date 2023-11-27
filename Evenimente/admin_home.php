<?php
// We need to use sessions, so you should always start sessions using the

session_start();

// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Acasă</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet"
          href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
</head>
<body class="loggedin">
<nav class="navtop">
    <div>
        <a href="logout.php"><i class="fas fa-sign-outalt"></i>Deconectare</a>
    </div>
</nav>
<div class="content">
    <h2>Acasă</h2>
    <p>Bine ați revenit, <?=$_SESSION['name']?>!</p>
</div>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<h1>Control Panel</h1>
<ul>
    <li><a href="add.php">Adaugă un eveniment nou</a></li>
    <li><a href="speaker_add.php">Adaugă un speaker nou</a></li>
    <li><a href="partener_add.php">Adaugă un partener nou</a></li>
    <li><a href="sponsor_add.php">Adaugă un sponsor nou</a></li>
    <li><a href="send_invitations.php">Trimite invitații la evenimente</a></li>
    <li><a href="view.php"</a>Vizualizează catalogul de evenimente</li>
</ul>
</body>
</html>

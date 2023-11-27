<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet"
          href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
</head>
<body>
<div class="login">
    <h1>Autentificare Utilizator</h1>
    <form action="user_login.php" method="post">

        <label for="username">
            <i class="fas fa-user"></i>
        </label>
        <input type="text" name="username" placeholder="Username"
               id="username" required>
        <label for="password">
            <i class="fas fa-lock"></i>
        </label>
        <input type="password" name="password"
               placeholder="Password" id="password" required>
        <input type="submit" value="Login">
    </form>
    <div><a href="user_registration.php">Utilizator nou</a></div>
    <br>
    <br>
    <h2><a href="index.html">Pagina de start</a></h2>
</div>
</body>
</html>


<?php
session_start();
include ('config.php');

if ( mysqli_connect_errno() ) {

    exit('Esec conectare MySQL: ' . mysqli_connect_error());
}

if ( !isset($_POST['username'], $_POST['password']) ) {
    exit('Completați cu nume de utilizator și parolă!');
}
if ($stmt = $mysqli->prepare('SELECT id, parola FROM inregistrare WHERE username = ?')) {
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password);
        $stmt->fetch();

        if (password_verify($_POST['password'], $password)) {

            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['id'] = $id;
            echo 'Bine ati venit' . $_SESSION['name'] . '!';
            header('Location: user_home.php');
        } else {
            echo 'Nume de utilizator sau parolă incorectă!';
        }
    } else {
        echo 'Nume de utilizator sau parolă incorectă!';
    }
    $stmt->close();
}


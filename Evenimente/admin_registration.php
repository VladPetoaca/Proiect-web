<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><!DOCTYPE html>
        <html>
        <head>
        <meta charset="utf-8">
        <title>Inregistrare</title>
    <link rel="stylesheet"
          href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
</head>
<body>
<div class="register">
    <h1>Înregistrare cont nou de Administrator</h1>
    <form action="admin_registration.php" method="post"
          autocomplete="off">
        <label for="username">
            <i class="fas fa-user"></i>
        </label>
        <input type="text" name="username"
               placeholder="Username" id="username" required>
        <label for="password">
            <i class="fas fa-lock"></i>
        </label>
        <input type="password" name="password"
               placeholder="Parolă" id="password" required>
        <input type="submit" value="Submit">
    </form>
    <br>
    <br>
    <h2><a href="index.html">Pagina de start</a></h2>
</div>
</body>
</html>




<?php
include('config.php');


if (!isset($_POST['username'], $_POST['password'])) {
    exit('Completați formularul!');
}
if (empty($_POST['username']) || empty($_POST['password'])) {
    exit('Completați formularul de înregistrare!');
}

if (preg_match('/[A-Za-z0-9]+/', $_POST['username']) == 0) {
    exit('Numele de utilizator nu este valid!');
}
if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {

    exit('Parola trebuie să aibă între 5 si 20 de caractere!');
}
if ($stmt = $mysqli->prepare("SELECT parola FROM admin WHERE user = ?")) {
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo 'Numele de utilizator există, alegeți altul!';
    } else {
        if ($stmt = $mysqli->prepare("INSERT INTO admin (user, parola) VALUES (?, ?)")) {

            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt->bind_param('ss', $_POST['username'], $password);
            $stmt->execute();
            echo 'Înregistrare efectuată cu succes!';
            header('Location: index.html');
        } else {

            echo 'Nu se poate face prepare statement!';
        }
    }
    $stmt->close();
} else {
    echo 'Nu se poate face prepare statement!';
}
$mysqli->close();

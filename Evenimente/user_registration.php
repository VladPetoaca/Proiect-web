<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Înregistrare utilizator nou</title>
    <link rel="stylesheet"
          href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
</head>
<body>
<div class="register">
    <h1>Înregistrare cont nou de utilizator</h1>
    <form action="user_registration.php" method="post"
          autocomplete="off">
        <label for="username">
            <i class="fas fa-user"></i>
        </label>
        <input type="text" name="username"
               placeholder="Username" id="username" required>
        <br>
        <br>
        <label for="password">
            <i class="fas fa-lock"></i>
        </label>
        <input type="password" name="password"
               placeholder="Parola" id="password" required>
        <br>
        <br>
        <label for="nume">
            <i class="fas fa-user"></i>
        </label>
        <input type="text" name="nume"
               placeholder="Nume" id="nume" required>
        <br>
        <br>
        <label for="Prenume">
            <i class="fas fa-user"></i>
        </label>
        <input type="text" name="prenume"
               placeholder="Prenume" id="prenume" required>
        <br>
        <br>
        <label for="email">
            <i class="fas fa-envelope"></i>
        </label>
        <input type="email" name="email"
               placeholder="Email" id="email" required>
        <br>
        <br>
        <label for="telefon">
            <i class="fas fa-phone"></i>
        </label>
        <input type="text" name="telefon"
               placeholder="Telefon" id="telefon" required>
        <br>
        <br>
        <label for="email">
            <i class="fas fa-tty"></i>
        </label>
        <textarea name="detalii"
                  placeholder="Detalii" value="Detalii" id="detalii"></textarea>
        <br>
        <br>
        <input type="submit" value="Register">
    </form>
</div>
<br>
<br>
<h2><a href="index.html">Pagina de start</a></h2>
</body>
</html>


<?php
include ('config.php');

if (mysqli_connect_errno()) {

    exit('Nu se poate conecta la MySQL: ' . mysqli_connect_error());
}

if (!isset($_POST['username'], $_POST['password'], $_POST['email'], $_POST['nume'], $_POST['prenume'], $_POST['telefon'])) {
    exit('Completati formularul de inregistrare!');
}
if (empty($_POST['username']) || empty($_POST['password']) ||
    empty($_POST['email'])) {
    exit('Completare formular de inregistrare!');
}
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    exit('Adresa de e-mail nu este valida!');
}
if (preg_match('/[A-Za-z0-9]+/', $_POST['username']) == 0) {
    exit('Numele de utilizator nu este valid!');
}
if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {

    exit('Parola trebuie sa aiba intre 5 si 20 charactere!');
}
if ($stmt = $mysqli->prepare("SELECT parola FROM inregistrare WHERE username = ?")) {
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo 'Numele de utilizator exista, alegeti altul!';
    } else {
        if ($stmt = $mysqli->prepare("INSERT INTO inregistrare (username, parola, nume, prenume, email, telefon, detalii) VALUES (?, ?, ?, ?, ?, ?, ?)")) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt->bind_param('sssssss', $_POST['username'], $password, $_POST['email'], $_POST['nume'], $_POST['prenume'], $_POST['telefon'], $_POST['detalii']);
            $stmt->execute();
            header('Location: Index.html');
        } else {
            echo 'Nu se poate face prepare statement!';
        }
    }
    $stmt->close();
} else {
    echo 'Nu se poate face prepare statement!';
}
$mysqli->close();


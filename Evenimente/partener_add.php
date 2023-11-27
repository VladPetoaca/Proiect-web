<?php
session_start();

include('config.php');
$error = '';

if (isset($_POST['submit'])) {
    // Preluam datele de pe formular
    $nume = htmlentities($_POST['nume'], ENT_QUOTES);
    $email = htmlentities($_POST['email'], ENT_QUOTES);
    $telefon = htmlentities($_POST['telefon'], ENT_QUOTES);
    $adresa = htmlentities($_POST['adresa'], ENT_QUOTES);

    // Verificam daca sunt completate
    if (empty($nume) || empty($email) || empty($telefon) || empty($adresa)) {
        // Daca sunt goale se afiseaza un mesaj
        $error = 'Eroare: Câmpuri goale!';
    } else {
        // Insert eveniment
        $sql = "INSERT INTO parteneri (Nume, Email, Adresa, Telefon) VALUES (?, ?, ?, ?)";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("ssss", $nume, $email, $adresa, $telefon);
            $stmt->execute();
            echo "Partenerul a fost adăugat cu succes!";
        } else {
            echo "Nu s-a putut adăuga partenerul.";
        }
        $stmt->close();
        echo "ERROR: " . $mysqli->error;
    }
}

// Close the database connection
$mysqli->close();

// Display error message if any
if ($error != '') {
    echo "<div style='padding:4px; border:1px solid red; color:red'>" . $error . "</div>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="crud.css">
    <title>Adăugare Partener</title>
</head>

<body>
<h1>Adăugare Partener</h1>

<?php
if ($error != '') {
    echo "<div style='padding:4px; border:1px solid red; color:red'>" . $error . "</div>";
}
?>

<form action="" method="post">
    <div>
        <label for="nume"><strong>Nume: </strong></label><input type="text" name="nume" value=""/><br />
        <label for="email"><strong>Email: </strong></label><input type="text" name="email"/><br />
        <label for="telefon"><strong>Telefon: </strong></label><input type="text" name="telefon"/><br />
        <label for="adresa"><strong>Adresa: </strong></label><input type="text" name="adresa" value=""/><br />

        <br />
        <input type="submit" name="submit" value="Adaugă" />
        <a href="partener_view.php">Lista de parteneri</a>
    </div>
</form>
</body>
</html>

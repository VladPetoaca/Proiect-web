<?php
include('config.php');

$con = mysqli_connect($hostname, $username, $password, $db);

if ($con->connect_error) {
    die("Conexiunea la baza de date a eșuat: " . $con->connect_error);
}

$eveniment_id = $_GET['id'];

// Selectează detaliile evenimentului
$sql_eveniment = "SELECT Titlu, Descriere, Data, Locatia, Contact_Nume, Contact_Telefon, Contact_Email FROM evenimente WHERE ID = $eveniment_id";
$result_eveniment = $con->query($sql_eveniment);

if ($result_eveniment->num_rows > 0) {
    $row_eveniment = $result_eveniment->fetch_assoc();
    $titlu = $row_eveniment['Titlu'];
    $descriere = $row_eveniment['Descriere'];
    $data = $row_eveniment['Data'];
    $locatia = $row_eveniment['Locatia'];
    $contact_nume = $row_eveniment['Contact_Nume'];
    $contact_telefon = $row_eveniment['Contact_Telefon'];
    $contact_email = $row_eveniment['Contact_Email'];
} else {
    echo "Evenimentul nu a fost găsit.";
    exit;
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titlu; ?> - Contact, Locația evenimentului</title>
</head>
<body>

<h1><?php echo $titlu; ?></h1>
<p><?php echo $descriere; ?></p>
<p>Data: <?php echo $data; ?></p>
<p>Locația: <?php echo $locatia; ?></p>

<h2>Contact:</h2>
<p>Nume: <?php echo $contact_nume; ?></p>
<p>Telefon: <?php echo $contact_telefon; ?></p>
<p>Email: <?php echo $contact_email; ?></p>

</body>
</html>
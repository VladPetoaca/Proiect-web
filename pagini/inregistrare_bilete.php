<?php
include('config.php');

//$mysqli = mysqli_connect($hostname, $username, $password, $db);

if ($mysqli->connect_error) {
    die("Conexiunea la baza de date a eșuat: " . $con->connect_error);
}

$eveniment_id = $_GET['id'];

// Selectează detaliile evenimentului
$sql_eveniment = "SELECT Titlu, Descriere, Data, Locatia FROM evenimente WHERE ID = $eveniment_id";
$result_eveniment = $mysqli->query($sql_eveniment);

if ($result_eveniment->num_rows > 0) {
    $row_eveniment = $result_eveniment->fetch_assoc();
    $titlu = $row_eveniment['Titlu'];
    $descriere = $row_eveniment['Descriere'];
    $data = $row_eveniment['Data'];
    $locatia = $row_eveniment['Locatia'];
} else {
    echo "Evenimentul nu a fost găsit.";
    exit;
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titlu; ?> - Bilete și Înregistrare</title>
</head>
<body>

<h1><?php echo $titlu; ?></h1>
<p><?php echo $descriere; ?></p>
<p>Data: <?php echo $data; ?></p>
<p>Locația: <?php echo $locatia; ?></p>

<h2>Bilete și Înregistrare:</h2>

</body>
</html>
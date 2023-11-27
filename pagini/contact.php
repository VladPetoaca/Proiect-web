<?php
include('config.php');

$eveniment_id = $_GET['id'];

// Selectează detaliile evenimentului
$sql = "SELECT Titlu, Descriere, Contact FROM evenimente WHERE ID = $eveniment_id";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    $row= $result->fetch_assoc();
    $titlu = $row['Titlu'];
    $descriere = $row['Descriere'];
    $contact = $row['Contact'];
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
    <title><?php echo $titlu; ?> - Contact, Locația evenimentului</title>
</head>
<body>

<h1><?php echo $titlu; ?></h1>
<p><i><?php echo $descriere; ?></i></p>

<h2>Contact:</h2>
<p><?php echo $contact; ?></p>
</body>
</html>
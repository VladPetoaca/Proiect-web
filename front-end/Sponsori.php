<?php
include('config.php');

//$mysqli = mysqli_connect($hostname, $username, $password, $db);

if ($mysqli->connect_error) {
    die("Conexiunea la baza de date a eșuat: " . $mysqli->connect_error);
}

$eveniment_id = $_GET['id'];

// Selectează partenerii pentru evenimentul specific
$sql_parteneri = "SELECT p.Nume FROM parteneri p
    JOIN eveniment_parteneri ep ON p.ID = ep.Parteneri_ID
    WHERE ep.Eveniment_ID = $eveniment_id";
$result_parteneri = $mysqli->query($sql_parteneri);

// Selectează sponsorii pentru evenimentul specific
$sql_sponsori = "SELECT s.Nume FROM sponsori s
    JOIN eveniment_sponsori es ON s.ID = es.Sponsori_ID
    WHERE es.Eveniment_ID = $eveniment_id";
$result_sponsori = $mysqli->query($sql_sponsori);

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
    <title><?php echo $titlu; ?> - Parteneri & Sponsori</title>
</head>
<body>

<h1><?php echo $titlu; ?></h1>
<p><?php echo $descriere; ?></p>
<p>Data: <?php echo $data; ?></p>
<p>Locația: <?php echo $locatia; ?></p>

<h2>Parteneri:</h2>
<ul>
    <?php
    while ($row_partener = $result_parteneri->fetch_assoc()) {
        echo "<li>{$row_partener['Nume']}</li>";
    }
    ?>
</ul>

<h2>Sponsori:</h2>
<ul>
    <?php
    while ($row_sponsori = $result_sponsori->fetch_assoc()) {
        echo "<li>{$row_sponsori['Nume']}</li>";
    }
    ?>
</ul>

</body>
</html>
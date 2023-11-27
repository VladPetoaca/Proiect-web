<?php
include('config.php');

//$mysqli = mysqli_connect($hostname, $username, $password, $db);

if ($mysqli->connect_error) {
    die("Conexiunea la baza de date a eșuat: " . $mysqli->connect_error);
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $eveniment_id = $_GET['id'];

    // Restul codului rămâne neschimbat
} else {
    echo "ID eveniment lipsă.";
    exit;
}

// Selectează detaliile evenimentului
$sql = "SELECT * FROM evenimente WHERE ID = $eveniment_id";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $titlu = $row['Titlu'];
    $descriere = $row['Descriere'];
    $data = $row['Data'];
    $locatia = $row['Locatia'];
} else {
    echo "Evenimentul nu a fost găsit.";
    exit;
}

// Selectează partenerii pentru evenimentul specific
$sql_parteneri = "SELECT p.Nume FROM parteneri p
    JOIN eveniment_parteneri ep ON p.ID = ep.Parteneri_ID
    WHERE ep.Eveniment_ID = $eveniment_id";
$result_parteneri = $mysqli->query($sql_parteneri);

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titlu; ?></title>
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

</body>
</html>
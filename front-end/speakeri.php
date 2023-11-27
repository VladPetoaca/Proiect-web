<?php
include('config.php');

//$mysqli = mysqli_connect($hostname, $username, $password, $db);

if ($mysqli->connect_error) {
    die("Conexiunea la baza de date a eșuat: " . $mysli->connect_error);
}

$eveniment_id = $_GET['id'];

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

// Selectează speakerii pentru evenimentul specific
$sql_speakeri = "SELECT s.Nume FROM speakeri s
    JOIN eveniment_speakeri es ON s.ID = es.Speakeri_ID
    WHERE es.Eveniment_ID = $eveniment_id";
$result_speakeri = $mysqli->query($sql_speakeri);

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titlu; ?> - Speakeri</title>
</head>
<body>

<h1><?php echo $titlu; ?></h1>
<p><?php echo $descriere; ?></p>
<p>Data: <?php echo $data; ?></p>
<p>Locația: <?php echo $locatia; ?></p>

<h2>Speakeri:</h2>
<ul>
    <?php
    while ($row_speaker = $result_speakeri->fetch_assoc()) {
        echo "<li>{$row_speaker['Nume']}</li>";
    }
    ?>
</ul>

</body>
</html>
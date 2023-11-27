<?php
include('config.php');

// Check if 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $eveniment_id = $_GET['id'];

    // Selectează agenda pentru evenimentul specific
    $sql_agenda = "SELECT id, titlu, descriere, data, locatia FROM evenimente WHERE ID = $eveniment_id";
    $result_agenda = $mysqli->query($sql_agenda);

    // Selectează detaliile evenimentului
    $sql_eveniment = "SELECT Titlu, Descriere, Data, Locatia FROM evenimente WHERE ID = $eveniment_id";
    $result_eveniment = $mysqli->query($sql_eveniment);

    // Selectează vorbitorii pentru evenimentul specific din tabela de relație
    $sql_speakers = "
        SELECT speakeri.id, speakeri.nume
        FROM speakeri
        JOIN eveniment_speakeri ON speakeri.id = eveniment_speakeri.speakeri_ID
        WHERE eveniment_speakeri.eveniment_id = $eveniment_id
        ORDER BY eveniment_speakeri.ID";
    $result_speakers = $mysqli->query($sql_speakers);

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
} else {
    echo "Parametrul 'id' lipsește din URL.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titlu; ?> - Agenda</title>
</head>
<body>

<h1><?php echo $titlu; ?></h1>
<p><i><?php echo $descriere; ?></i></p>
<p>Data: <?php echo $data; ?></p>
<p>Locația: <?php echo $locatia; ?></p>

<h2>Agenda:</h2>
<ul>
    <h3>Vorbitori:</h3>
    <ul>
        <?php
        while ($row_speaker = $result_speakers->fetch_assoc()) {
            echo "<li>{$row_speaker['nume']}</li>";
        }
        ?>
    </ul>
</ul>

</body>
</html>

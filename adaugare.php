<?php
include("config.php");
$error = '';

if (isset($_POST['submit'])) {
    // preluam datele de pe formular
    $titlu = htmlentities($_POST['titlu'], ENT_QUOTES);
    $descriere_eveniment = htmlentities($_POST['descriere_eveniment'], ENT_QUOTES);
    $data = htmlentities($_POST['data'], ENT_QUOTES);
    $locatia = htmlentities($_POST['locatia'], ENT_QUOTES);
    $speaker_id = intval($_POST['speaker_id']);
    $parteneri_id = intval($_POST['parteneri_id']);
    $sponsori_id = intval($_POST['sponsori_id']);

    // verificam daca sunt completate
    if (empty($titlu) || empty($descriere_eveniment) || empty($data) || empty($locatia) || empty($speaker_id) || empty($parteneri_id) || empty($sponsori_id)) {
        // daca sunt goale se afiseaza un mesaj
        $error = 'ERROR: Campuri goale!';
    } else {
        // insert
        $sql = "INSERT INTO evenimente (Titlu, Descriere, Data, Locatia) VALUES (?, ?, ?, ?)";
        $mysqli = "proiect_pagina_web";
        if ($stmt = $mysqli -> repare($sql)) {
            $stmt->bind_param("ssss", $titlu, $descriere_eveniment, $data, $locatia);

            if ($stmt->execute()) {
                // Get the last inserted ID
                $eveniment_id = $mysqli->insert_id;

                // Insert into eveniment_speakeri table
                $sql_speaker = "INSERT INTO eveniment_speakeri (Eveniment_ID, Speakeri_ID) VALUES (?, ?)";
                $stmt_speaker = $mysqli->prepare($sql_speaker);
                $stmt_speaker->bind_param("ii", $eveniment_id, $speaker_id);
                $stmt_speaker->execute();
                $stmt_speaker->close();

                // Insert into eveniment_parteneri table
                $sql_parteneri = "INSERT INTO eveniment_parteneri (Eveniment_ID, Parteneri_ID) VALUES (?, ?)";
                $stmt_parteneri = $mysqli->prepare($sql_parteneri);
                $stmt_parteneri->bind_param("ii", $eveniment_id, $parteneri_id);
                $stmt_parteneri->execute();
                $stmt_parteneri->close();

                // Insert into eveniment_sponsori table
                $sql_sponsori = "INSERT INTO eveniment_sponsori (Eveniment_ID, Sponsori_ID) VALUES (?, ?)";
                $stmt_sponsori = $mysqli->prepare($sql_sponsori);
                $stmt_sponsori->bind_param("ii", $eveniment_id, $sponsori_id);
                $stmt_sponsori->execute();
                $stmt_sponsori->close();

                echo "Inserare eveniment reusita!";
            } else {
                echo "ERROR: Nu se poate executa insert.";
            }

            $stmt->close();
        } else {
            echo "ERROR: " . $mysqli->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserare Eveniment</title>
</head>

<body>
<h1>Inserare Eveniment</h1>

<?php
if ($error != '') {
    echo "<div style='padding:4px; border:1px solid red; color:red'>" . $error . "</div>";
}
?>

<form action="" method="post">
    <div>
        <strong>Titlu: </strong> <input type="text" name="titlu" value="" required /><br />
        <strong>Descriere: </strong> <textarea name="descriere_eveniment" rows="4" cols="50" required></textarea><br />
        <strong>Data: </strong> <input type="date" name="data" required /><br />
        <strong>Locatia: </strong> <input type="text" name="locatia" value="" required /><br />
        <strong>ID Speaker: </strong> <input type="text" name="speaker_id" value="" required /><br />
        <strong>ID Parteneri: </strong> <input type="text" name="parteneri_id" value="" required /><br />
        <strong>ID Sponsori: </strong> <input type="text" name="sponsori_id" value="" required /><br />
        <br />
        <input type="submit" name="submit" value="Submit" />
        <a href="Vizualizare.php">Index</a>
    </div>
</form>
</body>

</html>

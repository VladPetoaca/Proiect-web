<?php
session_start();

include('config.php');
$error = '';
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Inserare Eveniment</title>
        <link rel="stylesheet" href="crud.css">
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.getElementById('addSpeakerBtn').addEventListener('click', function (event) {
                    event.preventDefault();
                    addSpeaker();
                });

                document.getElementById('addPartenerBtn').addEventListener('click', function (event) {
                    event.preventDefault();
                    addPartener();
                });

                document.getElementById('addSponsorBtn').addEventListener('click', function (event) {
                    event.preventDefault();
                    addSponsor();
                });
            });

            function addSpeaker() {
                var newSpeakerInput = document.createElement('input');
                newSpeakerInput.type = 'text';
                newSpeakerInput.name = 'speakeri[]';

                var speakerSection = document.getElementById('container-speakeri');
                speakerSection.appendChild(document.createElement('br'));
                speakerSection.appendChild(newSpeakerInput);
            }

            function addPartener() {
                var newPartenerInput = document.createElement('input');
                newPartenerInput.type = 'text';
                newPartenerInput.name = 'parteneri[]';

                var partenerSection = document.getElementById('container-parteneri');
                partenerSection.appendChild(document.createElement('br'));
                partenerSection.appendChild(newPartenerInput);
            }

            function addSponsor() {
                var newSponsorInput = document.createElement('input');
                newSponsorInput.type = 'text';
                newSponsorInput.name = 'sponsori[]';

                var sponsorSection = document.getElementById('container-sponsori');
                sponsorSection.appendChild(document.createElement('br'));
                sponsorSection.appendChild(newSponsorInput);
            }
        </script>

    </head>
    <body>
    <h1>Inserare Eveniment</h1>
    <form action="add.php" method="post">
        <div>
            <label for="titlu"><strong>Titlu: </strong></label> <input type="text" name="titlu" value=""/>
            <br>
            <br>
            <label for="descriere"><strong>Descriere: </strong></label> <textarea name="descriere_eveniment" rows="4" cols="50"></textarea>
            <br>
            <br>
            <label for="data"><strong>Data: </strong></label> <input type="date" name="data"/>
            <br>
            <br>
            <label for="ora"><strong>Ora: </strong></label> <input type="time" name="ora"/>
            <br>
            <br>
            <label for="locatia"><strong>Locația: </strong></label> <input type="text" name="locatia" value=""/>
            <br>
            <br>
            <div id="container-speakeri">
                <label for="speakeri"><strong>Speakeri: </strong></label><input type="text" name="speakeri[]" id="speakeri" />
                <button id="addSpeakerBtn" onclick="addSpeaker()">Adaugă speaker</button>
            </div>
            <br>
            <br>

            <div id="container-parteneri">
                <label for="parteneri"><strong>Parteneri: </strong></label> <input type="text" name="parteneri[]" id="parteneri" value=""/>
                <button id="addPartenerBtn" onclick="addPartener()">Adaugă partener</button>
            </div>
            <br>
            <br>

            <div id="container-sponsori">
                <label for="sponsori"><strong>Sponsori: </strong></label> <input type="text" name="sponsori[]" id="sponsori" value=""/>
                <button id="addSponsorBtn" onclick="addSponsor()">Adaugă sponsor</button>
            </div>
            <br>
            <br>
            <br>
            <input type="submit" name="submit" value="Submit" />
            <a href="view.php">Catalog</a>
        </div>
    </form>
    </body>
</html>

<?php

if (isset($_POST['submit'])) {
    // Preluam datele de pe formular
    $titlu = htmlentities($_POST['titlu'], ENT_QUOTES);
    $descriere_eveniment = htmlentities($_POST['descriere_eveniment'], ENT_QUOTES);
    $data = htmlentities($_POST['data'], ENT_QUOTES);
    $ora = htmlentities($_POST['ora'], ENT_QUOTES);
    $locatia = htmlentities($_POST['locatia'], ENT_QUOTES);
    $speaker_id = htmlentities($_POST['speaker_id']);
    $parteneri_id = htmlentities($_POST['parteneri_id']);
    $sponsori_id = htmlentities($_POST['sponsori_id']);

    // Verificam daca sunt completate
    if (empty($titlu) || empty($descriere_eveniment) || empty($data) || empty($ora) || empty($locatia) || empty($speaker_id) || empty($parteneri_id) || empty($sponsori_id)) {
        // Daca sunt goale se afiseaza un mesaj
        $error = 'ERROR: Campuri goale!';
    } else {
        // Insert eveniment
        $sql = "INSERT INTO evenimente (Titlu, Descriere, Data, Ora, Locatia) VALUES (?, ?, ?, ?, ?)";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("sssss", $titlu, $descriere_eveniment, $data, $ora, $locatia);
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
                echo "Evenimentul a fost adăugat cu succes!";
            } else {
                echo "EROARE: Nu se poate executa insert.";
            }

            $stmt->close();
        } else {
            echo "EROARE: " . $mysqli->error;
        }
    }
}

// Close the database connection
$mysqli->close();

// Display error message if any
if ($error != '') {
    echo "<div style='padding:4px; border:1px solid red; color:red'>" . $error . "</div>";
}
?>
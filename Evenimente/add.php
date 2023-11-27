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

                document.getElementById('removeSpeakerBtn').addEventListener('click', function (event) {
                    event.preventDefault();
                    removeSpeaker();
                });

                document.getElementById('removePartenerBtn').addEventListener('click', function (event) {
                    event.preventDefault();
                    removePartener();
                });

                document.getElementById('removeSponsorBtn').addEventListener('click', function (event) {
                    event.preventDefault();
                    removeSponsor();
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

            function removeSpeaker() {
                var speakerSection = document.getElementById('container-speakeri');
                var lastSpeakerInput = speakerSection.lastElementChild;

                if (lastSpeakerInput && lastSpeakerInput.nodeName === 'INPUT') {
                    speakerSection.removeChild(lastSpeakerInput.previousElementSibling);
                    speakerSection.removeChild(lastSpeakerInput);
                }
            }

            function removePartener() {
                var partenerSection = document.getElementById('container-parteneri');
                var lastPartenerInput = partenerSection.lastElementChild;

                if (lastPartenerInput && lastPartenerInput.nodeName === 'INPUT') {
                    partenerSection.removeChild(lastPartenerInput.previousElementSibling);
                    partenerSection.removeChild(lastPartenerInput);
                }
            }

            function removeSponsor() {
                var sponsorSection = document.getElementById('container-sponsori');
                var lastSponsorInput = sponsorSection.lastElementChild;

                if (lastSponsorInput && lastSponsorInput.nodeName === 'INPUT') {
                    sponsorSection.removeChild(lastSponsorInput.previousElementSibling);
                    sponsorSection.removeChild(lastSponsorInput);
                }
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
            <label for="pret"><strong>Preț: </strong></label> <input type="text" name="pret" value=""/>
            <br>
            <br>
            <label for="contact"><strong>Contact: </strong></label> <input type="text" name="contact" value=""/>
            <br>
            <br>
            <div id="container-speakeri">
                <label for="speakeri"><strong>Speakeri: </strong></label><input type="text" name="speakeri[]" id="speakeri" />
                <button id="addSpeakerBtn">Adaugă speaker</button>
                <button id="removeSpeakerBtn">Șterge speaker</button>
            </div>
            <br>
            <br>

            <div id="container-parteneri">
                <label for="parteneri"><strong>Parteneri: </strong></label> <input type="text" name="parteneri[]" id="parteneri" value=""/>
                <button id="addPartenerBtn">Adaugă partener</button>
                <button id="removePartenerBtn">Șterge partener</button>
            </div>
            <br>
            <br>

            <div id="container-sponsori">
                <label for="sponsori"><strong>Sponsori: </strong></label> <input type="text" name="sponsori[]" id="sponsori" value=""/>
                <button id="addSponsorBtn">Adaugă sponsor</button>
                <button id="removeSponsorBtn">Șterge sponsor</button>
            </div>
            <br>
            <br>
            <br>
            <input type="submit" name="submit" value="Adaugă eveniment" />
            <a href="view.php">Catalog</a>
        </div>
    </form>
    </body>
</html>


<?php
function getOrCreateID($tableName, $columnName, $value)
{
    global $mysqli;

    $sql = "SELECT ID FROM $tableName WHERE $columnName = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $value);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // The record already exists, get the ID
        $stmt->bind_result($id);
        $stmt->fetch();
        $stmt->close();
        return $id;
    } else {
        // The record doesn't exist, insert and get the new ID
        $stmt->close();

        $insertSql = "INSERT INTO $tableName ($columnName) VALUES (?)";
        $insertStmt = $mysqli->prepare($insertSql);
        $insertStmt->bind_param("s", $value);
        $insertStmt->execute();
        $insertStmt->close();

        // Return the newly inserted ID
        return $mysqli->insert_id;
    }
}

function insertIntoEventSpeakerTable($eveniment_id, $speaker_id)
{
    global $mysqli;

    $sql = "INSERT INTO eveniment_speakeri (Eveniment_ID, Speakeri_ID) VALUES (?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ii", $eveniment_id, $speaker_id);
    $stmt->execute();
    $stmt->close();
}

function insertIntoEventPartenerTable($eveniment_id, $partener_id)
{
    global $mysqli;

    $sql = "INSERT INTO eveniment_parteneri (Eveniment_ID, Parteneri_ID) VALUES (?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ii", $eveniment_id, $partener_id);
    $stmt->execute();
    $stmt->close();
}

function insertIntoEventSponsorTable($eveniment_id, $sponsor_id)
{
    global $mysqli;

    $sql = "INSERT INTO eveniment_sponsori (Eveniment_ID, Sponsori_ID) VALUES (?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ii", $eveniment_id, $sponsor_id);
    $stmt->execute();
    $stmt->close();
}


if (isset($_POST['submit'])) {
    // Preluam datele de pe formular
    $titlu = htmlentities($_POST['titlu'], ENT_QUOTES);
    $descriere_eveniment = htmlentities($_POST['descriere_eveniment'], ENT_QUOTES);
    $data = htmlentities($_POST['data'], ENT_QUOTES);
    $ora = htmlentities($_POST['ora'], ENT_QUOTES);
    $locatia = htmlentities($_POST['locatia'], ENT_QUOTES);
    $pret = strval($_POST['pret']);
    $contact = htmlentities($_POST['contact'], ENT_QUOTES);
    $speakeri = $_POST['speakeri'];
    $parteneri = $_POST['parteneri'];
    $sponsori = $_POST['sponsori'];

    // Verificam daca sunt completate
    if (empty($titlu) || empty($descriere_eveniment) || empty($data) || empty($ora) || empty($locatia) || empty($speakeri) || empty($parteneri) || empty($sponsori) || empty($pret) || empty($contact)) {
        // Daca sunt goale se afiseaza un mesaj
        $error = 'EROARE: Campuri goale!';
    } else {
        // Insert eveniment
        $sql = "INSERT INTO evenimente (Titlu, Descriere, Data, Ora, Locatia, Pret, Contact) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);

        // Check for errors in prepare
        if (!$stmt) {
            die("EROARE la pregatirea insert: " . $mysqli->error);
        }

        $stmt->bind_param("sssssss", $titlu, $descriere_eveniment, $data, $ora, $locatia, $pret, $contact);
        // Check for errors in bind_param
        if (!$stmt) {
            die("EROARE la bind_param: " . $mysqli->error);
        }

        if ($stmt->execute()) {
            // Get the last inserted ID
            $eveniment_id = $mysqli->insert_id;

            // Insert into eveniment_speakeri table
            foreach ($speakeri as $speaker) {
                $speaker_id = getOrCreateID('speakeri', 'Nume', $speaker);
                insertIntoEventSpeakerTable($eveniment_id, $speaker_id);
            }

            // Insert into eveniment_parteneri table
            foreach ($parteneri as $partener) {
                $partener_id = getOrCreateID('parteneri', 'Nume', $partener);
                insertIntoEventPartenerTable($eveniment_id, $partener_id);
            }

            // Insert into eveniment_sponsori table
            foreach ($sponsori as $sponsor) {
                $sponsor_id = getOrCreateID('sponsori', 'Nume', $sponsor);
                insertIntoEventSponsorTable($eveniment_id, $sponsor_id);
            }

            echo "Evenimentul a fost adăugat cu succes!";
        } else {
            echo "EROARE la executarea insert: " . $stmt->error;
        }

        $stmt->close();
    }
}
if ($error != '') {
    echo "<div style='padding:4px; border:1px solid red; color:red'>" . $error . "</div>";
}

$mysqli->close();


?>


<?php
include("config.php");
$error = '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editare Eveniment</title>
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
<h1>Editare Eveniment</h1>

<?php
if ($error != '') {
    echo "<div style='padding:4px; border:1px solid red; color:red'>" . $error . "</div>";
}
?>

<form action="edit.php" method="post">
    <div>
        <?php if ($_GET['id'] != '') { ?>
        <input type="hidden" name="id" value="<?php echo $_GET['id'];?>" />

        <p>ID:
            <?php echo $_GET['id'];
            if ($result = $mysqli->query("SELECT
    e.ID,
    e.Titlu,
    e.Descriere,
    e.Data,
    e.Ora,
    e.Locatia,
    e.Pret,
    e.Contact,
    GROUP_CONCAT(DISTINCT s.Nume SEPARATOR ', ') AS speaker_names,
    GROUP_CONCAT(DISTINCT p.Nume SEPARATOR ', ') AS partener_names,
    GROUP_CONCAT(DISTINCT sp.Nume SEPARATOR ', ') AS sponsor_names
    FROM evenimente e
    LEFT JOIN eveniment_speakeri es ON e.ID = es.Eveniment_ID
    LEFT JOIN speakeri s ON es.Speakeri_ID = s.ID
    LEFT JOIN eveniment_parteneri ep ON e.ID = ep.Eveniment_ID
    LEFT JOIN parteneri p ON ep.Parteneri_ID = p.ID
    LEFT JOIN eveniment_sponsori esp ON e.ID = esp.Eveniment_ID
    LEFT JOIN sponsori sp ON esp.Sponsori_ID = sp.ID
    WHERE e.ID= '" . $_GET['id'] . "'"))
            {
            if ($result->num_rows > 0)
            { $row = $result->fetch_object();?>
        </p>

        <label for="titlu"><strong>Titlu: </strong></label> <input type="text" name="titlu" value="<?php echo$row->Titlu;?>"/>
        <br>
        <br>
        <label for="descriere"><strong>Descriere: </strong></label> <textarea name="descriere_eveniment" rows="4" cols="50"> <?php echo html_entity_decode($row->Descriere, ENT_QUOTES, 'UTF-8'); ?></textarea>
        <br>
        <br>
        <label for="data"><strong>Data: </strong></label> <input type="date" name="data" value="<?php echo$row->Data;?>"/>
        <br>
        <br>
        <label for="ora"><strong>Ora: </strong></label> <input type="time" name="ora" value="<?php echo$row->Ora;?>"/>
        <br>
        <br>
        <label for="locatia"><strong>Locația: </strong></label> <input type="text" name="locatia" value="<?php echo$row->Locatia;?>"/>
        <br>
        <br>
        <label for="pret"><strong>Preț: </strong></label> <input type="text" name="pret" value="<?php echo$row->Pret;?>"/>
        <br>
        <br>
        <label for="contact"><strong>Contact: </strong></label> <input type="text" name="contact" value="<?php echo$row->Contact;}}}?>"/>
        <br>
        <br>
        <div id="container-speakeri">
            <label for="speakeri"><strong>Speakeri: </strong></label>
            <?php
            $speakeri_ids = array();
            $sql = "SELECT Speakeri_ID FROM eveniment_speakeri WHERE Eveniment_ID = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("i", $event_id);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $speakeri_ids[] = $row['Speakeri_ID'];
            }

            $stmt->close();

            foreach($speakeri_ids as $id) {
                $sql = "SELECT Nume FROM speakeri WHERE ID = ?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $speaker = $result->fetch_object();
                $stmt->close();
                ?>
                <input type="text" name="speakeri[]" value="<?php echo $speaker->Nume;?>" />
            <?php } ?>

            <?php
            $sql = "SELECT COUNT(*) as count FROM eveniment_speakeri WHERE Eveniment_ID = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("i", $event_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $count = $result->fetch_object()->count;
            $stmt->close();

            for ($i = 0; $i < (3 - $count); $i++) {
                ?>
                <br><br>
                <input type="text" name="speakeri[]" value="" />
            <?php } ?>
            <br><br>
            <button id="addSpeakerBtn">Adaugă speaker</button>
            <button id="removeSpeakerBtn">Șterge speaker</button>
        </div>
        <br><br>

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


<?php if (!empty($_POST['id'])) {
    if (isset($_POST['submit'])) {
        // preluam datele de pe formular
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

        // verificam daca sunt completate
        if (empty($titlu) || empty($descriere_eveniment) || empty($data) || empty($ora) || empty($locatia) || empty($speakeri) || empty($parteneri) || empty($sponsori) || empty($pret) || empty($contact)) {
            // Daca sunt goale se afiseaza un mesaj
            $error = 'EROARE: Campuri goale!';
        } else {
            // Begin a transaction for atomicity
            $mysqli->begin_transaction();

            try {
                // Update evenimente table
                $stmt_update_evenimente = $mysqli->prepare("
                    UPDATE evenimente
                    SET
                        Titlu = ?,
                        Descriere = ?,
                        Data = ?,
                        Ora = ?,
                        Locatia = ?,
                        Pret = ?,
                        Contact = ?
                    WHERE
                        ID = ?
                ");

                if ($stmt_update_evenimente) {
                    // Bind parameters
                    $stmt_update_evenimente->bind_param("ssssi", $titlu, $descriere_eveniment, $data, $locatia, $id);

                    // Execute the update statement
                    if ($stmt_update_evenimente->execute()) {
                        // Update related tables
                        // Assuming that the speaker, sponsor, and partner IDs are stored in the same field (e.g., comma-separated)
                        $speaker_ids = explode(',', $speakeri);
                        $parteneri_ids = explode(',', $parteneri);
                        $sponsori_ids = explode(',', $sponsori);

                        // Delete existing records in related tables
                        $stmt_delete_speakeri = $mysqli->prepare("DELETE FROM eveniment_speakeri WHERE Eveniment_ID = ?");
                        $stmt_delete_parteneri = $mysqli->prepare("DELETE FROM eveniment_parteneri WHERE Eveniment_ID = ?");
                        $stmt_delete_sponsori = $mysqli->prepare("DELETE FROM eveniment_sponsori WHERE Eveniment_ID = ?");

                        foreach ([$stmt_delete_speakeri, $stmt_delete_parteneri, $stmt_delete_sponsori] as $stmt) {
                            if ($stmt) {
                                $stmt->bind_param("i", $id);
                                $stmt->execute();
                                $stmt->close();
                            } else {
                                throw new Exception("Error in prepared statement: " . $mysqli->error);
                            }
                        }

                        // Insert new records in related tables
                        $stmt_insert_speakeri = $mysqli->prepare("INSERT INTO eveniment_speakeri (Eveniment_ID, Speakeri_ID) VALUES (?, ?)");
                        $stmt_insert_parteneri = $mysqli->prepare("INSERT INTO eveniment_parteneri (Eveniment_ID, Parteneri_ID) VALUES (?, ?)");
                        $stmt_insert_sponsori = $mysqli->prepare("INSERT INTO eveniment_sponsori (Eveniment_ID, Sponsori_ID) VALUES (?, ?)");

                        foreach ([$stmt_insert_speakeri, $stmt_insert_parteneri, $stmt_insert_sponsori] as $stmt) {
                            if ($stmt) {
                                $stmt->bind_param("ii", $id, $value);

                                switch ($stmt) {
                                    case $stmt_insert_speakeri:
                                        $values = $speaker_ids;
                                        break;
                                    case $stmt_insert_parteneri:
                                        $values = $parteneri_ids;
                                        break;
                                    case $stmt_insert_sponsori:
                                        $values = $sponsori_ids;
                                        break;
                                    default:
                                        break;
                                }

                                foreach ($values as $value) {
                                    $stmt->execute();
                                }

                                $stmt->close();
                            } else {
                                throw new Exception("Error in prepared statement: " . $mysqli->error);
                            }
                        }
                        echo "Actualizare efectuată cu succes!";
                    } else {
                        echo "Eroare la actualizarea evenimentului: " . $stmt_update_evenimente->error;
                    }

                    // Close the statement
                    $stmt_update_evenimente->close();
                } else {
                    throw new Exception("Eroare în prepared statement: " . $mysqli->error);
                }

                // Commit the transaction
                $mysqli->commit();
            } catch (Exception $e) {
                // Rollback the transaction on error
                $mysqli->rollback();
                echo "Error: " . $e->getMessage();
            }
        }
    } else {
        echo "EROARE: Nu se poate executa actualizarea!";
    }
}
?>
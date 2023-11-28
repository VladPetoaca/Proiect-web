<?php
include("config.php");
$error = '';

// Helper function to get the table name based on the statement type
function getTableName($stmt)
{
    $tableNames = [
        'eveniment_speakeri' => "eveniment_speakeri",
        'eveniment_parteneri' => "eveniment_parteneri",
        'eveniment_sponsori' => "eveniment_sponsori",
    ];

    $stmtName = str_replace('_', '', $stmt->result_metadata()->fetch_field()->name);
    return $tableNames[$stmtName];
}

// Helper function to get the column name based on the statement type
function getColumn($stmt)
{
    $columns = [
        'eveniment_speakeri' => "Speakeri_ID",
        'eveniment_parteneri' => "Parteneri_ID",
        'eveniment_sponsori' => "Sponsori_ID",
    ];

    $stmtName = str_replace('_', '', $stmt->result_metadata()->fetch_field()->name);
    return $columns[$stmtName];
}

if (!empty($_POST['id'])) {
    if (isset($_POST['submit'])) {
        if (is_numeric($_POST['id'])) {
            // preluam datele de pe formular
            $id = $_POST['id'];
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
                // Update evenimente table
                $stmt_update_evenimente = $mysqli->prepare("UPDATE evenimente
                    SET Titlu = ?,
                        Descriere = ?,
                        Data = ?,
                        Ora = ?,
                        Locatia = ?,
                        Pret = ?,
                        Contact = ?
                    WHERE ID = ?");

                if ($stmt_update_evenimente) {
                    // Bind parameters
                    $stmt_update_evenimente->bind_param("sssssssi", $titlu, $descriere_eveniment, $data, $ora, $locatia, $pret, $contact, $id);

                    // Execute the update statement
                    if ($stmt_update_evenimente->execute()) {
                        echo "Evenimentul actualizat cu succes!";

                        // Insert or update speakeri
                        $speakeri_ids = array();
                        foreach ($speakeri as $speaker_name) {
                            // Check if the speaker already exists in the database
                            $stmt_check_speaker = $mysqli->prepare("SELECT ID FROM speakeri WHERE Nume = ?");
                            $stmt_check_speaker->bind_param("s", $speaker_name);
                            $stmt_check_speaker->execute();
                            $result_check_speaker = $stmt_check_speaker->get_result();

                            if ($result_check_speaker->num_rows > 0) {
                                // If the speaker exists, retrieve its ID
                                $row = $result_check_speaker->fetch_assoc();
                                $speaker_id = $row['ID'];
                            } else {
                                // If the speaker doesn't exist, insert a new record
                                $stmt_insert_speaker = $mysqli->prepare("INSERT INTO speakeri (Nume) VALUES (?)");
                                $stmt_insert_speaker->bind_param("s", $speaker_name);
                                $stmt_insert_speaker->execute();
                                $speaker_id = $mysqli->insert_id;
                                $stmt_insert_speaker->close();
                            }

                            // Add the speaker ID to the array
                            $speakeri_ids[] = $speaker_id;

                            $stmt_check_speaker->close();
                        }

                        // Insert or update partners
                        $parteneri_ids = array();
                        foreach ($parteneri as $partener_name) {
                            // Check if the partner already exists in the database
                            $stmt_check_partener = $mysqli->prepare("SELECT ID FROM parteneri WHERE Nume = ?");
                            $stmt_check_partener->bind_param("s", $partener_name);
                            $stmt_check_partener->execute();
                            $result_check_partener = $stmt_check_partener->get_result();

                            if ($result_check_partener->num_rows > 0) {
                                // If the partner exists, retrieve its ID
                                $row = $result_check_partener->fetch_assoc();
                                $partener_id = $row['ID'];
                            } else {
                                // If the partner doesn't exist, insert a new record
                                $stmt_insert_partener = $mysqli->prepare("INSERT INTO parteneri (Nume) VALUES (?)");
                                $stmt_insert_partener->bind_param("s", $partener_name);
                                $stmt_insert_partener->execute();
                                $partener_id = $mysqli->insert_id;
                                $stmt_insert_partener->close();
                            }

                            // Add the partner ID to the array
                            $parteneri_ids[] = $partener_id;

                            $stmt_check_partener->close();
                        }

                        // Insert or update sponsors
                        $sponsori_ids = array();
                        foreach ($sponsori as $sponsor_name) {
                            // Check if the sponsor already exists in the database
                            $stmt_check_sponsor = $mysqli->prepare("SELECT ID FROM sponsori WHERE Nume = ?");
                            $stmt_check_sponsor->bind_param("s", $sponsor_name);
                            $stmt_check_sponsor->execute();
                            $result_check_sponsor = $stmt_check_sponsor->get_result();

                            if ($result_check_sponsor->num_rows > 0) {
                                // If the sponsor exists, retrieve its ID
                                $row = $result_check_sponsor->fetch_assoc();
                                $sponsor_id = $row['ID'];
                            } else {
                                // If the sponsor doesn't exist, insert a new record
                                $stmt_insert_sponsor = $mysqli->prepare("INSERT INTO sponsori (Nume) VALUES (?)");
                                $stmt_insert_sponsor->bind_param("s", $sponsor_name);
                                $stmt_insert_sponsor->execute();
                                $sponsor_id = $mysqli->insert_id;
                                $stmt_insert_sponsor->close();
                            }

                            // Add the sponsor ID to the array
                            $sponsori_ids[] = $sponsor_id;

                            $stmt_check_sponsor->close();
                        }

                        // Update speakeri, parteneri, and sponsori associations
                        $stmt_update_speakeri = $mysqli->prepare("DELETE FROM eveniment_speakeri WHERE Eveniment_ID = ?");
                        $stmt_update_speakeri->bind_param("i", $id);
                        $stmt_update_speakeri->execute();
                        $stmt_update_speakeri->close();

                        $stmt_update_parteneri = $mysqli->prepare("DELETE FROM eveniment_parteneri WHERE Eveniment_ID = ?");
                        $stmt_update_parteneri->bind_param("i", $id);
                        $stmt_update_parteneri->execute();
                        $stmt_update_parteneri->close();

                        $stmt_update_sponsori = $mysqli->prepare("DELETE FROM eveniment_sponsori WHERE Eveniment_ID = ?");
                        $stmt_update_sponsori->bind_param("i", $id);
                        $stmt_update_sponsori->execute();
                        $stmt_update_sponsori->close();

                        foreach ($speakeri_ids as $speaker_id) {
                            $stmt_insert_speakeri = $mysqli->prepare("INSERT INTO eveniment_speakeri (Eveniment_ID, Speakeri_ID) VALUES (?, ?)");
                            $stmt_insert_speakeri->bind_param("ii", $id, $speaker_id);
                            $stmt_insert_speakeri->execute();
                            $stmt_insert_speakeri->close();
                        }

                        foreach ($parteneri_ids as $partener_id) {
                            $stmt_insert_parteneri = $mysqli->prepare("INSERT INTO eveniment_parteneri (Eveniment_ID, Parteneri_ID) VALUES (?, ?)");
                            $stmt_insert_parteneri->bind_param("ii", $id, $partener_id);
                            $stmt_insert_parteneri->execute();
                            $stmt_insert_parteneri->close();
                        }

                        foreach ($sponsori_ids as $sponsor_id) {
                            $stmt_insert_sponsori = $mysqli->prepare("INSERT INTO eveniment_sponsori (Eveniment_ID, Sponsori_ID) VALUES (?, ?)");
                            $stmt_insert_sponsori->bind_param("ii", $id, $sponsor_id);
                            $stmt_insert_sponsori->execute();
                            $stmt_insert_sponsori->close();
                        }

                        echo "Detalii actualizate cu succes!";
                    } else {
                        $error = 'EROARE: Actualizare esuata!';
                    }
                } else {
                    $error = 'EROARE: ' . $mysqli->error;
                }

                // Close the statement
                $stmt_update_evenimente->close();
            }
        } else {
            $error = 'EROARE: ID invalid!';
        }
    } else {
        $error = 'EROARE: Cerere invalida!';
    }
}

// Fetch event details
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the select statement
    $stmt = $mysqli->prepare("SELECT * FROM evenimente WHERE ID = ?");

    // Bind parameters
    $stmt->bind_param("i", $id);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Fetch data
    $event = $result->fetch_assoc();

    // Close the statement
    $stmt->close();
} else {
    $error = 'EROARE: ID invalid!';
}

// Fetch existing speakeri IDs
$speakeri_ids = array();
$sql = "SELECT Speakeri_ID FROM eveniment_speakeri WHERE Eveniment_ID = ?";
$stmt_speakeri = $mysqli->prepare($sql);
$stmt_speakeri->bind_param("i", $id);
$stmt_speakeri->execute();
$result_speakeri = $stmt_speakeri->get_result();

while ($row_speakeri = $result_speakeri->fetch_assoc()) {
    $speakeri_ids[] = $row_speakeri['Speakeri_ID'];
}

$stmt_speakeri->close();

// Fetch existing parteneri IDs
$parteneri_ids = array();
$sql = "SELECT Parteneri_ID FROM eveniment_parteneri WHERE Eveniment_ID = ?";
$stmt_parteneri = $mysqli->prepare($sql);
$stmt_parteneri->bind_param("i", $id);
$stmt_parteneri->execute();
$result_parteneri = $stmt_parteneri->get_result();

while ($row_parteneri = $result_parteneri->fetch_assoc()) {
    $parteneri_ids[] = $row_parteneri['Parteneri_ID'];
}

$stmt_parteneri->close();

// Fetch existing sponsori IDs
$sponsori_ids = array();
$sql = "SELECT Sponsori_ID FROM eveniment_sponsori WHERE Eveniment_ID = ?";
$stmt_sponsori = $mysqli->prepare($sql);
$stmt_sponsori->bind_param("i", $id);
$stmt_sponsori->execute();
$result_sponsori = $stmt_sponsori->get_result();

while ($row_sponsori = $result_sponsori->fetch_assoc()) {
    $sponsori_ids[] = $row_sponsori['Sponsori_ID'];
}

$stmt_sponsori->close();

// Fetch existing speakeri, parteneri, and sponsori names for display
$speakeri_names = array();
foreach ($speakeri_ids as $speaker_id) {
    $sql_speaker = "SELECT Nume FROM speakeri WHERE ID = ?";
    $stmt_speaker = $mysqli->prepare($sql_speaker);
    $stmt_speaker->bind_param("i", $speaker_id);
    $stmt_speaker->execute();
    $result_speaker = $stmt_speaker->get_result();
    $speaker = $result_speaker->fetch_object();
    $speakeri_names[] = $speaker ? $speaker->Nume : '';
    $stmt_speaker->close();
}

$parteneri_names = array();
foreach ($parteneri_ids as $partener_id) {
    $sql_partener = "SELECT Nume FROM parteneri WHERE ID = ?";
    $stmt_partener = $mysqli->prepare($sql_partener);
    $stmt_partener->bind_param("i", $partener_id);
    $stmt_partener->execute();
    $result_partener = $stmt_partener->get_result();
    $partener = $result_partener->fetch_object();
    $parteneri_names[] = $partener ? $partener->Nume : '';
    $stmt_partener->close();
}

$sponsori_names = array();
foreach ($sponsori_ids as $sponsor_id) {
    $sql_sponsor = "SELECT Nume FROM sponsori WHERE ID = ?";
    $stmt_sponsor = $mysqli->prepare($sql_sponsor);
    $stmt_sponsor->bind_param("i", $sponsor_id);
    $stmt_sponsor->execute();
    $result_sponsor = $stmt_sponsor->get_result();
    $sponsor = $result_sponsor->fetch_object();
    $sponsori_names[] = $sponsor ? $sponsor->Nume : '';
    $stmt_sponsor->close();
}

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
        <?php
        if (!empty($_GET['id'])) { ?>
        <input type="hidden" name="id" value="<?php echo $_GET['id'];?>"/>

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
                GROUP_CONCAT(DISTINCT s.Nume) AS speaker_names,
                GROUP_CONCAT(DISTINCT p.Nume) AS partener_names,
                GROUP_CONCAT(DISTINCT sp.Nume) AS sponsor_names
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
        <label for="descriere_eveniment"><strong>Descriere: </strong></label> <textarea name="descriere_eveniment" rows="4" cols="50"> <?php echo html_entity_decode($row->Descriere, ENT_QUOTES, 'UTF-8'); ?></textarea>
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
            $event_id = isset($_GET['id']) ? $_GET['id'] : null;
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

            foreach ($speakeri_ids as $speaker_id) {
                $sql = "SELECT Nume FROM speakeri WHERE ID = ?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("i", $speaker_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $speaker = $result->fetch_object();
                $stmt->close();
                ?>
                <br>
                <input type="text" name="speakeri[]" value="<?php echo $speaker ? $speaker->Nume : ''; ?>" />
            <?php } ?>

            <?php
            $sql = "SELECT COUNT(*) as count FROM eveniment_speakeri WHERE Eveniment_ID = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("i", $event_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $count = $result->fetch_object()->count;
            $stmt->close();
            ?>
            <button id="addSpeakerBtn">Adaugă speaker</button>
            <button id="removeSpeakerBtn">Șterge speaker</button>
        </div>
        <br><br>


        <div id="container-parteneri">
            <label for="parteneri"><strong>Parteneri:</strong></label>
            <?php
            //$event_id = isset($_GET['id']) ? $_GET['id'] : null;
            $parteneri_ids = array();
            $sql = "SELECT Parteneri_ID FROM eveniment_parteneri WHERE Eveniment_ID = ?";
            $stmt1 = $mysqli->prepare($sql);
            $stmt1->bind_param("i", $event_id);
            $stmt1->execute();
            $result = $stmt1->get_result();

            while ($row = $result->fetch_assoc()) {
                $parteneri_ids[] = $row['Parteneri_ID'];
            }

            $stmt1->close();

            foreach ($parteneri_ids as $partener_id) {
                $sql = "SELECT Nume FROM parteneri WHERE ID = ?";
                $stmt1 = $mysqli->prepare($sql);
                $stmt1->bind_param("i", $partener_id);
                $stmt1->execute();
                $result = $stmt1->get_result();
                $partener = $result->fetch_object();
                $stmt1->close();
                ?>
                <br>
                <input type="text" name="parteneri[]" value="<?php echo $partener ? $partener->Nume : ''; ?>" />
            <?php } ?>

            <?php
            $sql = "SELECT COUNT(*) as count FROM eveniment_parteneri WHERE Eveniment_ID = ?";
            $stmt1 = $mysqli->prepare($sql);
            $stmt1->bind_param("i", $event_id);
            $stmt1->execute();
            $result = $stmt1->get_result();
            $count = $result->fetch_object()->count;
            $stmt1->close();
            ?>
            <button id="addPartenerBtn">Adaugă partener</button>
            <button id="removePartenerBtn">Șterge partener</button>
        </div>
        <br><br>


        <div id="container-sponsori">
            <label for="sponsori"><strong>Sponsori: </strong></label>
            <?php
            //$event_id = $_GET['id'];
            $sponsori_ids = array();
            $sql = "SELECT Sponsori_ID FROM eveniment_sponsori WHERE Eveniment_ID = ?";
            $stmt2 = $mysqli->prepare($sql);
            $stmt2->bind_param("i", $event_id);
            $stmt2->execute();
            $result = $stmt2->get_result();

            while ($row = $result->fetch_assoc()) {
                $sponsori_ids[] = $row['Sponsori_ID'];
            }

            $stmt2->close();

            foreach ($sponsori_ids as $sponsor_id) {
                $sql = "SELECT Nume FROM sponsori WHERE ID = ?";
                $stmt2 = $mysqli->prepare($sql);
                $stmt2->bind_param("i", $sponsor_id);
                $stmt2->execute();
                $result = $stmt2->get_result();
                $sponsor = $result->fetch_object();
                $stmt2->close();
                ?>
                 <br>
                <input type="text" name="sponsori[]" value="<?php echo $sponsor ? $sponsor->Nume : ''; ?>" />
            <?php } ?>

            <?php
            $sql = "SELECT COUNT(*) as count FROM eveniment_sponsori WHERE Eveniment_ID = ?";
            $stmt2 = $mysqli->prepare($sql);
            $stmt2->bind_param("i", $id);
            $stmt2->execute();
            $result = $stmt2->get_result();
            $count = $result->fetch_object()->count;
            $stmt2->close();
            ?>
            <button id="addSponsorBtn">Adaugă sponsor</button>
            <button id="removeSponsorBtn">Șterge sponsor</button>
        </div>
        <br><br>


        <input type="submit" name="submit" value="Salvează schimbările" />
        <a href="view.php">Catalog</a>
    </div>
</form>
</body>
</html>


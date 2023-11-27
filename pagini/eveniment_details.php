<?php
if (isset($_GET['id'])) {
    $eventId = $_GET['id'];

    include('config.php');

    $sql = "SELECT * FROM evenimente WHERE ID = $eventId";
    $result = $mysqli->query($sql);

?>

<html lang="en">
    <head>
        <title>Detalii Eveniment</title>
    </head>

        <body>

        <header>
            <h1>Detalii despre eveniment</h1>
        </header>

        <div class="event-details">
            <?php
            if ($result->num_rows > 0) {
                $row = $result->fetch_object();
                echo '<h2>' . $row->Titlu . '</h2>';
                echo '<p><strong>Data:</strong> ' . date('d.m.Y', strtotime($row->Data)) . '</p>';
                echo '<p><strong>Ora: </strong>' . date('H:i', strtotime($row->Ora)) . '</p>';
                echo '<p><strong>Locația: </strong>' . $row->Locatia . '</p>';
                echo '<p><strong>Contact:</strong> ' . $row->Contact . '</p>';
                echo '<p><strong>Prețul biletului:</strong> ' . $row->Pret . ' RON</p>';
                echo '<p><i>' . $row->Descriere . '</i></p>';
                /*echo '<form method="post" action="cos.php?action=add&eventId=' . $eventId . '">';
                echo '<input type="text" name="nr_tickets" value="1" size="2" />';
                echo '<input type="submit" value="Add to Cart" class="btnAddAction" />';
                echo '</form>';*/
            } else {
                echo 'Evenimentul nu a fost găsit.';
            }
            echo '<a href="agenda.php?id=' . $eventId . '">Agenda</a><br>';
        echo '<a href="speaker_details.php?id=' . $eventId . '">Speakeri</a><br>';
        echo '<a href="sponsor_details.php?id=' . $eventId . '">Sponsori & Parteneri</a><br><br><br><br>';
            echo '<a href="tickets.php?id=' . $eventId . '">Cumpără bilet</a>';
            $mysqli->close();
            }
        ?>
        </div>
    </body>
</html>

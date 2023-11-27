<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>Vizualizare evenimente</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
<h1>Evenimente din catalog</h1>
<p><b>Toate evenimentele din catalogul nostru</b></p>

<?php
include('config.php');

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
    GROUP BY e.ID
    ORDER BY e.ID"))
{
    if ($result->num_rows > 0)
    {
        echo "<table border=1 cellpadding=10>";
        echo "<tr><th>Titlu</th><th>Descriere</th><th>Data</th><th>Ora</th><th>Locația</th>
                <th>Preț</th><th>Speakeri</th><th>Parteneri</th><th>Sponsori</th><th>Contact</th><th>Detalii</th><th>Bilete</th></tr>";

        while ($row = $result->fetch_object())
        {
            echo "<tr>";
            echo "<td>" . $row->Titlu . "</td>";
            echo "<td>" . $row->Descriere . "</td>";
            echo "<td>" . date('d.m.Y', strtotime($row->Data)) . "</td>";
            echo "<td>" . date('H:i', strtotime($row->Ora)) . "</td>";
            echo "<td>" . $row->Locatia . "</td>";
            echo "<td>" . $row->Pret . "</td>";

            echo "<td>" . $row->speaker_names . "</td>";
            echo "<td>" . $row->partener_names . "</td>";
            echo "<td>" . $row->sponsor_names . "</td>";

            echo "<td>" . $row->Contact . "</td>";
            echo "<td><a href='./event_pages/event_" . $row->ID . ".html'>Detalii</a></td>";
            echo "<td><a>Cumpără bilet</a></td>";
            echo "</tr>";
        }

        echo "</table>";
    }
    else
    {
        echo "Nu sunt evenimente în baza de date!";
    }
}
else
{
    echo "Error: " . $mysqli->error;
}

$mysqli->close();
?>
<br>
<h2><a href="user_home.php">Acasă</a></h2>
</body>
</html>

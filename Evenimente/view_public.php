<!DOCTYPE HTML PUBLIC>
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
    e.Locatia,
    s.Nume AS speaker_name,
    p.Nume AS partener_name,
    sp.Nume AS sponsor_name
    FROM evenimente e
    LEFT JOIN eveniment_speakeri es ON e.ID = es.Eveniment_ID
    LEFT JOIN speakeri s ON es.Speakeri_ID = s.ID
    LEFT JOIN eveniment_parteneri ep ON e.ID = ep.Eveniment_ID
    LEFT JOIN parteneri p ON ep.Parteneri_ID = p.ID
    LEFT JOIN eveniment_sponsori esp ON e.ID = esp.Eveniment_ID
    LEFT JOIN sponsori sp ON esp.Sponsori_ID = sp.ID
    ORDER BY e.ID"))
{
    if ($result->num_rows > 0)
    {
        echo "<table border=1 cellpadding=10>";
        echo "<tr><th>ID</th><th>Titlu</th><th>Descriere</th><th>Data</th><th>Locatia</th><th>Speaker</th><th>Partener</th><th>Sponsor</th><th>Detalii</th>
<th>Bilete</th></tr>";

        while ($row = $result->fetch_object())
        {
            echo "<tr>";
            echo "<td>" . $row->ID . "</td>";
            echo "<td>" . $row->Titlu . "</td>";
            echo "<td>" . $row->Descriere . "</td>";
            echo "<td>" . $row->Data . "</td>";
            echo "<td>" . $row->Locatia . "</td>";
            echo "<td>" . $row->speaker_name . "</td>";
            echo "<td>" . $row->partener_name . "</td>";
            echo "<td>" . $row->sponsor_name . "</td>";
            echo "<td><a href='./event_pages/event_" . $row->ID . ".html'>Detalii</a></td>";
            echo "<td><a href='../../Proiect-web/Evenimente/user_login.html'>Adauga in cos</a></td>";
            echo "</tr>";
        }

        echo "</table>";
    }
    else
    {
        echo "Nu sunt evenimente in tabela!";
    }
}
else
{
    echo "Error: " . $mysqli->error;
}

$mysqli->close();
?>
<br>
<br>
<h2><a href="user_home.php">Acasă</a></h2>
</body>
</html>


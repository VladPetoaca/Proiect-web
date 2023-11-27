<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>Trimitere invitații</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
<h1>Trimite invitații la evenimente</h1>

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
    GROUP_CONCAT(DISTINCT p.Nume SEPARATOR ', ') AS partener_names,
    GROUP_CONCAT(DISTINCT sp.Nume SEPARATOR ', ') AS sponsor_names
    FROM evenimente e
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
        echo "<tr><th>ID</th><th>Titlu</th><th>Data</th><th>Ora</th>
                <th>Parteneri</th><th>Sponsori</th><th>Contact</th>
                <th>Invitații</th></tr>";

        while ($row = $result->fetch_object())
        {
            echo "<tr>";
            echo "<td>" . $row->ID . "</td>";
            echo "<td>" . $row->Titlu . "</td>";
            echo "<td>" . date('d.m.Y', strtotime($row->Data)) . "</td>";
            echo "<td>" . date('H:i', strtotime($row->Ora)) . "</td>";

            echo "<td>" . $row->partener_names . "</td>";
            echo "<td>" . $row->sponsor_names . "</td>";

            echo "<td>" . $row->Contact . "</td>";
            echo "<td><a href='../email/send.php?id=" . $row->ID . "'>Trimite invitații</a></td>";
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
<h2><a href="admin_home.php">Acasă</a></h2>
</body>
</html>

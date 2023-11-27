<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
    <title>Lista de speakeri</title>
    <link rel="stylesheet" href="crud.css">
</head>
<body>
<h1>Lista de speakeri</h1>

<?php

include('config.php');

if ($result = $mysqli->query("SELECT * FROM speakeri ORDER BY ID"))
{
    if ($result->num_rows > 0)
    {

        echo "<table border=1 cellpadding=10>";

        echo "<tr><th>ID</th><th>Nume</th><th>Email</th><th>Telefon</th><th>Adresa</th><th colspan='2'>Modifică/șterge</th></tr>";
        while ($row = $result->fetch_object())
        {
            echo "<tr>";
            echo "<td>" . $row->ID . "</td>";
            echo "<td>" . $row->Nume . "</td>";
            echo "<td>" . $row->Email . "</td>";
            echo "<td>" . $row->Telefon . "</td>";
            echo "<td>" . $row->Adresa . "</td>";
            echo "<td><a href='speaker_edit.php?id=" . $row->ID . "'>Modifică</a></td>";
            echo "<td><a href='speaker_delete.php?id=" .$row->ID . "'>Șterge</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    else
    {
        echo "Nu sunt speakeri în baza de date!";
    }
}

else
{ echo "Error: " . $mysqli->error(); }

$mysqli->close();
?>
<br>
<a href="speaker_add.php">Adaugă un speaker nou</a>
</body>
</html>

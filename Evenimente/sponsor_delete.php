<?php

include('config.php');

if (isset($_GET['id']) && is_numeric($_GET['id']))
{

    $id = $_GET['id'];

    if ($stmt = $mysqli->prepare("DELETE FROM sponsori WHERE ID = ? LIMIT 1"))
    {
        $stmt->bind_param("i",$id);
        $stmt->execute();
        $stmt->close();
    }
    else
    {
        echo "Nu s-a putut efectua ștergerea.";
    }
    $mysqli->close();
    echo "<div>Sponsorul a fost șters din baza de date.</div>";
}
echo "<p><a href=\"sponsor_view.php\">Lista de sponsori</a></p>";
?>


<?php
// Connect to the database
include('config.php');

// Check if 'id' is set in the URL and is a numeric value
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    // Retrieve the 'id' variable from the URL
    $id = $_GET['id'];

    // Begin a transaction to ensure atomicity
    $mysqli->begin_transaction();

    try {
        // Delete entries from eveniment_speakeri
        $stmt = $mysqli->prepare("DELETE FROM eveniment_speakeri WHERE Eveniment_ID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        // Delete entries from eveniment_parteneri
        $stmt = $mysqli->prepare("DELETE FROM eveniment_parteneri WHERE Eveniment_ID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        // Delete entries from eveniment_sponsori
        $stmt = $mysqli->prepare("DELETE FROM eveniment_sponsori WHERE Eveniment_ID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        // Delete the record from evenimente
        $stmt = $mysqli->prepare("DELETE FROM evenimente WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        // Commit the transaction
        $mysqli->commit();

        // Display a message indicating that the record has been deleted
        echo "<div>Evenimentul a fost șters!</div>";
    } catch (Exception $e) {
        // Rollback the transaction on error
        $mysqli->rollback();
        echo "Eroare! Nu s-a putut efectua ștergerea.";
    }

    // Close the database connection
    $mysqli->close();
}

// link to navigate back to the index page ("Vizualizare.php")
echo "<a href='view.php'> Înapoi la catalog</a>";